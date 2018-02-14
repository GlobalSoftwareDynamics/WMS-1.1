<?php
include('session.php');
if (!empty($_POST['conteoInventarioStock'])) {
    $query = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['conteoInventarioStock']}' AND idUbicacion = '{$_POST['ubicacion']}'");
    while ($row = mysqli_fetch_array($query)) {
        echo "<input class='form-control' type='number' id='stock' min='0' name='stock' value='{$row['stock']}'>";
    }
}

if (!empty($_POST['transferenciaUbicacionUbicacion'])) {
    $query = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idAlmacen = '{$_POST['transferenciaUbicacionUbicacion']}'");
    while ($row = mysqli_fetch_array($query)) {
        echo "<option value='{$row['idUbicacion']}'>{$row['idUbicacion']}</option>";
    }
}

if (!empty($_POST['clasecliente'])) {
    if ($_POST['clasecliente']==="Interno"){
        /*echo "
                <div class='form-group row'>
                    <label for='nombre' class='col-2 col-form-label'>Nombre:</label>
                    <div class='col-10'>
                        <select class='form-control' name='colaborador' id='nombre'>
                            <option value='shitu'>Seleccionar</option>";
                            $colaborador = mysqli_query($link,"SELECT * FROM Proveedor WHERE tipo = 'Consultora Propia'");
                            while($fila = mysqli_fetch_array($colaborador)){
                                echo "<option value=".$fila['idProveedor'].">{$fila['nombre']}</option>";
                            }
        echo "
                        </select>
                    </div>
                </div>
            ";*/
        echo "
                <div class='form-group row'>
                    <label for='nombreProveedor' class='col-2 col-form-label'>Nombre:</label>
                    <div class='col-10'>
                        <input type='text' id='nombreProveedor' name='colaborador' class='form-control'>
                    </div>
                </div>
            ";
    }elseif ($_POST['clasecliente']==="Externo"){
        echo "
                <div class='form-group row'>
                    <label class='col-2 col-form-label'>Datos:</label>
                    <div class='col-10'>
                        <label for='dni' class='sr-only'>DNI</label>
                        <input type='text' id='dni' name='dni' class='form-control col-2 mb-2 mr-2' placeholder='DNI'>
                        <label for='nombre' class='sr-only'>Nombre</label>
                        <input type='text' id='nombrecolaborador' name='colaborador' class='form-control col-7 mb-2' placeholder='Nombre'>
                        <label for='fechanacim' class='sr-only'>Fecha de Nacimiento</label>
                        <input type='date' id='fechanacim' name='fechanacimiento' class='form-control col-4 mb-2 mt-2' placeholder='Cumpleaños'>
                        <label for='mail' class='sr-only'>Email</label>
                        <input type='email' id='mail' name='email' class='form-control col-5 mb-2 mt-2' placeholder='Email'>
                        <label for='telf' class='sr-only'>Teléfono</label>
                        <input type='text' id='telf' name='telefono' class='form-control col-5 mb-2 mt-2' placeholder='Teléfono'>
                        <label for='direccion' class='sr-only'>Direccion</label>
                        <input type='text' id='direccion' name='direccion' class='form-control col-8 mt-2' placeholder='Dirección'>
                        <label for='ciudad' class='sr-only'>Ciudad</label>
                        <select type='text' id='ciudad' name='ciudad' class='form-control col-5 mt-2'>
                            <option>Ciudad</option>";
        $ciudad=mysqli_query($link,"SELECT * FROM Ciudad");
        while ($fila=mysqli_fetch_array($ciudad)){
            echo "
                                    <option value='{$fila['idCiudad']}'>{$fila['nombre']}</option>
                                ";
        }
        echo "
                        </select>
                    </div>
                </div>
            ";
    }
}

if (!empty($_POST['almacen'])) {
    $ubicacion = mysqli_query($link, "SELECT * FROM Ubicacion WHERE idAlmacen = '{$_POST['almacen']}'");
    while ($fila = mysqli_fetch_array($ubicacion)) {
        echo "<option value='{$fila['idUbicacion']}'>{$fila['idUbicacion']}</option>";
    }
}

