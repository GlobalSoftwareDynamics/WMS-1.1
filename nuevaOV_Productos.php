<?php
include('session.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplateAutocomplete.php');

    if(isset($_POST['addOV'])) {
        if ($_POST['clientclass'] === 'Externo') {
            $addProv = mysqli_query($link, "INSERT INTO Proveedor VALUES ('{$_POST['dni']}','3','{$_POST['colaborador']}','{$_POST['email']}',
            '{$_POST['fechanacimiento']}')");

            $queryPerformed = "INSERT INTO Proveedor VALUES ({$_POST['dni']},3,{$_POST['colaborador']},{$_POST['email']},
            {$_POST['fechanacimiento']})";

            $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Proveedor','{$queryPerformed}')");

            if(!empty($_POST['direccion'])){

                $direccion=mysqli_query($link,"INSERT INTO Direccion(idCiudad, descripcion) VALUES ('{$_POST['ciudad']}','{$_POST['direccion']}')");
                $queryPerformed = "INSERT INTO Direccion(idCiudad, descripcion) VALUES ('{$_POST['ciudad']}','{$_POST['direccion']}')";
                $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Direccion','{$queryPerformed}')");

                $aux=0;
                $numrowdireccion=mysqli_query($link,"SELECT * FROM Direccion");
                while ($row=mysqli_fetch_array($numrowdireccion)){
                    $aux++;
                }
                $direccion=mysqli_query($link,"INSERT INTO ProveedorDireccion VALUES ('{$_POST['dni']}','{$aux}')");
                $queryPerformed = "INSERT INTO ProveedorDireccion VALUES ('{$_POST['dni']}','{$aux}')";
                $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','ProveedorDireccion','{$queryPerformed}')");
            }

            if(!empty($_POST['telefono'])){

                $telefono=mysqli_query($link,"INSERT INTO Telefono(numeroTelefono) VALUES ('{$_POST['telefono']}')");
                $queryPerformed = "INSERT INTO Telefono(numeroTelefono) VALUES ('{$_POST['telefono']}')";
                $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Telefono','{$queryPerformed}')");

                $aux1=0;
                $numrowtelefono=mysqli_query($link,"SELECT * FROM Telefono");
                while ($row=mysqli_fetch_array($numrowtelefono)){
                    $aux1++;
                }
                $telefono=mysqli_query($link,"INSERT INTO ProveedorTelefono VALUES ('{$_POST['dni']}','{$aux1}')");
                $queryPerformed = "INSERT INTO ProveedorTelefono VALUES ('{$_POST['dni']}','{$aux1}')";
                $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','ProveedorTelefono','{$queryPerformed}')");

            }

            $addOV = mysqli_query($link, "INSERT INTO Transaccion VALUES ('{$_POST['idTransaccion']}','4','{$_POST['dni']}','5','{$_SESSION['user']}',NULL,NULL,'{$dateTime}',NULL,NULL,
		    '{$_POST['costoEnvio']}','{$_POST['observaciones']}',NULL,NULL,NULL)");

            $queryPerformed = "INSERT INTO Transaccion VALUES ({$_POST['idTransaccion']},4,{$_POST['dni']},5,{$_SESSION['user']},NULL,NULL,{$dateTime},NULL,NULL,
		    {$_POST['costoEnvio']},{$_POST['observaciones']},NULL,NULL,NULL)";

            $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','OV','{$queryPerformed}')");

        }else {
            $id=mysqli_query($link,"SELECT * FROM Proveedor WHERE nombre = '{$_POST['colaborador']}'");
            while ($fila=mysqli_fetch_array($id)){
                $idProveedor = $fila['idProveedor'];
            }

            $addOV = mysqli_query($link, "INSERT INTO Transaccion VALUES ('{$_POST['idTransaccion']}','4','{$idProveedor}','5','{$_SESSION['user']}',NULL,NULL,'{$dateTime}',NULL,NULL,
		    '{$_POST['costoEnvio']}','{$_POST['observaciones']}',NULL,NULL,NULL)");

            $queryPerformed = "INSERT INTO Transaccion VALUES ({$_POST['idTransaccion']},4,{$idProveedor},5,{$_SESSION['user']},NULL,NULL,{$dateTime},NULL,NULL,
		    {$_POST['costoEnvio']},{$_POST['observaciones']},NULL,NULL,NULL)";

            $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','OV','{$queryPerformed}')");
        }
    }
    if(isset($_POST['addProducto'])){

        $promocion = "null";
        $addPromocion = 1;
        $query = mysqli_query($link, "SELECT * FROM Descuento");
        while ($row = mysqli_fetch_array($query)){
            if($row['porcentaje']==$_POST['promocion']){
                $addPromocion = 0;
            }
        }
        if(($addPromocion == 1) && ($_POST['promocion'] != null)){
            $insert = mysqli_query($link, "INSERT INTO Descuento (porcentaje, montoMin, tipo) VALUES ('{$_POST['promocion']}',null,false)");
            $addPromocion = 0;
        }
        if($addPromocion == 0){
            $query = mysqli_query($link, "SELECT * FROM Descuento");
            while ($row = mysqli_fetch_array($query)){
                if($row['porcentaje']==$_POST['promocion']){
                    $promocion = $row['idDescuento'];
                }
            }
        }

        $nombreProducto = explode("_",$_POST['nombreProducto']);
        $id=mysqli_query($link,"SELECT * FROM Producto WHERE nombreCorto = '{$nombreProducto[0]}'");
        while ($fila=mysqli_fetch_array($id)){
            $idProducto = $fila['idProducto'];
        }

        $query = mysqli_query($link,"SELECT idProducto, cantidad, stockInicial FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}' AND idProducto = '{$idProducto}'");
        $numrow = mysqli_num_rows($query);
        if ($numrow > 0){
            while ($fila = mysqli_fetch_array($query)){
                $stockinicial = $fila['stockInicial'] - $fila['cantidad'];
                echo $stockinicial;
                $stockfinal=$stockinicial-$_POST['cantidad'];
            }
        }else{
            $stockinicial=0;
            $stock=mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$idProducto}'");
            while ($fila=mysqli_fetch_array($stock)){
                $stockinicial=$stockinicial+$fila['stock'];
            }
            $stockfinal=$stockinicial-$_POST['cantidad'];
        }

        $promo="Sin Promoción";
        switch ($_POST['promo']){
            case 1:
                $promo="NA";
                break;
            case 0.5:
                $promo="2x1";
                break;
            case 0.666667:
                $promo="6x4";
                break;
            case 0.75:
                $promo="4x3";
                break;
        }

        if($_POST['notas'] != null){
            $_POST['notas']="{$_POST['notas']} (Promoción: {$promo}, Descuento: {$_POST['promocion']}%)";
        }elseif(!empty($_POST['promocion'])){
            $_POST['notas']="Promoción: {$promo}, Descuento: {$_POST['promocion']}%";
        }elseif(empty($_POST['promocion'])){
            $_POST['notas']="Promoción: {$promo}";
        }else{
            $_POST['notas']=null;
        }

        $checkCatalogo=substr($idProducto,4,1);
        if($checkCatalogo=="C"){
            $precio=$_POST['precio'];
            $precioBase=$_POST['precio'];
        }else{
            $precio1=explode(" ",$_POST['precio']);
            $precio=$precio1[0]*$_POST['promo'];
            $precioBase=$precio1[1];
        }

        $add = mysqli_query($link, "INSERT INTO TransaccionProducto(idProducto,idTransaccion,idUbicacionInicial,idUbicacionFinal,idDescuento,valorUnitario,cantidad,idPromocion,observacion,stockInicial,stockFinal,descuento,descuentoMonetario) VALUES ('{$idProducto}','{$_POST['idTransaccion']}',null,null,{$promocion},'{$precio}','{$_POST['cantidad']}',
		'{$precioBase}','{$_POST['notas']}','{$stockinicial}','{$stockfinal}',false,'{$_POST['descMonetario']}')");

        $queryPerformed = "INSERT INTO TransaccionProducto(idProducto,idTransaccion,idUbicacionInicial,idUbicacionFinal,idDescuento,valorUnitario,cantidad,idPromocion,observacion,stockInicial,stockFinal,descuento,descuentoMonetario) VALUES ({$idProducto},{$_POST['idTransaccion']},null,null,{$promocion},{$precio},{$_POST['cantidad']},
		{$precioBase},{$_POST['notas']},{$stockinicial},{$stockfinal},false,{$_POST['descMonetario']})";
		
        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','OV-addProducto','{$queryPerformed}')");

    }

    if(isset($_POST['deleteProducto'])){
        $delete = mysqli_query($link, "DELETE FROM TransaccionProducto WHERE idProducto = '{$_POST['idProducto']}' AND idTransaccion = '{$_POST['idTransaccion']}'");

        $queryPerformed = "DELETE FROM TransaccionProducto WHERE idProducto = {$_POST['idProducto']} AND idTransaccion = {$_POST['idTransaccion']}";

        $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','DELETE','OV-deleteProducto','{$queryPerformed}')");
    }

    ?>

    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-shopping-cart"></i>
                            Agregar Productos Orden de Venta <?php echo $_POST['idTransaccion']?>
                        </div>
                        <div class="float-right">
                            <div class="dropdown">
                                <button form="formOV" value="Guardar" name="addOC" class="btn btn-secondary btn-sm">Guardar</button>
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
                                    <form method="post" action="nuevaOV_Cancelacion.php" id="formOV">
                                        <input type="hidden" name="idTransaccion" value="<?php echo $_POST['idTransaccion'];?>">
                                        <input type="hidden" name="costoEnvio" value="<?php echo $_POST['costoEnvio'];?>">
                                        <table class="table text-center">
                                            <thead>
                                            <tr>
                                                <th class="text-center" style="width: 10%"><label for="idCatalogo">Cód. Catalogo</label></th>
                                                <th class="text-center" style="width: 17%"><label for="Productos">Producto</label></th>
                                                <th class="text-center" style="width: 12%"><label for="cantidad">Cantidad</label></th>
                                                <th class="text-center" style="width: 14%"><label for="preciosugerido">Precio Unitario (S/.)</label></th>
                                                <th class="text-center" style="width: 10%"><label for="descento">Promoción</label></th>
                                                <th class="text-center" style="width: 10%"><label for="promocion">Descuento (%)</label></th>
                                                <th class="text-center" style="width: 10%"><label for="descMonetario">Ajuste (S/.)</label></th>
                                                <th class="text-center" style="width: 10%"><label for="notas">Notas</label></th>
                                                <th class="text-center" style="width: 8%"><label for="addProducto">Acciones</label></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td id="codigoCatalogo"><input type="number" min="0" class="form-control" name="idCatalogo" id="idCatalogo" onchange="getproductoCatalogo(this.value);"></td>
                                                <td id="nombreProdID"><input type="text" class="form-control" name="nombreProducto" id="Productos" onchange="getcantidadprod(this.value);getprecioprom(this.value);"></td>
                                                <td id="maxcantidad"><input type="number" min="0" name="cantidad" class="form-control" id="cantidad"></td>
                                                <td id="precioprom"><input type="text" name="precio" class="form-control" id="preciosugerido"></td>
                                                <td>
                                                    <select id="descento" class="form-control" name="promo">
                                                        <option value="1">Sin Promoción</option>
                                                        <option value="0.5">2X1</option>
                                                        <option value="0.75">4X3</option>
                                                        <option value="0.666667">6X4</option>
                                                    </select>
                                                </td>
                                                <td><input type="number" name="promocion" class="form-control" id="promocion" placeholder="xx%" min="0" max="100"></td>
                                                <td><input type="number" step='0.1' name="descMonetario" class="form-control" id="descMonetario" placeholder="xx" min="0"></td>
                                                <td><input type="text" name="notas" class="form-control" id="notas"></td>
                                                <td><input type="submit" class="btn btn-primary" value="Agregar" name="addProducto" id="addProducto" formaction="#"></td>
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

    <section class="container">
        <table class="table text-center">
            <thead>
            <tr>
                <th class="text-center">Ítem Nro.</th>
                <th class="text-center">Descripción</th>
                <th class="text-center">Cantidad</th>
                <th class="text-center">Precio Unitario (S/.)</th>
                <th class="text-center">Total Ítem (S/.)</th>
                <th class="text-center">Notas</th>
                <th class="text-center">Acciones</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $aux = 1;
            $totalventa=0;
            $total=0;
            $totaldescuento=0;
            $subtotal=0;
            $subtotalsinsunat=0;
            $totalsunat=0;
            $descuentounitario=0;
            $totaldescuentocatalogo = 0;
            $subtotalcatalogo=0;
            $costoEnvio=$_POST['costoEnvio'];
            $query = mysqli_query($link, "SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$_POST['idTransaccion']}'");
            while($row = mysqli_fetch_array($query)){
                echo "<tr>";
                echo "<td>{$aux}</td>";
                $aux ++;
                $query2 = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$row['idProducto']}'");
                while($row2 = mysqli_fetch_array($query2)){
                    echo "<td>{$row2['idProducto']} - {$row2['nombreCorto']}</td>";
                }

                $valor=round($row['valorUnitario'],2);
                $descuentocatalogoprodcuto = ($row['idPromocion']-$valor)*$row['cantidad'];
                $totaldescuentocatalogo+=$descuentocatalogoprodcuto;

                echo "<td>{$row['cantidad']}</td>";
                echo "<td>S/. {$valor}</td>";

                $descuento=1;
                $descuentoneto=1;
                $desc=mysqli_query($link,"SELECT * FROM Descuento WHERE idDescuento = '{$row['idDescuento']}'");
                while ($fila=mysqli_fetch_array($desc)){
                    if($fila['porcentaje']!=null){
                        $descuento=1-($fila['porcentaje']/100);
                        $descuentoneto=$fila['porcentaje']/100;
                        $descuentounitario=$row['cantidad'] *$row['valorUnitario']*$descuentoneto;
                        $totaldescuento=$totaldescuento+$descuentounitario;
                    }
                }

                $subtotalproductocatalogo=$row['cantidad'] * $row['idPromocion'];
                $subtotalcatalogo=$subtotalcatalogo+$subtotalproductocatalogo;

                $subtotalproducto=$row['cantidad'] * $row['valorUnitario'];
                $subtotal=$subtotal+$subtotalproducto;

                $descuentoproducto=($row['valorUnitario'] - $row['descuentoMonetario']) * $descuento;
                $total = ($row['cantidad'] * $descuentoproducto);

                $subtotalsinsunat=$subtotalsinsunat+$total;

                $subtotalproductoround=round($subtotalproducto,2);

                echo "<td>S/. {$subtotalproductoround}</td>";
                echo "<td>{$row['observacion']}</td>";
                echo "<td><form method='post' action='#'>
						<input type='hidden' name='idProducto' value='{$row['idProducto']}'>
						<input type='hidden' name='idTransaccion' value='{$_POST['idTransaccion']}'>
						<input type='hidden' name='costoEnvio' value='{$_POST['costoEnvio']}'>
						<input type='submit' class='btn btn-warning' name='deleteProducto' value='Eliminar'>
					</form></td>";
                echo "</tr>";
            }

            $totalsunat=$subtotalsinsunat*0.02+$costoEnvio*0.02;

            $subtotalsinsunat=$subtotalsinsunat+$costoEnvio;

            $totalventa=$subtotalsinsunat+$totalsunat;
            ?>
            </tbody>
        </table>
    </section>

    <section class="container">
        <div class="row">
            <div class="col-5 offset-7">
                <table class="table text-center">
                    <tbody>
                    <tr>
                        <th>Venta Público:</th>
                        <td>S/. <?php echo round($subtotal,1)?></td>
                    </tr>
                    <tr>
                        <th>Descuento Especial:</th>
                        <td>S/. <?php echo round($totaldescuento,1)?></td>
                    </tr>
                    <tr>
                        <th>Costo de Envío:</th>
                        <td>S/. <?php echo round($costoEnvio,1);?></td>
                    </tr>
                    <tr>
                        <th>Sub Total sin Impuestos:</th>
                        <td>S/. <?php echo round($subtotalsinsunat,1);?></td>
                    </tr>
                    <tr>
                        <th>Percepción RS.261-2005 SUNAT 2%:</th>
                        <td>S/. <?php echo round($totalsunat,1);?></td>
                    </tr>
                    <tr>
                        <th>Total Venta:</th>
                        <td>S/. <?php echo round($totalventa,1)?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <?php
    include('footerTemplate.php');
}else {
    include('sessionError.php');
}