<?php

require_once("../connection.php");

if (isset($_GET["act"]) && $_GET["act"] == "create") {

    $role_id = $_POST["role_id"];
    $position_id = $_POST["position_id"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO `users` (`role_id`, `position_id`, `username`, `password`) VALUES (:role_id, :position_id, :username, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":role_id", $role_id, PDO::PARAM_INT);
        $stmt->bindParam(":position_id", $position_id, PDO::PARAM_INT);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR, 100);
        $stmt->bindParam(":password", $password, PDO::PARAM_STR, 100);
        $result = $stmt->execute();
        if ($result === false) {
            header("location:./index.php?error_id=4");
        }

        header("location:./users.php?success_id=1");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
} else if (isset($_GET["act"]) && $_GET["act"] == "update" && $_GET["user_id"]) {
    $user_id = $_GET["user_id"];
    $role_id = $_POST["role_id"];
    if (!empty($_POST["position_id"])) {
        $position_id = $_POST["position_id"];
    }
    $username = $_POST["username"];
    if (!empty($_POST["password"])) {
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    }

    try {
        $sql = "UPDATE `users` SET ";
        $sql .= "`role_id` = :role_id, ";
        if (!empty($_POST["position_id"])) {
            $sql .= "`position_id` = :position_id, ";
        }
        if (!empty($_POST["password"])) {
            $sql .= "`password` = :password, ";
        }
        $sql .= "`username` = :username ";

        $sql .= "WHERE id = :user_id ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":role_id", $role_id, PDO::PARAM_INT);
        if (!empty($_POST["position_id"])) {
            $stmt->bindParam(":position_id", $position_id, PDO::PARAM_INT);
        }
        $stmt->bindParam(":username", $username, PDO::PARAM_STR, 100);
        if (!empty($_POST["password"])) {
            $stmt->bindParam(":password", $password, PDO::PARAM_STR, 100);
        }
        $result = $stmt->execute();
        if ($result === false) {
            header("location:./index.php?error_id=4");
        }

        header("location:./users.php?success_id=2");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
} else if (isset($_GET["act"]) && $_GET["act"] == "delete" && $_GET["user_id"]) {
    $user_id = $_GET["user_id"];

    try {
        $sql = "DELETE FROM `users` WHERE id = :user_id ";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $result = $stmt->execute();
        if ($result === false) {
            header("location:./index.php?error_id=4");
        }

        header("location:./users.php?success_id=3");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        die();
    }
} else {
    header("location:./index.php?error_id=3");
}
