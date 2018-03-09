<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');

    if(isset($_POST['agregarCuenta'])){

        $query=mysqli_query($link,"INSERT INTO Cuenta VALUES ({$_POST['numerocuenta']},1,null,{$_POST['saldo']},'{$_POST['alias']}')");

        $queryPerformed = "INSERT INTO Cuenta VALUES ({$_POST['numerocuenta']},1,null,{$_POST['saldo']},{$_POST['alias']})";

        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Cuenta','{$queryPerformed}')");

    }

    if(isset($_POST['registrarMovimiento'])){

        $result=mysqli_query($link,"SELECT * FROM Proveedor WHERE nombre = '{$_POST['nombreProveedor']}'");
        while ($fila=mysqli_fetch_array($result)){
            $idProveedor=$fila['idProveedor'];
        }

        if(!empty($_POST['transaccionPrimaria'])){
            $_POST['transaccionPrimaria']="'{$_POST['transaccionPrimaria']}'";
            $result = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = {$_POST['transaccionPrimaria']}");
            while ($fila = mysqli_fetch_array($result)){
                $montoRestante = $fila['montoRestante'] - $_POST['monto'];
                if($montoRestante<0){
                    $montoRestante = 0;
                }
                $idTipoTransaccionPrimaria = $fila['idTipoTransaccion'];
            }

                $query = mysqli_query($link,"UPDATE Transaccion SET montoRestante = {$montoRestante} WHERE idTransaccion = {$_POST['transaccionPrimaria']}");

                $queryPerformed = "UPDATE Transaccion SET montoRestante = {$montoRestante} WHERE idTransaccion = {$_POST['transaccionPrimaria']}";

                $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','Transaccion','{$queryPerformed}')");

        }else{
            $_POST['transaccionPrimaria']="null";
        }
        if(!empty($_POST['transaccionReferencia'])){
            $_POST['transaccionReferencia']="'{$_POST['transaccionReferencia']}'";
            $result = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = {$_POST['transaccionReferencia']}");
            while ($fila = mysqli_fetch_array($result)){
                $montoRestante = $fila['montoRestante'] - $_POST['monto'];
                if($montoRestante<0){
                    $montoRestante = 0;
                }
                $idTipoTransaccionReferencial = $fila['idTipoTransaccion'];
            }

                $query = mysqli_query($link,"UPDATE Transaccion SET montoRestante = {$montoRestante} WHERE idTransaccion = {$_POST['transaccionReferencia']}");

                $queryPerformed = "UPDATE Transaccion SET montoRestante = {$montoRestante} WHERE idTransaccion = {$_POST['transaccionReferencia']}";

                $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','Transaccion','{$queryPerformed}')");

        }else{
            $_POST['transaccionReferencia']="null";
        }

        if($_POST['cuenta']=="cupon"){
            $_POST['cuenta']="null";
        }else{
            $operacion = mysqli_query($link,"SELECT * FROM TipoMovimiento WHERE idTipoMovimiento = '{$_POST['tipo']}'");
            while ($row=mysqli_fetch_array($operacion)){
                $cuenta = mysqli_query($link,"SELECT * FROM Cuenta WHERE idCuenta = '{$_POST['cuenta']}'");
                while ($filacuenta=mysqli_fetch_array($cuenta)){
                    switch ($row['tipo']){
                        case 0:
                            $saldo = $filacuenta['saldo']-$_POST['monto'];
                            $query=mysqli_query($link,"UPDATE Cuenta SET saldo = {$saldo}, fechaActualizacion = '{$date}' WHERE idCuenta = '{$_POST['cuenta']}'");
                            $queryPerformed="UPDATE Cuenta SET saldo = {$saldo}, fechaActualizacion = {$date} WHERE idCuenta = {$_POST['cuenta']}";
                            $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','Cuenta','{$queryPerformed}')");
                            break;
                        case 1:
                            $saldo = $filacuenta['saldo']+$_POST['monto'];
                            $query=mysqli_query($link,"UPDATE Cuenta SET saldo = {$saldo}, fechaActualizacion = '{$date}' WHERE idCuenta = '{$_POST['cuenta']}'");
                            $queryPerformed="UPDATE Cuenta SET saldo = {$saldo}, fechaActualizacion = {$date} WHERE idCuenta = {$_POST['cuenta']}";
                            $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','Cuenta','{$queryPerformed}')");
                            break;
                    }
                }
            }

            $logsaldo = mysqli_query($link,"SELECT * FROM LogSaldos WHERE idCuenta = '{$_POST['cuenta']}' AND fecha = '{$date}'");
            $numrows=mysqli_num_rows($logsaldo);
            if($numrows>0){

                while ($filax=mysqli_fetch_array($logsaldo)){
                    $query = mysqli_query($link,"UPDATE LogSaldos SET saldo = {$saldo} WHERE idLogSaldos = '{$filax['idLogSaldos']}'");
                    $queryPerformed="UPDATE LogSaldos SET saldo = {$saldo} WHERE idLogSaldos = {$filax['idLogSaldos']}";
                    $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','LogSaldos','{$queryPerformed}')");
                }

            }else{

                $query = mysqli_query($link,"INSERT INTO LogSaldos(idCuenta,saldo,fecha) VALUES('{$_POST['cuenta']}','{$saldo}','{$date}')");
                $queryPerformed="INSERT INTO LogSaldos(idCuenta,saldo,fecha) VALUES({$_POST['cuenta']},{$saldo},{$date})";
                $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','LogSaldos','{$queryPerformed}')");

            }

            $_POST['cuenta']="'{$_POST['cuenta']}'";
        }

        $query=mysqli_query($link,"INSERT INTO Movimiento VALUES ('{$_POST['idMovimiento']}',{$_POST['cuenta']},'{$idProveedor}',{$_POST['transaccionPrimaria']},{$_POST['transaccionReferencia']},
        '{$_POST['medioPago']}','{$_POST['comprobante']}','{$_POST['tipo']}','{$_SESSION['user']}','{$dateTime}',null,'{$_POST['monto']}','{$_POST['observacion']}')");

        $queryPerformed="INSERT INTO Movimiento VALUES ({$_POST['idMovimiento']},{$_POST['cuenta']},{$idProveedor},{$_POST['transaccionPrimaria']},{$_POST['transaccionReferencia']},
        {$_POST['medioPago']},{$_POST['comprobante']},{$_POST['tipo']},{$_SESSION['user']},{$dateTime},null,{$_POST['monto']},{$_POST['observacion']})";

        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Movimiento','{$queryPerformed}')");

    }

