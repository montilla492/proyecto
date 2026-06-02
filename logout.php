<?php
require_once "init.php";

session_destroy();

if (isset($_COOKIE['session_id_activa'])) {
    setcookie('session_id_activa', '', time() - 3600, '/');
}

header("Location: login.php");
exit;
?>
