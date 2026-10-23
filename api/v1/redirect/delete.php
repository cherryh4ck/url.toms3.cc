<?php
    // SIN TERMINAR
    // !!!!!!!!!!!!
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
    if (!isset($_SESSION["account_id"])) {
        if (!isset($_POST["token"])) {
            http_response_code(401);
            echo(json_encode(["msg" => "Unauthorized."]));
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
    http_response_code(200);
    echo(json_encode($fetch));
    exit;
?>