<?php
    header("Content-Type: application/json");
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        http_response_code(405);
        echo(json_encode(["msg" => "Invalid petition."]));
        exit;
    }

    if (!isset($_POST["alias"])) {
        http_response_code(400);
        echo(json_encode(["msg" => "Missing alias."]));
        exit;
    }

    session_start();
    $validated = false;
    if (!isset($_SESSION["account_id"])) {
        if (!isset($_POST["token"])) {
            http_response_code(401);
            echo(json_encode(["msg" => "Missing token."]));
            exit;
        }
    }

    $alias = $_POST["alias"];
    $token = $_POST["token"] ?? "";

    require __DIR__ . "/../config/conn.php";
    $stmt = $conn->prepare("SELECT * FROM urls WHERE alias = ?");
    $stmt->execute([$alias]);
    $fetch = $stmt->fetch();

    if (!$fetch) {
        http_response_code(400);
        echo(json_encode(["msg" => "Not found."]));
        exit;
    }

    if (isset($_SESSION["account_id"])) {
        $stmt = $conn->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$_SESSION["account_role_id"]]);
        $role_fetch = $stmt->fetch();

        if (strtolower($role_fetch["name"]) !== "admin") {
            if ($fetch["associated_to"] != $_SESSION["account_id"]) {
                http_response_code(401);
                echo(json_encode(["msg" => "Unauthorized."]));
                exit;
            }
        }
    } else {
        if (!password_verify($token, $fetch["token"])) {
            http_response_code(401);
            echo(json_encode(["msg" => "Incorrect token."]));
            exit;
        }
    }

    try {
        $stmt = $conn->prepare("DELETE FROM urls WHERE alias = ?");
        $stmt->execute([$alias]);
        http_response_code(200);
        echo(json_encode(["msg" => "Deleted URL."]));
    }
    catch (PDOException) {
        http_response_code(400);
        echo(json_encode(["msg" => "Unexpected error, please contact an administrator."]));
    }
    exit;
?>