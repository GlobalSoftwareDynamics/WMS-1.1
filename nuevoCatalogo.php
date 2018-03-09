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
                                <i class="fa fa-book"></i>
                                Añadir Nuevo Catálogo
                            </div>
                            <div class="float-right">
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Acciones
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <button form="formCatalogo" type="submit" class="dropdown-item" name="addCatalogo">Guardar</button>
                                        <input type="submit" value="Regresar" formaction="gestionCatalogos.php" name="regresar" class="dropdown-item">
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <div class="spacer10"></div>
                            <form method="post" action="nuevoCatalogo_Productos.php" id="formCatalogo">
                                <div class="form-group row">
                                    <label for="tipo" class="col-3 col-form-label">Tipo de Catálogo:</label>
                                    <div class="col-9">
                                        <select class="form-control" name="tipo" id="tipo" required>
                                            <option disabled selected>Seleccionar</option>
                                            <option value="Productos">Productos</option>
                                            <option value="Entrenos">Entrenos</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="campana" class="col-3 col-form-label">Campaña:</label>
                                    <div class="col-9">
                                        <select name="campana" id="campana" class="form-control" onchange="codigoCatalogo(this.value,document.getElementById('tipo').value)">
                                            <option disabled selected>Seleccionar</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                            <option value="4">4</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                            <option value="11">11</option>
                                            <option value="12">12</option>
                                            <option value="13">13</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="codigo" class="col-3 col-form-label">Código de Catálogo:</label>
                                    <div class="col-9" id="catalogo">
                                        <input class="form-control" type="text" id="codigo" name="idCatalogo" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fechaInicio" class="col-3 col-form-label">Fecha de Inicio de Campaña:</label>
                                    <div class="col-9">
                                        <input class="form-control" type="date" id="fechaInicio" name="fechaInicio">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="fechaFin" class="col-3 col-form-label">Fecha de Cierre de Campaña:</label>
                                    <div class="col-9">
                                        <input class="form-control" type="date" id="fechaFin" name="fechaFin">
                                    </div>
                                </div>
                            </form>
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