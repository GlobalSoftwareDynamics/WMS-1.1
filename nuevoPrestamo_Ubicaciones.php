<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');
    include('funciones.php');

    $total=0;
    $i=0;
    $array=array();
    $result=mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}'");
    while ($fila=mysqli_fetch_array($result)){
        $cantidad=$fila['cantidad'];
        $query=mysqli_query($link,"SELECT * FROM Almacen ORDER BY prioridad ASC");
        while ($row=mysqli_fetch_array($query)){
            $query1=mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$fila['idProducto']}' AND idUbicacion IN (SELECT idUbicacion FROM Ubicacion WHERE idAlmacen = '{$row['idAlmacen']}') ORDER BY idUbicacion");
            while ($row1=mysqli_fetch_array($query1)){
                if($cantidad>0&&$row1['stock']>0){
                    if ($row1['stock']>$cantidad||$row1['stock']===$cantidad){
                        $array[$i]=array("{$row1['idProducto']}","{$row1['idUbicacion']}",$cantidad);
                        $cantidad=0;
                        $i++;
                    }else{
                        $cantidad=$cantidad-$row1['stock'];
                        $array[$i]=array("{$row1['idProducto']}","{$row1['idUbicacion']}",$row1['stock']);
                        $i++;
                    }
                }
            }
        }
    }
    ?>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-shopping-cart"></i>
                            Ubicación de Productos para Retiro
                        </div>
                        <div class="float-right">
                            <div class="dropdown">
                                <button form="formPS" name="addPS" class="btn btn-secondary btn-sm">Finalizar</button>
                                <button form="formPS" formaction="nuevoPrestamo_Productos.php" name="regresar" class="btn btn-secondary btn-sm">Regresar</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general" role="tab">Productos y Ubicaciones</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="spacer30"></div>
                                    <form method="post" action="gestionPrestamos.php" id="formPS">
                                        <input type='hidden' name='idTransaccion' value='<?php echo $_POST['idTransaccion'];?>'>
                                        <table class="table text-center">
                                            <thead>
                                            <tr>
                                                <th class="text-center">Producto</th>
                                                <th class="text-center">Ubicación</th>
                                                <th class="text-center">Cantidad</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            for ($row = 0; $row < $i; $row++) {
                                                echo "<tr>";
                                                for ($col = 0; $col < 3; $col++) {
                                                    echo "<td>".$array[$row][$col]."</td>";
                                                }
                                                echo "</tr>";
                                            }
                                            ?>
                                            </tbody>
                                        </table>
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
?>