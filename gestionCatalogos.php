<?php
include('session.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');
    ?>
    <script>
        function myFunction() {
            // Declare variables
            var input, input2, filter, filter2, table, tr, td, td2, i;
            input = document.getElementById("campana");
            input2 = document.getElementById("tipo");
            filter = input.value.toUpperCase();
            filter2 = input2.value.toUpperCase();
            table = document.getElementById("myTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                td2 = tr[i].getElementsByTagName("td")[5];
                if ((td)&&(td2)) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        if(td2.innerHTML.toUpperCase().indexOf(filter2) > -1){
                            tr[i].style.display = "";
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
                <i class="fa fa-book"></i>
                Listado de Catálogos
                <div class="float-right">
                    <div class="dropdown">
                        <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Acciones
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="nuevoCatalogo.php">Nuevo Catálogo</a>
                            <a class="dropdown-item" href="files/catalogos.txt" download>Exportar Listado</a>
                        </div>
                    </div>
                </div>
                <div class="float-right">&nbsp;&nbsp;&nbsp;&nbsp;</div>
                <div class="float-right">
                    <button href="#collapsed" class="btn btn-secondary btn-sm" data-toggle="collapse">Mostrar Filtros</button>
                </div>
            </div>
            <div class="card-block">
                <div class="row">
                    <div class="col-12">
                        <div id="collapsed" class="collapse">
                            <form class="form-inline justify-content-center" method="post" action="gestionCatalogos.php">
                                <label class="col-form-label m-2" for="campaña">Campaña:</label>
                                <input type="number" max="13" min="1" class="form-control pr-0 mt-2 mb-2 mr-2 col-1" id="campana" placeholder="00" name="Campana" onkeyup="myFunction()" onchange="myFunction()">
                                <label class="col-form-label m-2" for="tipo">Tipo:</label>
                                <select class="form-control pr-0 mt-2 mb-2 mr-2" id="tipo" name="tipo" onchange="myFunction()">
                                    <option selected value="o">Selección de Tipo</option>
                                    <option>Productos</option>
                                    <option>Entrenos</option>
                                </select>
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
                                <th class="text-center">Código</th>
                                <th class="text-center">Campaña</th>
                                <th class="text-center">Inicio de Campaña</th>
                                <th class="text-center">Fin de Campaña</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Tipo</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $file = fopen("files/catalogos.txt","w") or die("No se encontró el archivo!");
                            fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                            $txt = "Código,Campaña,Fecha de Inicio,Fecha de Cierre,Stock,Tipo".PHP_EOL;
                            fwrite($file, $txt);
                            $query = mysqli_query($link, "SELECT * FROM Catalogo ORDER BY fecha ASC");
                            while($row = mysqli_fetch_array($query)){
                                $stock = 0;
                                $search = mysqli_query($link,"SELECT * FROM UbicacionProducto WHERE idProducto = '{$row['idCatalogo']}'");
                                while($searchIndex = mysqli_fetch_array($search)){
                                    $stock += $searchIndex['stock'];
                                }
                                $query1 = mysqli_query($link, "SELECT * FROM Campana WHERE idCampana = '{$row['idCampana']}'");
                                while($row1 = mysqli_fetch_array($query1)){
                                    echo "<tr>
                                        <td>{$row['idCatalogo']}</td>
                                        <td>{$row['idCampana']}</td>
                                        <td>{$row['fechaInicio']}</td>
                                        <td>{$row['fechaFin']}</td>
                                        <td>{$stock}</td>
                                        <td>{$row['tipo']}</td>
                                        <td class=\"text-center\">
                                            <form method='post'>
                                                <div class='dropdown'>
                                                    <input type='hidden' name='idCatalogo' value='".$row['idCatalogo']."'>
                                                    <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                    Acciones
                                                    </button>
                                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>
                                                        <button name='entregarCatalogo' class='dropdown-item' type=\"submit\" formaction='nuevoCatalogo_Productos.php'>Agregar Productos</button>
                                                        <button name='entregarCatalogo' class='dropdown-item' type=\"submit\" formaction='nuevaOV_DatosGenerales.php'>Entrega de Catálogo</button>
                                                        <button name='verProdcutosCatalogo' class='dropdown-item' type=\"submit\" formaction='verProductosCatalogo.php'>Ver Catálogo</button>
                                                        <button name='historialCatalogos' class='dropdown-item' type=\"submit\" formaction='historialCatalogos.php'>Historial</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </td>
                                      </tr>";
	                                $txt = $row['idCatalogo'].",".$row['idCampana'].",".$row['fechaInicio'].",".$row['fechaFin'].",".$row['stock'].",".$row['tipo'].PHP_EOL;
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