<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');
	?>

	<section class="container">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header card-inverse card-info">
							<div class="float-left">
								<i class="fa fa-shopping-bag"></i>
								Añadir Nuevo Almacén
							</div>
							<div class="float-right">
								<div class="dropdown">
                                    <button type="submit" form="formAlmacen" name='addAlmacen' class="btn btn-secondary btn-sm">Guardar</button>
                                    <button type="submit" form="formBack" class="btn btn-secondary btn-sm">Regresar</button>
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
                                    <form method="post" action="gestionAlmacenes.php" id="formBack"></form>
                                    <form method="post" action="gestionAlmacenes.php" id="formAlmacen">
                                        <div class="form-group row">
                                            <label for="nombreAlmacen" class="col-2 col-form-label">Nombre de Almacén:</label>
                                            <div class="col-10">
                                                <input class="form-control" type="text" id="nombreAlmacen" name="nombreAlmacen" required>
                                            </div>
                                        </div>
                                    </form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}