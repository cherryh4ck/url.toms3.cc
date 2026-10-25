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

    require __DIR__ . "/../config/conn.php";
    $stmt = $conn->prepare("SELECT * FROM roles WHERE id = ?");
    $stmt->execute([$_SESSION["account_role_id"]]);
    $role_fetch = $stmt->fetch();

    if (strtolower($role_fetch["name"]) !== "admin") {
        http_response_code(401);
        echo(json_encode(["msg" => "Unauthorized."]));
        exit;
    }

    if (!isset($_POST["username"]) || !isset($_POST["password"]) || !isset($_POST["role"])) {
        http_response_code(400);
        echo(json_encode(["msg" => "Missing data."]));
        exit;
    }

    $username = $_POST["username"] ?? "";
    $password = $_POST["password"] ?? "";
    $role_id = $_POST["role"] ?? 1;

    if (!preg_match('^[A-Za-z0-9_-]{3,32}$', $username)) {
        http_response_code(400);
        echo(json_encode(["msg" => "Invalid username."]));
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM accounts WHERE username = ?");
    $stmt->execute([$username]);
    $user_fetch = $stmt->fetch();

    if ($user_fetch) {
        http_response_code(400);
        echo(json_encode(["msg" => "User already exists."]));
        exit;
    }

    $hash = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $conn->prepare("INSERT INTO accounts(username, password, role_id) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hash, $role_id]);
        http_response_code(200);
        echo(json_encode(["msg" => "Created successfully."]));
    }
    catch (PDOException) {
        http_response_code(400);
        echo(json_encode(["msg" => "Unexpected error."]));
    }
    exit;
?>