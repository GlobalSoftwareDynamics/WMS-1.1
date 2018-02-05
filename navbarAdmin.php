<body>
<header>
	<nav class="navbar fixed-top navbar-toggleable-md navbar-light bg-faded">
		<button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<a class="navbar-brand" href="mainAdmin.php"><img src="img/logo4.png" width="50"></a>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav mr-auto">
				<li class="nav-item active">
					<a class="nav-link" href="mainAdmin.php">Dashboard<span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						Productos
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
						<a class="dropdown-item" href="gestionProductos.php">Productos y Premios</a>
						<a class="dropdown-item" href="gestionInventario.php">Inventario</a>
						<a class="dropdown-item" href="gestionCatalogos.php">Catálogos</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="gestionPartners.php">Partners</a>
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
						Gestión de Deudas
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
						<a class="dropdown-item" href="#">Deudas Propias</a>
						<a class="dropdown-item" href="#">Deudas de Terceros</a>
					</div>
				</li>
			</ul>
			<form class="form-inline my-2 my-lg-0">
				<button class="btn btn-outline-success my-2 my-sm-0" type="submit">Cerrar Sesión</button>
			</form>
		</div>
	</nav>
</header>