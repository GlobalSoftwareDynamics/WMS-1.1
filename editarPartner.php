<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');
    if(isset($_POST['addDireccion'])){

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

    }

    if(isset($_POST['addTelefono'])){

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

    if(isset($_POST['deleteDireccion'])){

        $query=mysqli_query($link,"DELETE FROM ProveedorDireccion WHERE idProveedor = '{$_POST['idProveedor']}' AND idDireccion = '{$_POST['direccion']}'");
        $queryPerformed = "DELETE FROM ProveedorDireccion WHERE idProveedor = '{$_POST['idProveedor']}' AND idDireccion = '{$_POST['direccion']}'";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','DELETE','ProveedorDireccion','{$queryPerformed}')");

    }

    if(isset($_POST['deleteTelefono'])){

        $query=mysqli_query($link,"DELETE FROM ProveedorTelefono WHERE idProveedor = '{$_POST['idProveedor']}' AND idTelefono = '{$_POST['telefono']}'");
        $queryPerformed = "DELETE FROM ProveedorTelefono WHERE idProveedor = '{$_POST['idProveedor']}' AND idTelefono = '{$_POST['telefono']}'";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','DELETE','ProveedorTelefono','{$queryPerformed}')");

    }

    ?>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-shopping-bag"></i>
                            Editar Partner
                        </div>
                        <div class="float-right">
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <button form="formProveedor" name="editarPartner" class="dropdown-item">Guardar</button>
                                    <button form="formProveedor" name="regresar" class="dropdown-item">Regresar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general" role="tab">General</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="spacer30"></div>
                                    <form method="post" action="gestionPartners.php" id="formProveedor">
                                        <?php
                                        $query = mysqli_query($link, "SELECT * FROM Proveedor WHERE idProveedor = '{$_POST['idProveedor']}'");
                                        while($row = mysqli_fetch_array($query)){
                                            $id = $row['idProveedor'];
											$nombre = $row['nombre'];
                                            $correo = $row['correoElectronico'];
                                            $fechaNacimiento = $row['fechaNacimiento'];
                                            $tipo = $row['idTipoProveedor'];
                                        }
                                        ?>
                                        <div class="form-group row">
                                            <input type='hidden' name='idProveedor' value='<?php echo $_POST['idProveedor'];?>'>
                                            <label for="idProveedorNuevo" class="col-2 col-form-label">DNI/RUC:</label>
                                            <div class="col-10">
                                                <input class="form-control" type="text" id="idProveedorNuevo" name="idProveedorNuevo" value="<?php echo $id;?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="nombre" class="col-2 col-form-label">Nombre Completo:</label>
                                            <div class="col-10">
                                                <input class="form-control" type="text" id="nombre" name="nombre" value="<?php echo $nombre;?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="email" class="col-2 col-form-label">Correo Electrónico:</label>
                                            <div class="col-10">
                                                <input class="form-control" type="text" id="email" name="email" value="<?php echo $correo;?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="tipo" class="col-2 col-form-label">Tipo:</label>
                                            <div class="col-10">
                                                <select class="form-control" id="tipo" name="tipo">
                                                    <?php
                                                    $query=mysqli_query($link,"SELECT * FROM TipoProveedor WHERE idTipoProveedor = '{$tipo}'");
                                                    while ($row=mysqli_fetch_array($query)){
                                                        echo "
                                                        <option value='{$row['idTipoProveedor']}'>{$row['descripcion']}</option>
                                                    ";
                                                    }
                                                    $query=mysqli_query($link,"SELECT * FROM TipoProveedor");
                                                    while ($row=mysqli_fetch_array($query)){
                                                        echo "
                                                        <option value='{$row['idTipoProveedor']}'>{$row['descripcion']}</option>
                                                    ";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="cumple" class="col-2 col-form-label">Fecha de Nacimiento:</label>
                                            <div class="col-10">
                                                <input class="form-control" type="date" id="cumple" name="fechaNacimiento" value="<?php echo $fechaNacimiento;?>">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="spacer15"></div>
    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-home"></i>
                            Agenda de Direcciones
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Dirección</th>
                                    <th class="text-center">Ciudad</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $result=mysqli_query($link,"SELECT * FROM ProveedorDireccion WHERE idProveedor = '{$_POST['idProveedor']}'");
                                while ($fila=mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    $result1=mysqli_query($link,"SELECT * FROM Direccion WHERE idDireccion = '{$fila['idDireccion']}'");
                                    while ($fila1=mysqli_fetch_array($result1)){
                                        $result2=mysqli_query($link,"SELECT * FROM Ciudad WHERE idCiudad = '{$fila1['idCiudad']}'");
                                        while ($fila2=mysqli_fetch_array($result2)){
                                            echo "<td>{$fila1['descripcion']}</td>";
                                            echo "<td>{$fila2['nombre']}</td>";
                                            echo "
                                                <td class='text-center'>
                                                    <form method='post'>
                                                        <div class='dropdown'>
                                                            <input type='hidden' name='idProveedor' value='{$_POST['idProveedor']}'>
                                                            <input type='hidden' name='direccion' value='{$fila['idDireccion']}'>
                                                            <input type='submit' class='btn btn-warning' name='deleteDireccion' value='Eliminar'>
                                                        </div>
                                                    </form>
                                                </td>
                                            ";
                                        }
                                    }
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-outline-primary col-4 offset-4" data-toggle="modal" data-target="#modalDireccion">Agregar Dirección</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="spacer15"></div>
    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-phone"></i>
                            Agenda Telefónica
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Teléfono</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $result=mysqli_query($link,"SELECT * FROM ProveedorTelefono WHERE idProveedor = '{$_POST['idProveedor']}'");
                                while ($fila=mysqli_fetch_array($result)){
                                    echo "<tr>";
                                    $result1=mysqli_query($link,"SELECT * FROM Telefono WHERE idTelefono = '{$fila['idTelefono']}'");
                                    while ($fila1=mysqli_fetch_array($result1)){
                                        echo "<td>{$fila1['numeroTelefono']}</td>";
                                        echo "
                                                <td class='text-center'>
                                                    <form method='post'>
                                                        <div class='dropdown'>
                                                            <input type='hidden' name='idProveedor' value='{$_POST['idProveedor']}'>
                                                            <input type='hidden' name='telefono' value='{$fila['idTelefono']}'>
                                                            <input type='submit' class='btn btn-warning' name='deleteTelefono' value='Eliminar'>
                                                        </div>
                                                    </form>
                                                </td>
                                            ";
                                    }
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-outline-primary col-4 offset-4" data-toggle="modal" data-target="#modalTelefono">Agregar Teléfono</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modalTelefono" tabindex="-1" role="dialog" aria-labelledby="modalTelefono" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Direccion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="formTelefono" method="post" action="#">
                            <input type="hidden" value="<?php echo $_POST['idProveedor']?>" name="idProveedor">
                            <div class="form-group row">
                                <label class="col-form-label" for="telefono">Número de Teléfono:</label>
                                <input type="text" name="telefono" id="telefono" class="form-control">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" form="formTelefono" value="Submit" name="addTelefono">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDireccion" tabindex="-1" role="dialog" aria-labelledby="modalDireccion" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Dirección</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="formDireccion" method="post" action="#">
                            <input type="hidden" value="<?php echo $_POST['idProveedor']?>" name="idProveedor">
                            <div class="form-group row">
                                <label class="col-form-label" for="direccion">Dirección:</label>
                                <input type="text" name="direccion" id="direccion" class="form-control">
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label" for="ciudad">Ciudad:</label>
                                <select name="ciudad" id="ciudad" class="form-control">
                                    <option disabled selected>Seleccionar</option>
                                    <?php
                                    $query = mysqli_query($link, "SELECT * FROM Ciudad");
                                    while($row = mysqli_fetch_array($query)){
                                        echo "<option value='{$row['idCiudad']}'>{$row['nombre']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" form="formDireccion" value="Submit" name="addDireccion">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    include('footerTemplate.php');
}else{
    include('sessionError.php');
}