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
                        <form method="post" action="gestionCatalogos.php" id="formProducto">
                            <div class="float-left">
                                <i class="fa fa-arrow-circle-left"></i>
                                Registrar Entrega de Catálogo
                            </div>
                            <div class="float-right">&nbsp;&nbsp;&nbsp;&nbsp;</div>
                            <div class="float-right">
                                <div class="dropdown">
                                    <input type="submit" value="Guardar" name="asignarCatalogo" class="btn btn-secondary btn-sm">
                                </div>
                            </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <div class="spacer10"></div>
                            <div class="form-group row">
                                <label for="codigo" class="col-2 col-form-label">Código de Catálogo:</label>
                                <div class="col-10">
                                    <input class="form-control" type="text" id="codigo" name="codigo" value="<?php echo $_POST['idCatalogo'];?>" readonly required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="fecha" class="col-2 col-form-label">Fecha de Entrega:</label>
                                <div class="col-10">
                                    <input class="form-control" type="date" id="fecha" name="fecha" placeholder="dd/mm/aaaa" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="stock" class="col-2 col-form-label">Cantidad:</label>
                                <?php
                                $result=mysqli_query($link,"SELECT * FROM Catalogo WHERE idCatalogo ='{$_POST['idCatalogo']}'");
                                while ($fila=mysqli_fetch_array($result)){
                                    $cantidad=$fila['stock'];
                                }
                                ?>
                                <div class="col-10" id="stocklimite">
                                    <input class="form-control" type="number" id="stock" min="0" name="cantidad" placeholder="<?php echo $cantidad;?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nombrecolaborador" class="col-2 col-form-label">Recibido por:</label>
                                <div class="col-10">
                                    <input type="text" id="nombrecolaborador" name="colaborador" required class="form-control">
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