<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');
    $fecha2=explode("-",$date);
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
                <div class="col-6 offset-1">
                    <div class="card">
                        <div class="card-header card-inverse card-info">
                            <div class="float-left">
                                <i class="fa fa-reorder"></i>
                                Información Relacionada
                            </div>
                        </div>
                        <div class="card-block">
                            <a href="gestionOC.php" class="icon-btn">
                                <?php
                                $result1=mysqli_query($link,"SELECT idProducto, SUM(cantidad) AS numero FROM TransaccionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idTransaccion IN (SELECT idTransaccion FROM Transaccion WHERE idTipoTransaccion = '1' AND idEstado IN (3,6))");
                                while ($fila1=mysqli_fetch_array($result1)) {
                                    ?>
                                    <i class="fa fa-truck"></i>
                                    <div>Ord. de Compra</div>
                                    <span class="badge badge-primary"><?php echo $fila1['numero'];?></span>
                                    <?php
                                }
                                ?>
                            </a>
                            <a href="gestionOV.php" class="icon-btn">
                                <?php
                                $result1=mysqli_query($link,"SELECT idProducto, SUM(cantidad) AS numero FROM TransaccionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idTransaccion IN (SELECT idTransaccion FROM Transaccion WHERE idTipoTransaccion = '5' AND fechaTransaccion LIKE '%/{$fecha2[1]}/%')");
                                while ($fila1=mysqli_fetch_array($result1)) {
                                    ?>
                                    <i class="fa fa-shopping-cart"></i>
                                    <div>Ord. de Venta</div>
                                    <span class="badge badge-primary"><?php echo $fila1['numero'];?></span>
                                    <?php
                                }
                                ?>
                            </a>
                            <a href="gestionInventario.php" class="icon-btn">
                                <?php
                                $stock=0;
                                $result1=mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProducto']}'");
                                while ($fila1=mysqli_fetch_array($result1)) {
                                    $stock=$stock+$fila1['stock'];
                                }
                                ?>
                                <i class="fa fa-line-chart"></i>
                                <div>Inventario</div>
                                <span class="badge badge-primary"><?php echo $stock;?></span>
                            </a>
                            <a href="gestionPrestamos.php" class="icon-btn">
                                <?php
                                $result1=mysqli_query($link,"SELECT idProducto, SUM(cantidad) AS numero FROM TransaccionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idTransaccion IN (SELECT idTransaccion FROM Transaccion WHERE idTipoTransaccion = '6' AND idEstado IN (3,6))");
                                while ($fila1=mysqli_fetch_array($result1)) {
                                    ?>
                                    <i class="fa fa-retweet"></i>
                                    <div>Préstamos</div>
                                    <span class="badge badge-primary"><?php echo $fila1['numero'];?></span>
                                    <?php
                                }
                                ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-inverse card-info">
                            <div class="float-left">
                                <i class="fa fa-info-circle"></i>
                                Detalle del Producto
                            </div>
                            <div class="float-right">
                                <form method='post'>
                                    <div class="dropdown">
                                        <input type="hidden" name="idProducto" value="<?php echo $_POST['idProducto'];?>">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Acciones
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <input type="submit" name='histTrans' class="dropdown-item" formaction='historialTransacciones.php' value="Historial de Transacciones">
                                            <input type="submit" name='editarProducto' class="dropdown-item" formaction='editarProducto.php' value="Editar">
                                            <input type="submit" name='retirar' class="dropdown-item" formaction='gestionProductos.php' value="Retirar">
                                            <input type="submit" name='regresar' class="dropdown-item" formaction='<?php echo $_POST['volver'];?>' value="Regresar">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-block">
                            <div class="col-12">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#general"
                                           role="tab">General</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#especifico"
                                           role="tab">Atributos</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#inventario"
                                           role="tab">Logística</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#url" role="tab">URL</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#graph" role="tab">Estadísticas de Venta</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="general" role="tabpanel">
                                        <p></p>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Nombre del Producto:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <p><?php echo $fila['nombreCorto']?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Descripción:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <p><?php echo $fila['descripcion']?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Código SKU:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <p><?php echo $fila['idProducto']?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Tipo de Producto:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <?php
                                                $result1=mysqli_query($link,"SELECT * FROM TipoProducto WHERE idTipoProducto ='{$fila['idTipoProducto']}'");
                                                while ($fila1=mysqli_fetch_array($result1)){
                                                    echo "<p>".$fila1['descripcion']."</p>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Categoría:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <?php
                                                $result1=mysqli_query($link,"SELECT * FROM Categoria WHERE idCategoria IN (SELECT idCategoria FROM SubCategoria WHERE idSubCategoria ='{$fila['idSubCategoria']}')");
                                                while ($fila1=mysqli_fetch_array($result1)){
                                                    echo "<p>".$fila1['descripcion']."</p>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>SubCategoría:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <?php
                                                $result1=mysqli_query($link,"SELECT * FROM SubCategoria WHERE idSubCategoria ='{$fila['idSubCategoria']}'");
                                                while ($fila1=mysqli_fetch_array($result1)){
                                                    echo "<p>".$fila1['descripcion']."</p>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Fecha de Creación:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <p><?php echo $fila['fechaCreacion']?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="especifico" role="tabpanel">
                                        <p></p>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Unidad de Medida:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <?php
                                                $result1=mysqli_query($link,"SELECT * FROM UnidadMedida WHERE idUnidadMedida ='{$fila['idUnidadMedida']}'");
                                                while ($fila1=mysqli_fetch_array($result1)){
                                                    echo "<p>".$fila1['descripcion']."</p>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Atributo:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <?php
                                                $result1=mysqli_query($link,"SELECT * FROM Color WHERE idColor ='{$fila['idColor']}'");
                                                while ($fila1=mysqli_fetch_array($result1)){
                                                    echo "<p>".$fila1['descripcion']."</p>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Género:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <?php
                                                $result1=mysqli_query($link,"SELECT * FROM Genero WHERE idGenero ='{$fila['idGenero']}'");
                                                while ($fila1=mysqli_fetch_array($result1)){
                                                    echo "<p>".$fila1['descripcion']."</p>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Tamaño:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <?php
                                                $result1=mysqli_query($link,"SELECT * FROM Tamaño WHERE idTamaño ='{$fila['idTamaño']}'");
                                                while ($fila1=mysqli_fetch_array($result1)){
                                                    echo "<p>".$fila1['nombre']."</p>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="inventario" role="tabpanel">
                                        <p></p>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Precio Promedio:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <p><?php echo $fila['costoEstimado']?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Stock de Reposición:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <p><?php echo $fila['puntoReposicion']?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>Stock Actual:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <?php
                                                $stock=0;
                                                $result1=mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto ='{$_POST['idProducto']}'");
                                                while ($fila1=mysqli_fetch_array($result1)){
                                                    $stock=$stock+$fila1['stock'];
                                                }
                                                echo "<p>".$stock."</p>";
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="url" role="tabpanel">
                                        <p></p>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>URL Imagen:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <p style="overflow: scroll"><?php echo $fila['urlImagen']?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2">
                                                <p><b>URL del Producto:</b></p>
                                            </div>
                                            <div class="col-8">
                                                <p style="overflow: scroll"><?php echo $fila['urlProducto']?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="graph" role="tabpanel">
                                        <?php
                                        $anioFecha=explode("0",$fecha2[2]);
                                        $fragmento="";
                                        $aux1="";
                                        $mes="";
                                        for ($i = 1; $i < 13; $i++) {
                                            switch ($i) {
                                                case 1:
                                                    $aux1 = "A";
                                                    $mes="Enero";
                                                    break;
                                                case 2:
                                                    $aux1 = "B";
                                                    $mes="Febrero";
                                                    break;
                                                case 3:
                                                    $aux1 = "C";
                                                    $mes="Marzo";
                                                    break;
                                                case 4:
                                                    $aux1 = "D";
                                                    $mes="Abril";
                                                    break;
                                                case 5:
                                                    $aux1 = "E";
                                                    $mes="Mayo";
                                                    break;
                                                case 6:
                                                    $aux1 = "F";
                                                    $mes="Junio";
                                                    break;
                                                case 7:
                                                    $aux1 = "G";
                                                    $mes="Julio";
                                                    break;
                                                case 8:
                                                    $aux1 = "H";
                                                    $mes="Agosto";
                                                    break;
                                                case 9:
                                                    $aux1 = "I";
                                                    $mes="Septiembre";
                                                    break;
                                                case 10:
                                                    $aux1 = "J";
                                                    $mes="Octubre";
                                                    break;
                                                case 11:
                                                    $aux1 = "K";
                                                    $mes="Noviembre";
                                                    break;
                                                case 12:
                                                    $aux1 = "L";
                                                    $mes="Diciembre";
                                                    break;
                                            }
                                            $result=mysqli_query($link, "SELECT idProducto, COUNT(*) AS numero FROM TransaccionProducto WHERE idTransaccion LIKE 'OV".$anioFecha[1]."_%".$aux1."%'");
                                            while ($fila=mysqli_fetch_array($result)){
                                                $fragmento=$fragmento.",['".$mes."',".$fila['numero']."]";
                                            }
                                        }
                                        $fragmento="['Mes','Cantidad']".$fragmento;
                                        ?>
                                        <script type="text/javascript">
                                            // Load the Visualization API and the corechart package.
                                            google.charts.load('current', {'packages':['corechart']});
                                            // Set a callback to run when the Google Visualization API is loaded.

                                            google.charts.setOnLoadCallback(drawChart1);
                                            function drawChart1() {
                                                var data = google.visualization.arrayToDataTable([
                                                    <?php echo $fragmento;?>
                                                ]);
                                                var view = new google.visualization.DataView(data);
                                                view.setColumns([0, 1,
                                                    { calc: "stringify",
                                                        sourceColumn: 1,
                                                        type: "string",
                                                        role: "annotation" }
                                                ]);
                                                var options = {
                                                    width: '1050',
                                                    height: 300
                                                };
                                                // Instantiate and draw our chart, passing in some options.
                                                var chart = new google.visualization.LineChart(document.getElementById('grafica'));
                                                chart.draw(view, options);
                                            }
                                        </script>
                                        <div class="row">
                                            <div id="grafica" ></div>
                                        </div>
                                    </div>
                                </div>
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