if (!empty($_POST['getcantidadprod'])) {
    $stocktotal=0;
    $nombreProducto = explode("_",$_POST['getcantidadprod']);
    $id=mysqli_query($link,"SELECT * FROM Producto WHERE nombreCorto = '{$nombreProducto[0]}'");
    while ($fila=mysqli_fetch_array($id)){
        $idProducto = $fila['idProducto'];
    }
    $query = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$idProducto}'");
    while ($row = mysqli_fetch_array($query)) {
        $stocktotal = $stocktotal + $row['stock'];
    }
    echo "<input class='form-control' type='number' id='cantidad' min='0' name='cantidad' max='{$stocktotal}' placeholder='max: {$stocktotal}'>";
}

if (!empty($_POST['getcantidadprodID'])) {
    $stocktotal=0;

    $query = mysqli_query($link, "SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['getcantidadprodID']}'");
    while ($row = mysqli_fetch_array($query)) {
        $stocktotal = $stocktotal + $row['stock'];
    }
    echo "<input class='form-control' type='number' id='cantidad' min='0' name='cantidad' max='{$stocktotal}' placeholder='max: {$stocktotal}'>";
}

if (!empty($_POST['getnombreprodID'])) {

    $query = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$_POST['getnombreprodID']}'");
    while ($row = mysqli_fetch_array($query)) {
        echo "<input class='form-control' type='text' id='nombreProducto' name='nombreProducto' value='{$row['nombreCorto']}'>";
    }
}

if (!empty($_POST['getidproducto'])) {

    $nombreProducto = explode("_",$_POST['getidproducto']);
    $query=mysqli_query($link,"SELECT * FROM Producto WHERE nombreCorto = '{$nombreProducto[0]}'");
    while ($row = mysqli_fetch_array($query)) {
        echo "<input class='form-control' type='text' id='Productos' name='producto' value='{$row['idProducto']}'>";
    }
}

