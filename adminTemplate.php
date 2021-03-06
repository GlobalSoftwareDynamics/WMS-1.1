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
        <link href="css/font-awesome/css/font-awesome.css" rel="stylesheet">
        <link href="css/styles.css" rel="stylesheet">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>

<body>
<?php
$flag = true;

?>
<header>
	<nav class="navbar fixed-top navbar-toggleable-md navbar-light bg-faded">
		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a class="navbar-brand" href="mainAdmin.php"><img src="img/logo4.png" width="50"></a>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="mainAdmin.php">Inicio<span class="sr-only">(current)</span></a>
				</li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Productos
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="gestionProductos.php">Productos y Premios</a>
                        <a class="dropdown-item" href="gestionInventario.php">Inventario</a>
                        <a class="dropdown-item" href="gestionCatalogos.php">Catálogos</a>
                        <a class="dropdown-item" href="CorreccionProductos.php">Corrección Productos</a>
                    </div>
                </li>
				<li class="nav-item">
					<a class="nav-link" href="gestionPartners.php">Directorio</a>
				</li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Órdenes de Compra
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="nuevaOC.php">Nueva Orden de Compra</a>
                        <a class="dropdown-item" href="gestionOC.php">Listado de Órdenes de Compra</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Órdenes de Venta
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="nuevaOV_DatosGenerales.php">Nueva Orden de Venta</a>
                        <a class="dropdown-item" href="gestionOV.php">Listado de Órdenes de Venta</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Préstamos
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="nuevoPrestamo_DatosGenerales.php">Nuevo Prestamo de Productos</a>
                        <a class="dropdown-item" href="nuevoPrestamoEfectivo.php">Nuevo Prestamo de Efectivo</a>
                        <a class="dropdown-item" href="gestionPrestamos.php">Listado de Préstamos</a>
                    </div>
                </li>
                <?php
                $query = mysqli_query($link, "SELECT * FROM Colaborador WHERE idColaborador = '{$_SESSION['user']}'");
                while($row = mysqli_fetch_array($query)){
                    if($row['idCategoriaUsuario'] == 2){
                        $flag = false;
                    }
                }
                if($flag){
                ?>
                <li class="nav-item">
                    <a class="nav-link" href="gestionDeudas.php">Gestión de Deudas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="gestionCaja.php">Caja</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Reportes
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="reporteDiario.php">Reporte de Personal</a>
                        <a class="dropdown-item" href="reporteFechas.php">Reportes por Fechas</a>
                    </div>
                </li>
                <?php
                }
                ?>
			</ul>
			<form class="form-inline my-2 my-lg-0" action="index.php" method="post">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Cerrar Sesión</button>
			</form>
		</div>
	</nav>
</header>

