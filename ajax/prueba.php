<?php session_start(); error_reporting(0);
// Verificar si la variable de sesión para el usuario existe
if (isset($_SESSION['usuario'])) {
    // Obtener el usuario de la variable de sesión
    $usuario = $_SESSION['usuario'];
} else {
    // Si la variable de sesión no existe, redirigir al formulario de inicio de sesión
    header("Location: ../login.php");
    exit();
}
// Conectarse a la base de datos
require_once('../admin/conex.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <title>Document</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        }

        .timeline {
        position: relative;
        width: 100%;
        height: 100px;
        display: flex;
        align-items: center;
        overflow-x: hidden; 
        position: relative; 
        animation: slideRight 2s ease-in-out;
        }

        .line {
        position: absolute;
        width: 100%;
        height: 4px;
        background-color: #ccc;
        top: 50%;
        transform: translateY(-50%);
        }

        .circle-container {
        display: flex;
        justify-content: space-between;
        width: 100%;
        position: relative;
        }

        .circle {
        width: 20px;
        height: 20px;
        background-color: #3498db;
        border-radius: 50%;
        position: relative;
        z-index: 1;
        }

        .circle::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        background-color: #fff;
        border-radius: 50%;
        z-index: -1;
        }
        .comment {
        display: none;
        position: absolute;
        top: -40px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #fff;
        padding: 5px 10px;
        border-radius: 5px;
        box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        font-size: 12px;
        text-align: center;
        width: 100px;
        }

        .circle:hover .comment {
        display: block;
        }
        @keyframes slideRight {
        from {
            transform: translateX(-100%);
        }
        to {
            transform: translateX(0);
        }
        }
        .title {
        font-size: 10px;
        text-align: center;
        color: #555;
        margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="timeline">
    <div class="line"></div>
    <div class="circle-container">
      <div class="circle"> <div class="comment">Etapa 1</div><div class="title">Inicio</div></div>
      <div class="circle"> <div class="comment">Etapa 1</div><div class="title">Inicio</div></div>
      <div class="circle"> <div class="comment">Etapa 1</div><div class="title">Inicio</div></div>
      <div class="circle"></div>
      <div class="circle"></div>
      <div class="circle"></div>
      <div class="circle"></div>
    </div>
  </div>
</body>
</html>