if (!empty($_POST['getprecioprom'])) {
    $date = date("Y-m-d");
    $anio = explode("-",$date);
    $campanaactual=0;
    $campanaanterior=0;
    $campanasiguiente=0;
    $tipoProducto=0;
    $aux=0;
    $array=array();
    $idProducto = null;

    $nombreProducto = explode("_",$_POST['getprecioprom']);
    $id=mysqli_query($link,"SELECT * FROM Producto WHERE nombreCorto = '{$nombreProducto[0]}'");
    while ($fila=mysqli_fetch_array($id)){
        $idProducto = $fila['idProducto'];
        $array[$aux]=array($fila['costoEstimado'],'NA');
        $tipoProducto = $fila['idTipoProducto'];
    }
    if($tipoProducto==2||$tipoProducto==3){
        echo "<input type='text' name='precio' class='form-control'>";
    }else{
        $catalogos=mysqli_query($link,"SELECT * FROM Catalogo WHERE fechaInicio <= '{$date}' AND fechaFin >= '{$date}' AND tipo != 'Entrenos'");
        while ($row=mysqli_fetch_array($catalogos)){
            $campanaactual=$row['idCampana'];
            $idcatalogoactual=$row['idCatalogo'];
        }

        if($campanaactual==1){

            $anioanterior=$anio[0]-1;
            $campanasiguiente=$campanaactual+1;
            $campanaanterior=13;
            $idCatalogoSiguiente="{$anio[0]}C{$campanasiguiente}";
            $idCatalogoAnterior="{$anioanterior}C{$campanaanterior}";

        }elseif ($campanaactual==13){

            $aniosiguiente=$anio[0]+1;
            $campanasiguiente=1;
            $campanaanterior=$campanaactual-1;
            $idCatalogoSiguiente="{$aniosiguiente}C{$campanasiguiente}";
            $idCatalogoAnterior="{$anio[0]}C{$campanaanterior}";

        }else{

            $campanasiguiente=$campanaactual+1;
            $campanaanterior=$campanaactual-1;
            $idCatalogoAnterior="{$anio[0]}C{$campanaanterior}";
            $idCatalogoSiguiente="{$anio[0]}C{$campanasiguiente}";

        }

        $catalogoscampana=mysqli_query($link,"SELECT * FROM CatalogoProducto WHERE idProducto = '{$idProducto}' AND idCatalogo = '{$idCatalogoAnterior}'");
        $numrows=mysqli_num_rows($catalogoscampana);
        if($numrows>0){
            while ($row1=mysqli_fetch_array($catalogoscampana)){
                $aux++;
                if(!empty($row1['promocion'])){
                    $array[$aux]=array($campanaanterior,$row1['idCatalogoProducto'],$row1['precio'],$row1['promocion'],$row1['precioBase']);
                }else{
                    $array[$aux]=array($campanaanterior,$row1['idCatalogoProducto'],$row1['precio'],"NA",$row1['precioBase']);
                }
            }
        }else{
            $aux++;
            $array[$aux]=array("NA","","");
        }


        $catalogoscampana1=mysqli_query($link,"SELECT * FROM CatalogoProducto WHERE idProducto = '{$idProducto}' AND idCatalogo = '{$idcatalogoactual}'");
        $numrows=mysqli_num_rows($catalogoscampana1);
        if($numrows>0){
            while ($row1=mysqli_fetch_array($catalogoscampana1)){
                $aux++;
                if(!empty($row1['promocion'])){
                    $array[$aux]=array($campanaactual,$row1['idCatalogoProducto'],$row1['precio'],$row1['promocion'],$row1['precioBase']);
                }else{
                    $array[$aux]=array($campanaactual,$row1['idCatalogoProducto'],$row1['precio'],"NA",$row1['precioBase']);
                }
            }
        }else{
            $aux++;
            $array[$aux]=array("NA","","");
        }

        $catalogoscampana1=mysqli_query($link,"SELECT * FROM CatalogoProducto WHERE idProducto = '{$idProducto}' AND idCatalogo = '{$idCatalogoSiguiente}'");
        $numrows=mysqli_num_rows($catalogoscampana1);
        if($numrows>0){
            while ($row1=mysqli_fetch_array($catalogoscampana1)){
                $aux++;
                if(!empty($row1['promocion'])){
                    $array[$aux]=array($campanasiguiente,$row1['idCatalogoProducto'],$row1['precio'],$row1['promocion'],$row1['precioBase']);
                }else{
                    $array[$aux]=array($campanasiguiente,$row1['idCatalogoProducto'],$row1['precio'],"NA",$row1['precioBase']);
                }
            }
        }else{
            $aux++;
            $array[$aux]=array("NA","","");
        }

        echo "
            <select class='form-control' name='precio'>
                <option disabled>Opciones</option>";
        for ($i = 0; $i < $aux; $i++){
            if($i===0){
                echo "
                <option value='{$array[$i][0]}'>Precio Promedio: S/.{$array[$i][0]}</option>
            ";
            }else{
                echo "
                    <option value='{$array[$i][2]}|{$array[$i][4]}'>C{$array[$i][0]}({$array[$i][1]}): S/.{$array[$i][0]} ({$array[$i][3]})</option>
                ";
            }
        }
        echo "
            </select>
        ";
    }
}

