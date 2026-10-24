<?php
    if (!isset($_GET["c"])) {
        http_response_code(302);
        header("Location: index.php");
        exit;
    }

    $code = $_GET["c"];
    require __DIR__ . "/api/v1/config/conn.php";
    try {
        $cleanup = $conn->prepare("DELETE FROM urls WHERE alias = ? AND expires_on IS NOT NULL AND expires_on <= NOW()");
        $cleanup->execute([$code]);
        $stmt = $conn->prepare("SELECT url FROM urls WHERE alias = ? AND (expires_on IS NULL OR expires_on > NOW())");
        $stmt->execute([$code]);
        $fetch = $stmt->fetch();

        if (!$fetch) {
            http_response_code(200);
            echo "No redirection.";
            exit;
        }

        http_response_code(302);
        header("Location: " . $fetch["url"]);
    }
    catch (PDOException) {
        http_response_code(400);
        echo "Server error.";
    }
    exit;
?>