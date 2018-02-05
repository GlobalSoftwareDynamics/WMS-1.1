<?php
include('session.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');
    ?>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <form method="post" action="gestionCaja.php" id="formCatalogo">
                            <div class="float-left">
                                <i class="fa fa-book"></i>
                                Añadir Nueva Cuenta
                            </div>
                            <div class="float-right">
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <input type="submit" value="Guardar" name="agregarCuenta" class="dropdown-item">
                                        <input type="submit" value="Regresar" name="regresar" class="dropdown-item">
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <div class="spacer10"></div>
                            <div class="form-group row">
                                <label for="cuenta" class="col-3 col-form-label">Número de Cuenta:</label>
                                <div class="col-9">
                                    <input class="form-control" type="number" min="1" id="cuenta" name="numerocuenta">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="alias" class="col-3 col-form-label">Alias:</label>
                                <div class="col-9">
                                    <input class="form-control" type="text" id="alias" name="alias">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="saldo" class="col-3 col-form-label">Saldo Disponible:</label>
                                <div class="col-9">
                                    <input class="form-control" type="number" id="saldo" name="saldo">
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