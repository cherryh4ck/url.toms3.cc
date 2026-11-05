<?php
    header("Content-Type: application/json");
    if ($_SERVER["REQUEST_METHOD"] != "GET") {
        http_response_code(405);
        echo(json_encode(["msg" => "Invalid petition."]));
        exit;
    }

    session_start();
    if (!isset($_SESSION["account_id"])) {
        http_response_code(400);
        echo(json_encode(["msg" => "No session detected."]));
        exit;
    }

    session_unset();
    session_destroy();
    http_response_code(200);
    echo(json_encode(["msg" => "Success."]));
    exit;
?>