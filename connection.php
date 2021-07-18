<?php
error_reporting(E_ALL);
if (strpos($_SERVER['DOCUMENT_ROOT'], ":")) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "system_roles";
} else {
    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "";
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8");
} catch (PDOException $e) {
    echo "เชื่อมต่อล้มเหลว: " . $e->getMessage();
}


$arr_errors = array(
    1 => "ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง",
    2 => "ชื่อผู้ใช้งานหรือรหัสผ่านไม่ถูกต้อง",
    3 => "คุณไม่มีสิทธิ์การเข้าใช้งานนี้",
    4 => "เกิดข้อผิดพลาด",
);


$arr_success = array(
    1 => "เพิ่มข้อมูลสำเร็จ",
    2 => "แก้ไขข้อมูลสำเร็จ",
    3 => "ลบข้อมูลสำเร็จ",
);


date_default_timezone_set('Asia/Bangkok');
