<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['username']);

    if($_SESSION['username'] == "administrador"){
        header("Location: ../admin/administrador_management.php");
    }
    elseif($_SESSION['username'] == "gestor"){
        header("Location: ../gestor/gestor_management.php");
    }
    else{
        header("Location: ../paginaInicial/PaginaInicial.php");
    }
}
?>
