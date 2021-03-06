<?php
include('session.php');
include('declaracionFechas.php');
include('funciones.php');
if(isset($_SESSION['login'])) {
include('adminTemplate.php');

?>
    <script>
        function myFunction() {
            //Determine Tab
            var tab1,tab2,tab3,tab4, tabla;
            var input2, input3, input4, filter2, filter3, filter4, table, tr, td, td2, td3, i;

            tab1=document.getElementById("1");
            tab2=document.getElementById("2");
            tab3=document.getElementById("3");
            tab4=document.getElementById("4");

            if (tab1.className === "nav-link active"){
                tabla = "myTable"
            }else if (tab2.className === "nav-link active"){
                tabla = "myTable1"
            }else if (tab3.className === "nav-link active"){
                tabla = "myTable2"
            }else if (tab4.className === "nav-link active"){
                tabla = "myTable3"
            }

            // Declare variables
            input2 = document.getElementById("idTransaccion");
            input3 = document.getElementById("anio");
            input4 = document.getElementById("persona");
            filter2 = input2.value.toUpperCase();
            filter3 = input3.value.toUpperCase();
            filter4 = input4.value.toUpperCase();
            table = document.getElementById(tabla);
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

    <section class="container">
        <div class="card">
            <div class="card-header card-inverse card-info">
                <div class="float-left">
                    <i class="fa fa-exchange"></i>
                    Historial de Transacciones de Catálogos
                </div>
                <div class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="gestionCatalogos.php">Regresar</a>
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
                                <input type="hidden" name="idCatalogo" value="<?php echo $_POST['idCatalogo']?>">
                                <label class="sr-only" for="idTransaccion">Transacción</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="idTransaccion" placeholder="Orden #" onkeyup="myFunction()">
                                <label class="sr-only" for="anio">Fecha</label>
                                <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="anio" placeholder="Año" onkeyup="myFunction()">
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
                                <a class="nav-link active" data-toggle="tab" href="#completo" role="tab" id="1">Historia Completo</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#compras" role="tab" id="2">Compras</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#ventas" role="tab" id="3">Entregas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#recepcion" role="tab" id="4">Recepción</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="completo" role="tabpanel">
                                <table class="table table-bordered text-center" id="myTable">
                                    <thead class="thead-default">
                                    <tr>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Transacción</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Stock Inicial</th>
                                        <th class="text-center">Variación</th>
                                        <th class="text-center">Stock Final</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Observaciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $value="";
                                        $result=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion IN (SELECT idTransaccion FROM TransaccionProducto WHERE idProducto = '{$_POST['idCatalogo']}') ORDER BY fechaTransaccion DESC");
                                        while($fila=mysqli_fetch_array($result)){
                                            $date=explode(" ",$fila['fechaTransaccion']);
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
                                                    break;
                                            }
                                            echo "<tr>";
                                            $result1=mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$fila['idTransaccion']}' AND idProducto = '{$_POST['idCatalogo']}'");
                                            while ($fila1=mysqli_fetch_array($result1)){
                                                echo "<td>{$date[0]}</td>";
                                                echo "<td>{$fila['idTransaccion']}</td>";
                                                $result2=mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$fila['idColaborador']}'");
                                                while ($fila2=mysqli_fetch_array($result2)){
                                                    echo "
                                                        <td>{$fila2['nombres']} {$fila2['apellidos']}</td>
                                                    ";
                                                }
                                                if($fila['idTipoTransaccion']==='8'){
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
                            <div class="tab-pane" id="compras" role="tabpanel">
                                <table class="table table-bordered text-center" id="myTable1">
                                    <thead class="thead-default">
                                    <tr>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Transaccion</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Stock Inicial</th>
                                        <th class="text-center">Variación</th>
                                        <th class="text-center">Stock Final</th>
                                        <th class="text-center">P. Unitario</th>
                                        <th class="text-center">Inversión</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Observaciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $value="";
                                        $result=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion IN (SELECT idTransaccion FROM TransaccionProducto WHERE idProducto = '{$_POST['idCatalogo']}') AND idTipoTransaccion ='1' ORDER BY fechaTransaccion DESC");
                                        while($fila=mysqli_fetch_array($result)){
                                            $date=explode(" ",$fila['fechaTransaccion']);
                                            echo "<tr>";
                                            $result1=mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$fila['idTransaccion']}' AND idProducto = '{$_POST['idCatalogo']}'");
                                            while ($fila1=mysqli_fetch_array($result1)){
                                                echo "<td>{$date[0]}</td>";
                                                echo "<td>{$fila['idTransaccion']}</td>";
                                                $result2=mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$fila['idColaborador']}'");
                                                while ($fila2=mysqli_fetch_array($result2)){
                                                    echo "
                                                        <td>{$fila2['nombres']} {$fila2['apellidos']}</td>
                                                    ";
                                                }
                                                echo "
                                                    <td>{$fila1['stockInicial']}</td>
                                                    <td>+{$fila1['cantidad']}</td>
                                                    <td>{$fila1['stockFinal']}</td>
                                                    <td>{$fila1['valorUnitario']}</td>
                                                ";
                                                $inversion=$fila1['valorUnitario'] * $fila1['cantidad'];
                                                echo "
                                                    <td>{$inversion}</td>
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
                            <div class="tab-pane" id="ventas" role="tabpanel">
                                <table class="table table-bordered text-center" id="myTable2">
                                    <thead class="thead-default">
                                    <tr>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Transacción</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Stock Inicial</th>
                                        <th class="text-center">Variación</th>
                                        <th class="text-center">Stock Final</th>
                                        <th class="text-center">P. Unitario</th>
                                        <th class="text-center">Monto</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Observaciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $value="";
                                        $result=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion IN (SELECT idTransaccion FROM TransaccionProducto WHERE idProducto = '{$_POST['idCatalogo']}') AND idTipoTransaccion ='5' ORDER BY fechaTransaccion DESC");
                                        while($fila=mysqli_fetch_array($result)){
                                            $date=explode(" ",$fila['fechaTransaccion']);
                                            echo "<tr>";
                                            $result1=mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$fila['idTransaccion']}' AND idProducto = '{$_POST['idCatalogo']}'");
                                            while ($fila1=mysqli_fetch_array($result1)){
                                                echo "<td>{$date[0]}</td>";
                                                echo "<td>{$fila['idTransaccion']}</td>";
                                                $result2=mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$fila['idColaborador']}'");
                                                while ($fila2=mysqli_fetch_array($result2)){
                                                    echo "
                                                        <td>{$fila2['nombres']} {$fila2['apellidos']}</td>
                                                    ";
                                                }
                                                echo "
                                                    <td>{$fila1['stockInicial']}</td>
                                                    <td>-{$fila1['cantidad']}</td>
                                                    <td>{$fila1['stockFinal']}</td>
                                                    <td>{$fila1['valorUnitario']}</td>
                                                ";
                                                $inversion=$fila1['valorUnitario']*$fila1['cantidad'];
                                                echo "
                                                    <td>{$inversion}</td>
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
                            <div class="tab-pane" id="recepcion" role="tabpanel">
                                <table class="table table-bordered text-center" id="myTable3">
                                    <thead class="thead-default">
                                    <tr>
                                        <th class="text-center">Fecha</th>
                                        <th class="text-center">Transacción</th>
                                        <th class="text-center">Responsable</th>
                                        <th class="text-center">Stock Inicial</th>
                                        <th class="text-center">Variación</th>
                                        <th class="text-center">Stock Final</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-center">Observaciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $value="";
                                        $result=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion IN (SELECT idTransaccion FROM TransaccionProducto WHERE idProducto = '{$_POST['idCatalogo']}') AND idTipoTransaccion ='4' ORDER BY fechaTransaccion DESC");
                                        while($fila=mysqli_fetch_array($result)){
                                            $date=explode(" ",$fila['fechaTransaccion']);
                                            echo "<tr>";
                                            $result1=mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$fila['idTransaccion']}' AND idProducto = '{$_POST['idCatalogo']}'");
                                            while ($fila1=mysqli_fetch_array($result1)){
                                                echo "<td>{$date[0]}</td>";
                                                echo "<td>{$fila['idTransaccion']}</td>";
                                                $result2=mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$fila['idColaborador']}'");
                                                while ($fila2=mysqli_fetch_array($result2)){
                                                    echo "
                                                        <td>{$fila2['nombres']} {$fila2['apellidos']}</td>
                                                    ";
                                                }
                                                echo "
                                                    <td>{$fila1['stockInicial']}</td>
                                                    <td>+{$fila1['cantidad']}</td>
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
            </div>
        </div>
    </section>

<?php
include('footerTemplate.php');
}else{
    include('sessionError.php');
}