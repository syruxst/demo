<?php
session_start();
if (isset($_SESSION['operador']) || isset($_SESSION['usuario'])) {
    if (isset($_SESSION['operador'])) {
        session_destroy();
        header("Location: https://acreditasys.tech/");
        exit();
    } else {
        session_destroy();
        header("Location: ../");
        exit();
    }
} else {
    header("Location: ../ajax/login.php");
    exit();
}
?>