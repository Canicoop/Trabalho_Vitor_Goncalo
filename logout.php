<?php
session_start();
session_unset();
session_destroy();
header("Location: ./paginaInicial/PaginaInicial.php");
exit();
?>
