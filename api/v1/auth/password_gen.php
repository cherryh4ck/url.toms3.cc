<?php
    header("Content-Type: application/json");
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        http_response_code(405);
        echo(json_encode(["msg" => "Invalid petition."]));
        exit;
    }

    $password = $_POST["password"] ?? "";
    if (trim($password ?? "") === "") {
        http_response_code(400);
        echo(json_encode(["msg" => "Invalid data."]));
        exit;
    }

    $password = password_hash($password, PASSWORD_BCRYPT);
    http_response_code(200);
    echo(json_encode(["password" => $password]));
    exit;
?>