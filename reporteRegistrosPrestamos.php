<?php
include 'WebClientPrint.php';
use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;
?>

<div class="spacer30"></div>
<script>
    function myFunction() {
        // Declare variables
        var input, input2, input3, input4, input5, filter, filter2, filter3, filter4, filter5, table, tr, td, td2, td3, td4, td5, i;
        input = document.getElementById("idTransaccion");
        input2 = document.getElementById("fechaCreacion");
        input3 = document.getElementById("fechaVencimiento");
        input4 = document.getElementById("colaborador");
        input5 = document.getElementById("estado");
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
            td2 = tr[i].getElementsByTagName("td")[2];
            td3 = tr[i].getElementsByTagName("td")[3];
            td4 = tr[i].getElementsByTagName("td")[4];
            td5 = tr[i].getElementsByTagName("td")[5];
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
            Listado de Prestamos
            <div class="float-right">
                <div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Acciones
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="files/prestamosAntiguos.txt" download>Exportar Listado</a>
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
                            <input type="hidden" name="selectTipoReporte" value="3">
                            <input type="hidden" name="generar" value="true">
                            <input type="hidden" name="fechaInicioReporte" value="<?php echo $_POST['fechaInicioReporte'];?>">
                            <input type="hidden" name="fechaFinReporte" value="<?php echo $_POST['fechaFinReporte'];?>">
                            <label class="sr-only" for="idTransaccion">Orden #</label>
                            <input type="text" class="form-control mt-2 mb-2 mr-2" id="idTransaccion" placeholder="Orden #" onkeyup="myFunction()">
                            <label class="sr-only" for="fechaCreacion">Fecha de Creación</label>
                            <input type="text" class="form-control mt-2 mb-2 mr-2" id="fechaCreacion" placeholder="Fecha de Préstamo" onkeyup="myFunction()">
                            <label class="sr-only" for="fechaVencimiento">Fecha de Vencimiento</label>
                            <input type="text" class="form-control mt-2 mb-2 mr-2" id="fechaVencimiento" placeholder="Fecha de Vencimiento" onkeyup="myFunction()">
                            <label class="sr-only" for="colaborador">Colaborador</label>
                            <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="colaborador" placeholder="Consultora" onkeyup="myFunction()">
                            <label class="sr-only" for="estado">Estado</label>
                            <input type="text" class="search-key form-control mt-2 mb-2 mr-2" id="estado" placeholder="Estado" onkeyup="myFunction()">
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
                            <th class="text-center">Orden #</th>
                            <th class="text-center">Responsable</th>
                            <th class="text-center">Fecha Préstamo</th>
                            <th class="text-center">Fecha Vencimiento</th>
                            <th class="text-center">Consultora/Directora</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $dateInicio = explode("-", $_POST['fechaInicioReporte']);
                        $dateFin = explode("-", $_POST['fechaFinReporte']);
                        $fechaInicio = $dateInicio[0]."-".$dateInicio[1]."-".$dateInicio[2];
                        $fechaFin = $dateFin[0]."-".$dateFin[1]."-".$dateFin[2];
                        $i = 0;
                        $file = fopen("files/prestamosAntiguos.txt","w") or die("No se encontró el archivo!");
                        fwrite($file, pack("CCC",0xef,0xbb,0xbf));
                        $txt = "Nro. Orden,Responsable,Fecha de Préstamo,Fecha de Vencimiento,Deudor,Estado".PHP_EOL;
                        fwrite($file, $txt);
                        $query = mysqli_query($link, "SELECT * FROM Transaccion WHERE idTipoTransaccion = '6' AND fechaTransaccion >= '{$fechaInicio} 00:00:00' AND fechaTransaccion <= '{$fechaFin} 23:59:59' ORDER BY fechaTransaccion DESC");
                        while($row = mysqli_fetch_array($query)){
                            $i++;
                            $replace = [
                                '&lt;' => '', '&gt;' => '', '&#039;' => '', '&amp;' => '',
                                '&quot;' => '', 'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'Ae',
                                '&Auml;' => 'A', 'Å' => 'A', 'Ā' => 'A', 'Ą' => 'A', 'Ă' => 'A', 'Æ' => 'Ae',
                                'Ç' => 'C', 'Ć' => 'C', 'Č' => 'C', 'Ĉ' => 'C', 'Ċ' => 'C', 'Ď' => 'D', 'Đ' => 'D',
                                'Ð' => 'D', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ē' => 'E',
                                'Ę' => 'E', 'Ě' => 'E', 'Ĕ' => 'E', 'Ė' => 'E', 'Ĝ' => 'G', 'Ğ' => 'G',
                                'Ġ' => 'G', 'Ģ' => 'G', 'Ĥ' => 'H', 'Ħ' => 'H', 'Ì' => 'I', 'Í' => 'I',
                                'Î' => 'I', 'Ï' => 'I', 'Ī' => 'I', 'Ĩ' => 'I', 'Ĭ' => 'I', 'Į' => 'I',
                                'İ' => 'I', 'Ĳ' => 'IJ', 'Ĵ' => 'J', 'Ķ' => 'K', 'Ł' => 'K', 'Ľ' => 'K',
                                'Ĺ' => 'K', 'Ļ' => 'K', 'Ŀ' => 'K', 'Ñ' => 'N', 'Ń' => 'N', 'Ň' => 'N',
                                'Ņ' => 'N', 'Ŋ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O',
                                'Ö' => 'Oe', '&Ouml;' => 'Oe', 'Ø' => 'O', 'Ō' => 'O', 'Ő' => 'O', 'Ŏ' => 'O',
                                'Œ' => 'OE', 'Ŕ' => 'R', 'Ř' => 'R', 'Ŗ' => 'R', 'Ś' => 'S', 'Š' => 'S',
                                'Ş' => 'S', 'Ŝ' => 'S', 'Ș' => 'S', 'Ť' => 'T', 'Ţ' => 'T', 'Ŧ' => 'T',
                                'Ț' => 'T', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'Ue', 'Ū' => 'U',
                                '&Uuml;' => 'Ue', 'Ů' => 'U', 'Ű' => 'U', 'Ŭ' => 'U', 'Ũ' => 'U', 'Ų' => 'U',
                                'Ŵ' => 'W', 'Ý' => 'Y', 'Ŷ' => 'Y', 'Ÿ' => 'Y', 'Ź' => 'Z', 'Ž' => 'Z',
                                'Ż' => 'Z', 'Þ' => 'T', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a',
                                'ä' => 'ae', '&auml;' => 'ae', 'å' => 'a', 'ā' => 'a', 'ą' => 'a', 'ă' => 'a',
                                'æ' => 'ae', 'ç' => 'c', 'ć' => 'c', 'č' => 'c', 'ĉ' => 'c', 'ċ' => 'c',
                                'ď' => 'd', 'đ' => 'd', 'ð' => 'd', 'è' => 'e', 'é' => 'e', 'ê' => 'e',
                                'ë' => 'e', 'ē' => 'e', 'ę' => 'e', 'ě' => 'e', 'ĕ' => 'e', 'ė' => 'e',
                                'ƒ' => 'f', 'ĝ' => 'g', 'ğ' => 'g', 'ġ' => 'g', 'ģ' => 'g', 'ĥ' => 'h',
                                'ħ' => 'h', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ī' => 'i',
                                'ĩ' => 'i', 'ĭ' => 'i', 'į' => 'i', 'ı' => 'i', 'ĳ' => 'ij', 'ĵ' => 'j',
                                'ķ' => 'k', 'ĸ' => 'k', 'ł' => 'l', 'ľ' => 'l', 'ĺ' => 'l', 'ļ' => 'l',
                                'ŀ' => 'l', 'ñ' => 'n', 'ń' => 'n', 'ň' => 'n', 'ņ' => 'n', 'ŉ' => 'n',
                                'ŋ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'oe',
                                '&ouml;' => 'oe', 'ø' => 'o', 'ō' => 'o', 'ő' => 'o', 'ŏ' => 'o', 'œ' => 'oe',
                                'ŕ' => 'r', 'ř' => 'r', 'ŗ' => 'r', 'š' => 's', 'ù' => 'u', 'ú' => 'u',
                                'û' => 'u', 'ü' => 'ue', 'ū' => 'u', '&uuml;' => 'ue', 'ů' => 'u', 'ű' => 'u',
                                'ŭ' => 'u', 'ũ' => 'u', 'ų' => 'u', 'ŵ' => 'w', 'ý' => 'y', 'ÿ' => 'y',
                                'ŷ' => 'y', 'ž' => 'z', 'ż' => 'z', 'ź' => 'z', 'þ' => 't', 'ß' => 'ss',
                                'ſ' => 'ss', 'ый' => 'iy', 'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G',
                                'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
                                'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
                                'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F',
                                'Х' => 'H', 'Ц' => 'C', 'Ч' => 'CH', 'Ш' => 'SH', 'Щ' => 'SCH', 'Ъ' => '',
                                'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA', 'а' => 'a',
                                'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo',
                                'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l',
                                'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's',
                                'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch',
                                'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e',
                                'ю' => 'yu', 'я' => 'ya', ':' => '.', '=' => '.'
                            ];

                            if(substr($row['idTransaccion'],0,2) == 'PS'){
                                $query5 = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$row['idTransaccion']}'");
                                while($row5 = mysqli_fetch_array($query5)){

                                    $result1 = mysqli_query($link,"SELECT nombres FROM Colaborador WHERE idColaborador = '{$row5['idColaborador']}'");
                                    while($fila3 = mysqli_fetch_array($result1)){
                                        $colaborador = substr($fila3['nombres'],0,25);
                                    }
                                    $result1 = mysqli_query($link,"SELECT nombre FROM Proveedor WHERE idProveedor = '{$row5['idProveedor']}'");
                                    while($fila3 = mysqli_fetch_array($result1)){
                                        $cliente = substr($fila3['nombre'],0,30);
                                    }

                                    $fechaTransaccion = $row5['fechaTransaccion'];
                                    $montoTotal = round($row5['montoTotal'],2);
                                    $montoRestante = round($row5['montoRestante'],2);
                                    $fechaVencimiento = $row5['fechaVencimiento'];
                                }

                                $colaborador = str_replace(array_keys($replace),$replace,$colaborador);
                                $cliente = str_replace(array_keys($replace),$replace,$cliente);

                                $fechaTransaccionArray = explode("|",$fechaTransaccion);

                                //Create ESC/POS commands for sample receipt
                                $esc = '0x1B'; //ESC byte in hex notation
                                $newLine = '0x0A'; //LF byte in hex notation
                                $cmds = '';
                                $cmds = $esc . "@"; //Initializes the printer (ESC @)
                                $cmds .= $esc . '!' . '0x00'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
                                $cmds .= $newLine;
                                $cmds .= 'DETALLE PRESTAMO';
                                $cmds .= $newLine;
                                $cmds .= '-------------------------';
                                $cmds .= $newLine;
                                $cmds .= 'CARVASQ E.I.R.L.'; //text to print
                                $cmds .= $newLine;
                                $cmds .= '-------------------------';
                                $cmds .= $newLine;
                                $cmds .= 'ID TRANSACCION  '.$row['idTransaccion'];
                                $cmds .= $newLine;
                                $cmds .= 'FECHA  '.$fechaTransaccionArray[0];
                                $cmds .= $newLine;
                                $cmds .= 'COLABORADORA  '.$colaborador;
                                $cmds .= $newLine;
                                $cmds .= 'CLIENTE  '.$cliente;
                                $cmds .= $newLine;
                                $cmds .= 'CODIGO  PRODUCTO  CANT V.U.';
                                $cmds .= $newLine;
                                $query4 = mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$row['idTransaccion']}'");
                                while($row4 = mysqli_fetch_array($query4)){
                                    $query5 = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$row4['idProducto']}'");
                                    while($row5 = mysqli_fetch_array($query5)){
                                        $nombreCorto = substr($row5['nombreCorto'],0,10);
                                    }
                                    $nombreCorto = strtoupper($nombreCorto);
                                    $nombreCorto = str_replace(array_keys($replace),$replace,$nombreCorto);
                                    $cantidad = $row4['cantidad'];
                                    if($cantidad < 10){
                                        $cantidad = "0".$cantidad;
                                    }
                                    $valorUnitario = round($row4['valorUnitario'],2);
                                    $cmds .= $row4['idProducto']." ".strtoupper($nombreCorto)." ".$cantidad."   ".$valorUnitario;
                                    $cmds .= $newLine;
                                }
                                $cmds .= 'MONTO TOTAL  '.$montoTotal;
                                $cmds .= $newLine;
                                $cmds .= 'PENDIENTE DE PAGO  '.$montoRestante;
                                $cmds .= $newLine;
                                $cmds .= 'FECHA VENCIMIENTO  '.$fechaVencimiento;
                                $cmds .= $newLine.$newLine.$newLine;
                                $cmds .= '------------------';
                                $cmds .= $newLine;
                                $cmds .= ' FIRMA CONSULTORA';
                                $cmds .=$newLine.$newLine.$newLine.$newLine.$newLine;
                            }else{
                                $query5 = mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$row['idTransaccion']}'");
                                while($row5 = mysqli_fetch_array($query5)){
                                    $colaborador = $row5['idColaborador'];
                                    $fechaTransaccion = $row5['fechaTransaccion'];
                                    $cliente = $row5['idProveedor'];
                                    $montoTotal = round($row5['montoTotal'],2);
                                    $montoRestante = round($row5['montoRestante'],2);
                                    $fechaVencimiento = $row5['fechaVencimiento'];
                                }
                                $colaborador = str_replace(array_keys($replace),$replace,$colaborador);
                                $cliente = str_replace(array_keys($replace),$replace,$cliente);

                                $fechaTransac = explode("|",$fechaTransaccion);

                                //Create ESC/POS commands for sample receipt
                                $esc = '0x1B'; //ESC byte in hex notation
                                $newLine = '0x0A'; //LF byte in hex notation

                                $cmds = '';
                                $cmds = $esc . "@"; //Initializes the printer (ESC @)
                                $cmds .= $esc . '!' . '0x00'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
                                $cmds .= $newLine;
                                $cmds .= 'DETALLE PRESTAMO';
                                $cmds .= $newLine;
                                $cmds .= '-------------------------';
                                $cmds .= $newLine;
                                $cmds .= 'CARVASQ E.I.R.L.'; //text to print
                                $cmds .= $newLine;
                                $cmds .= '-------------------------';
                                $cmds .= $newLine;
                                $cmds .= 'ID TRANSACCION  '.$row['idTransaccion'];
                                $cmds .= $newLine;
                                $cmds .= 'FECHA  '.$fechaTransac[0];
                                $cmds .= $newLine;
                                $cmds .= 'COLABORADORA  '.$colaborador;
                                $cmds .= $newLine;
                                $cmds .= 'CLIENTE  '.$cliente;
                                $cmds .= $newLine;
                                $cmds .= 'MONTO TOTAL  '.$montoTotal;
                                $cmds .= $newLine;
                                $cmds .= 'PENDIENTE DE PAGO  '.$montoRestante;
                                $cmds .= $newLine;
                                $cmds .= 'FECHA VENCIMIENTO  '.$fechaVencimiento;
                                $cmds .= $newLine.$newLine.$newLine;
                                $cmds .= '------------------';
                                $cmds .= $newLine;
                                $cmds .= ' FIRMA CONSULTORA';
                                $cmds .=$newLine.$newLine.$newLine.$newLine.$newLine;
                            }
                            if(substr($row['idTransaccion'],0,2) != 'PS'){
                                $sendlink = 'cancelacionPrestamoEfectivo.php';
                            }else{
                                $sendlink = 'nuevaCancelacionPrestamo.php';
                            }
                            $fecha=explode("|",$row['fechaTransaccion']);
                            $query4=mysqli_query($link,"SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
                            while ($row4=mysqli_fetch_array($query4)){
                                $nombre="{$row4['nombres']} {$row4['apellidos']}";
                            }
                            $query2 = mysqli_query($link,"SELECT * FROM Estado WHERE idEstado = '{$row['idEstado']}'");
                            while($row2 = mysqli_fetch_array($query2)){
                                $estado = $row2['descripcion'];
                            }
                            echo "<tr>
                                        <td class='text-center'>{$row['idTransaccion']}</td>
                                        <td class='text-center'>{$nombre}</td>
                                        <td class='text-center'>{$fecha[0]}</td>
                                        <td class='text-center'>{$row['fechaVencimiento']}</td>
                                        ";
                            $query3=mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
                            while ($row3=mysqli_fetch_array($query3)){
                                $proveedor = $row3['nombre'];
                                echo "<td class='text-center'>{$row3['nombre']}</td>";
                            }
                            $sida = session_id();
                            echo "
                                        <td class='text-center'>{$estado}</td>
                                        <td class='text-center'>
                                            <form method='post' id='myForm{$i}'>
                                            <input type='hidden' id='sid' name='sid' value='{$sida}'>
                                            <input type='hidden' id='pid' name='pid' value='0'>
                                            <textarea id='printerCommands' name='printerCommands' class='form-control' hidden>".$cmds."</textarea>
                                                <div class='dropdown'>
                                                    <input type='hidden' name='idTransaccion' value='".$row['idTransaccion']."'>
                                                    <input type='hidden' id='useDefaultPrinter' checked/>
                                                    <button class='btn btn-secondary btn-sm dropdown-toggle' type='button' id='dropdownMenuButton' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                                    Acciones
                                                    </button>
                                                    <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
                            if($estado == 'Parcial'){
                                echo "<button name='recepcion' class='dropdown-item' type='submit' formaction='".$sendlink."'>Registrar Devolución</button>";
                            }
                            echo "  
                                <button name='verProductos' class='dropdown-item' type='submit' formaction='detallePrestamo.php'>Ver Detalle</button>";?>
                            <button formaction="#" onclick='javascript:doClientPrint<?php echo $i;?>();' class="dropdown-item">Imprimir</button>
                            <?php
                            if($estado=='Abierta'){
                                echo "<button name='delete' style='color: red' class='dropdown-item' type='submit' formaction='#'>Eliminar</button>";
                            }
                            echo "                  </div>
                                                </div>
                                            </form>
                                        </td>
                                      </tr>";
                            $txt = $row['idTransaccion'].",".$nombre.",".$fecha[0].",".$row['fechaVencimiento'].",".$proveedor.",".$estado.PHP_EOL;
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

//Get Absolute URL of this page
$currentAbsoluteURL = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
$currentAbsoluteURL .= $_SERVER["SERVER_NAME"];
if($_SERVER["SERVER_PORT"] != "80" && $_SERVER["SERVER_PORT"] != "443")
{
    $currentAbsoluteURL .= ":".$_SERVER["SERVER_PORT"];
}
$currentAbsoluteURL .= $_SERVER["REQUEST_URI"];

//WebClientPrinController.php is at the same page level as WebClientPrint.php
$webClientPrintControllerAbsoluteURL = substr($currentAbsoluteURL, 0, strrpos($currentAbsoluteURL, '/')).'/WebClientPrintController.php';

//DemoPrintCommandsProcess.php is at the same page level as WebClientPrint.php
$demoPrintCommandsProcessAbsoluteURL = substr($currentAbsoluteURL, 0, strrpos($currentAbsoluteURL, '/')).'/DemoPrintCommandsProcess.php';

//Specify the ABSOLUTE URL to the WebClientPrintController.php and to the file that will create the ClientPrintJob object (DemoPrintCommandsProcess.php)
echo WebClientPrint::createScript($webClientPrintControllerAbsoluteURL, $demoPrintCommandsProcessAbsoluteURL, session_id());

for($j=1;$j<=$i;$j++){
    echo "
	    <script type=\"text/javascript\">

        function doClientPrint{$j}() {

            //collect printer settings and raw commands
            var printJobInfo = $(\"#myForm{$j}\").serialize();

            // Launch WCPP at the client side for printing...
            jsWebClientPrint.print(printJobInfo);

        }


        $(document).ready(function () {
            //jQuery-based Wizard
            $(\"#myForm{$j}\").formToWizard();

            //change printer options based on user selection
            $(\"#pid\").change(function () {
                var printerId = $(\"select#pid\").val();

                displayInfo(printerId);
                hidePrinters();
                if (printerId == 2)
                    $(\"#installedPrinter\").show();
                else if (printerId == 3)
                    $(\"#netPrinter\").show();
                else if (printerId == 4)
                    $(\"#parallelPrinter\").show();
                else if (printerId == 5)
                    $(\"#serialPrinter\").show();
            });

            hidePrinters();
            displayInfo(0);


        });

        function displayInfo(i) {
            if (i == 0)
                $(\"#info\").html('This will make the WCPP to send the commands to the printer installed in your machine as \"Default Printer\" without displaying any dialog!');
            else if (i == 1)
                $(\"#info\").html('This will make the WCPP to display the Printer dialog so you can select which printer you want to use.');
            else if (i == 2)
                $(\"#info\").html('Please specify the <b>Printer\'s Name</b> as it figures installed under your system.');
            else if (i == 3)
                $(\"#info\").html('Please specify the Network Printer info.<br /><strong>On Linux &amp; Mac</strong> it\'s recommended you install the printer through <strong>CUPS</strong> and set the assigned printer name to the <strong>\"Use an installed Printer\"</strong> option on this demo.');
            else if (i == 4)
                $(\"#info\").html('Please specify the Parallel Port which your printer is connected to.<br /><strong>On Linux &amp; Mac</strong> you must install the printer through <strong>CUPS</strong> and set the assigned printer name to the <strong>\"Use an installed Printer\"</strong> option on this demo.');
            else if (i == 5)
                $(\"#info\").html('Please specify the Serial RS232 Port info which your printer does support.<br /><strong>On Linux &amp; Mac</strong> you must install the printer through <strong>CUPS</strong> and set the assigned printer name to the <strong>\"Use an installed Printer\"</strong> option on this demo.');
        }

        function hidePrinters() {
            $(\"#installedPrinter\").hide(); $(\"#netPrinter\").hide(); $(\"#parallelPrinter\").hide(); $(\"#serialPrinter\").hide();
        }




        /* FORM to WIZARD */
        /* Created by jankoatwarpspeed.com */

        (function ($) {
            $.fn.formToWizard = function () {

                var element = this;

                var steps = $(element).find(\"fieldset\");
                var count = steps.size();


                // 2
                $(element).before(\"<ul id='steps' style='margin: 0px;'></ul>\");

                steps.each(function (i) {
                    $(this).wrap(\"<div id='step\" + i + \"'></div>\");
                    $(this).append(\"<p id='step\" + i + \"commands'></p>\");

                    // 2
                    var name = $(this).find(\"legend\").html();
                    $(\"#steps\").append(\"<li id='stepDesc\" + i + \"'>Step \" + (i + 1) + \"<span>\" + name + \"</span></li>\");

                    if (i == 0) {
                        createNextButton(i);
                        selectStep(i);
                    }
                    else if (i == count - 1) {
                        $(\"#step\" + i).hide();
                        createPrevButton(i);
                    }
                    else {
                        $(\"#step\" + i).hide();
                        createPrevButton(i);
                        createNextButton(i);
                    }
                });

                function createPrevButton(i) {
                    var stepName = \"step\" + i;
                    $(\"#\" + stepName + \"commands\").append(\"<a href='#' id='\" + stepName + \"Prev' class='prev btn btn-info'>< Back</a>\");

                    $(\"#\" + stepName + \"Prev\").bind(\"click\", function (e) {
                        $(\"#\" + stepName).hide();
                        $(\"#step\" + (i - 1)).show();

                        selectStep(i - 1);
                    });
                }

                function createNextButton(i) {
                    var stepName = \"step\" + i;
                    $(\"#\" + stepName + \"commands\").append(\"<a href='#' id='\" + stepName + \"Next' class='next btn btn-info'>Next ></a>\");

                    $(\"#\" + stepName + \"Next\").bind(\"click\", function (e) {
                        $(\"#\" + stepName).hide();
                        $(\"#step\" + (i + 1)).show();

                        selectStep(i + 1);
                    });
                }

                function selectStep(i) {
                    $(\"#steps li\").removeClass(\"current\");
                    $(\"#stepDesc\" + i).addClass(\"current\");
                }

            }
        })(jQuery);

    </script>
	    ";
}
?>