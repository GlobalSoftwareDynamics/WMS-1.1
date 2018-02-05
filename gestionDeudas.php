<?php
include('session.php');
if(isset($_SESSION['login'])) {

	include('adminTemplate.php');
	?>
    <script>
        function myFunction() {
            //Determine Tab
            var tab1,tab2,tab3, tabla;
            var input, input3, input4, filter, filter2, filter3, table, tr, td, td2, td3, i;

            tab1=document.getElementById("1");
            tab2=document.getElementById("2");
            tab3=document.getElementById("3");

            if (tab1.className === "nav-link active"){
                tabla = "myTable"
            }else if (tab2.className === "nav-link active"){
                tabla = "myTable1"
            }else if (tab3.className === "nav-link active"){
                tabla = "myTable2"
            }

            // Declare variables
            input = document.getElementById("idTransaccion");
            input3 = document.getElementById("anio");
            input4 = document.getElementById("persona");
            filter = input.value.toUpperCase();
            filter2 = input3.value.toUpperCase();
            filter3 = input4.value.toUpperCase();
            table = document.getElementById(tabla);
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                td2 = tr[i].getElementsByTagName("td")[2];
                td3 = tr[i].getElementsByTagName("td")[3];
                if ((td)&&(td2)) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        if(td2.innerHTML.toUpperCase().indexOf(filter2) > -1){
                            if(td3.innerHTML.toUpperCase().indexOf(filter3) > -1){
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
                }
            }

        }
    </script>

    <section class="container">
        <div class="card">
            <div class="card-header card-inverse card-info">
                <i class="fa fa-list"></i>
                Gestión de Deudas
                <div class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="files/deudas.txt" download>Exportar Listado</a>
                        </div>
                    </div>
                </div>
                <div class="float-right">&nbsp;</div>
                <div class="float-right">
                    <button href="#collapsed" class="btn btn-secondary btn-sm" data-toggle="collapse">Mostrar Filtros</button>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-12">
                        <div id="collapsed" class="collapse">
                            <form class="form-inline justify-content-center" method="post" action="#">
                                <label class="sr-only" for="idTransaccion">Transacción</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="idTransaccion" placeholder="Orden #" onkeyup="myFunction()">
                                <label class="sr-only" for="anio">Año</label>
                                <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="anio" placeholder="Año-Mes-Día" onkeyup="myFunction()">
                                <label class="sr-only" for="persona">Persona</label>
                                <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="persona" placeholder="Persona" onkeyup="myFunction()">
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
                                <a class="nav-link active" data-toggle="tab" href="#general" role="tab" id="1">General</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#propias" role="tab" id="2">Propias</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#terceros" role="tab" id="3">Terceros</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="general" role="tabpanel">
                                <table class="table table-bordered text-center" id="myTable">
                                    <thead class="thead-default">
                                    <tr>
                                        <th class="text-center">Transacción</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Fecha de Vencimiento</th>
                                        <th class="text-center">Persona</th>
                                        <th class="text-center">Monto (S/.)</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									$file = fopen("files/deudas.txt","w") or die("No se encontró el archivo!");
									fwrite($file, pack("CCC",0xef,0xbb,0xbf));
									$txt = "Transaccion,Responsable,Fecha de Vencimiento,Deudor,Monto (S/.)".PHP_EOL;
									fwrite($file, $txt);
									$result = mysqli_query($link,"SELECT * FROM Transaccion WHERE montoRestante > 0  ORDER BY fechaTransaccion DESC");
									while ($fila = mysqli_fetch_array($result)){
										echo "<tr>";
										echo "<td>{$fila['idTransaccion']}</td>";
										$result1 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$fila['idColaborador']}'");
										while ($fila1= mysqli_fetch_array($result1)){
										    $responsable = $fila1['nombres']." ".$fila1['apellidos'];
											echo "<td>{$fila1['nombres']} {$fila1['apellidos']}</td>";
										}
										echo "<td>{$fila['fechaVencimiento']}</td>";
										$result1 = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$fila['idProveedor']}'");
										while ($fila1= mysqli_fetch_array($result1)){
										    $deudor = $fila1['nombre'];
											echo "<td>{$fila1['nombre']}</td>";
										}
										echo "<td>{$fila['montoRestante']}</td>";
										echo "
                                              <td>
                                                  <form method='post'>
                                                        <div class='dropdown'>
                                                            <input type='hidden' name='idTransaccion' value='{$fila['idTransaccion']}'>
                                                            <input type='hidden' name='idTransaccionOC' value='{$fila['idTransaccion']}'>
                                                            <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                            Acciones
                                                            </button>
                                                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                                            ";
										$cancelacion = substr($fila['idTransaccion'], 0,2);
										if($fila['idTipoTransaccion']==1){
											echo "<button name='cancelacionGDC' class='dropdown-item' type='submit' formaction='detalleOC.php'>Ver Detalle</button>";
											echo "<button name='cancelacionGD' class='dropdown-item' type='submit' formaction='nuevoMovimiento.php'>Cancelar Deuda</button>";
										}elseif($fila['idTipoTransaccion']==5){
											echo "<button name='cancelacionGDV' class='dropdown-item' type='submit' formaction='detalleOV.php'>Ver Detalle</button>";
											echo "<button name='cancelacionGD' class='dropdown-item' type='submit' formaction='nuevoMovimiento.php'>Cancelar Deuda</button>";
										}elseif ($fila['idTipoTransaccion']==6&&$cancelacion==="PE"){
											echo "<button name='cancelacionPE' class='dropdown-item' type='submit' formaction='detallePrestamo.php'>Ver Detalle</button>";
											echo "<button name='cancelacion' class='dropdown-item' type='submit' formaction='cancelacionPrestamoEfectivo.php'>Cancelar Deuda</button>";
										}elseif ($fila['idTipoTransaccion']==6&&$cancelacion==="PS"){
											echo "<button name='cancelacionPS' class='dropdown-item' type='submit' formaction='detallePrestamo.php'>Ver Detalle</button>";
											echo "<button name='cancelacion' class='dropdown-item' type='submit' formaction='nuevaCancelacionPrestamo.php'>Cancelar Deuda</button>";
										}
										echo "
                                                            </div>
                                                        </div>
                                                  </form>    
                                              </td>
                                              ";
										$txt = $fila['idTransaccion'].",".$responsable.",".$fila['fechaVencimiento'].",".$deudor.",".$fila['montoRestante'].PHP_EOL;
										fwrite($file, $txt);
									}
									fclose($file);
									?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="propias" role="tabpanel">
                                <table class="table table-bordered text-center" id="myTable1">
                                    <thead class="thead-default">
                                    <tr>
                                        <th class="text-center">Transacción</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Fecha de Vencimiento</th>
                                        <th class="text-center">Persona</th>
                                        <th class="text-center">Monto (S/.)</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									$result = mysqli_query($link,"SELECT * FROM Transaccion WHERE montoRestante > 0 AND idTipoTransaccion = 1 ORDER BY fechaTransaccion DESC");
									while ($fila = mysqli_fetch_array($result)){
										echo "<tr>";
										echo "<td>{$fila['idTransaccion']}</td>";
										$result1 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$fila['idColaborador']}'");
										while ($fila1= mysqli_fetch_array($result1)){
											echo "<td>{$fila1['nombres']} {$fila1['apellidos']}</td>";
										}
										echo "<td>{$fila['fechaVencimiento']}</td>";
										$result1 = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$fila['idProveedor']}'");
										while ($fila1= mysqli_fetch_array($result1)){
											echo "<td>{$fila1['nombre']}</td>";
										}
										echo "<td>{$fila['montoRestante']}</td>";
										echo "
                                              <td>
                                                  <form method='post'>
                                                        <div class='dropdown'>
                                                            <input type='hidden' name='idTransaccion' value='{$fila['idTransaccion']}'>
                                                            <input type='hidden' name='idTransaccionOC' value='{$fila['idTransaccion']}'>
                                                            <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                            Acciones
                                                            </button>
                                                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                                            ";
										$cancelacion = substr($fila['idTransaccion'], 0,2);
										if($fila['idTipoTransaccion']==1){
											echo "<button name='cancelacionGDC' class='dropdown-item' type='submit' formaction='detalleOC.php'>Ver Detalle</button>";
											echo "<button name='cancelacionGD' class='dropdown-item' type='submit' formaction='nuevoMovimiento.php'>Cancelar Deuda</button>";
										}elseif($fila['idTipoTransaccion']==5){
											echo "<button name='cancelacionGDV' class='dropdown-item' type='submit' formaction='detalleOV.php'>Ver Detalle</button>";
											echo "<button name='cancelacionGD' class='dropdown-item' type='submit' formaction='nuevoMovimiento.php'>Cancelar Deuda</button>";
										}elseif ($fila['idTipoTransaccion']==6&&$cancelacion==="PE"){
											echo "<button name='cancelacionPE' class='dropdown-item' type='submit' formaction='detallePrestamo.php'>Ver Detalle</button>";
											echo "<button name='cancelacion' class='dropdown-item' type='submit' formaction='cancelacionPrestamoEfectivo.php'>Cancelar Deuda</button>";
										}elseif ($fila['idTipoTransaccion']==6&&$cancelacion==="PS"){
											echo "<button name='cancelacionPS' class='dropdown-item' type='submit' formaction='detallePrestamo.php'>Ver Detalle</button>";
											echo "<button name='cancelacion' class='dropdown-item' type='submit' formaction='nuevaCancelacionPrestamo.php'>Cancelar Deuda</button>";
										}
										echo "
                                                            </div>
                                                        </div>
                                                  </form>    
                                              </td>
                                              ";
									}
									?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="terceros" role="tabpanel">
                                <table class="table table-bordered text-center" id="myTable2">
                                    <thead class="thead-default">
                                    <tr>
                                        <th class="text-center">Transacción</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Fecha de Vencimiento</th>
                                        <th class="text-center">Persona</th>
                                        <th class="text-center">Monto (S/.)</th>
                                        <th class="text-center">Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
									<?php
									$result = mysqli_query($link,"SELECT * FROM Transaccion WHERE montoRestante > 0 AND idTipoTransaccion <> 1 ORDER BY fechaTransaccion DESC");
									while ($fila = mysqli_fetch_array($result)){
										echo "<tr>";
										echo "<td>{$fila['idTransaccion']}</td>";
										$result1 = mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$fila['idColaborador']}'");
										while ($fila1= mysqli_fetch_array($result1)){
											echo "<td>{$fila1['nombres']} {$fila1['apellidos']}</td>";
										}
										echo "<td>{$fila['fechaVencimiento']}</td>";
										$result1 = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$fila['idProveedor']}'");
										while ($fila1= mysqli_fetch_array($result1)){
											echo "<td>{$fila1['nombre']}</td>";
										}
										echo "<td>{$fila['montoRestante']}</td>";
										echo "
                                              <td>
                                                  <form method='post'>
                                                        <div class='dropdown'>
                                                            <input type='hidden' name='idTransaccion' value='{$fila['idTransaccion']}'>
                                                            <input type='hidden' name='idTransaccionOC' value='{$fila['idTransaccion']}'>
                                                            <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                            Acciones
                                                            </button>
                                                            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                                            ";
										$cancelacion = substr($fila['idTransaccion'], 0,2);
										if($fila['idTipoTransaccion']==1){
											echo "<button name='cancelacionGDC' class='dropdown-item' type='submit' formaction='detalleOC.php'>Ver Detalle</button>";
											echo "<button name='cancelacionGD' class='dropdown-item' type='submit' formaction='nuevoMovimiento.php'>Cancelar Deuda</button>";
										}elseif($fila['idTipoTransaccion']==5){
											echo "<button name='cancelacionGDV' class='dropdown-item' type='submit' formaction='detalleOV.php'>Ver Detalle</button>";
											echo "<button name='cancelacionGD' class='dropdown-item' type='submit' formaction='nuevoMovimiento.php'>Cancelar Deuda</button>";
										}elseif ($fila['idTipoTransaccion']==6&&$cancelacion==="PE"){
											echo "<button name='cancelacionPE' class='dropdown-item' type='submit' formaction='detallePrestamo.php'>Ver Detalle</button>";
											echo "<button name='cancelacion' class='dropdown-item' type='submit' formaction='cancelacionPrestamoEfectivo.php'>Cancelar Deuda</button>";
										}elseif ($fila['idTipoTransaccion']==6&&$cancelacion==="PS"){
											echo "<button name='cancelacionPS' class='dropdown-item' type='submit' formaction='detallePrestamo.php'>Ver Detalle</button>";
											echo "<button name='cancelacion' class='dropdown-item' type='submit' formaction='nuevaCancelacionPrestamo.php'>Cancelar Deuda</button>";
										}
										echo "
                                                            </div>
                                                        </div>
                                                  </form>    
                                              </td>
                                              ";
									}
									?>
                                    </tbody>
                                </table>
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