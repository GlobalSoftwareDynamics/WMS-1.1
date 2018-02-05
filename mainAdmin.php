<?php
include('session.php');
if(isset($_SESSION['login'])) {
	include('adminTemplate.php');

	$fechahoyformato2 = date("Y-m-d");
	$fecha2=explode("-",$fechahoyformato2);
	$fechaInicioDeudas = date("Y-m-d");
	$fechaFinDeudas = date('Y-m-d', strtotime($fechaInicioDeudas. ' + 15 days'));
	?>

    <section class="container">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-shopping-cart"></i>
                            Productos Más Vendidos del Mes
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$aux=1;
								$result=mysqli_query($link,"SELECT idProducto, SUM(cantidad) AS Cantidad FROM TransaccionProducto WHERE idTransaccion IN (SELECT idTransaccion FROM Transaccion WHERE fechaTransaccion LIKE '%-{$fecha2[1]}-%' AND idTipoTransaccion = 5) GROUP BY idProducto ORDER BY Cantidad DESC");
								while ($fila=mysqli_fetch_array($result)){
									echo "<tr>";
									if($aux>5){
									}else{
										$nombre = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$fila['idProducto']}'");
										while ($fila1=mysqli_fetch_array($nombre)){
											$atributo=mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$fila1['idColor']}'");
											while ($fila2=mysqli_fetch_array($atributo)){
												$nombreatributo = $fila2['descripcion'];
											}
											$nombreprod = $fila1['nombreCorto']." ".$nombreatributo;
										}
										echo "<td>{$nombreprod}</td>";
										echo "<td>{$fila['Cantidad']}</td>";
										$aux++;
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
            <div class="col-6">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-money"></i>
                            Productos Más Rentables del Mes
                        </div>
                    </div>
                    <div class="card-block">
                        <div class="col-12">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Monto Total</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$aux=1;
								$result=mysqli_query($link,"SELECT idProducto, SUM(Monto) as MontoTotal FROM (SELECT idProducto,(valorUnitario*cantidad) AS Monto FROM TransaccionProducto WHERE idTransaccion IN (SELECT idTransaccion FROM Transaccion WHERE fechaTransaccion LIKE '%-{$fecha2[1]}-%' AND idTipoTransaccion = 5) ORDER BY idProducto) as puto GROUP BY idProducto ORDER BY MontoTotal DESC");
								while ($fila=mysqli_fetch_array($result)){
									echo "<tr>";
									if($aux>5){
									}else{
										$nombre = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$fila['idProducto']}'");
										while ($fila1=mysqli_fetch_array($nombre)){
											$atributo=mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$fila1['idColor']}'");
											while ($fila2=mysqli_fetch_array($atributo)){
												$nombreatributo = $fila2['descripcion'];
											}
											$nombreprod = $fila1['nombreCorto']." ".$nombreatributo;
										}
										echo "<td>{$nombreprod}</td>";
										echo "<td>S/. {$fila['MontoTotal']}</td>";
										$aux++;
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
        <div class="spacer15"></div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-calendar"></i>
                            Deudas a Vencer en los Próximos 15 Días
                        </div>
                    </div>
                    <div class="card-block" style="height: 250px; overflow: scroll">
                        <div class="col-12">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 20%">Transacción</th>
                                    <th class="text-center" style="width: 30%">Cliente</th>
                                    <th class="text-center" style="width: 20%">Fecha de Vencimiento</th>
                                    <th class="text-center" style="width: 30%">Monto</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$aux=1;
								$result=mysqli_query($link,"SELECT * FROM Transaccion WHERE montoRestante > 0 AND fechaVencimiento >= '{$fechahoyformato2}' AND fechaVencimiento <= '{$fechaFinDeudas}' ORDER BY fechaVencimiento ASC");
								while ($fila=mysqli_fetch_array($result)){
									echo "<tr>";
									$cliente = mysqli_query($link,"SELECT * FROM Proveedor WHERE idProveedor = '{$fila['idProveedor']}'");
									while ($fila1=mysqli_fetch_array($cliente)){
										$nombrecliente=$fila1['nombre'];
									}
									echo "<td>{$fila['idTransaccion']}</td>";
									echo "<td>{$nombrecliente}</td>";
									echo "<td>{$fila['fechaVencimiento']}</td>";
									echo "<td>S/. {$fila['montoRestante']}</td>";
									echo "</tr>";
								}
								?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-birthday-cake"></i>
                            Cumpleaños del Mes
                        </div>
                    </div>
                    <div class="card-block" style="height: 250px; overflow: scroll">
                        <div class="col-12">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Fecha de Cumpleaños</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$aux=1;
								$result=mysqli_query($link,"SELECT * FROM Proveedor WHERE fechaNacimiento LIKE '%-{$fecha2[1]}-%'");
								while ($fila=mysqli_fetch_array($result)){
									echo "<tr>";
									echo "<td>{$fila['nombre']}</td>";
									echo "<td>{$fila['fechaNacimiento']}</td>";
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
        <div class="spacer15"></div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-bar-chart"></i>
                            Productos por Debajo del Stock Mínimo
                        </div>
                    </div>
                    <div class="card-block" style="height: 250px; overflow: scroll">
                        <div class="col-12">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center" style="width: 20%">Código</th>
                                    <th class="text-center" style="width: 30%">Nombre</th>
                                    <th class="text-center" style="width: 20%">Stock Actual</th>
                                    <th class="text-center" style="width: 30%">Stock Mínimo</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$aux=1;
								$result=mysqli_query($link,"SELECT idProducto, SUM(stock) AS stockDisponible FROM `UbicacionProducto` GROUP BY idProducto");
								while ($fila=mysqli_fetch_array($result)){
									echo "<tr>";
									$nombre = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$fila['idProducto']}'");
									while ($fila1=mysqli_fetch_array($nombre)){
										$stockReposicion=$fila1['puntoReposicion'];
										$atributo=mysqli_query($link,"SELECT * FROM Color WHERE idColor = '{$fila1['idColor']}'");
										while ($fila2=mysqli_fetch_array($atributo)){
											$nombreatributo = $fila2['descripcion'];
										}
										$nombreprod = $fila1['nombreCorto']." ".$nombreatributo;
									}
									if($stockReposicion>=$fila['stockDisponible']){
										echo "<td>{$fila['idProducto']}</td>";
										echo "<td>{$nombreprod}</td>";
										echo "<td>{$fila['stockDisponible']}</td>";
										echo "<td>{$stockReposicion}</td>";
										echo "</tr>";
									}else{}
								}
								?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header card-inverse card-info">
                        <div class="float-left">
                            <i class="fa fa-calendar"></i>
                            Premios Próximos a Llegar
                        </div>
                    </div>
                    <div class="card-block" style="height: 250px; overflow: scroll">
                        <div class="col-12">
                            <table class="table text-center">
                                <thead>
                                <tr>
                                    <th class="text-center">Transacción</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">Fecha Estimada</th>
                                    <th class="text-center">Cantidad</th>
                                </tr>
                                </thead>
                                <tbody>
								<?php
								$aux=1;
								$result=mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion LIKE 'OCP%'");
								while ($fila=mysqli_fetch_array($result)){
									$cantidad = $fila['cantidad'];
									$idproducto = $fila['idProducto'];
									$producto = mysqli_query($link,"SELECT * FROM Producto WHERE idProducto = '{$fila['idProducto']}'");
									while ($fila1=mysqli_fetch_array($producto)){
										$nombreproducto = $fila1['nombreCorto'];
									}
									$result1=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$fila['idTransaccion']}' AND idEstado <> 5 ");
									while ($fila1=mysqli_fetch_array($result1)){
										$cantidadrecibida=0;
										$result3=mysqli_query($link,"SELECT * FROM Transaccion WHERE referenciaTransaccion = '{$fila['idTransaccion']}' AND idTransaccion LIKE 'OR%'");
										while ($fila3=mysqli_fetch_array($result3)){
											$result4=mysqli_query($link,"SELECT * FROM TransaccionProducto WHERE idTransaccion = '{$fila3['idTransaccion']}' AND idProducto = '{$idproducto}'");
											while ($fila4=mysqli_fetch_array($result4)){
												$cantidadrecibida += $fila4['cantidad'];
											}
										}
										$dato=$fila1['fechaEstimada'];
										if($cantidad==$cantidadrecibida){

										}else{
											$resta=$cantidad-$cantidadrecibida;
											if($dato=="OC - Premios"){
												echo "<tr>";
												echo "<td>{$fila['idTransaccion']}</td>";
												echo "<td>{$nombreproducto}</td>";
												echo "<td>Premio de Campaña</td>";
												echo "<td>{$resta}</td>";
												echo "</tr>";
											}else{
												$result2=mysqli_query($link,"SELECT * FROM Transaccion WHERE idTransaccion = '{$fila1['fechaEstimada']}' AND fechaEstimada <= '{$fechaFinDeudas}' ORDER BY fechaEstimada DESC");
												while ($fila2=mysqli_fetch_array($result2)){
													echo "<tr>";
													echo "<td>{$fila['idTransaccion']}</td>";
													echo "<td>{$nombreproducto}</td>";
													echo "<td>{$fila2['fechaEstimada']}</td>";
													echo "<td>{$resta}</td>";
													echo "</tr>";
												}
											}
										}
									}
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
	include('footerTemplate.php');
}else{
	include('sessionError.php');
}
