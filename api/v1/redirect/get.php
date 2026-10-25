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

    $offset = max(0, (int)$_POST["offset"]);
    if (!is_numeric($offset)) {
        http_response_code(400);
        echo(json_encode(["msg" => "Invalid data."]));
        exit;
    }

    require __DIR__ . "/../config/conn.php";
    $offset = $offset * 3;
    $stmt = $conn->prepare("SELECT id, url, alias, expires_on FROM urls WHERE associated_to = ? ORDER BY id LIMIT 3 OFFSET ?");
    $stmt->execute([$_SESSION["account_id"], $offset]);
    $fetch = $stmt->fetchAll();

    http_response_code(200);
    echo(json_encode($fetch));
    exit;
?>