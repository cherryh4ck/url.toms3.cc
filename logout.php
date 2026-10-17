<?php
    session_start();
    if (!isset($_SESSION["account_id"])) {
        header("Location: index.php");
        exit;
    }

    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
?>