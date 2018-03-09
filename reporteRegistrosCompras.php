<div class="spacer30"></div>
<script>
    function myFunction() {
        // Declare variables
        var input, input2, input3, input4, input5, filter, filter2, filter3, filter4, filter5, table, tr, td, td2, td3, td4, td5, i;
        input = document.getElementById("idTransaccion");
        input2 = document.getElementById("persona");
        input3 = document.getElementById("fechaCreacion");
        input4 = document.getElementById("fechaRecepcion");
        input5 = document.getElementById("estado");
        filter = input.value.toUpperCase();
        filter2 = input2.value.toUpperCase();
        filter3 = input3.value.toUpperCase();
        filter4 = input4.value.toUpperCase();
        filter5 = input5.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            td2 = tr[i].getElementsByTagName("td")[1];
            td3 = tr[i].getElementsByTagName("td")[2];
            td4 = tr[i].getElementsByTagName("td")[3];
            td5 = tr[i].getElementsByTagName("td")[4];
            if ((td)&&(td2)) {
                if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    if(td2.innerHTML.toUpperCase().indexOf(filter2) > -1){
                        if(td3.innerHTML.toUpperCase().indexOf(filter3) > -1){
                            if(td4.innerHTML.toUpperCase().indexOf(filter4) > -1){
                                if(td5.innerHTML.toUpperCase().indexOf(filter5) > -1){
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
                }else {
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
            Listado de Órdenes de Compra
            <div class="float-right">
                <div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Acciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="files/ordenesCompraAntiguas.txt" download>Exportar Listado</a>
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
                            <input type="hidden" name="selectTipoReporte" value="1">
                            <input type="hidden" name="generar" value="true">
                            <input type="hidden" name="fechaInicioReporte" value="<?php echo $_POST['fechaInicioReporte'];?>">
                            <input type="hidden" name="fechaFinReporte" value="<?php echo $_POST['fechaFinReporte'];?>">
                            <label class="sr-only" for="idTransaccion">Orden #</label>
                            <input type="text" class="form-control mt-2 mb-2 mr-2" id="idTransaccion" placeholder="Orden #" onkeyup="myFunction()">
                            <label class="sr-only" for="persona">Persona</label>
                            <input type="text" class="form-control mt-2 mb-2 mr-2" id="persona" placeholder="Nombre" onkeyup="myFunction()">
                            <label class="sr-only" for="fechaCreacion">Fecha de Creación</label>
                            <input type="text" class="form-control mt-2 mb-2 mr-2" id="fechaCreacion" placeholder="Fecha de Creación" onkeyup="myFunction()">
                            <label class="sr-only" for="fechaRecepcion">Fecha Estimada</label>
                            <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="fechaRecepcion" placeholder="Fecha Estimada/OC Relacionada" onkeyup="myFunction()">
                            <label class="sr-only" for="estado">Estado</label>
                            <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="estado" placeholder="Estado" onkeyup="myFunction()">
                            <input type="submit" class="btn btn-primary" value="Limpiar" style="padding-left:28px; padding-right: 28px;">
                        </form>
                    </div>
                </div>
            </div>
            <div class="spacer10"></div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered text-center" id="myTable">
                        <thead class="thead-default">
                        <tr>
                            <th class="text-center">Orden #</th>
                            <th class="text-center">Persona</th>
                            <th class="text-center">Fecha de Creación</th>
                            <th class="text-center">Fecha Estimada de Recepción/OC Relacionada</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                        $dateFin = explode("-", $_POST['fechaFinReporte']);
                        $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                        $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                        $file = fopen("files/ordenesCompraAntiguas.txt","w") or die("No se encontró el archivo!");
                        fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                        $txt = "Nro. Orden,Fecha de Creación,Fecha Estimada de Recepción/OC Relacionada,Estado".PHP_EOL;
                        fwrite($file, $txt);
                        $idOR = idgen("OR");
                        $query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTipoTransaccion = '1' AND fechaTransaccion >= '{$fechaInicio} 00:00:00' AND fechaTransaccion <= '{$fechaFin} 23:59:59' ORDER BY fechaTransaccion DESC");
                        while($row = mysqli_fetch_array($query)){
                            $fechaTransaccion = explode("|",$row['fechaTransaccion']);
                            $query2 = mysqli_query($link,"SELECT * FROM Estado WHERE idEstado = '{$row['idEstado']}'");
                            while($row2 = mysqli_fetch_array($query2)){
                                $estado = $row2['descripcion'];
                            }
                            $query2 = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
                            while($row2 = mysqli_fetch_array($query2)){
                                $nombreProveedor = $row2['nombre'];
                            }
                            echo "<tr>
                                        <td class=\"text-center\">{$row['idTransaccion']}</td>
                                        <td class=\"text - center\">{$nombreProveedor}</td>
                                        <td class=\"text-center\">{$fechaTransaccion[0]} - {$fechaTransaccion[1]}</td>
                                        <td class=\"text-center\">{$row['fechaEstimada']}</td>
                                        <td class=\"text-center\">{$estado}</td>
                                        <td class=\"text-center\">
                                            <form method='post'>
                                                <div class=\"dropdown\">
                                                    <input type='hidden' name='idTransaccionOC' value='".$row['idTransaccion']."'>
                                                    <input type='hidden' name='idOrdenRecepcion' value='".$idOR."'>
                                                    <input type='hidden' name='crearOR' value='".$idOR."'>
                                                    <button class=\"btn btn-secondary btn-sm dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                                    Acciones
                                                    </button>
                                                    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                                                        <button name='verProductos' class=\"dropdown-item\" type=\"submit\" formaction='detalleOC.php'>Ver Detalle</button>";
                            if(substr($row['idTransaccion'],0,3)=='OCP'){
                                if($estado == 'Emitida'){
                                    echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='nuevaOR.php'>Registrar Recepción</button>";
                                }elseif($estado == 'Parcial'){
                                    echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='nuevaOR.php'>Registrar Recepción</button>";
                                }
                            }else{
                                if($estado == 'Emitida'){
                                    echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='nuevaOR.php'>Registrar Recepción</button>";
                                    echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='recepcionPremios.php?idTransaccionRel=".$row['idTransaccion']."'>Registrar OC Premios</button>";
                                }elseif($estado == 'Parcial'){
                                    echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='nuevaOR.php'>Registrar Recepción</button>";
                                    echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='recepcionPremios.php?idTransaccionRel=".$row['idTransaccion']."'>Registrar OC Premios</button>";
                                    echo "<button name='completar' class=\"dropdown-item\" type=\"submit\" formaction='#'>Completar Orden</button>";
                                }elseif($estado == 'Completa'){
                                    echo "<button name='recepcion' class=\"dropdown-item\" type=\"submit\" formaction='recepcionPremios.php?idTransaccionRel=".$row['idTransaccion']."'>Registrar OC Premios</button>";
                                }
                            }
                            echo "                  </div>
                                                </div>
                                            </form>
                                        </td>
                                      </tr>";
                            $txt = $row['idTransaccion'].",".$fechaTransaccion[0]." - ".$fechaTransaccion[1].",".$row['fechaEstimada'].",".$estado.PHP_EOL;
                            fwrite($file, $txt);
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