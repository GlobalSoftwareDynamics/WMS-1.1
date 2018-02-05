<?php
ob_start();
include('login.php'); // Includes Login Script
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CARVASQ, Sistema de Gestión Logístico</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="apple-touch-icon-precomposed" href="favicon152.png">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="favicon152.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="favicon144.png">
    <link rel="apple-touch-icon-precomposed" sizes="120x120" href="favicon120.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="favicon114.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="favicon72.png">
    <link rel="apple-touch-icon-precomposed" href="favicon57.png">
    <link rel="icon" href="favicon32.png" sizes="32x32">
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
    <script>
        $(document).ready(function() {
            $(document).mousemove(function(event) {
                TweenLite.to($("body"),
                    .5, {
                        css: {
                            backgroundPosition: "" + parseInt(event.pageX / 8) + "px " + parseInt(event.pageY / '12') + "px, " + parseInt(event.pageX / '15') + "px " + parseInt(event.pageY / '15') + "px, " + parseInt(event.pageX / '30') + "px " + parseInt(event.pageY / '30') + "px",
                            "background-position": parseInt(event.pageX / 8) + "px " + parseInt(event.pageY / 12) + "px, " + parseInt(event.pageX / 15) + "px " + parseInt(event.pageY / 15) + "px, " + parseInt(event.pageX / 30) + "px " + parseInt(event.pageY / 30) + "px"
                        }
                    })
            })
        })
    </script>
</head>
<body>

<div class="container">
    <div class="login-container">
        <div id="output"></div>
        <img src="img/logo4.png" style="margin: 20px;" width="200">
        <div class="form-box">
            <form action="#" method="post">
                <input name="username" type="text" placeholder="usuario">
                <input name="password" type="password" placeholder="contraseña" autocomplete="new-password">
                <button class="btn btn-success btn-block login" type="submit" name="submit">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>