if (!empty($_POST['getpreciopromID'])) {
    $date = date("Y-m-d");
    $anio = explode("-",$date);
    $campanaactual=0;
    $campanaanterior=0;
    $campanasiguiente=0;
    $tipoProducto=0;
    $aux=0;
    $array=array();

    $id=mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$_POST['getpreciopromID']}'");
    while ($fila=mysqli_fetch_array($id)){
        $idProducto = $fila['idProducto'];
        $array[$aux]=array($fila['costoEstimado'],'NA');
        $tipoProducto = $fila['idTipoProducto'];
    }
    if($tipoProducto==2||$tipoProducto==3){
        echo "<input type='text' name='precio' class='form-control'>";
    }else{
        $catalogos=mysqli_query($link,"SELECT * FROM Catalogo WHERE fechaInicio <= '{$date}' AND fechaFin >= '{$date}' AND tipo != 'Entrenos'");
        while ($row=mysqli_fetch_array($catalogos)){
            $campanaactual=$row['idCampana'];
            $idcatalogoactual=$row['idCatalogo'];
        }

        if($campanaactual==1){

            $anioanterior=$anio[0]-1;
            $campanasiguiente=$campanaactual+1;
            $campanaanterior=13;
            $idCatalogoSiguiente="{$anio[0]}C{$campanasiguiente}";
            $idCatalogoAnterior="{$anioanterior}C{$campanaanterior}";

        }elseif ($campanaactual==13){

            $aniosiguiente=$anio[0]+1;
            $campanasiguiente=1;
            $campanaanterior=$campanaactual-1;
            $idCatalogoSiguiente="{$aniosiguiente}C{$campanasiguiente}";
            $idCatalogoAnterior="{$anio[0]}C{$campanaanterior}";

        }else{

            $campanasiguiente=$campanaactual+1;
            $campanaanterior=$campanaactual-1;
            $idCatalogoAnterior="{$anio[0]}C{$campanaanterior}";
            $idCatalogoSiguiente="{$anio[0]}C{$campanasiguiente}";

        }

        $catalogoscampana=mysqli_query($link,"SELECT * FROM CatalogoProducto WHERE idProducto = '{$_POST['getpreciopromID']}' AND idCatalogo = '{$idCatalogoAnterior}'");
        $numrows=mysqli_num_rows($catalogoscampana);
        if($numrows>0){
            while ($row1=mysqli_fetch_array($catalogoscampana)){
                $aux++;
                if(!empty($row1['promocion'])){
                    $array[$aux]=array($row1['precio'],$row1['promocion'],$row1['precioBase']);
                }else{
                    $array[$aux]=array($row1['precio'],"NA",$row1['precioBase']);
                }
            }
        }else{
            $aux++;
            $array[$aux]=array("NA","","");
        }


        $catalogoscampana1=mysqli_query($link,"SELECT * FROM CatalogoProducto WHERE idProducto = '{$_POST['getpreciopromID']}' AND idCatalogo = '{$idcatalogoactual}'");
        $numrows=mysqli_num_rows($catalogoscampana1);
        if($numrows>0){
            while ($row1=mysqli_fetch_array($catalogoscampana1)){
                $aux++;
                if(!empty($row1['promocion'])){
                    $array[$aux]=array($row1['precio'],$row1['promocion'],$row1['precioBase']);
                }else{
                    $array[$aux]=array($row1['precio'],"NA",$row1['precioBase']);
                }
            }
        }else{
            $aux++;
            $array[$aux]=array("NA","","");
        }

        $catalogoscampana1=mysqli_query($link,"SELECT * FROM CatalogoProducto WHERE idProducto = '{$_POST['getpreciopromID']}' AND idCatalogo = '{$idCatalogoSiguiente}'");
        $numrows=mysqli_num_rows($catalogoscampana1);
        if($numrows>0){
            while ($row1=mysqli_fetch_array($catalogoscampana1)){
                $aux++;
                if(!empty($row1['promocion'])){
                    $array[$aux]=array($row1['precio'],$row1['promocion'],$row1['precioBase']);
                }else{
                    $array[$aux]=array($row1['precio'],"NA",$row1['precioBase']);
                }
            }
        }else{
            $aux++;
            $array[$aux]=array("NA","","");
        }

        echo "
            <select class='form-control' name='precio'>
                <option disabled>Opciones</option>";
        for ($i=0;$i<4;$i++){
            if($i===0){
                echo "
                <option value='{$array[$i][0]}'>Precio Promedio: S/.{$array[$i][0]}</option>
            ";
            }elseif ($i===1){
                echo "
                <option value='{$array[$i][0]}|{$array[$i][2]}'>Catálogo Anterior: S/.{$array[$i][0]} ({$array[$i][1]})</option>
            ";
            }elseif ($i===2){
                echo "
                <option value='{$array[$i][0]}|{$array[$i][2]}'>Catálogo Actual: S/.{$array[$i][0]} ({$array[$i][1]})</option>
            ";
            }elseif ($i===3){
                echo "
                <option value='{$array[$i][0]}|{$array[$i][2]}'>Catálogo Siguiente: S/.{$array[$i][0]} ({$array[$i][1]})</option>
            ";
            }
        }
        echo "
            </select>
        ";
    }
}

