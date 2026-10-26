<?php
    // Esto realmente no sirve mucho porque la sesión queda colgando (no se refresca)
    // entonces el usuario queda en un limbo
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

    if (!isset($_POST["id"])) {
        http_response_code(400);
        echo(json_encode(["msg" => "Missing data."]));
        exit;
    }

    $id = $_POST["id"];
    if (!is_numeric($id)) {
        http_response_code(400);
        echo(json_encode(["msg" => "Invalid data."]));
        exit;
    }

    if ($id == $_SESSION["account_id"]) {
        http_response_code(401);
        echo(json_encode(["msg" => "You cannot delete yourself."]));
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

    $stmt = $conn->prepare("SELECT a.username, r.permission_level AS level FROM accounts a INNER JOIN roles r ON a.role_id = r.id WHERE a.id = ?");
    $stmt->execute([$id]);
    $fetch = $stmt->fetch();
    if (!$fetch) {
        http_response_code(400);
        echo(json_encode(["msg" => "Not found."]));
        exit;
    }

    if ($fetch["level"] >= $role_fetch["permission_level"]) {
        http_response_code(400);
        echo(json_encode(["msg" => "This user has elevated permissions, so you can't do this."]));
        exit;
    }

    try {
        $stmt = $conn->prepare("DELETE FROM urls WHERE associated_to = ?");
        $stmt->execute([$id]);
        $stmt = $conn->prepare("DELETE FROM accounts WHERE id = ?");
        $stmt->execute([$id]);
        http_response_code(200);
        echo(json_encode(["msg" => "Deleted user along with his URLs."]));
    }
    catch (PDOException) {
        http_response_code(400);
        echo(json_encode(["msg" => "Unexpected error, please contact an administrator."]));
    }
    exit;
?>