<?php

require_once "../config/config.php";

if($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . Paths::$INDEX);
    exit;
}

$action = $_GET["action"] ?? "";
switch($action) {
    case "login":
        require_once Paths::$PROC_LOGIN;
        break;
    case "signup":
        require_once Paths::$PROC_SIGNUP;
        break;
    case "new":
        require_once Paths::$PROC_NEW;
        break;
    case "edit":
        require_once Paths::$PROC_NEW;
        break;
    case "comment":
        require_once Paths::$PROC_CMNT;
        break;
    case "appr_cmnt":
        require_once Paths::$APPR_CMNT;
        break;
    default:
        require_once Paths::$P404;
        break;
}
