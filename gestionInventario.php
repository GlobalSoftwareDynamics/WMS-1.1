<?php
include('session.php');
include ('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');

    if (isset($_POST['conteo'])){

        $query=mysqli_query($link,"INSERT INTO Transaccion VALUES ('{$_POST['codigo']}',5,'20539587294',8,'{$_SESSION['user']}',null,null,'{$dateTime}',null,null,null,'{$_POST['observaciones']}',null,null,null)");

        $queryPerformed = "INSERT INTO Transaccion VALUES ({$_POST['codigo']},5,20539587294,8,{$_SESSION['user']},null,null,{$dateTime},null,null,null,{$_POST['observaciones']},null,null,null)";

        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','ConteoInventario','{$queryPerformed}')");

        $stockfinal=0;
        $stockinicial=0;
        $diferencia=0;
        $tabla=mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idUbicacion = '{$_POST['ubicacion']}'");
        while ($row=mysqli_fetch_array($tabla)){
            $stockinicial=$row['stock'];
            $diferencia=$_POST['stock']-$stockinicial;
        }

        $stockinicial=0;
        $tabla=mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProducto']}'");
        while ($row=mysqli_fetch_array($tabla)){
            $stockinicial += $row['stock'];
            $stockfinal += $row['stock'];
        }
        $stockfinal += $diferencia;

        $query="UPDATE UbicacionProducto SET stock = '{$_POST['stock']}', fechaModificacion = '{$date}' WHERE idProducto = '{$_POST['idProducto']}' AND idUbicacion = '{$_POST['ubicacion']}'";
        $update=mysqli_query($link,$query);

        $queryPerformed = "UPDATE UbicacionProducto SET stock = {$_POST['stock']}, fechaModificacion = {$date} WHERE idProducto = {$_POST['idProducto']} AND idUbicacion = {$_POST['ubicacion']}";

        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','UbicacionProducto','{$queryPerformed}')");

        $query=mysqli_query($link,"INSERT INTO TransaccionProducto VALUES ('{$_POST['idProducto']}','{$_POST['codigo']}','{$_POST['ubicacion']}','{$_POST['ubicacion']}',null,0,{$diferencia},null,'{$_POST['observaciones']}',{$stockinicial},{$stockfinal},null,0)");

        $queryPerformed = "INSERT INTO TransaccionProducto VALUES ({$_POST['idProducto']},{$_POST['codigo']},{$_POST['ubicacion']},{$_POST['ubicacion']},null,0,{$diferencia},null,{$_POST['observaciones']},{$stockinicial},{$stockfinal},null,0)";

        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','ConteoInventarioProducto','{$queryPerformed}')");
    }

    if (isset($_POST['transferencia'])) {

        $query = mysqli_query($link,"INSERT INTO Transaccion VALUES ('{$_POST['codigo']}',5,'20539587294',7,'{$_SESSION['user']}',null,null,'{$dateTime}',null,null,null,'{$_POST['observaciones']}',null,null,null)");

        $queryPerformed = "INSERT INTO Transaccion VALUES ({$_POST['codigo']},5,20539587294,7,{$_SESSION['user']},null,null,{$dateTime},null,null,null,{$_POST['observaciones']},null,null,null)";

        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','ConteoInventario','{$queryPerformed}')");

        echo $queryPerformed." ";

        $stockinicial1 = 0;
        $stockinicial2 = 0;
        $tabla = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idUbicacion = '{$_POST['ubicacioninicial']}'");
        while ($row = mysqli_fetch_array($tabla)) {
            $stockinicial1 = $row['stock'];
            $stockFinalInicial = $row['stock'] - $_POST['stock'];
        }
        $tabla = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idUbicacion = '{$_POST['ubicaciondestino']}'");
        $numrows = mysqli_num_rows($tabla);
        if ($numrows > 0) {
            while ($row = mysqli_fetch_array($tabla)) {
                $stockinicial2 = $row['stock'];
                $stockFinalFinal = $row['stock'] + $_POST['stock'];
            }
        } else {
            $query = mysqli_query($link, "INSERT INTO UbicacionProducto VALUES ('{$_POST['idProducto']}','{$_POST['ubicaciondestino']}',0,'{$date}')");
            $queryPerformed = "INSERT INTO UbicacionProducto VALUES ({$_POST['idProducto']},{$_POST['ubicaciondestino']},0,{$date})";
            $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','UbicacionProducto','{$queryPerformed}')");
            $stockFinalFinal = 0 + $_POST['stock'];
        }

        $stockinventario = 0;
        $tabla = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProducto']}'");
        while ($row = mysqli_fetch_array($tabla)) {
            $stockinventario += $row['stock'];
        }

        $query = mysqli_query($link,"INSERT INTO TransaccionProducto VALUES ('{$_POST['idProducto']}','{$_POST['codigo']}','{$_POST['ubicacioninicial']}','{$_POST['ubicaciondestino']}',null,0,{$_POST['stock']},null,'{$_POST['observaciones']}',{$stockinventario},{$stockinventario},null,0)");

        $queryPerformed = "INSERT INTO TransaccionProducto VALUES ({$_POST['idProducto']},{$_POST['codigo']},{$_POST['ubicacioninicial']},{$_POST['ubicaciondestino']},null,0,{$_POST['stock']},null,{$_POST['observaciones']},{$stockinventario},{$stockinventario},null,0)";

        echo $queryPerformed;

        $update = mysqli_query($link, "UPDATE UbicacionProducto SET stock = '{$stockFinalInicial}', fechaModificacion = '{$date}' WHERE idProducto = '{$_POST['idProducto']}' AND idUbicacion = '{$_POST['ubicacioninicial']}'");
        $queryPerformed = "UPDATE UbicacionProducto SET stock = {$stockFinalInicial}, fechaModificacion = {$date} WHERE idProducto = {$_POST['idProducto']} AND idUbicacion = {$_POST['ubicacioninicial']}";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','ConteoInventarioProducto','{$queryPerformed}')");

        $update = mysqli_query($link, "UPDATE UbicacionProducto SET stock = '{$stockFinalFinal}', fechaModificacion = '{$date}' WHERE idProducto = '{$_POST['idProducto']}' AND idUbicacion = '{$_POST['ubicaciondestino']}'");
        $queryPerformed = "UPDATE UbicacionProducto SET stock = {$stockFinalFinal}, fechaModificacion = {$date} WHERE idProducto = {$_POST['idProducto']} AND idUbicacion = {$_POST['ubicaciondestino']}";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','ConteoInventarioProducto','{$queryPerformed}')");

    }

