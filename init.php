<?php
if (isset($_COOKIE['session_id_activa'])) {
    $sessionId = $_COOKIE['session_id_activa'];
    session_id($sessionId);
}
session_start();
?>
