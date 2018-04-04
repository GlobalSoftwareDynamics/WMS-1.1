<?php
$nombreProducto = explode("_",$_POST['nombreProducto']);
$nombreProducto = $nombreProducto[0];
if ($_POST['nombreProducto'] == ''){
    $result = mysqli_query($link,"SELECT nombreCorto FROM Producto WHERE idProducto = '{$_POST['idProducto']}'");
    while ($fila = mysqli_fetch_array($result)){
        $nombreProducto = $fila['nombreCorto'];
    }
    $idProducto = $_POST['idProducto'];
}
?>

<script>
    function myFunction() {
        //Determine Tab
        var input, input2, input3, input4, filter, filter2, filter3, filter4, table, tr, td, td2, td3, i;

        // Declare variables
        input2 = document.getElementById("idTransaccion");
        input3 = document.getElementById("anio");
        input4 = document.getElementById("persona");
        filter2 = input2.value.toUpperCase();
        filter3 = input3.value.toUpperCase();
        filter4 = input4.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            td2 = tr[i].getElementsByTagName("td")[1];
            td3 = tr[i].getElementsByTagName("td")[2];
            if ((td)&&(td2)) {
                if (td.innerHTML.toUpperCase().indexOf(filter3) > -1) {
                    if(td2.innerHTML.toUpperCase().indexOf(filter2) > -1){
                        if(td3.innerHTML.toUpperCase().indexOf(filter4) > -1){
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

<div class="spacer20"></div>
<section class="container">
    <div class="card">
        <div class="card-header card-inverse card-info">
            <div class="float-left">
                <i class="fa fa-exchange"></i>
                Historial de Transacciones para <b>
                    <?php
                    echo $nombreProducto;
                    ?>
                </b>
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
                            <input type="hidden" name="idProducto" value="<?php echo $_POST['idProducto'];?>">
                            <input type="hidden" name="nombreProducto" value="<?php echo $nombreProducto;?>">
                            <input type="hidden" name="generar" value="true">
                            <input type="hidden" name="fechaInicioReporte" value="<?php echo $_POST['fechaInicioReporte'];?>">
                            <input type="hidden" name="fechaFinReporte" value="<?php echo $_POST['fechaFinReporte'];?>">
                            <label class="sr-only" for="idTransaccion">Transacción</label>
                            <input type="text" class="form-control mt-2 mb-2 mr-2" id="idTransaccion" placeholder="Orden #" onkeyup="myFunction()">
                            <label class="sr-only" for="anio">Fecha</label>
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
                    <table class="table table-bordered text-center" id="myTable">
                        <thead class="thead-default">
                        <tr>
                            <th class="text-center">Fecha</th>
                            <th class="text-center">Transacción</th>
                            <th class="text-center">Cliente/Proveedor</th>
                            <th class="text-center">Stock Inicial</th>
                            <th class="text-center">Variación</th>
                            <th class="text-center">Stock Final</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Observaciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                        $dateFin = explode("-", $_POST['fechaFinReporte']);
                        $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                        $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                        $value="";
                        $result=mysqli_query($link,"SELECT * FROM Transaccion WHERE fechaTransaccion >= '{$fechaInicio} 00:00:00' AND fechaTransaccion <= '{$fechaFin} 23:59:59' AND idTransaccion IN (SELECT idTransaccion FROM TransaccionProducto WHERE idProducto = '{$_POST['idProducto']}') ORDER BY fechaTransaccion DESC");
                        while($fila=mysqli_fetch_array($result)){
                            $date=explode(" ",$fila['fechaTransaccion']);
                            $result1=mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$fila['idTransaccion']}' AND idProducto = '{$_POST['idProducto']}'");
                            while ($fila1=mysqli_fetch_array($result1)){
                                echo "<tr>";
                                switch($fila['idTipoTransaccion']){
                                    case 1:
                                        $value="+";
                                        break;
                                    case 2:
                                        $value="+";
                                        break;
                                    case 3:
                                        break;
                                    case 4:
                                        $value="+";
                                        break;
                                    case 5:
                                        $value="-";
                                        break;
                                    case 6:
                                        $value="-";
                                        break;
                                    case 7:
                                        break;
                                    case 8:
                                        if($fila1['stockFinal']>$fila1['stockInicial']){
                                            $value="+";
                                        }elseif ($fila1['stockFinal']<$fila1['stockInicial']){
                                            $value="";
                                        }
                                        break;
                                }
                                echo "<td>{$date[0]}</td>";

                                echo "<td>{$fila['idTransaccion']}</td>";

                                $result2=mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$fila['idProveedor']}'");
                                while ($fila2=mysqli_fetch_array($result2)){
                                    echo "
                                                        <td>{$fila2['nombre']}</td>
                                                    ";
                                }
                                if($fila['idTipoTransaccion']==='7'){
                                    $fila1['cantidad']=0;
                                }
                                echo "
                                                    <td>{$fila1['stockInicial']}</td>
                                                    <td>{$value}{$fila1['cantidad']}</td>
                                                    <td>{$fila1['stockFinal']}</td>
                                                ";
                                $result2=mysqli_query($link,"SELECT * FROM Estado WHERE idEstado = '{$fila['idEstado']}'");
                                while ($fila2=mysqli_fetch_array($result2)){
                                    echo " 
                                                        <td>{$fila2['descripcion']}</td>
                                                    ";
                                }
                                echo " 
                                                    <td>{$fila['observacion']}</td>
                                                ";
                            }
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