?>

    <script>
        function myFunction() {
            // Declare variables
            var input, input2, input3, input4, filter, filter2, filter3, filter4, table, tr, td, td2, td3, td4, i;
            input = document.getElementById("SKU");
            input2 = document.getElementById("nombre");
            input3 = document.getElementById("subCategoria");
			input4 = document.getElementById("FechaVariacion");
            filter = input.value.toUpperCase();
            filter2 = input2.value.toUpperCase();
            filter3 = input3.value.toUpperCase();
			filter4 = input4.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                td2 = tr[i].getElementsByTagName("td")[1];
                td3 = tr[i].getElementsByTagName("td")[3];
				td4 = tr[i].getElementsByTagName("td")[5];
                if ((td)&&(td2)&&(td3)) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        if(td2.innerHTML.toUpperCase().indexOf(filter2) > -1){
                            if(td3.innerHTML.toUpperCase().indexOf(filter3) > -1){
								if(td4.innerHTML.toUpperCase().indexOf(filter4) > -1){
                                	tr[i].style.display = "";
								}else{
                                	tr[i].style.display = "none";
                            	}
                            }else{
                                tr[i].style.display = "none";
                            }
                        }else{
                            tr[i].style.display = "none";
                        }
                    }else{
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    </script>

    <section class="container">
        <div class="card">
            <div class="card-header card-inverse card-info">
                <i class="fa fa-list"></i>
                Listado de Productos
                <div class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="gestionAlmacenes.php">Gestionar Almacenes</a>
                            <a class="dropdown-item" href="recepcionPremios.php">Registrar Productos a Costo Cero</a>
                            <a class="dropdown-item" href="files/inventario.txt" download>Exportar Listado</a>
                        </div>
                    </div>
                </div>
                <span class="float-right">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <div class="float-right">
                    <button href="#collapsed" class="btn btn-secondary btn-sm" data-toggle="collapse">Mostrar Filtros</button>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-12">
                        <div id="collapsed" class="collapse">
                            <form class="form-inline justify-content-center" method="post" action="#">
                                <label class="sr-only" for="SKU">SKU</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="SKU" placeholder="SKU" onkeyup="myFunction()">
                                <label class="sr-only" for="Nombre">Nombre de Producto</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="nombre" placeholder="Nombre" onkeyup="myFunction()">
                                <label class="sr-only" for="subCategoria">SubCategoría</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="subCategoria" placeholder="SubCategoría" onkeyup="myFunction()">
                                <label class="sr-only" for="FechaVariacion">Fecha de Variación</label>
                                <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="FechaVariacion" placeholder="Año-Mes-Día" onkeyup="myFunction()">
                                <input type="submit" class="btn btn-primary" value="Limpiar" style="padding-left:28px; padding-right: 28px;">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="spacer10"></div>
                <div class="row">
                    <div class="col-12" style="overflow-y: scroll; height: 500px;">
                        <table class="table table-bordered" id="myTable">
                            <thead class="thead-default">
                            <tr>
                                <th class="text-center">SKU</th>
                                <th class="text-center">Nombre de Producto</th>
                                <th class="text-center">Atributo</th>
                                <th class="text-center">SubCategoría</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Última Variación</th>
                                <th class="text-center">V.U. Promedio</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $file = fopen("files/inventario.txt","w") or die("No se encontró el archivo!");
                            fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                            $txt = "SKU,Nombre Corto,Atributo,Stock,Fecha de Modificación,Precio Promedio".PHP_EOL;
                            fwrite($file, $txt);
                            $stock = 0;
                            $color = null;
                            $fechaActual = array();
                            $flag = true;
                            $fechaUltimaModificacion = array();
                            $select = mysqli_query($link, "SELECT idProducto,idSubCategoria,idColor,nombreCorto,costoEstimado FROM Producto WHERE idProducto IN (SELECT idProducto FROM UbicacionProducto WHERE stock > 0)");
                            while($row = mysqli_fetch_array($select)){
                                if($row['idSubCategoria'] === '10'){
                                }else{
                                    $stock = 0;
                                    $fechaUltima = array();
                                    $flag = true;
                                    $select2 = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$row['idProducto']}'");
                                    while($row2 = mysqli_fetch_array($select2)){
                                        $stock += $row2['stock'];
                                        $fechaAct = explode("|",$row2['fechaModificacion']);
                                        $fechaActual = explode("-",$fechaAct[0]);
                                        if($flag){
                                            $fechaUltima = $fechaActual;
                                            $flag = false;
                                        }
                                        if($fechaActual[0]>$fechaUltima[0]){
                                            $fechaUltima = $fechaActual;
                                        }elseif(($fechaActual[0]==$fechaUltima[0])&&($fechaActual[1]>$fechaUltima[1])){
                                            $fechaUltima = $fechaActual;
                                        }elseif(($fechaActual[0]==$fechaUltima[0])&&($fechaActual[1]==$fechaUltima[1])&&($fechaActual[2]>$fechaUltima[2])){
                                            $fechaUltima = $fechaActual;
                                        }
                                    }
                                    $fechaUltimaModificacion = $fechaUltima;
                                    if($fechaUltimaModificacion == null){
                                        $mostrarFecha = "N/A";
                                    }else{
                                        $mostrarFecha = $fechaUltimaModificacion[0]."-".$fechaUltimaModificacion[1]."-".$fechaUltimaModificacion[2];
                                    }
                                    $select2 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = {$row['idColor']}");
                                    while($row2 = mysqli_fetch_array($select2)){
                                        $color = $row2['descripcion'];
                                    }
									
									$select2 = mysqli_query($link,"SELECT * FROM SubCategoria WHERE idSubCategoria = {$row['idColor']}");
                                    while($row2 = mysqli_fetch_array($select2)){
                                        $subCategoria = $row2['descripcion'];
                                    }

                                    $costoEstimado = round($row['costoEstimado'],2);
                                    echo "<tr>
                                        <td class=\"text-center\">{$row['idProducto']}</td>
                                        <td class=\"text-center\">{$row['nombreCorto']}</td>
                                        <td class=\"text-center\">{$color}</td>
										<td class=\"text-center\">{$subCategoria}</td>
                                        <td class=\"text-center\">{$stock}</td>
                                        <td class=\"text-center\">{$mostrarFecha}</td>
                                        <td class=\"text-center\">S/. {$costoEstimado}</td>
                                        <td class=\"text-center\">
                                            <form method='post'>
                                                <div class=\"dropdown\">
                                                    <input type='hidden' name='idProducto' value='".$row['idProducto']."'>
                                                    <input type='hidden' name='volver' value='gestionInventario.php'>
                                                    <button class=\"btn btn-secondary btn-sm dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                                    Acciones
                                                    </button>
                                                    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                                                        <button name='comprobarInventario' class=\"dropdown-item\" type=\"submit\" formaction='conteoInventario.php'>Comprobación de Inventario</button>
                                                        <button name='transferirProducto' class=\"dropdown-item\" type=\"submit\" formaction='transferenciaUbicacion.php'>Transferencia de Ubicación</button>
                                                        <button name='verProducto' class=\"dropdown-item\" type=\"submit\" formaction='detalleProducto.php'>Ver Detalles de Producto</button>
														<button name='editar' class=\"dropdown-item\" type=\"submit\" formaction='editarProducto.php'>Editar Producto</button>
                                                        <button name='verUbicacionProducto' class=\"dropdown-item\" type=\"submit\" formaction='ubicacionProducto.php'>Ver Ubicaciones de Producto</button>
                                                        <button name='historialProductoInv' class=\"dropdown-item\" type=\"submit\" formaction='historialTransacciones.php'>Historial de Transacciones</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                      </tr>";
	                                $txt = $row['idProducto'].",".$row['nombreCorto'].",".$color.",".$stock.",".$mostrarFecha.",".$costoEstimado.PHP_EOL;
	                                fwrite($file, $txt);
                                }
                            }
                            fclose($file);
                            ?>
                            </tbody>
                        </table>
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