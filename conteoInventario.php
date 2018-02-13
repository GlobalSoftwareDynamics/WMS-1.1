<?php
include('session.php');
include('funciones.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');

    $result=mysqli_query($link,"SELECT * FROM Producto WHERE idProducto='{$_POST['idProducto']}'");
    while($fila=mysqli_fetch_array($result)) {
        ?>
        <section class="container">
            <div class="row">
                <div class="col-5">
                    <div class="card">
                        <div class="card-header card-inverse card-info">
                            <div class="float-left">
                                <i class="fa fa-camera"></i>
                                Fotografía del Producto
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <img src="<?php echo $fila['urlImagen']; ?>" alt="foto" height="120" width="120" class="offset-4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-8">
                    <div class="card">
                        <div class="card-header card-inverse card-info">
                            <div class="float-left">
                                <i class="fa fa-info-circle"></i>
                                <?php echo $fila['nombreCorto']; ?>
                            </div>
                            <div class="float-right">
                                <button form="formConteo" name='conteo' class="btn btn-secondary btn-sm">Guardar</button>
                                <button form="formConteo" formaction="gestionInventario.php" name='regresar' class="btn btn-secondary btn-sm">Regresar</button>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="col-12">
                                <div class="spacer10"></div>
                                <form method='post' action="gestionInventario.php" id="formConteo">
                                    <input type="hidden" name="idProducto" id="idProducto" value="<?php echo $_POST['idProducto'];?>">
                                    <div class="form-group row">
                                        <?php
                                        $clase="CI";
                                        $codigo=idgen($clase);
                                        echo "<input type='hidden' name='codigo' value='{$codigo}' readonly>"
                                        ?>
                                        <label for="ubi" class="col-3 col-form-label">Ubicación:</label>
                                        <div class="col-9">
                                            <select name="ubicacion" id="ubi" class="form-control" onchange="conteoInventarioStock(<?php echo $_POST['idProducto'];?>,this.value)" required>
                                                <option selected disabled>Todas</option>
                                                <?php
                                                $result1=mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto ='{$_POST['idProducto']}'");
                                                while ($fila1=mysqli_fetch_array($result1)){
                                                    echo "
                                                    <option value='{$fila1['idUbicacion']}'>{$fila1['idUbicacion']}</option>
                                                ";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="stock" class="col-3 col-form-label">Stock Disponible:</label>
                                        <div class="col-9" id="stockfinal">
                                            <?php
                                            $stock=0;
                                            $result1=mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto ='{$_POST['idProducto']}'");
                                            while ($fila1=mysqli_fetch_array($result1)){
                                                $stock=$stock+$fila1['stock'];
                                            }
                                            ?>
                                            <input class="form-control" type="number" id="stock" min="0" name="stock" value="<?php echo $stock;?>" required readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="obs" class="col-3 col-form-label">Observaciones:</label>
                                        <div class="col-9">
                                            <input class="form-control" type="text" id="obs" name="observaciones">
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
    }
    include('footerTemplate.php');
}else{
    include('sessionError.php');
}
