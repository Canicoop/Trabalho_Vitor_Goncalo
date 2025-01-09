<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: PaginaInicial.php");
    exit();
}
$user_id = $_SESSION['username'];

function is_logged_in() {
    return isset($_SESSION['username']);
}
?>
