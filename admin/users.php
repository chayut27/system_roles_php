<?php

session_start();
require_once("../connection.php");

$app = explode('/', $_SERVER['REQUEST_URI']);
$app = "/" . $app[2];

$role_id = $_SESSION["role_id"];

function filterArray($products, $field, $value)
{
    foreach ($products as $key => $product) {
        if ($product[$field] === $value)
            return $key;
    }
    return false;
}

try {
    $stmt = $conn->prepare("SELECT * FROM `role_permissions` WHERE role_id = :role_id AND permission = :app ");
    $stmt->bindParam(":role_id", $role_id, PDO::PARAM_INT);
    $stmt->bindParam(":app", $app, PDO::PARAM_STR);
    $result = $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row === false) {
        header("location:login.php?error_id=3");
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

$flag_read = true;
$flag_create = true;
$flag_update = true;
$flag_delete = true;
if ($role_id != 1) {
    $app = "users";
    $position_id = $_SESSION["position_id"];
    try {
        $stmt = $conn->prepare("SELECT * FROM `position_permissions` WHERE position_id = :position_id AND app = :app  ");
        $stmt->bindParam(":position_id", $position_id, PDO::PARAM_INT);
        $stmt->bindParam(":app", $app, PDO::PARAM_STR);
        $result = $stmt->execute();
        $data = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        if (empty($data)) {
            header("location:./index.php?error_id=3");
        }

        if (filterArray($data, "permission", "all") === false) {

            $flag_read = false;
            $flag_create = false;
            $flag_update = false;
            $flag_delete = false;

            foreach ($data as $row) {
                if ($row["permission"] == "view") {
                    $flag_read = true;;
                } else if ($row["permission"] == "create") {
                    $flag_create = true;;
                } else if ($row["permission"] == "edit") {
                    $flag_update = true;;
                } else if ($row["permission"] == "delete") {
                    $flag_delete = true;;
                }
            }
        }

        if (!$flag_read) {
            header("location:./index.php?error_id=3");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
}


try {
    $stmt = $conn->prepare("SELECT * FROM `users` ");
    $result = $stmt->execute();
    $arr_users = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $arr_users[] = $row;
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

try {
    $stmt = $conn->prepare("SELECT `id`, `name` FROM `positions` ");
    $result = $stmt->execute();
    $arr_positions = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $arr_positions[$row["id"]] = $row["name"];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

try {
    $stmt = $conn->prepare("SELECT `id`, `name` FROM `roles` ");
    $result = $stmt->execute();
    $arr_roles = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $arr_roles[$row["id"]] = $row["name"];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>

    <div class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2> สวัสดี <?php echo $_SESSION["username"]; ?></php>
                    </h2>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-md-4 mx-auto">
                    <?php if (!empty($_GET["success_id"]) && isset($arr_success[$_GET["success_id"]])) { ?>
                        <div class="alert alert-success">
                            <?php echo $arr_success[$_GET["success_id"]]; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2>รายชื่อพนักงาน</h2>
                </div>
            </div>
            <?php if ($flag_create) { ?>
                <div class="row mb-3">
                    <div class="col-12 text-end">
                        <a href="create_user.php" class="btn btn-primary">Create User</a>
                    </div>
                </div>
            <?php } ?>
            <div class="row mb-5">
                <div class="col-12 mx-auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ชื่อผู้ใช้งาน</th>
                                <th>ตำแหน่ง</th>
                                <th>บทบาท</th>
                                <th></th>
                                <?php if ($flag_update) { ?>
                                    <th></th>
                                <?php } ?>
                                <?php if ($flag_delete) { ?>
                                    <th></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($arr_users as $value) { ?>
                                <tr>
                                    <td><?php echo $value["username"]; ?></td>
                                    <td><?php echo isset($arr_positions[$value["position_id"]]) ? $arr_positions[$value["position_id"]] : '-'; ?></td>
                                    <td><?php echo $arr_roles[$value["role_id"]]; ?></td>
                                    <td><a href="read_user.php?user_id=<?php echo $value["id"]; ?>">ดูข้อมูล</a></td>
                                    <?php if ($flag_update) { ?>
                                        <td><a href="update_user.php?user_id=<?php echo $value["id"]; ?>">แก้ไขข้อมูล</a></td>
                                    <?php } ?>
                                    <?php if ($flag_delete) { ?>
                                        <td><a href="save-user.php?act=delete&user_id=<?php echo $value["id"]; ?>">ลบข้อมูล</a></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <a href="./">หน้าแรก</a>
                    |
                    <a href="../logout.php">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </div>



</body>

</html>