$numcuentasquery=mysqli_query($link,"SELECT * FROM Cuenta");
$numcuentas=mysqli_num_rows($numcuentasquery);

    $today = date("Y-m-d");
    $ago = date('Y-m-d', strtotime($today. ' - 30 days'));

?>
    <script>
        function myFunction() {
            //Determine Tab
            var j;
            var tab, tabla;
            var input, input4, input5, input6, input7, filter, filter4, filter5, filter6, filter7, table, tr, td, td2, td3, td4, td5, i;

            for(j=0; j<=<?php echo $numcuentas?>; j++){
                tab=document.getElementById(j);
                if(tab.className === "nav-link active"){
                    tabla="myTable"+j;
                }
            }

            // Declare variables
            input = document.getElementById("fecha");
            input4 = document.getElementById("idTransaccion");
            input5 = document.getElementById("persona");
            input6 = document.getElementById("mediopago");
            input7 = document.getElementById("tipoMovimiento");
            filter = input.value.toUpperCase();
            filter4 = input4.value.toUpperCase();
            filter5 = input5.value.toUpperCase();
            filter6 = input6.value.toUpperCase();
            filter7 = input7.value.toUpperCase();
            table = document.getElementById(tabla);
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                td2 = tr[i].getElementsByTagName("td")[1];
                td3 = tr[i].getElementsByTagName("td")[4];
                td4 = tr[i].getElementsByTagName("td")[5];
                td5 = tr[i].getElementsByTagName("td")[6];
                if ((td)&&(td2)) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        if(td2.innerHTML.toUpperCase().indexOf(filter4) > -1){
                            if(td3.innerHTML.toUpperCase().indexOf(filter5) > -1){
                                if(td4.innerHTML.toUpperCase().indexOf(filter6) > -1){
                                    if(td5.innerHTML.toUpperCase().indexOf(filter7) > -1){
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
                <div class="float-left">
                    <i class="fa fa-exchange"></i>
                    Movimientos Financieros
                </div>
                <div class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="nuevoMovimiento.php">Registrar Movimiento</a>
                            <a class="dropdown-item" href="nuevaCuenta.php">Agregar Cuenta</a>
                            <a class="dropdown-item" href="files/caja.txt" download>Exportar Listado</a>
                        </div>
                    </div>
                </div>
                <span class="float-right">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                <span class="float-right">
                    <button href="#collapsed" class="btn btn-secondary btn-sm" data-toggle="collapse">Mostrar Filtros</button>
                </span>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-12">
                        <div id="collapsed" class="collapse">
                            <form class="form-inline justify-content-center" method="post" action="#">
                                <label class="sr-only" for="fecha">Fecha</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="fecha" placeholder="Año-Mes-Día" onkeyup="myFunction()">
                                <label class="sr-only" for="idTransaccion">Código</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="idTransaccion" placeholder="Código" onkeyup="myFunction()">
                                <label class="sr-only" for="persona">Persona</label>
                                <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="persona" placeholder="Persona" onkeyup="myFunction()">
                                <label class="sr-only" for="mediopago">Medio de Pago</label>
                                <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="mediopago" placeholder="Medio de Pago" onkeyup="myFunction()">
                                <label class="sr-only" for="tipoMovimiento">Tipo</label>
                                <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="tipoMovimiento" placeholder="Tipo" onkeyup="myFunction()">
                                <input type="submit" class="btn btn-primary" value="Limpiar" style="padding-left:28px; padding-right: 28px;">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="spacer10"></div>
                <div class="row">
                    <div class="col-12">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#general" role="tab" id="0">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#cajachica" role="tab" id="1">Caja Chica</a>
                            </li>
                            <?php
                            $aux=1;
                            $result=mysqli_query($link,"SELECT * FROM Cuenta WHERE tipo <> 0");
                            while ($fila=mysqli_fetch_array($result)){
                                $aux++;
                                echo "
                                    <li class='nav-item'>
                                        <a class='nav-link' data-toggle='tab' href='#Cuenta{$aux}' role='tab'  id='{$aux}'>Cta. {$fila['alias']}</a>
                                    </li>
                                ";
                            }
                            echo "<p style='display: none' id='conteo'>{$aux}</p>";
                            ?>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="general" role="tabpanel">
                                <table class="table table-bordered text-center" id="myTable0">
                                    <thead class="thead-default">
                                    <tr>
                                        <th class="text-center" style="width: 8%">Fecha</th>
                                        <th class="text-center" style="width: 9%">Código</th>
                                        <th class="text-center" style="width: 9%">Ref. Primaria</th>
                                        <th class="text-center" style="width: 9%">Ref. Secundaria</th>
                                        <th class="text-center" style="width: 13%">Persona</th>
                                        <th class="text-center" style="width: 10%">Medio de Pago</th>
                                        <th class="text-center" style="width: 10%">Tipo</th>
                                        <th class="text-center" style="width: 12%">Monto</th>
                                        <th class="text-center" style="width: 13%">Notas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $file = fopen("files/caja.txt","w") or die("No se encontró el archivo!");
                                    fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                                    $txt = "Fecha,Referencia Primaria,Referencia Secundaria,Persona,Medio de Pago,Comprobante,Tipo,Monto,Destino".PHP_EOL;
                                    fwrite($file, $txt);
                                    $proveedor = null;
                                    $medioPago = null;
                                    $comprobante = null;
                                    $tipoMovimiento = null;
                                    $result=mysqli_query($link,"SELECT * FROM Movimiento WHERE fecha >= '{$ago} 00:00:00' ORDER BY fecha DESC");
                                    while ($fila=mysqli_fetch_array($result)){
                                        if($fila['monto'] > 0){
											$fecha=explode("|",$fila['fecha']);
											echo "<tr>";
											echo "
												<td>{$fecha[0]}</td>
												<td>
													<form method='post' action='detalleMovimiento.php'>
														<input type='hidden' name='idMovimiento' value='{$fila['idMovimiento']}'>
														<input type='submit' name='detalle' value='{$fila['idMovimiento']}' class='btn-link'>
													</form>
												</td>
											";
											if($fila['idTransaccionPrimaria']!=null){
												$result2=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$fila['idTransaccionPrimaria']}'");
												while ($fila2=mysqli_fetch_array($result2)){
													if($fila2['idTipoTransaccion']==1){
														echo "
														<td>
														<form method='post' action='detalleOC.php'>
															<input type='hidden' name='idTransaccionOC' value='{$fila['idTransaccionPrimaria']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionPrimaria']}' class='btn-link'>
														</form>
														</td>
														";
													}elseif ($fila2['idTipoTransaccion']==2||$fila2['idTipoTransaccion']==6){
														echo "
														<td>
														<form method='post' action='detallePrestamo.php'>
															<input type='hidden' name='idTransaccion' value='{$fila['idTransaccionPrimaria']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionPrimaria']}' class='btn-link'>
														</form>
														</td>
														";
													}elseif ($fila2['idTipoTransaccion']==5){
														echo "
														<td>
														<form method='post' action='detalleOV.php'>
															<input type='hidden' name='idTransaccion' value='{$fila['idTransaccionPrimaria']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionPrimaria']}' class='btn-link'>
														</form>
														</td>
														";
													}else{
														echo "<td>{$fila['idTransaccionPrimaria']}</td>";
													}
												}
											}else{
												echo "
												<td></td>
												";
											}
											if($fila['idTransaccionReferencial']!=null){
												$result2=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$fila['idTransaccionReferencial']}'");
												while ($fila2=mysqli_fetch_array($result2)){
													if($fila2['idTipoTransaccion']==1){
														echo "
														<td>
														<form method='post' action='detalleOC.php'>
															<input type='hidden' name='idTransaccionOC' value='{$fila['idTransaccionReferencial']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionReferencial']}' class='btn-link'>
														</form>
														</td>
														";
													}elseif ($fila2['idTipoTransaccion']==2||$fila2['idTipoTransaccion']==6){
														echo "
														<td>
														<form method='post' action='detallePrestamo.php'>
															<input type='hidden' name='idTransaccion' value='{$fila['idTransaccionReferencial']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionReferencial']}' class='btn-link'>
														</form>
														</td>
														";
													}elseif ($fila2['idTipoTransaccion']==5){
														echo "
														<td>
														<form method='post' action='detalleOV.php'>
															<input type='hidden' name='idTransaccion' value='{$fila['idTransaccionReferencial']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionReferencial']}' class='btn-link'>
														</form>
														</td>
														";
													}else{
														echo "<td>{$fila['idTransaccionReferencial']}</td>";
													}
												}
											}else{
												echo "
												<td></td>
												";
											}
											$result3=mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$fila['idProveedor']}'");
											while ($fila3=mysqli_fetch_array($result3)){
												$proveedor = $fila3['nombre'];
												echo "<td>{$fila3['nombre']}</td>";
											}
											$result4=mysqli_query($link,"SELECT * FROM MedioPago WHERE idMedioPago = '{$fila['idMedioPago']}'");
											$numrows=mysqli_num_rows($result4);
											if($numrows>0){
												while ($fila4=mysqli_fetch_array($result4)){
													$medioPago = $fila4['descripcion'];
													echo "<td>{$fila4['descripcion']}</td>";
												}
											}else{
												echo "<td></td>";
											}
											$result4=mysqli_query($link,"SELECT * FROM TipoMovimiento WHERE idTipoMovimiento = '{$fila['idTipoMovimiento']}'");
											while ($fila4=mysqli_fetch_array($result4)){
												$tipoMovimiento = $fila4['descripcion'];
												echo "<td>{$fila4['descripcion']}</td>";
												if($fila4['tipo']==0){
													$signo="-";
												}else{
													$signo="+";
												}
											}
											echo "<td>S/. {$signo} {$fila['monto']}</td>";
											echo "<td>{$fila['observaciones']}</td>";
											echo "</tr>";
											$txt = $fecha[0].",".$fila['idMovimiento'].",".$fila['idTransaccionPrimaria'].",".$fila['idTransaccionReferencial'].",".$proveedor.",".$medioPago.",".$tipoMovimiento.",".$signo.$fila['monto'].",".$fila['observaciones'].PHP_EOL;
											fwrite($file, $txt);
										}
                                    }
                                    fclose($file);
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="cajachica" role="tabpanel">
                                <table class="table table-bordered text-center" id="myTable1">
                                    <thead class="thead-default">
                                    <tr>
                                        <th class="text-center" style="width: 10%">Fecha</th>
                                        <th class="text-center" style="width: 9%">Código</th>
                                        <th class="text-center" style="width: 9%">Ref. Primaria</th>
                                        <th class="text-center" style="width: 9%">Ref. Secundaria</th>
                                        <th class="text-center" style="width: 16%">Persona</th>
                                        <th class="text-center" style="width: 12%">Medio de Pago</th>
                                        <th class="text-center" style="width: 12%">Tipo</th>
                                        <th class="text-center" style="width: 10%">Monto</th>
                                        <th class="text-center" style="width: 13%">Notas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $result=mysqli_query($link,"SELECT * FROM Movimiento WHERE idCuenta = 1 AND fecha >= '{$ago} 00:00:00' ORDER BY fecha DESC");
                                    while ($fila=mysqli_fetch_array($result)){
										if($fila['monto'] > 0){
											$fecha=explode("|",$fila['fecha']);
											echo "<tr>";
											echo "
												<td>{$fecha[0]}</td>
												<td>
													<form method='post' action='detalleMovimiento.php'>
														<input type='hidden' name='idMovimiento' value='{$fila['idMovimiento']}'>
														<input type='submit' name='detalle' value='{$fila['idMovimiento']}' class='btn-link'>
													</form>
												</td>
											";
											if($fila['idTransaccionPrimaria']!=null){
												$result2=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$fila['idTransaccionPrimaria']}'");
												while ($fila2=mysqli_fetch_array($result2)){
													if($fila2['idTipoTransaccion']==1){
														echo "
														<td>
														<form method='post' action='detalleOC.php'>
															<input type='hidden' name='idTransaccionOC' value='{$fila['idTransaccionPrimaria']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionPrimaria']}' class='btn-link'>
														</form>
														</td>
														";
													}elseif ($fila2['idTipoTransaccion']==2||$fila2['idTipoTransaccion']==6){
														echo "
														<td>
														<form method='post' action='detallePrestamo.php'>
															<input type='hidden' name='idTransaccion' value='{$fila['idTransaccionPrimaria']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionPrimaria']}' class='btn-link'>
														</form>
														</td>
														";
													}elseif ($fila2['idTipoTransaccion']==5){
														echo "
														<td>
														<form method='post' action='detalleOV.php'>
															<input type='hidden' name='idTransaccion' value='{$fila['idTransaccionPrimaria']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionPrimaria']}' class='btn-link'>
														</form>
														</td>
														";
													}else{
														echo "<td>{$fila['idTransaccionPrimaria']}</td>";
													}
												}
											}else{
												echo "
												<td></td>
												";
											}
											if($fila['idTransaccionReferencial']!=null){
												$result2=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$fila['idTransaccionReferencial']}'");
												while ($fila2=mysqli_fetch_array($result2)){
													if($fila2['idTipoTransaccion']==1){
														echo "
														<td>
														<form method='post' action='detalleOC.php'>
															<input type='hidden' name='idTransaccionOC' value='{$fila['idTransaccionReferencial']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionReferencial']}' class='btn-link'>
														</form>
														</td>
														";
													}elseif ($fila2['idTipoTransaccion']==2||$fila2['idTipoTransaccion']==6){
														echo "
														<td>
														<form method='post' action='detallePrestamo.php'>
															<input type='hidden' name='idTransaccion' value='{$fila['idTransaccionReferencial']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionReferencial']}' class='btn-link'>
														</form>
														</td>
														";
													}elseif ($fila2['idTipoTransaccion']==5){
														echo "
														<td>
														<form method='post' action='detalleOV.php'>
															<input type='hidden' name='idTransaccion' value='{$fila['idTransaccionReferencial']}'>
															<input type='submit' name='detalle' value='{$fila['idTransaccionReferencial']}' class='btn-link'>
														</form>
														</td>
														";
													}else{
														echo "<td>{$fila['idTransaccionReferencial']}</td>";
													}
												}
											}else{
												echo "
												<td></td>
												";
											}
											$result3=mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$fila['idProveedor']}'");
											while ($fila3=mysqli_fetch_array($result3)){
												echo "<td>{$fila3['nombre']}</td>";
											}
											$result4=mysqli_query($link,"SELECT * FROM MedioPago WHERE idMedioPago = '{$fila['idMedioPago']}'");
											$numrows=mysqli_num_rows($result4);
											if($numrows>0){
												while ($fila4=mysqli_fetch_array($result4)){
													echo "<td>{$fila4['descripcion']}</td>";
												}
											}else{
												echo "<td></td>";
											}
											$result4=mysqli_query($link,"SELECT * FROM TipoMovimiento WHERE idTipoMovimiento = '{$fila['idTipoMovimiento']}'");
											while ($fila4=mysqli_fetch_array($result4)){
												echo "<td>{$fila4['descripcion']}</td>";
												if($fila4['tipo']==0){
													$signo="-";
												}else{
													$signo="+";
												}
											}
											echo "<td>S/. {$signo} {$fila['monto']}</td>";
											echo "<td>{$fila['observaciones']}</td>";
											echo "</tr>";
										}
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            $aux1=1;
                            $result=mysqli_query($link,"SELECT * FROM Cuenta WHERE tipo != 0");
                            while ($fila=mysqli_fetch_array($result)){
                                $aux1++;
                                echo "
                                    <div class='tab-pane' id='Cuenta{$aux1}' role='tabpanel'>
                                        <table class='table table-bordered text-center' id='myTable{$aux1}'>
                                            <thead class='thead-default'>
                                            <tr>
                                                <th class='text-center' style='width: 10%'>Fecha</th>
                                                <th class='text-center' style='width: 9%'>Código</th>
                                                <th class='text-center' style='width: 9%'>Ref. Primaria</th>
                                                <th class='text-center' style='width: 9%'>Ref. Secundaria</th>
                                                <th class='text-center' style='width: 16%'>Persona</th>
                                                <th class='text-center' style='width: 12%'>Medio de Pago</th>
                                                <th class='text-center' style='width: 12%'>Tipo</th>
                                                <th class='text-center' style='width: 10%'>Monto</th>
                                                <th class='text-center' style='width: 13%'>Notas</th>
                                            </tr>
                                            </thead>
                                            <tbody>";
                                $result4=mysqli_query($link,"SELECT * FROM Movimiento WHERE idCuenta = {$fila['idCuenta']} AND fecha >= '{$ago} 00:00:00' ORDER BY fecha DESC");
                                while ($fila4=mysqli_fetch_array($result4)){
                                    $fecha=explode("|",$fila4['fecha']);
                                    echo "<tr>";
                                    echo "
                                            <td>{$fecha[0]}</td>
                                            <td>
                                                <form method='post' action='detalleMovimiento.php'>
                                                    <input type='hidden' name='idMovimiento' value='{$fila4['idMovimiento']}'>
                                                    <input type='submit' name='detalle' value='{$fila4['idMovimiento']}' class='btn-link'>
                                                </form>
                                            </td>
                                        ";
                                    if($fila4['idTransaccionPrimaria']!=null){
                                        $result2=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$fila4['idTransaccionPrimaria']}'");
                                        while ($fila2=mysqli_fetch_array($result2)){
                                            if($fila2['idTipoTransaccion']==1){
                                                echo "
                                                    <td>
                                                    <form method='post' action='detalleOC.php'>
                                                        <input type='hidden' name='idTransaccionOC' value='{$fila4['idTransaccionPrimaria']}'>
                                                        <input type='submit' name='detalle' value='{$fila4['idTransaccionPrimaria']}' class='btn-link'>
                                                    </form>
                                                    </td>
                                                    ";
                                            }elseif ($fila2['idTipoTransaccion']==2||$fila2['idTipoTransaccion']==6){
                                                echo "
                                                    <td>
                                                    <form method='post' action='detallePrestamo.php'>
                                                        <input type='hidden' name='idTransaccion' value='{$fila4['idTransaccionPrimaria']}'>
                                                        <input type='submit' name='detalle' value='{$fila4['idTransaccionPrimaria']}' class='btn-link'>
                                                    </form>
                                                    </td>
                                                    ";
                                            }elseif ($fila2['idTipoTransaccion']==5){
                                                echo "
                                                    <td>
                                                    <form method='post' action='detalleOV.php'>
                                                        <input type='hidden' name='idTransaccion' value='{$fila4['idTransaccionPrimaria']}'>
                                                        <input type='submit' name='detalle' value='{$fila4['idTransaccionPrimaria']}' class='btn-link'>
                                                    </form>
                                                    </td>
                                                    ";
                                            }else{
                                                echo "<td>{$fila4['idTransaccionPrimaria']}</td>";
                                            }
                                        }
                                    }else{
                                        echo "
                                            <td></td>
                                            ";
                                    }
                                    if($fila4['idTransaccionReferencial']!=null){
                                        $result2=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$fila4['idTransaccionReferencial']}'");
                                        while ($fila2=mysqli_fetch_array($result2)){
                                            if($fila2['idTipoTransaccion']==1){
                                                echo "
                                                    <td>
                                                    <form method='post' action='detalleOC.php'>
                                                        <input type='hidden' name='idTransaccionOC' value='{$fila4['idTransaccionReferencial']}'>
                                                        <input type='submit' name='detalle' value='{$fila4['idTransaccionReferencial']}' class='btn-link'>
                                                    </form>
                                                    </td>
                                                    ";
                                            }elseif ($fila2['idTipoTransaccion']==2||$fila2['idTipoTransaccion']==6){
                                                echo "
                                                    <td>
                                                    <form method='post' action='detallePrestamo.php'>
                                                        <input type='hidden' name='idTransaccion' value='{$fila4['idTransaccionReferencial']}'>
                                                        <input type='submit' name='detalle' value='{$fila4['idTransaccionReferencial']}' class='btn-link'>
                                                    </form>
                                                    </td>
                                                    ";
                                            }elseif ($fila2['idTipoTransaccion']==5){
                                                echo "
                                                    <td>
                                                    <form method='post' action='detalleOV.php'>
                                                        <input type='hidden' name='idTransaccion' value='{$fila4['idTransaccionReferencial']}'>
                                                        <input type='submit' name='detalle' value='{$fila4['idTransaccionReferencial']}' class='btn-link'>
                                                    </form>
                                                    </td>
                                                    ";
                                            }else{
                                                echo "<td>{$fila4['idTransaccionReferencial']}</td>";
                                            }
                                        }
                                    }else{
                                        echo "
                                            <td></td>
                                            ";
                                    }
                                    $result3=mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$fila4['idProveedor']}'");
                                    while ($fila3=mysqli_fetch_array($result3)){
                                        echo "<td>{$fila3['nombre']}</td>";
                                    }
                                    $result5=mysqli_query($link,"SELECT * FROM MedioPago WHERE idMedioPago = '{$fila4['idMedioPago']}'");
                                    $numrows=mysqli_num_rows($result5);
                                    if($numrows>0){
                                        while ($fila5=mysqli_fetch_array($result5)){
                                            echo "<td>{$fila5['descripcion']}</td>";
                                        }
                                    }else{
                                        echo "<td></td>";
                                    }
                                    $result5=mysqli_query($link,"SELECT * FROM TipoMovimiento WHERE idTipoMovimiento = '{$fila4['idTipoMovimiento']}'");
                                    while ($fila5=mysqli_fetch_array($result5)){
                                        echo "<td>{$fila5['descripcion']}</td>";
                                        if($fila5['tipo']==0){
                                            $signo="-";
                                        }else{
                                            $signo="+";
                                        }
                                    }
                                    echo "<td>S/. {$signo} {$fila4['monto']}</td>";
                                    echo "<td>{$fila4['observaciones']}</td>";
                                    echo "</tr>";
                                }
                                echo "
                                            </tbody>
                                        </table>
                                    </div>
                                ";
                            }
                            ?>
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