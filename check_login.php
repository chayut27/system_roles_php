<?php

session_start();
require_once("connection.php");


$username = $_POST["username"];
$password = $_POST["password"];

try {
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE username = :username ");
    $stmt->bindParam(":username", $username, PDO::PARAM_STR);
    $result = $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row === false) {
        header("location:./index.php?error_id=1");
    } else {
        $validPassword = password_verify($password, $row["password"]);
        if ($validPassword) {
            try {
                $stmt = $conn->prepare("SELECT * FROM `role_permissions` WHERE role_id = :role_id AND permission = '/admin' ");
                $stmt->bindParam(":role_id", $row["role_id"], PDO::PARAM_STR);
                $result = $stmt->execute();
                $row_role = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($row_role === false) {
                    header("location:./index.php?error_id=3");
                } else {
                    $_SESSION["is_logged_in"] = true;
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["role_id"] = $row["role_id"];
                    $_SESSION["position_id"] = $row["position_id"];

                    header("location:./admin/index.php");
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                die();
            }
        } else {
            header("location:./index.php?error_id=2");
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    die();
}
