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
    $stmt = $conn->prepare("SELECT count(id) AS total FROM urls WHERE associated_to = ?");
    $stmt->execute([$_SESSION["account_id"]]);
    $fetch = $stmt->fetch();

    http_response_code(200);
    echo(json_encode($fetch));
    exit;
?>