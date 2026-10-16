<?php
    header("Content-Type: application/json");
    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        http_response_code(405);
        echo(json_encode(["msg" => "Invalid petition."]));
        exit;
    }

    require __DIR__ . "/../config/conn.php";
    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";

    if ((trim($username ?? "") === "") || (trim($password ?? "") === "")) {
        http_response_code(400);
        echo(json_encode(["msg" => "Invalid data."]));
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM accounts WHERE BINARY username = ?");
    $stmt->execute([$username]);
    $fetch = $stmt->fetch();

    if ($fetch && password_verify($password, $fetch["password"])) {
        session_start();
        $_SESSION["account_id"] = $fetch["id"];
        $_SESSION["account_username"] = $fetch["username"];
        $_SESSION["account_role_id"] = $fetch["role_id"];
        http_response_code(200);
        echo(json_encode(["msg" => "Success."]));
        exit;
    }
    else {
        http_response_code(401);
        echo(json_encode(["msg" => "Unauthorized."]));
        exit;
    }
?>