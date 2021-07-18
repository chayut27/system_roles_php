<?php

session_start();
require_once("../connection.php");

$app = explode('/', $_SERVER['REQUEST_URI']);
$app = "/" . $app[2];

$role_id = $_SESSION["role_id"];

try {
    $stmt = $conn->prepare("SELECT * FROM `role_permissions` WHERE role_id = :role_id AND permission = :app ");
    $stmt->bindParam(":role_id", $role_id, PDO::PARAM_STR);
    $stmt->bindParam(":app", $app, PDO::PARAM_STR);
    $result = $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row === false) {
        header("location:./index.php?error_id=3");
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}

if ($role_id != 1) {
    $app = "users";
    $permission = "view";
    $position_id = $_SESSION["position_id"];
    try {
        $stmt = $conn->prepare("SELECT * FROM `position_permissions` WHERE position_id = :position_id AND app = :app AND permission = :permission OR permission = 'all' ");
        $stmt->bindParam(":position_id", $position_id, PDO::PARAM_INT);
        $stmt->bindParam(":app", $app, PDO::PARAM_STR);
        $stmt->bindParam(":permission", $permission, PDO::PARAM_STR);
        $result = $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row === false) {
            header("location:./index.php?error_id=3");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
}

$user_id = $_GET["user_id"];

try {
    $stmt = $conn->prepare("SELECT * FROM `users`  WHERE id = :user_id ");
    $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
    $result = $stmt->execute();
    $data_user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($data_user === false) {
        header("location:./index.php?error_id=4");
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
            <div class="row justify-content-center align-items-center h-100 mb-5">
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 text-center">
                                    <h2 class="mb-4">ดูข้อมูลพนักงาน</h2>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 ">
                                    <?php if (!empty($_GET["error_id"]) && isset($arr_errors[$_GET["error_id"]])) { ?>
                                        <div class="alert alert-danger">
                                            <?php echo $arr_errors[$_GET["error_id"]]; ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <form action="save-user.php?act=update&user_id=<?php echo $data_user["id"]; ?>" method="POST" class="row gy-3">
                                <div class="col-12">
                                    <label for="role_id">บทบาท</label>
                                    <?php echo $arr_roles[$data_user["role_id"]]; ?>
                                </div>
                                <?php if ($data_user["role_id"] != 1) { ?>
                                    <div class="col-12">
                                        <label for="position_id">ตำแหน่ง</label>
                                        <?php echo $arr_positions[$data_user["position_id"]]; ?>
                                    </div>
                                <?php } ?>
                                <div class="col-12">
                                    <label for="username">ชื่อผู้ใช้งาน</label>
                                    <?php echo $data_user["username"]; ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <a href="./">หน้าแรก</a>
                    |
                    <a href="./users.php">พนักงาน</a>
                    |
                    <a href="../logout.php">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </div>



</body>

</html>