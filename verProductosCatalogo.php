<?php
include('session.php');
include ('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');
    ?>

    <script>
        function myFunction() {
            // Declare variables
            var input, input2, input3, input4, filter, filter2, filter3, filter4, table, tr, td, td2, td3, td4, i;
            input = document.getElementById("SKU");
            input2 = document.getElementById("Nombre");
            input3 = document.getElementById("Subcategoria");
            input4 = document.getElementById("Genero");
            filter = input.value.toUpperCase();
            filter2 = input2.value.toUpperCase();
            filter3 = input3.value.toUpperCase();
            filter4 = input4.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                td2 = tr[i].getElementsByTagName("td")[2];
                td3 = tr[i].getElementsByTagName("td")[4];
                td4 = tr[i].getElementsByTagName("td")[5];
                if ((td)&&(td2)) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        if(td2.innerHTML.toUpperCase().indexOf(filter2) > -1){
                            if(td3.innerHTML.toUpperCase().indexOf(filter3) > -1){
                                if(td4.innerHTML.toUpperCase().indexOf(filter4) > -1){
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
                Listado de Productos del Catálogo
                <div class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <form method="post">
                                <input type="hidden" name="idCatalogo" value="<?php echo $_POST['idCatalogo']?>">
                                <button class="dropdown-item" formaction="nuevoCatalogo_Productos.php">Agregar Productos</button>
                                <button class="dropdown-item" formaction="gestionCatalogos.php">Regresar</button>
                            </form>
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
                                <label class="sr-only" for="SKU">SKU</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="SKU" placeholder="SKU" onkeyup="myFunction()">
                                <label class="sr-only" for="Nombre">Nombre de Producto</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="Nombre" placeholder="Nombre" onkeyup="myFunction()">
                                <label class="sr-only" for="Subcategoria">Selección de Subcategoría</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="Subcategoria" placeholder="Subcategoría" onkeyup="myFunction()">
                                <label class="sr-only" for="Genero">Género</label>
                                <input type="text" class="form-control mt-2 mb-2 mr-2" id="Genero" placeholder="Género" onkeyup="myFunction()">
                                <input type="hidden" name="idCatalogo" value="<?php echo $_POST['idCatalogo']?>">
                                <input type="submit" class="btn btn-primary" value="Limpiar" style="padding-left:15px; padding-right: 15px;">
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
                                <th class="text-center">Imágen</th>
                                <th class="text-center">SKU</th>
                                <th class="text-center">ID Catálogo</th>
                                <th class="text-center">Nombre de Producto</th>
                                <th class="text-center">Atributo</th>
                                <th class="text-center">Subcategoría</th>
                                <th class="text-center">Género</th>
                                <th class="text-center">Precio</th>
                                <th class="text-center">Promoción</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $categoria = null;
                            $estado = null;
                            $query1 = mysqli_query($link, "SELECT * FROM CatalogoProducto WHERE idCatalogo = '{$_POST['idCatalogo']}'");
                            while ($fila=mysqli_fetch_array($query1)){
                                $query=mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$fila['idProducto']}'");
                                while($row = mysqli_fetch_array($query)){
                                    $query2 = mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$row['idColor']}'");
                                    while($row2 = mysqli_fetch_array($query2)){
                                        $color = $row2['descripcion'];
                                    }
                                    $query2 = mysqli_query($link, "SELECT * FROM SubCategoria WHERE idSubCategoria = '{$row['idSubCategoria']}'");
                                    while($row2 = mysqli_fetch_array($query2)){
                                        $subcategoria = $row2['descripcion'];
                                    }
                                    $query2 = mysqli_query($link, "SELECT * FROM Genero WHERE idGenero = '{$row['idGenero']}'");
                                    while($row2 = mysqli_fetch_array($query2)){
                                        $genero = $row2['descripcion'];
                                    }
                                    echo "<tr>
                                        <!--<td class=\"text-center\"><img src='{$row['urlImagen']}' class='thumbnail' width='40px' height='40px'></td>-->
                                        <td><a rel=\"popover\" data-img='{$row['urlImagen']}'><img src='{$row['urlImagen']}' width='40px' height='40px'></a></td>
                                        <td class=\"text-center\">{$row['idProducto']}</td>
                                        <td class=\"text-center\">{$fila['idCatalogoProducto']}</td>
                                        <td class=\"text-center\">{$row['nombreCorto']}</td>
                                        <td class=\"text-center\">{$color}</td>
                                        <td class=\"text-center\">{$subcategoria}</td>
                                        <td class=\"text-center\">{$genero}</td>
                                        <td class=\"text-center\">{$fila['precio']}</td>
                                        <td class=\"text-center\">{$fila['promocion']}</td>
                                      </tr>";
                                }
                            }
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