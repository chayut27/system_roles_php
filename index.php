<?php

$arr_errors = array(
    1 => "ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง",
    2 => "ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง",
    3 => "คุณไม่มีสิทธิ์การเข้าใช้งานนี้",
    4 => "เกิดข้อผิดพลาด",
);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>

    <div class="py-5">
        <div class="container">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-md-4">
                    <div class="card">
                        <div class="card-body">

                            <div class="row">
                                <div class="col-12 text-center">
                                    <h2 class="mb-4">เข้าสู่ระบบ</h2>
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
                            <form action="check_login.php" method="POST" class="row gy-3">
                                <div class="col-12">
                                    <label for="username">ชื่อผู้ใช้งาน</label>
                                    <input type="text" name="username" id="username" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label for="password">รหัสผ่าน</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                <div class="col-12 d-grid">
                                    <button class="btn btn-primary">เข้าสู่ระบบ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



</body>

</html>