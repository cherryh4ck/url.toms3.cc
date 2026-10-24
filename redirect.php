<?php
    if (!isset($_GET["c"])) {
        http_response_code(301);
        header("Location: index.php");
        exit;
    }

    $code = $_GET["c"];
    require __DIR__ . "/api/v1/config/conn.php";
    try {
        $stmt = $conn->prepare("SELECT url, expires_on FROM urls WHERE alias = ?");
        $stmt->execute([$code]);
        $fetch = $stmt->fetch();

        if (!$fetch) {
            http_response_code(200);
            echo "No redirection.";
            exit;
        }

        $expires = $fetch["expires_on"] ?? null;
        $redirect = $fetch["url"];

        if ($expires !== null) {
            $date = new DateTime($expires);
            $current_date = new DateTime();
            if ($date < $current_date) {
                http_response_code(200);
                echo "No redirection.";
                var_dump(date_default_timezone_get());
                var_dump($conn->query("SELECT @@session.time_zone, NOW()")->fetch());
                exit;
            }
        }

        http_response_code(301);
        header("Location: $redirect");
    }
    catch (PDOException) {
        http_response_code(400);
        echo "Server error.";
    }
    exit;
?>