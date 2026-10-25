<?php
    header("Content-Type: application/json");
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        http_response_code(405);
        echo(json_encode(["msg" => "Invalid petition."]));
        exit;
    }

    session_start();
    if (!isset($_SESSION["account_id"])) {
        http_response_code(401);
        echo(json_encode(["msg" => "Unauthorized."]));
        exit;
    }

    require __DIR__ . "/../config/conn.php";
    $stmt = $conn->prepare("SELECT * FROM roles WHERE id = ?");
    $stmt->execute([$_SESSION["account_role_id"]]);
    $role_fetch = $stmt->fetch();

    if (strtolower($role_fetch["name"]) !== "admin") {
        http_response_code(401);
        echo(json_encode(["msg" => "Unauthorized."]));
        exit;
    }

    if (isset($_POST["id"])) {
        $id = $_POST["id"] ?? 1;
        $stmt = $conn->prepare("SELECT username, role_id FROM accounts WHERE id = ?");
        $stmt->execute([$id]);
        $user_fetch = $stmt->fetch();

        if (!$user_fetch) {
            http_response_code(400);
            echo(json_encode(["msg" => "User not found."]));
        }
        else {
            http_response_code(400);
            echo(json_encode($user_fetch));
        }
    } else {
        if (!isset($_POST["offset"])) {
            http_response_code(400);
            echo(json_encode(["msg" => "Missing data."]));
            exit;
        }

        $offset = $_POST["offset"];
        if (!is_numeric($offset)) {
            http_response_code(400);
            echo(json_encode(["msg" => "Invalid data."]));
            exit;
        }

        $offset = $offset * 3;
        $stmt = $conn->prepare("SELECT a.username, a.role_id, r.name FROM accounts a INNER JOIN roles r ON a.role_id = r.id ORDER BY a.id LIMIT 3 OFFSET ?");
        $stmt->execute([$offset]);
        $fetch = $stmt->fetchAll();

        http_response_code(200);
        echo(json_encode($fetch));
    }
    exit;
?>