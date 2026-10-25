<?php
    //TODO: 
    // Timestamps -> unix timestamps
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

    if ($alias !== "") {
        $prohibited_aliases = [
            "api", "index", "logout", "404", "resources", "js",
            "view"
        ];
        $low_alias = strtolower($alias);
        if (in_array($low_alias, $prohibited_aliases)) {
            http_response_code(400);
            echo(json_encode(["msg" => "Alias is prohibited."]));
            exit;
        }

        $pattern = '/^[A-Za-z0-9_-]{1,64}$/';
        if (!preg_match($pattern, $alias)) {
            http_response_code(400);
            echo(json_encode(["msg" => "Alias contains invalid characters or is too large (or short)."]));
            exit;
        }
    }

    $host = parse_url($url, PHP_URL_HOST);
    $ip = $host ? gethostbyname($host) : false;
    $public = $ip ? filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) : false;

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
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $max_chars = strlen($chars) -1;
        $tries = 0;
        if ($alias === "") {
            while($tries < 100) {
                $gen = "";
                for ($i = 0; $i < 8; $i++) {
                    $gen .= $chars[random_int(0, $max_chars)];
                }
                $stmt = $conn->prepare("SELECT 1 FROM urls WHERE alias = ?");
                $stmt->execute([$gen]);

                $fetch = $stmt->fetch();
                if (!$fetch) break;

                $tries++;
            }

            if ($tries >= 100) {
                http_response_code(500);
                echo(json_encode(["msg" => "Timed out, please contact an administrator (0x): " . $gen]));
                exit;
            }
            $alias = $gen;
        }
        else {
            $stmt = $conn->prepare("SELECT 1 FROM urls WHERE alias = ?");
            $stmt->execute([$alias]);

            $fetch = $stmt->fetch();
            if ($fetch) {
                http_response_code(400);
                echo(json_encode(["msg" => "Alias already exists."]));
                exit;
            }
        }

        $expires_values = [
                "30m" => "NOW() + INTERVAL 30 MINUTE",
                "1h" => "NOW() + INTERVAL 1 HOUR",
                "1d" => "NOW() + INTERVAL 1 DAY",
                "1w" => "NOW() + INTERVAL 1 WEEK",
                "never" => "NULL",
                "" => "NULL",
        ];
        $expires_stmt = $expires_values[$expires] ?? "NULL";

        $hashed_token = password_hash($token, PASSWORD_BCRYPT);

        try {
            $stmt = $conn->prepare("INSERT INTO urls(url, alias, expires_on, associated_to, token) VALUES (?, ?, $expires_stmt, ?, ?)");
            $stmt->execute([$url, $alias, $_SESSION["account_id"], $token === "" ? null : $hashed_token]);
            http_response_code(200);
            echo(json_encode(["msg" => "Created successfully."]));
        }
        catch (PDOException) {
            http_response_code(400);
            echo(json_encode(["msg" => "Unexpected error."]));
        }
        exit;
    }
    else {
        http_response_code(400);
        echo(json_encode(["msg" => "URL timed out."]));
        exit;
    }
    exit;
?>