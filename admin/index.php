<?php

session_start();
require_once("../connection.php");

$app = explode('/', $_SERVER['REQUEST_URI']);
$app = "/" . $app[2];

$role_id = $_SESSION["role_id"];

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
                    <?php if (!empty($_GET["error_id"]) && isset($arr_errors[$_GET["error_id"]])) { ?>
                        <div class="alert alert-danger">
                            <?php echo $arr_errors[$_GET["error_id"]]; ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-12 col-md-4 mx-auto">
                    <div class=" list-group">
                        <a href="users.php" class=" list-group-item list-group-item-action">พนักงาน</a>
                    </div>
                </div>
            </div>
            <div class="text-center">
                <a href="../logout.php">ออกจากระบบ</a>
            </div>
        </div>

    </div>




</body>

</html>