if (!empty($_POST['montorestante'])) {
    $resta=round($_POST['montorestante']-$_POST['cancelado'],2);
    if($resta<0){
        $resta=0;
    }
    echo "<input class='form-control' type='text' id='restante' name='montofaltante' value='{$resta}' readonly>";
}

if (!empty($_POST['categoria'])) {
    $ubicacion = mysqli_query($link, "SELECT * FROM SubCategoria WHERE idCategoria = '{$_POST['categoria']}'");
    while ($fila = mysqli_fetch_array($ubicacion)) {
        echo "<option value='{$fila['idSubCategoria']}'>{$fila['descripcion']}</option>";
    }
}

if (!empty($_POST['dias'])) {
    if( $_POST['dias']>$_POST['cancelado']){
        echo "
            <script>
                function fechavenc(dias) {
                    $.ajax({
                        type: 'POST',
                        url: 'getAjax.php',
                        data:{'fechavenc':dias},
                        success: function(data){
                            $('#fechavenc').html(data);
                        }
                    });
                }
            </script>
        ";
        echo "
            <div class='form-group row'>
                <label for='diasfecha' class='col-4 col-form-label'>Fecha de Pago:</label>
                <div class='col-8 row'>
                    <input type='number' min='0' name='diasfecha' id='diasfecha' class='form-control col-3' onkeyup='fechavenc(this.value)' onchange='fechavenc(this.value)'>
                    <div class='col-9' id='fechavenc'></div>
                </div>
            </div>
        ";
    }
}

if (!empty($_POST['fechavenc'])) {
    $fechaInicioDeudas = date("Y-m-d");
    $fechaFinDeudas = date('Y-m-d', strtotime($fechaInicioDeudas. ' + '.$_POST['fechavenc'].' days'));
    echo "
        <input type='date' name='fechavencimiento' id='fechav' class='form-control' value='{$fechaFinDeudas}'>
    ";
}

if (!empty($_POST['diasPrestamo'])) {
    if( $_POST['diasPrestamo']>$_POST['cancelado']){
        echo "
            <script>
                function fechavencPrestamo(dias) {
                    $.ajax({
                        type: 'POST',
                        url: 'getAjax.php',
                        data:{'fechavencPrestamo':dias},
                        success: function(data){
                            $('#fechavenc').html(data);
                        }
                    });
                }
            </script>
        ";
        echo "
            <div class='form-group row'>
                <label for='diasfecha' class='col-2 col-form-label'>Fecha de Pago:</label>
                <div class='col-10 row'>
                    <input type='number' min='0' name='diasfecha' id='diasfecha' class='form-control col-3' onkeyup='fechavencPrestamo(this.value)' onchange='fechavencPrestamo(this.value)'>
                    <div id='fechavenc' class='col-9'></div>
                </div>
            </div>
        ";
    }
}

if (!empty($_POST['fechavencPrestamo'])) {
    $fechaInicioDeudas = date("Y-m-d");
    $fechaFinDeudas = date('Y-m-d', strtotime($fechaInicioDeudas. ' + '.$_POST['fechavencPrestamo'].' days'));
    echo "
        <input type='date' name='fechavencimiento' id='fechav' class='form-control' value='{$fechaFinDeudas}'>
    ";
}

