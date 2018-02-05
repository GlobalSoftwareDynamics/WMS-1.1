<?php
include('session.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	include('funciones.php');
	?>

	<section class="container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header card-inverse card-info">
						<form method="post" action="gestionPartners.php" id="formProveedor">
							<div class="float-left">
								<i class="fa fa-shopping-bag"></i>
								Agregar Partner
							</div>
							<div class="float-right">
								<div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <input type="submit" value="Guardar" name="addPartner" class="dropdown-item">
                                        <input type="submit" value="Regresar" name="regresar" class="dropdown-item">
                                    </div>
                                </div>
                            </div>
					</div>
					<div class="card-block">
						<div class="col-12">
							<ul class="nav nav-tabs" role="tablist">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="tab" href="#general" role="tab">General</a>
								</li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="general" role="tabpanel">
									<div class="spacer30"></div>
                                    <div class="form-group row">
                                        <label for="idProveedor" class="col-2 col-form-label">DNI / RUC:</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" id="idProveedor" name="idProveedor">
                                        </div>
                                    </div>
									<div class="form-group row">
										<label for="nombre" class="col-2 col-form-label">Nombre Completo:</label>
										<div class="col-10">
											<input class="form-control" type="text" id="nombre" name="nombre">
										</div>
									</div>
                                    <div class="form-group row">
                                        <label for="direccion" class="col-2 col-form-label">Dirección:</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" id="direccion" name="direccion">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="ciudad" class="col-2 col-form-label">Ciudad:</label>
                                        <div class="col-10">
                                            <select class="form-control" id="ciudad" name="ciudad">
                                                <option disabled selected>Seleccionar</option>
                                                <?php
                                                $query=mysqli_query($link,"SELECT * FROM Ciudad");
                                                while ($row=mysqli_fetch_array($query)){
                                                    echo "
                                                        <option value='{$row['idCiudad']}'>{$row['nombre']}</option>
                                                    ";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
									<div class="form-group row">
										<label for="email" class="col-2 col-form-label">Correo Electrónico:</label>
										<div class="col-10">
											<input class="form-control" type="text" id="email" name="email">
										</div>
									</div>
									<div class="form-group row">
										<label for="telefono" class="col-2 col-form-label">Teléfono:</label>
										<div class="col-10">
											<input class="form-control" type="text" id="telefono" name="telefono">
										</div>
									</div>
									<div class="form-group row">
										<label for="tipo" class="col-2 col-form-label">Tipo:</label>
										<div class="col-10">
                                            <select class="form-control" id="tipo" name="tipo">
                                                <option disabled selected>Seleccionar</option>
                                                <?php
                                                $query=mysqli_query($link,"SELECT * FROM TipoProveedor");
                                                while ($row=mysqli_fetch_array($query)){
                                                    echo "
                                                        <option value='{$row['idTipoProveedor']}'>{$row['descripcion']}</option>
                                                    ";
                                                }
                                                ?>
                                            </select>
										</div>
									</div>
                                    <div class="form-group row">
                                        <label for="cumple" class="col-2 col-form-label">Fecha de Nacimiento:</label>
                                        <div class="col-10">
                                            <input class="form-control" type="date" id="cumple" name="fechaNacimiento">
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
					</form>
				</div>
			</div>
		</div>
	</section>

	<?php
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}