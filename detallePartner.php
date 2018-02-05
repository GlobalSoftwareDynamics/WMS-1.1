<?php
include('session.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');
    include('funciones.php');

    $result = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$_POST['idProveedor']}'");
    while($row = mysqli_fetch_array($result)) {
        $result2 = mysqli_query($link,"SELECT * FROM TipoProveedor WHERE idTipoProveedor = '{$row['idTipoProveedor']}'");
        while($row2 = mysqli_fetch_array($result2)){
            $tipoproveedor = $row2['descripcion'];
        }
        ?>
        <section class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header card-inverse card-info">
                            <form method="post" action="gestionPartners.php" id="form">
                                <div class="float-left">
                                    <i class="fa fa-user"></i>
                                    Tarjeta de Contacto
                                </div>
                                <div class="float-right">
                                    <div class="dropdown">
                                        <input type='submit' value='Volver' name='volver' class='btn btn-secondary btn-sm'>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-block">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-3"><p><b>Nombre:</b></p></div>
                                    <div class="col-9"><p><?php echo $row['nombre']; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Correo Electrónico:</b></p></div>
                                    <div class="col-9"><p><?php echo $row['correoElectronico']; ?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Fecha de Nacimiento:</b></p></div>
                                    <div class="col-9"><p><?php echo $row['fechaNacimiento'];?></p></div>
                                </div>
                                <div class="row">
                                    <div class="col-3"><p><b>Tipo de Partner:</b></p></div>
                                    <div class="col-9"><p><?php echo $tipoproveedor;?></p></div>
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
                                            }
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
        </section>

        <?php
    }
    include('footerTemplate.php');
}else{
    include('sessionError.php');
}