if (!empty($_POST['idCatalogoProducto'])) {
    $date = date("Y");
    $query = mysqli_query($link, "SELECT * FROM CatalogoProducto WHERE idCatalogoProducto = '{$_POST['idCatalogoProducto']}' AND idCatalogo LIKE '%{$date}C{$_POST['numCampana']}%'");
    while ($fila = mysqli_fetch_array($query)) {
        $query2 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$fila['idProducto']}'");
        while($fila2 = mysqli_fetch_array($query2)){
            echo "<input type='hidden' name='idProductoAdd' value='{$fila2['idProducto']}' class='form-control'>";
            echo "<input type='text' name='nameProducto' value='{$fila2['nombreCorto']}' class='form-control'>";
        }
    }
}

if (!empty($_POST['idCatalogoProducto2'])) {
    $date = date("Y");
    $query = mysqli_query($link, "SELECT * FROM CatalogoProducto WHERE idCatalogoProducto = '{$_POST['idCatalogoProducto2']}' AND idCatalogo LIKE '%{$date}C{$_POST['numCampana']}%'");
    while ($fila = mysqli_fetch_array($query)) {
        echo "<input type='text' name='precioUnitario' value='{$fila['precio']}' class='form-control'>";
    }
}

if (!empty($_POST['idCatalogoProducto3'])) {
    $date = date("Y");
    $query = mysqli_query($link, "SELECT * FROM CatalogoProducto WHERE idCatalogoProducto = '{$_POST['idCatalogoProducto3']}' AND idCatalogo LIKE '%{$date}C{$_POST['numCampana']}%'");
    while ($fila = mysqli_fetch_array($query)) {
        echo "<input type='text' name='promocion' value='{$fila['promocion']}' class='form-control'>";
    }
}

if (!empty($_POST['campana'])){
    $yearToday = date('Y');
    $monthToday = date('m');
    $nextYear = $yearToday+1;
    if($monthToday == 12 && $_POST['campana'] == 1 && $_POST['tipo'] == "Productos"){
        echo "<input class='form-control' type='text' id='codigo' name='idCatalogo' value='{$nextYear}C{$_POST['campana']}' readonly>";
    }elseif($_POST['tipo'] == "Productos"){
        echo "<input class='form-control' type='text' id='codigo' name='idCatalogo' value='{$yearToday}C{$_POST['campana']}' readonly>";
    }elseif($_POST['tipo'] == "Entrenos" && $monthToday == 12 && $_POST['campana'] == 1){
        echo "<input class='form-control' type='text' id='codigo' name='idCatalogo' value='{$nextYear}C{$_POST['campana']}E' readonly>";
    }else{
        echo "<input class='form-control' type='text' id='codigo' name='idCatalogo' value='{$yearToday}C{$_POST['campana']}E' readonly>";
    }
}

if (!empty($_POST['idProductoSel'])) {
    $query = mysqli_query($link, "SELECT * FROM Producto WHERE idProducto = '{$_POST['idProductoSel']}'");
    while ($fila = mysqli_fetch_array($query)) {
        echo "<input type='hidden' name='idProductoAdd' value='{$fila['idProducto']}' class='form-control'>";
        echo "<input type='text' name='nameProducto' value='{$fila['nombreCorto']}' class='form-control'>";
    }
}

