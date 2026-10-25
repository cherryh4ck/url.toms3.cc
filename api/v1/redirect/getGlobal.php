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

    require __DIR__ . "/../config/conn.php";
    $stmt = $conn->prepare("SELECT * FROM roles WHERE id = ?");
    $stmt->execute([$_SESSION["account_role_id"]]);
    $role_fetch = $stmt->fetch();

    if (strtolower($role_fetch["name"]) !== "admin") {
        http_response_code(401);
        echo(json_encode(["msg" => "Unauthorized."]));
        exit;
    }

    $offset = $offset * 3;
    $stmt = $conn->prepare("SELECT u.id, u.url, u.alias, u.expires_on, a.username FROM urls u INNER JOIN accounts a ON u.associated_to = a.id ORDER BY u.id LIMIT 3 OFFSET ?");
    $stmt->execute([$offset]);
    $fetch = $stmt->fetchAll();

    http_response_code(200);
    echo(json_encode($fetch));
    exit;
?>