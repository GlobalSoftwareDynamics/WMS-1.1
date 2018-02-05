<?php
include('session.php');
include ('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');

    if(isset($_POST['editarPartner'])){

        $insert = mysqli_query($link, "UPDATE Proveedor SET idProveedor = '{$_POST['idProveedor']}', idTipoProveedor = '{$_POST['tipo']}', nombre = '{$_POST['nombre']}', correoElectronico = '{$_POST['email']}',
         fechaNacimiento = '{$_POST['fechaNacimiento']}' WHERE idProveedor = '{$_POST['idProveedor']}'");
        $queryPerformed = "UPDATE Proveedor SET idProveedor = {$_POST['idProveedor']}, idTipoProveedor = {$_POST['tipo']}, nombre = {$_POST['nombre']}, correoElectronico = {$_POST['email']},
         fechaNacimiento = {$_POST['fechaNacimiento']} WHERE idProveedor = {$_POST['idProveedor']}";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','Proveedor','{$queryPerformed}')");


    }

    if(isset($_POST['addPartner'])){
        $insert = mysqli_query($link, "INSERT INTO Proveedor VALUES ('{$_POST['idProveedor']}', '{$_POST['tipo']}', '{$_POST['nombre']}', '{$_POST['email']}', '{$_POST['fechaNacimiento']}')");
        $queryPerformed = "INSERT INTO Proveedor VALUES ({$_POST['idProveedor']}, {$_POST['tipo']}, {$_POST['nombre']}, {$_POST['email']}, {$_POST['telefono']}, {$_POST['direccion']}, {$_POST['fechaNacimiento']})";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Proveedor','{$queryPerformed}')");

        $direccion=mysqli_query($link,"INSERT INTO Direccion(idCiudad, descripcion) VALUES ('{$_POST['ciudad']}','{$_POST['direccion']}')");
        $queryPerformed = "INSERT INTO Direccion(idCiudad, descripcion) VALUES ('{$_POST['ciudad']}','{$_POST['direccion']}')";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Direccion','{$queryPerformed}')");

        $aux=0;
        $numrowdireccion=mysqli_query($link,"SELECT * FROM Direccion");
        while ($row=mysqli_fetch_array($numrowdireccion)){
            $aux++;
        }
        $direccion=mysqli_query($link,"INSERT INTO ProveedorDireccion VALUES ('{$_POST['idProveedor']}','{$aux}')");
        $queryPerformed = "INSERT INTO ProveedorDireccion VALUES ('{$_POST['idProveedor']}','{$aux}')";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','ProveedorDireccion','{$queryPerformed}')");

        $telefono=mysqli_query($link,"INSERT INTO Telefono(numeroTelefono) VALUES ('{$_POST['telefono']}')");
        $queryPerformed = "INSERT INTO Telefono(numeroTelefono) VALUES ('{$_POST['telefono']}')";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Telefono','{$queryPerformed}')");

        $aux1=0;
        $numrowtelefono=mysqli_query($link,"SELECT * FROM Telefono");
        while ($row=mysqli_fetch_array($numrowtelefono)){
            $aux1++;
        }
        $telefono=mysqli_query($link,"INSERT INTO ProveedorTelefono VALUES ('{$_POST['idProveedor']}','{$aux1}')");
        $queryPerformed = "INSERT INTO ProveedorTelefono VALUES ('{$_POST['idProveedor']}','{$aux1}')";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','ProveedorTelefono','{$queryPerformed}')");

    }

    ?>

    <script>
        function myFunction() {
            // Declare variables
            var input, input1, input2, input3, filter, filter1, filter2, filter3, table, tr, td, td1, td2, td3, i;
            input = document.getElementById("id");
            input1 = document.getElementById("nombre");
            input2 = document.getElementById("email");
            input3 = document.getElementById("tipo");
            filter = input.value.toUpperCase();
            filter1 = input1.value.toUpperCase();
            filter2 = input2.value.toUpperCase();
            filter3 = input3.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                td1 = tr[i].getElementsByTagName("td")[1]
                td2 = tr[i].getElementsByTagName("td")[2];
                td3 = tr[i].getElementsByTagName("td")[3];
                if ((td)&&(td1)) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        if (td1.innerHTML.toUpperCase().indexOf(filter1) > -1) {
                            if (td2.innerHTML.toUpperCase().indexOf(filter2) > -1) {
                                if (td3.innerHTML.toUpperCase().indexOf(filter3) > -1) {
                                    tr[i].style.display = "";
                                } else {
                                    tr[i].style.display = "none";
                                }
                            } else {
                                tr[i].style.display = "none";
                            }
                        } else {
                            tr[i].style.display = "none";
                        }
                    } else {
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
                Directorio
                <div class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="nuevoPartner.php">Agregar Nuevo Partner</a>
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
                                <label class="sr-only" for="id">DNI/RUC</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="id" placeholder="DNI/RUC" onkeyup="myFunction()">
                                <label class="sr-only" for="Nombre">Nombre Completo</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="nombre" placeholder="Nombre Completo" onkeyup="myFunction()">
                                <label class="sr-only" for="email">Correo Electrónico</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="email" placeholder="Correo Electrónico" onkeyup="myFunction()">
                                <label class="sr-only" for="tipo">Tipo</label>
                                <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="tipo" placeholder="Tipo" onkeyup="myFunction()">
                                <input type="submit" class="btn btn-primary" value="Limpiar" style="padding-left:28px; padding-right: 28px;">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="spacer10"></div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered" id="myTable">
                            <thead class="thead-default">
                            <tr>
                                <th class="text-center">DNI/RUC</th>
                                <th class="text-center">Nombre Completo</th>
                                <th class="text-center">Correo Electrónico</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Fecha Nacimiento</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $file = fopen("files/directorio.txt","w") or die("No se encontró el archivo!");
                            fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                            $txt = "Nombre Completo,Correo Electrónico,Tipo,Fecha de Nacimiento".PHP_EOL;
                            fwrite($file, $txt);
                            $categoria = null;
                            $estado = null;
                            $query = mysqli_query($link, "SELECT * FROM Proveedor");
                            while($row = mysqli_fetch_array($query)){
                                echo "<tr>
                                        <td class='text-center'>{$row['idProveedor']}</td>
                                        <td class=\"text-center\">{$row['nombre']}</td>
                                        <td class=\"text-center\">{$row['correoElectronico']}</td>
                                ";
                                $query1=mysqli_query($link,"SELECT * FROM TipoProveedor WHERE idTipoProveedor = '{$row['idTipoProveedor']}'");
                                while ($fila=mysqli_fetch_array($query1)){
                                    $descripcion = $fila['descripcion'];
                                    echo "<td class='text-center'>{$fila['descripcion']}</td>";
                                }
                                echo "
                                        <td class=\"text-center\">{$row['fechaNacimiento']}</td>
                                        <td class=\"text-center\">
                                            <form method='post'>
                                                <div class=\"dropdown\">
                                                    <input type='hidden' name='idProveedor' value='".$row['idProveedor']."'>
                                                    <button class=\"btn btn-secondary btn-sm dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                                    Acciones
                                                    </button>
                                                    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                                                        <button name='detallePartenr' class=\"dropdown-item\" type=\"submit\" formaction='detallePartner.php'>Ver Detalle</button>
                                                        <button name='editar' class=\"dropdown-item\" type=\"submit\" formaction='editarPartner.php'>Modificar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                      </tr>";
	                            $txt = $row['nombre'].",".$row['correoElectronico'].",".$descripcion.",".$row['fechaNacimiento'].PHP_EOL;
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

    <?php
    include('footerTemplate.php');
}else{
    include('sessionError.php');
}