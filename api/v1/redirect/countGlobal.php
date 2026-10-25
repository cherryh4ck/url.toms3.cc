<?php
    header("Content-Type: application/json");
    if ($_SERVER["REQUEST_METHOD"] != "GET") {
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

    $stmt = $conn->prepare("SELECT count(id) AS total FROM urls");
    $stmt->execute();
    $fetch = $stmt->fetch();

    http_response_code(200);
    echo(json_encode($fetch));
    exit;
?>