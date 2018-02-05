<?php
include('session.php');
include 'WebClientPrint.php';
use Neodynamic\SDK\Web\WebClientPrint;
use Neodynamic\SDK\Web\Utils;
include('funciones.php');
include('declaracionFechas.php');
if(isset($_SESSION['login'])) {
    include('adminTemplate.php');
    $result = mysqli_query($link,"SELECT * FROM Movimiento WHERE idMovimiento = '{$_POST['idMovimiento']}'");
    while($row = mysqli_fetch_array($result)) {
        $fecha = explode("|", $row['fecha']);
        $result2 = mysqli_query($link, "SELECT * FROM Proveedor WHERE idProveedor = '{$row['idProveedor']}'");
        while ($row2 = mysqli_fetch_array($result2)) {
            $proveedor = $row2['nombre'];
        }
        $result2 = mysqli_query($link, "SELECT * FROM Colaborador WHERE idColaborador = '{$row['idColaborador']}'");
        while ($row2 = mysqli_fetch_array($result2)) {
            $prestamista = $row2['nombres']." ".$row2['apellidos'];
        }
        $result2 = mysqli_query($link, "SELECT * FROM MedioPago WHERE idMedioPago = '{$row['idMedioPago']}'");
        while ($row2 = mysqli_fetch_array($result2)) {
            $mediopago = $row2['descripcion'];
        }
        $result2 = mysqli_query($link, "SELECT * FROM Comprobante WHERE idComprobante = '{$row['idComprobante']}'");
        while ($row2 = mysqli_fetch_array($result2)) {
            $comprobante = $row2['descripcion'];
        }
        $fechaVencimiento = $row['fechaVencimiento'];
        $observacion = $row['observaciones'];
        $monto = $row['monto'];
        $transaccionPrimaria = $row['idTransaccionPrimaria'];
        $transaccionReferencial = $row['idTransaccionReferencial'];
    }
    $sida = session_id();
    ?>
    <section class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <form method="post" id="myForm">
                            <input type='hidden' id='sid' name='sid' value='<?php echo $sida;?>'>
                            <input type='hidden' id='pid' name='pid' value='0'>
                            <div class="float-left">
                                <i class="fa fa-money"></i>
                                Detalle de Movimiento de Efectivo
                            </div>
                            <div class="float-right">
                                <input type='hidden' name='idMovimiento' value='<?php echo $_POST['idMovimiento']?>'>
                                <textarea id='printerCommands' name='printerCommands' class='form-control' hidden>
                                    <?php
                                    $query5 = mysqli_query($link,"SELECT * FROM Movimiento WHERE idMovimiento = '{$_POST['idMovimiento']}'");
                                    while($row5 = mysqli_fetch_array($query5)){

                                        $idMovimiento=$row5['idMovimiento'];
                                        $transPrimaria=$row5['idTransaccionPrimaria'];
                                        $transReferencial=$row5['idTransaccionReferencial'];
                                        $colaborador = $row5['idColaborador'];
                                        $nuevafecha=explode("|",$row5['fecha']);
                                        $fechaTransaccion = $nuevafecha[0];
                                        $cliente = $row5['idProveedor'];
                                        $montoTotal = $row5['monto'];
                                        $observaciones = $row5['observaciones'];
                                        $tipomovi = mysqli_query($link, "SELECT * FROM TipoMovimiento WHERE idTipoMovimiento = '{$row5['idTipoMovimiento']}'");
                                        while ($fila=mysqli_fetch_array($tipomovi)){
                                            $tipomovimiento=$fila['descripcion'];
                                        }
                                        $mediopa = mysqli_query($link, "SELECT * FROM MedioPago WHERE idMedioPago = '{$row5['idMedioPago']}'");
                                        while ($fila=mysqli_fetch_array($mediopa)){
                                            $mediodepago=$fila['descripcion'];
                                        }
                                    }

                                    $colaborador = str_replace(array_keys($replace),$replace,$colaborador);
                                    $cliente = str_replace(array_keys($replace),$replace,$cliente);
                                    $observaciones = str_replace(array_keys($replace),$replace,$observaciones);
                                    $tipomovimiento = str_replace(array_keys($replace),$replace,$tipomovimiento);
                                    $mediodepago = str_replace(array_keys($replace),$replace,$mediodepago);

                                    //Create ESC/POS commands for sample receipt
                                    $esc = '0x1B'; //ESC byte in hex notation
                                    $newLine = '0x0A'; //LF byte in hex notation
                                    $cmds = '';
                                    $cmds = $esc . "@"; //Initializes the printer (ESC @)
                                    $cmds .= $esc . '!' . '0x00'; //Emphasized + Double-height + Double-width mode selected (ESC ! (8 + 16 + 32)) 56 dec => 38 hex
                                    $cmds .= $newLine;
                                    $cmds .= 'DOCUMENTO DE MOVIMIENTO DE DINERO';
                                    $cmds .= $newLine;
                                    $cmds .= '-------------------------';
                                    $cmds .= $newLine;
                                    $cmds .= 'CARVASQ E.I.R.L.'; //text to print
                                    $cmds .= $newLine;
                                    $cmds .= '-------------------------';
                                    $cmds .= $newLine;
                                    $cmds .= 'ID MOVIMIENTO  '.$idMovimiento;
                                    $cmds .= $newLine;
                                    $cmds .= 'TRANS PRIMARIA  '.$transPrimaria;
                                    $cmds .= $newLine;
                                    $cmds .= 'TRANS REFERENCIAL  '.$transReferencial;
                                    $cmds .= $newLine;
                                    $cmds .= 'FECHA  '.$fechaTransaccion;
                                    $cmds .= $newLine;
                                    $cmds .= 'COLABORADORA  '.$colaborador;
                                    $cmds .= $newLine;
                                    $cmds .= 'CLIENTE  '.$cliente;
                                    $cmds .= $newLine;
                                    $cmds .= 'MEDIO DE PAGO  '.strtoupper($mediodepago);
                                    $cmds .= $newLine;
                                    $cmds .= 'TIPO  '.strtoupper($tipomovimiento);
                                    $cmds .= $newLine;
                                    $cmds .= 'MONTO  '.$monto;
                                    $cmds .= $newLine;
                                    $cmds .= 'DESCRIPCION  '.strtoupper($observaciones);
                                    $cmds .= $newLine . $newLine.$newLine;
									$cmds .='------------------';
									$cmds .= $newLine;
									$cmds .='  FIRMA CONSULTORA';
									$cmds .=$newLine.$newLine.$newLine.$newLine.$newLine;
                                    echo $cmds;
                                    ?>
                                </textarea>
                                <input type='hidden' id='useDefaultPrinter' checked>
                                <button href='#' onclick='javascript:doClientPrint();' class='btn btn-secondary btn-sm'>Imprimir</button>
                                <input type="submit" value="Volver" name="volver" class="btn btn-secondary btn-sm" formaction="gestionCaja.php">
                            </div>
                        </form>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-2"><p><b>Código de Movimiento:</b></p></div>
                                <div class="col-10"><p><?php echo $_POST['idMovimiento']; ?></p></div>
                            </div>
                            <div class="row">
                                <div class="col-2"><p><b>Transacción Primaria:</b></p></div>
                                <div class="col-10"><p><?php echo $transaccionPrimaria; ?></p></div>
                            </div>
                            <div class="row">
                                <div class="col-2"><p><b>Transacción Referencial:</b></p></div>
                                <div class="col-10"><p><?php echo $transaccionReferencial; ?></p></div>
                            </div>
                            <div class="row">
                                <div class="col-2"><p><b>Responsable:</b></p></div>
                                <div class="col-10"><p><?php echo $prestamista; ?></p></div>
                            </div>
                            <div class="row">
                                <div class="col-2"><p><b>Cliente:</b></p></div>
                                <div class="col-10"><p><?php echo $proveedor; ?></p></div>
                            </div>
                            <div class="row">
                                <div class="col-2"><p><b>Fecha de Realización:</b></p></div>
                                <div class="col-10"><p><?php echo $fecha[0]; ?></p></div>
                            </div>
                            <div class="row">
                                <div class="col-2"><p><b>Monto:</b></p></div>
                                <div class="col-10"><p>S/. <?php echo $monto; ?></p></div>
                            </div>
                            <div class="row">
                                <div class="col-2"><p><b>Observaciones:</b></p></div>
                                <div class="col-10"><p><?php echo $observacion; ?></p></div>
                            </div>
                        </div>
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
    ?>


    <script type="text/javascript">

        function doClientPrint() {

            //collect printer settings and raw commands
            var printJobInfo = $("#myForm").serialize();

            // Launch WCPP at the client side for printing...
            jsWebClientPrint.print(printJobInfo);

        }

        $(document).ready(function () {
            //jQuery-based Wizard
            $("#myForm").formToWizard();

            //change printer options based on user selection
            $("#pid").change(function () {
                var printerId = $("select#pid").val();

                displayInfo(printerId);
                hidePrinters();
                if (printerId == 2)
                    $("#installedPrinter").show();
                else if (printerId == 3)
                    $("#netPrinter").show();
                else if (printerId == 4)
                    $("#parallelPrinter").show();
                else if (printerId == 5)
                    $("#serialPrinter").show();
            });

            hidePrinters();
            displayInfo(0);


        });

        function displayInfo(i) {
            if (i == 0)
                $("#info").html('This will make the WCPP to send the commands to the printer installed in your machine as "Default Printer" without displaying any dialog!');
            else if (i == 1)
                $("#info").html('This will make the WCPP to display the Printer dialog so you can select which printer you want to use.');
            else if (i == 2)
                $("#info").html('Please specify the <b>Printer\'s Name</b> as it figures installed under your system.');
            else if (i == 3)
                $("#info").html('Please specify the Network Printer info.<br /><strong>On Linux &amp; Mac</strong> it\'s recommended you install the printer through <strong>CUPS</strong> and set the assigned printer name to the <strong>"Use an installed Printer"</strong> option on this demo.');
            else if (i == 4)
                $("#info").html('Please specify the Parallel Port which your printer is connected to.<br /><strong>On Linux &amp; Mac</strong> you must install the printer through <strong>CUPS</strong> and set the assigned printer name to the <strong>"Use an installed Printer"</strong> option on this demo.');
            else if (i == 5)
                $("#info").html('Please specify the Serial RS232 Port info which your printer does support.<br /><strong>On Linux &amp; Mac</strong> you must install the printer through <strong>CUPS</strong> and set the assigned printer name to the <strong>"Use an installed Printer"</strong> option on this demo.');
        }

        function hidePrinters() {
            $("#installedPrinter").hide(); $("#netPrinter").hide(); $("#parallelPrinter").hide(); $("#serialPrinter").hide();
        }

        /* FORM to WIZARD */
        /* Created by jankoatwarpspeed.com */

        (function ($) {
            $.fn.formToWizard = function () {

                var element = this;

                var steps = $(element).find("fieldset");
                var count = steps.size();


                // 2
                $(element).before("<ul id='steps'></ul>");

                steps.each(function (i) {
                    $(this).wrap("<div id='step" + i + "'></div>");
                    $(this).append("<p id='step" + i + "commands'></p>");

                    // 2
                    var name = $(this).find("legend").html();
                    $("#steps").append("<li id='stepDesc" + i + "'>Step " + (i + 1) + "<span>" + name + "</span></li>");

                    if (i == 0) {
                        createNextButton(i);
                        selectStep(i);
                    }
                    else if (i == count - 1) {
                        $("#step" + i).hide();
                        createPrevButton(i);
                    }
                    else {
                        $("#step" + i).hide();
                        createPrevButton(i);
                        createNextButton(i);
                    }
                });

                function createPrevButton(i) {
                    var stepName = "step" + i;
                    $("#" + stepName + "commands").append("<a href='#' id='" + stepName + "Prev' class='prev btn btn-info'>< Back</a>");

                    $("#" + stepName + "Prev").bind("click", function (e) {
                        $("#" + stepName).hide();
                        $("#step" + (i - 1)).show();

                        selectStep(i - 1);
                    });
                }

                function createNextButton(i) {
                    var stepName = "step" + i;
                    $("#" + stepName + "commands").append("<a href='#' id='" + stepName + "Next' class='next btn btn-info'>Next ></a>");

                    $("#" + stepName + "Next").bind("click", function (e) {
                        $("#" + stepName).hide();
                        $("#step" + (i + 1)).show();

                        selectStep(i + 1);
                    });
                }

                function selectStep(i) {
                    $("#steps li").removeClass("current");
                    $("#stepDesc" + i).addClass("current");
                }

            }
        })(jQuery);

    </script>

    <?php
	
    include('footerTemplate.php');
}else{
    include('sessionError.php');
}