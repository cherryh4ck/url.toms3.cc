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

    if (!isset($_POST["url"]) || !isset($_POST["expires"])) {
        http_response_code(400);
        echo(json_encode(["msg" => "Missing data."]));
        exit;
    }

    require __DIR__ . "/../config/conn.php";
    $url = $_POST["url"];
    $alias = $_POST["alias"] ?? "";
    $expires = $_POST["expires"] ?? "";
    $token = $_POST["token"] ?? "";

    $ip = gethostbyname(parse_url($url, PHP_URL_HOST));
    $public = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);

    if (!str_starts_with($url, "https://") || !$public) {
        http_response_code(400);
        echo(json_encode(["msg" => "Invalid URL."]));
        exit;
    }

    $cu = curl_init($url);
    curl_setopt_array($cu, [
        CURLOPT_NOBODY         => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => false,
        CURLOPT_CONNECTTIMEOUT => 3,
        CURLOPT_TIMEOUT        => 5,
        CURLOPT_PROTOCOLS      => CURLPROTO_HTTPS,
    ]);
    curl_exec($cu);
    $code = curl_getinfo($cu, CURLINFO_RESPONSE_CODE);

    if ($code >= 200 && $code < 400) {
        echo(json_encode(["msg" => "got response"]));
    }
    else {
        echo(json_encode(["msg" => "got no response"]));
    }
    exit;
?>