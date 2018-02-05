<?php
include('session.php');
include ('declaracionFechas.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');

	if(isset($_POST['retirar'])){
	    $update = mysqli_query($link,"UPDATE Producto SET idEstado = 2 WHERE idProducto = '{$_POST['idProducto']}'");

		$queryPerformed = "UPDATE Producto SET idEstado = 2 WHERE idProducto = {$_POST['idProducto']}";

		$databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','Retirar','Producto','{$queryPerformed}')");
    }

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

	    $insert = mysqli_query($link, "INSERT INTO Producto VALUES ('{$_POST['codigo']}','{$_POST['tipoProducto']}','{$_POST['subcategoria']}','{$color}','{$_POST['unidadMedida']}','{$_POST['tamano']}','{$_SESSION['user']}','1','{$_POST['genero']}','{$nombreCorto}','{$descripcion}',
                  '0','{$_POST['stockReposicion']}','{$_POST['urlImagen']}','{$_POST['urlProducto']}','{$date}')");
	    $queryPerformed = "INSERT INTO Producto VALUES ({$_POST['codigo']},{$_POST['tipoProducto']},{$_POST['subcategoria']},
                  {$color},{$_POST['unidadMedida']},{$_POST['tamano']},{$_SESSION['user']},1,{$_POST['genero']},{$nombreCorto},{$descripcion},
                  0,{$_POST['stockReposicion']},{$_POST['urlImagen']},{$_POST['urlProducto']},{$date})";
	    $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','INSERT','Producto','{$queryPerformed}')");
    }

    if(isset($_POST['editarProducto'])){
	    $color = null;
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

	    $nombreCorto = str_replace(array_keys($replace2),$replace2,$_POST['nombreCorto']);
	    $descripcion = str_replace(array_keys($replace2),$replace2,$_POST['descripcion']);
	    $update = mysqli_query($link, "UPDATE Producto SET idProducto = '{$_POST['codigo']}', idTipoProducto = '{$_POST['tipoProducto']}', idSubCategoria = '{$_POST['subcategoria']}',
                  idColor = '{$color}', idUnidadMedida = '{$_POST['unidadMedida']}', idTamaño = '{$_POST['tamano']}', idColaborador = '{$_SESSION['user']}', idEstado = '{$_POST['estado']}', idGenero = '{$_POST['genero']}',
                  nombreCorto = '{$nombreCorto}', descripcion = '{$descripcion}',
                  puntoReposicion = '{$_POST['stockReposicion']}', urlImagen = '{$_POST['urlImagen']}', urlProducto = '{$_POST['urlProducto']}', 
                  fechaCreacion = '{$date}' WHERE idProducto = {$_POST['codigoAnt']}");
	    $queryPerformed = "UPDATE Producto SET idProducto = {$_POST['codigo']}, idTipoProducto = {$_POST['tipoProducto']}, idCategoria = {$_POST['categoria']},
                  idColor = {$color}, idUnidadMedida = {$_POST['unidadMedida']}, idTamaño = {$_POST['tamano']}, idColaborador = {$_SESSION['user']}, idEstado = {$_POST['estado']}, idGenero = {$_POST['genero']}
                  nombreCorto = {$nombreCorto}, descripcion = {$descripcion},
                  puntoReposicion = {$_POST['stockReposicion']}, urlImagen = {$_POST['urlImagen']}, urlProducto = {$_POST['urlProducto']}, 
                  fechaCreacion = {$date} WHERE idProducto = {$_POST['codigoAnt']}";
	    $databaseLog = mysqli_query($link, "INSERT INTO DatabaseLog (idColaborador,fechaHora,evento,tipo,consulta) VALUES ('{$_SESSION['user']}','{$dateTime}','UPDATE','Producto','{$queryPerformed}')");
    }

    if(isset($_POST['nuevoProductoPlantilla'])){
	    $color = null;
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
    ?>

    <script>
        function myFunction() {
            // Declare variables
            var input, input2, input3, input4, input5, filter, filter2, filter3, filter4, filter5, table, tr, td, td2, td3, td4, td5, i;
            input = document.getElementById("SKU");
            input2 = document.getElementById("Nombre");
            input3 = document.getElementById("Subcategoria");
            input4 = document.getElementById("Genero");
            input5 = document.getElementById("Estado");
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
                td3 = tr[i].getElementsByTagName("td")[3];
                td4 = tr[i].getElementsByTagName("td")[4];
                td5 = tr[i].getElementsByTagName("td")[6];
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
                Listado de Productos
                <span class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="nuevoProducto.php">Agregar Nuevo Producto</a>
                            <a class="dropdown-item" href="files/productos.txt" download>Exportar Listado</a>
                        </div>
                    </div>
                </span>
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
                                <label class="sr-only" for="SKU">SKU</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="SKU" placeholder="SKU" onkeyup="myFunction()">
                                <label class="sr-only" for="Nombre">Nombre de Producto</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="Nombre" placeholder="Nombre" onkeyup="myFunction()">
                                <label class="sr-only" for="Subcategoria">Selección de Subcategoría</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="Subcategoria" placeholder="Subcategoría" onkeyup="myFunction()">
                                <label class="sr-only" for="Genero">Género</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="Genero" placeholder="Género" onkeyup="myFunction()">
                                <label class="sr-only" for="Estado">Estado</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="Estado" placeholder="Estado" onkeyup="myFunction()">
                                <input type="submit" class="btn btn-primary" value="Limpiar" style="padding-left:15px; padding-right: 15px;">
                            </form>
                        </div>
                    </div>
                </div>
                <div class="spacer10"></div>
                <div class="row">
                    <div class="col-12" style="overflow-y: scroll; height: 700px;">
                        <table class="table table-bordered" id="myTable">
                            <thead class="thead-default">
                            <tr>
                                <!--<th class="text-center">Imágen</th>-->
                                <th class="text-center">SKU</th>
                                <th class="text-center">Nombre de Producto</th>
                                <th class="text-center">Atributo</th>
                                <th class="text-center">Subcategoría</th>
                                <th class="text-center">Género</th>
                                <!--<th class="text-center">Descripción</th>-->
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $file = fopen("files/productos.txt","w") or die("No se encontró el archivo!");
                            fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                            $txt = "SKU,Nombre Corto,Atributo,Subcategoría,Género,Fecha de Creación,Estado".PHP_EOL;
                            fwrite($file, $txt);
                            $categoria = null;
                            $estado = null;
                            $query = mysqli_query($link, "SELECT * FROM Producto");
                            while($row = mysqli_fetch_array($query)){
                                if($row['idSubCategoria'] === '10'){
                                }else{
	                                $query2 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row['idColor']}'");
	                                while($row2 = mysqli_fetch_array($query2)){
		                                $color = $row2['descripcion'];
	                                }
	                                $query2 = mysqli_query($link, "SELECT * FROM SubCategoria WHERE idSubCategoria = '{$row['idSubCategoria']}'");
	                                while($row2 = mysqli_fetch_array($query2)){
		                                $subcategoria = $row2['descripcion'];
	                                }
	                                $query2 = mysqli_query($link, "SELECT * FROM Estado WHERE clase = 'estadoProducto' AND idEstado = '{$row['idEstado']}'");
	                                while($row2 = mysqli_fetch_array($query2)){
		                                $estado = $row2['descripcion'];
	                                }
	                                $query2 = mysqli_query($link, "SELECT * FROM Genero WHERE idGenero = '{$row['idGenero']}'");
	                                while($row2 = mysqli_fetch_array($query2)){
		                                $genero = $row2['descripcion'];
	                                }
	                                echo "<tr>
                                        <!--<td class=\"text-center\"><img src='{$row['urlImagen']}' class='thumbnail' width='40px' height='40px'></td>-->
                                        <!--<td><a rel=\"popover\" data-img='{$row['urlImagen']}'><img src='{$row['urlImagen']}' width='40px' height='40px'></a></td>-->
                                        <td class=\"text-center\">{$row['idProducto']}</td>
                                        <td class=\"text-center\">{$row['nombreCorto']}</td>
                                        <td class=\"text-center\">{$color}</td>
                                        <td class=\"text-center\">{$subcategoria}</td>
                                        <td class=\"text-center\">{$genero}</td>
                                        <!--<td class=\"text-center\">{$row['descripcion']}</td>-->
                                        <td class=\"text-center\">{$estado}</td>
                                        <td class=\"text-center\">
                                            <form method='post'>
                                                <div class=\"dropdown\">
                                                    <input type='hidden' name='idProducto' value='".$row['idProducto']."'>
                                                    <input type='hidden' name='volver' value='gestionProductos.php'>
                                                    <button class=\"btn btn-secondary btn-sm dropdown-toggle\" type=\"button\" id=\"dropdownMenuButton\" data-toggle=\"dropdown\" aria-haspopup=\"true\" aria-expanded=\"false\">
                                                    Acciones
                                                    </button>
                                                    <div class=\"dropdown-menu\" aria-labelledby=\"dropdownMenuButton\">
                                                        <button name='nuevoProducto' class=\"dropdown-item\" type=\"submit\" formaction='nuevoProductoPlantilla.php'>Agregar Producto Similar</button>
                                                        <button name='verProducto' class=\"dropdown-item\" type=\"submit\" formaction='detalleProducto.php'>Ver</button>
                                                        <button name='editarProducto' class=\"dropdown-item\" type=\"submit\" formaction='editarProducto.php'>Editar</button>
                                                        <button name='historialProducto' class=\"dropdown-item\" type=\"submit\" formaction='historialTransacciones.php'>Historial de Transacciones</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                      </tr>";
	                                $txt = $row['idProducto'].",".$row['nombreCorto'].",".$color.",".$subcategoria.",".$genero.",".$row['fechaCreacion'].",".$estado.PHP_EOL;
	                                fwrite($file, $txt);
                                }
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