<?php
    if (!isset($_GET["c"])) {
        http_response_code(302);
        header("Location: index.php");
        exit;
    }

    $code = $_GET["c"];
    require __DIR__ . "/api/v1/config/conn.php";
    try {
        $stmt = $conn->prepare("SELECT url FROM urls WHERE alias = ? AND (expires_on IS NULL OR expires_on > NOW())");
        $stmt->execute([$code]);
        $fetch = $stmt->fetch();

        if (!$fetch) {
            http_response_code(404);
            header("Location: 404");
            exit;
        }

        $stmt = $conn->prepare("UPDATE urls SET clicks = clicks + 1 WHERE alias = ?");
        $stmt->execute([$code]);
        http_response_code(302);
        header("Location: " . $fetch["url"]);
    }
    catch (PDOException) {
        http_response_code(400);
        echo "Server error.";
    }
    exit;
?>