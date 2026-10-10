<?php
    $ip = "127.0.0.1";
    $port = "3306";
    $user = "root";
    $pass = "";
    $db_name = "toms3";
    
    try {
        $conn = new PDO("mysql:host=$ip;port=$port;dbname=$db_name;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
    catch (PDOException $e) {
        http_response_code(500);
        header("Content-Type: application/json");
        error_log("somethin is not workin: " . $e);
        echo json_encode(["error" => "Serversided connection error."]);
        exit;
    }