if (!empty($_POST['selectMetodo'])) {
    $arreglo = explode("PS",$_POST['idProductoSelect']);
    $arreglo[1] = 'PS'.$arreglo[1];
    switch ($_POST['selectMetodo']){
        case 'Devolucion':
            echo '';
            break;

        case 'Cambio':
            echo '<div class="col-12">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<div class="float-left">
								<i class="fa fa-camera"></i>
								Detalle de Cancelación
							</div>
						</div>
						<div class="card-block">
							<div class="col-12">
							<form method="post">
								<div class="form-group row">
								 	<label for="selectProducto" class="col-2 col-form-label">Selección de Producto:</label>
                                    <div class="col-10">
                                    	<input type="text" class="form-control" name="producto" id="Productos" onchange="getprecioprom2(this.value,'.$_POST['totalPrestado'].')">
                                    </div>
                                </div>
                                <div class="form-group row">
								 	<label for="cantidadDevuelta" class="col-2 col-form-label">Cantidad:</label>
                                    <div class="col-10">
                                    	<input class="form-control" type="text" id="cantidadDevuelta" name="cantidadDevuelta">
                                    </div>
                                 </div>
								<div class="form-group row">
								 	<label for="selectUbicacion" class="col-2 col-form-label">Selección de Ubicación:</label>
                                    <div class="col-10">
                                    	<select id="selectUbicacion" name="selectUbicacion" class="form-control">
                                    		<option selected disabled>Seleccionar</option>';

            /*   Pendiente Seleccion de Ubicación de Producto Seleccionado!   */

            $query = mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$_POST['idProductoSelect']}'");
            while($row = mysqli_fetch_array($query)){
                echo "<option value='{$row['idUbicacion']}'>{$row['idUbicacion']}</option>";
            }
            echo '</select>
                                    </div>
                                 </div>
                                 <div class="form-group row">
								 	<label class="col-2 col-form-label">Valor Considerado:</label>
                                    <div class="col-10" id="precioprom">
                                    	<select id="valorConsiderado" name="valorConsiderado" class="form-control">
                                    		<option selected disabled>Seleccionar</option>
										</select>
                                    </div>
                                 </div>
                                 <div class="form-group row">
								 	<label for="montorest" class="col-2 col-form-label">Monto Pendiente:</label>
                                    <div class="col-10" id="montorest">
                                    	<input type="text" name="montorest" value="NOT AJAX" readonly>
                                    </div>
                                 </div>
                                 <div class="form-group row">
								 	<label for="fechavenc" class="col-2 col-form-label">Fecha Nueva de Pago:</label>
                                    <div class="col-10" id="fechavenc">
                                    	<input type="text" name="fechavenc" value="NOT AJAX" readonly>
                                    </div>
                                 </div>
                            </form>
							</div>
						</div>
					</div>
				</div>';
            break;

        case 'Efectivo':
            echo '<div class="col-12">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<div class="float-left">
								<i class="fa fa-camera"></i>
								Detalle de Cancelación
							</div>
						</div>
						<div class="card-block">
							<div class="col-12">
							<form method="post">
								 <div class="form-group row">
								 	<label for="cantidadDevuelta" class="col-2 col-form-label">Cantidad:</label>
                                    <div class="col-10">
                                    	<input type="hidden" name="metodoSeleccionado" value="'.$_POST['selectMetodo'].'">
                                    	<input class="form-control" type="text" id="cantidadDevuelta" name="cantidadDevuelta">
                                    </div>
                                 </div>
                            </form>
							</div>
						</div>
					</div>
				</div>';
            break;

        case 'Tarjeta':
            echo '<div class="col-12">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<div class="float-left">
								<i class="fa fa-camera"></i>
								Detalle de Cancelación
							</div>
						</div>
						<div class="card-block">
							<div class="col-12">
							<form method="post">
								 <div class="form-group row">
								 	<label for="cantidadDevuelta" class="col-2 col-form-label">Cantidad:</label>
                                    <div class="col-10">
                                    	<input type="hidden" name="metodoSeleccionado" value="'.$_POST['selectMetodo'].'">
                                    	<input class="form-control" type="text" id="cantidadDevuelta" name="cantidadDevuelta">
                                    </div>
                                 </div>
                            </form>
							</div>
						</div>
					</div>
				</div>';
            break;

        case 'Cupon':
            echo '<div class="col-12">
					<div class="card">
						<div class="card-header card-inverse card-info">
							<div class="float-left">
								<i class="fa fa-camera"></i>
								Detalle de Cancelación
							</div>
						</div>
						<div class="card-block">
							<div class="col-12">
							<form method="post">
								 <div class="form-group row">
								 	<label for="cantidadDevuelta" class="col-2 col-form-label">Cantidad:</label>
                                    <div class="col-10">
                                    	<input type="hidden" name="metodoSeleccionado" value="'.$_POST['selectMetodo'].'">
                                    	<input class="form-control" type="text" id="cantidadDevuelta" name="cantidadDevuelta">
                                    </div>
                                 </div>
                            </form>
							</div>
						</div>
					</div>
				</div>';
            break;
    }
}