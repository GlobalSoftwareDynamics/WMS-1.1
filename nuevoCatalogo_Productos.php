<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {

    if(isset($_POST['addProducto'])){
        $color = 'N/A';
        $addColor = 1;
        $colorAdd = str_replace(array_keys($replace2),$replace2,$_POST['color']);
        $query = mysqli_query($link, "SELECT * FROM Color");
        while ($row = mysqli_fetch_array($query)){
            if($row['descripcion']==$colorAdd){
                $addColor = 0;
            }
        }
        if(($addColor == 1) && ($colorAdd != null)){
            $insert = mysqli_query($link, "INSERT INTO Color (descripcion) VALUES ('{$colorAdd}')");
            $addColor = 0;
        }
        if($addColor == 0){
            $query = mysqli_query($link, "SELECT * FROM Color");
            while ($row = mysqli_fetch_array($query)){
                if($row['descripcion']==$_POST['color']){
                    $color = $row['idColor'];
                }
            }
        }
        if($addColor == 1){
            $color = 4;
        }

        $nombreCorto = str_replace(array_keys($replace2),$replace2,$_POST['nombreCorto']);
        $descripcion = str_replace(array_keys($replace2),$replace2,$_POST['descripcion']);

        $insert = mysqli_query($link, "INSERT INTO Producto VALUES ('{$_POST['codigo']}','{$_POST['tipoProducto']}','{$_POST['subcategoria']}',
                  '{$color}','{$_POST['unidadMedida']}','{$_POST['tamano']}','{$_SESSION['user']}','1','{$_POST['genero']}','{$nombreCorto}','{$descripcion}',
                  '0','{$_POST['stockReposicion']}','{$_POST['urlImagen']}','{$_POST['urlProducto']}','{$date}')");

        $queryPerformed = "INSERT INTO Producto VALUES ({$_POST['codigo']},{$_POST['tipoProducto']},{$_POST['subcategoria']},
                  {$color},{$_POST['unidadMedida']},{$_POST['tamano']},{$_SESSION['user']},1,{$_POST['genero']},{$nombreCorto},{$descripcion},
                  0,{$_POST['stockReposicion']},{$_POST['urlImagen']},{$_POST['urlProducto']},{$date})";

        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Producto','{$queryPerformed}')");
    }


    include('adminTemplateAutocomplete.php');


    if(isset($_POST['addCatalogo'])) {

        $fecha=date("Y-m-d");
        $query="INSERT INTO Catalogo(idCatalogo, idCampana, stock, tipo, fecha, fechaInicio, fechaFin) VALUES ('{$_POST['idCatalogo']}','{$_POST['campana']}','0','{$_POST['tipo']}','{$fecha}','{$_POST['fechaInicio']}','{$_POST['fechaFin']}')";
        $agregar=mysqli_query($link,$query);
        $queryPerformed = "INSERT INTO Catalogo(idCatalogo, idCampana, stock, tipo, fecha, fechaInicio, fechaFin) VALUES ({$_POST['idCatalogo']},{$_POST['campana']},0,{$_POST['tipo']},{$fecha},{$_POST['fechaInicio']},{$_POST['fechaFin']})";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Catalogo','{$queryPerformed}')");

        $query="INSERT INTO Producto VALUES ('{$_POST['idCatalogo']}','3','10',
                  4,'1','2','{$_SESSION['user']}','1','1','Catálogo {$_POST['idCatalogo']}','Catálogo {$_POST['idCatalogo']}','Catálogo',
                  '0',null,null,'{$date}')";
        $agregar=mysqli_query($link,$query);
        $queryPerformed = "INSERT INTO Producto VALUES ({$_POST['idCatalogo']},3,10,
                  4,1,2,{$_SESSION['user']},1,1,Catálogo {$_POST['idCatalogo']},Catálogo {$_POST['idCatalogo']},Catálogo,
                  0,null,null,{$date})";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Producto','{$queryPerformed}')");
    }

    if(isset($_POST['addProductoCatalogo'])){
        $nombreProducto = explode("_",$_POST['nombreprod']);
        $result=mysqli_query($link,"SELECT * FROM Producto WHERE nombreCorto = '{$nombreProducto[0]}'");
        while ($fila=mysqli_fetch_array($result)){
            $idProducto=$fila['idProducto'];
        }
        $query="INSERT INTO CatalogoProducto(idCatalogo, idProducto, idCatalogoProducto, precio, precioBase, promocion) VALUES ('{$_POST['idCatalogo']}','{$idProducto}', '{$_POST['idPromocion']}','{$_POST['precio']}', '{$_POST['precioBase']}'
            ,'{$_POST['promocion']}')";
        $agregar=mysqli_query($link,$query);
        $queryPerformed = "INSERT INTO CatalogoProducto(idCatalogo, idProducto, idCatalogoProducto, precio, precioBase, promocion) VALUES ({$_POST['idCatalogo']},{$idProducto}, {$_POST['idPromocion']},{$_POST['precio']}, {$_POST['precioBase']}
            ,{$_POST['promocion']})";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','CatalogoProducto','{$queryPerformed}')");
    }

    if(isset($_POST['deleteProducto'])){

        $delete = mysqli_query($link, "DELETE FROM CatalogoProducto WHERE idCatalogo = '{$_POST['idCatalogo']}' AND idProducto = '{$_POST['idProducto']}'");
        $queryPerformed = "DELETE FROM CatalogoProducto WHERE idCatalogo = {$_POST['idCatalogo']} AND idProducto = {$_POST['idProducto']}";
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','DELETE','CatalogoProducto','{$queryPerformed}')");

    }

    if(isset($_FILES['documento']['name'])){
        $valid_file = false;
        if($_FILES['documento']['name'])
        {
            $valid_file = true;
            //if no errors...
            if(!$_FILES['documento']['error'])
            {
                //now is the time to modify the future file name and validate the file
                $new_file_name = 'ExcelCatalogo.xls'; //rename file
                if($_FILES['documento']['size'] > (3072000)) //can't be larger than 1 MB
                {
                    $valid_file = false;
                    $message = 'El archivo seleccionado es demasiado grande!.';
                }

                //if the file has passed the test
                if($valid_file)
                {
                    //move it to where we want it to be
                    move_uploaded_file($_FILES['documento']['tmp_name'], 'uploads/'.$new_file_name);
                    $message = 'Archivo subido correctamente';
                }
            }
            //if there is an error...
            else
            {
                $valid_file = false;
                //set that to be the returned message
                $message = 'La subida del archivo devolvió el siguiente error:  '.$_FILES['documento']['error'];
            }
        }

        if($valid_file){
            require_once 'excel_reader2.php';
            $path = "uploads/ExcelCatalogo.xls";
            $data = new Spreadsheet_Excel_Reader($path,false);

            $row = 0;

            while($row < 1500){
                $row++;
                $flag = true;

                $codigoProducto = '';
                $nombreProducto = '';
                $precioCatalogo = '';
                $precioBaseProducto = '';
                $promo = '';
                $idCatalogoProducto = '';

                $codigoProducto = substr($data -> val($row,'E'),1,7);
                $nombreProducto = $data -> val($row,'H');
                $precioCatalogo = $data -> val($row,'L');
                $precioBaseProducto = $data -> val($row,'K');
                $promo = $data -> val($row,'O');
                $idCatalogoProducto = $data -> val($row,'D');

                $query = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$codigoProducto}'");
                while($fila = mysqli_fetch_array($query)){
                    if($promo != ''){
                        $flag = false;
                        $insert = mysqli_query($link,"INSERT INTO CatalogoProducto VALUES ('{$codigoProducto}','{$_POST['idCatalogo']}','{$idCatalogoProducto}','{$precioCatalogo}','{$precioBaseProducto}','{$promo}')");
                        $queryPerformed = "INSERT INTO CatalogoProducto VALUES ({$codigoProducto},{$_POST['idCatalogo']},{$idCatalogoProducto},{$precioCatalogo},{$precioBaseProducto},{$promo})";
                        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','CatalogoProducto (EXCEL)','{$queryPerformed}')");
                    }elseif($precioCatalogo != '' && $precioBaseProducto != ''){
	                    $flag = false;
	                    $insert = mysqli_query($link,"INSERT INTO CatalogoProducto VALUES ('{$codigoProducto}','{$_POST['idCatalogo']}','{$idCatalogoProducto}','{$precioCatalogo}','{$precioBaseProducto}','{$promo}')");
	                    $queryPerformed = "INSERT INTO CatalogoProducto VALUES ({$codigoProducto},{$_POST['idCatalogo']},{$idCatalogoProducto},{$precioCatalogo},{$precioBaseProducto},{$promo})";
	                    $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','CatalogoProducto (EXCEL)','{$queryPerformed}')");
                    }
                }

                if($flag){
                    if($codigoProducto != '' && $codigoProducto != 'od Mapi'){
                        $arrayCodigos[$row][1] = $codigoProducto;
                        $arrayCodigos[$row][2] = $nombreProducto;
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
                            Agregar Productos al Catálogo
                        </div>
                        <div class="float-right">
                            <div class="dropdown">
                                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#modalFile">Agregar Excel</button>
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Acciones
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <form method="post">
                                        <input type="hidden" name="idCatalogo" value="<?php echo $_POST['idCatalogo']?>">
                                        <button form="formOV" class="dropdown-item" name="guardar" formaction="gestionCatalogos.php">Finalizar</button>
                                        <button form="formOV" class="dropdown-item" name="addCatalogo" formaction="nuevoProducto.php">Nuevo Producto</button>
                                    </form>
                                </div>
                            </div>
                            <div class="dropdown">
                            </div>
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#general" role="tab">Productos</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general" role="tabpanel">
                                    <div class="spacer30"></div>
                                    <form method="post" action="gestionCatalogos.php" id="formOV">
                                        <table class="table text-center">
                                            <thead>
                                            <tr>
                                                <th class="text-center" style="width: 30%"><label for="nombreProducto">Producto</label></th>
                                                <th class="text-center" style="width: 10%"><label for="precio">Precio Catálogo (S/.)</label></th>
                                                <th class="text-center" style="width: 10%"><label for="precioBase">Precio Base (S/.)</label></th>
                                                <th class="text-center" style="width: 10%"><label for="promocion">Tipo Promoción</label></th>
                                                <th class="text-center" style="width: 10%"><label for="idPromocion">ID</label></th>
                                                <th class="text-center" style="width: 10%"><label for="addProducto">Acciones</label></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <input type='hidden' name='idCatalogo' value='<?php echo $_POST['idCatalogo'];?>'>
                                                    <input type="text" name="nombreprod" id="nombreProducto" class="form-control">
                                                </td>
                                                <td>
                                                    <input type="text" name="precio" class="form-control" id="precio">
                                                </td>
                                                <td>
                                                    <input type="text" name="precioBase" class="form-control" id="precioBase">
                                                </td>
                                                <td>
                                                    <input type="text" name="promocion" class="form-control">
                                                </td>
                                                <td><input type="text" name="idPromocion" class="form-control" id="idPromocion"></td>
                                                <td><input type="submit" class="btn btn-primary" value="Agregar" name="addProductoCatalogo" id="addProductoCatalogo" formaction="#"></td>
                                            </tr>
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

    <div class="spacer50"></div>

    <section class="container" style="height: 250px; overflow: scroll">
        <table class="table text-center">
            <thead>
            <tr>
                <th class="text-center">Ítem Nro.</th>
                <th class="text-center">Producto</th>
                <th class="text-center">Precio Catálogo (S/.)</th>
                <th class="text-center">Precio Base (S/.)</th>
                <th class="text-center">Tipo Promoción</th>
                <th class="text-center">ID</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $aux = 1;
            $query = mysqli_query($link, "SELECT * FROM CatalogoProducto WHERE idCatalogo = '{$_POST['idCatalogo']}'");
            while($row = mysqli_fetch_array($query)){
                echo "<tr>";
                echo "<td>{$aux}</td>";
                $aux ++;
                $query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
                while($row2 = mysqli_fetch_array($query2)){
                    echo "<td>{$row2['idProducto']} - {$row2['nombreCorto']}</td>";
                }
                echo "<td>{$row['precio']}</td>";
                echo "<td>{$row['precioBase']}</td>";
                echo "<td>{$row['promocion']}</td>";
                echo "<td>{$row['idCatalogoProducto']}</td>";
                echo "<td><form method='post' action='#'>
						<input type='hidden' name='idProducto' value='{$row['idProducto']}'>
						<input type='hidden' name='idCatalogo' value='{$_POST['idCatalogo']}'>
						<input type='submit' class='btn btn-warning' name='deleteProducto' value='Eliminar'>
					</form></td>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </section>

    <div class="spacer30"> </div>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-warning"></i>
                            Productos no ingresados
                        </div>
                    </div>
                    <div class="card-block" style="height: 250px; overflow: scroll">
                        <div class="col-12">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Ítem Nro.</th>
                                    <th class="text-center">Producto</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach($arrayCodigos as $codigoProd){
                                    echo "<tr>";
                                    echo "<td>{$codigoProd[1]}</td>";
                                    echo "<td>{$codigoProd[2]}</td>";
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

    <div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="modalFile" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Subir documento Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="formExcel" method="post" action="#" enctype="multipart/form-data">
                            <div class="form-group row">
                                <input type="hidden" name="idCatalogo" value="<?php echo $_POST['idCatalogo'];?>"/>
                                <label class="col-form-label" for="documento">Archivo:</label>
                                <input type="file" name="documento" id="documento" class="form-control"/>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="formExcel" value="Submit" name="addProductos">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <?php
    include('footerTemplateAutocomplete.php');
}else {
    include('sessionError.php');
}