<?php
session_start();
?>

<html>
	
	<?php if ((isset($_GET['id'])) && isset($_SESSION['idAparcaCoches'])) { ?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">				
			<title>Registro de Servicio</title>
			<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
			<img src="../../images/valet-parking-512.png" alt="EliteParking" height="100" width="100">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  			<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
			<h2>EliteParking</h2>
			<h2><?php 
			
					include_once '../../db_conf.php';
					$conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

				    $id = $_GET['id'];
				    
					$stmt = $conn->prepare("SELECT `EstablecimientoNombre` FROM  `Establecimiento` WHERE  `idEstablecimiento` =  '$id'");
					$stmt->execute();

					$EstablecimientoNombre = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$EstablecimientoNombre = $EstablecimientoNombre[0]['EstablecimientoNombre'];

					echo $EstablecimientoNombre;

			?>  Nuevo Servicio </h2>
			<a href="../../logout">Cerrar sesión</a><br><br>
			<a href="../">Volver al índice</a>
		</head>		

		<body>

			<?php 

				function test_input($data) {
					  $data = trim($data);
					  $data = stripslashes($data);
					  $data = htmlspecialchars($data);
					  return $data;
				}			
				
				if ($_SERVER["REQUEST_METHOD"] == "POST") {

					$VehiculoPlaca = test_input($_POST["VehiculoPlaca"]);			  								
					$VehiculoMarca = test_input($_POST["VehiculoMarca"]);						
					$VehiculoColor = test_input($_POST["VehiculoColor"]);
					$VehiculoLinea = test_input($_POST["VehiculoLinea"]);

					$idEstablecimiento = $_GET['id'];
					$idAparcaCoches = $_SESSION['idAparcaCoches'];

					if(empty($VehiculoPlaca)) {

						echo "Ingrese placa <br>";
					} else {						

						try {						  
						    
							$stmt = $conn->prepare("SELECT `idVehiculo` FROM  `Vehiculo` WHERE  `VehiculoPlaca` =  '$VehiculoPlaca'");
							$stmt->execute();

							$idVehiculo = $stmt->fetchAll(PDO::FETCH_ASSOC);

							if (count($idVehiculo)) {

								$idVehiculo = $idVehiculo[0]['idVehiculo'];

								$tempTime = 1430144805;
								$ServicioToken = time() - $tempTime;

								echo "<br><br> ¡Datos recibidos con éxito! <br><br>"; 
								echo "<h3> Ingreso al app </h3>";
								echo "<strong> Usuario: </strong>" . $VehiculoPlaca . "<br>";
								echo "<strong> Contraseña: </strong>" . $ServicioToken;								

								$stmt = $conn->prepare("INSERT INTO `ServicioActivo` (`idServicioActivo`, `ServicioToken`, `idVehiculo`, `idEstablecimiento`, `idAparcaCoches`, `FechaHoraRecepcion`) 
														VALUES ('0', '$ServicioToken', '$idVehiculo', '$idEstablecimiento', '$idAparcaCoches', CONVERT_TZ(NOW(),'UTC','America/Bogota'));");

								$stmt->execute();

							} else {

								if(empty($VehiculoMarca)) {
									echo "Ingrese marca <br>";						
								}

								if(empty($VehiculoLinea)) {
									echo "Ingrese línea <br>";					
								}

								if(empty($VehiculoColor)) {
									echo "Ingrese color <br>";					
								}

							    elseif(!empty($VehiculoColor) && !empty($VehiculoMarca) && !empty($VehiculoLinea)) {

								    $stmt = $conn->prepare("INSERT INTO `Vehiculo`(`idVehiculo`, `VehiculoPlaca`, `VehiculoMarca`, `VehiculoLinea`, `VehiculoColor`) 
								    	VALUES (0, '$VehiculoPlaca', '$VehiculoMarca', '$VehiculoLinea', '$VehiculoColor')");
									$stmt->execute();

									$idVehiculo = $conn ->lastInsertId(); 

									$tempTime = 1430144805;
									$ServicioToken = time() - $tempTime;

									echo "<br><br> ¡Datos recibidos con éxito! <br><br>"; 
									echo "<h3> Ingreso al app </h3>";
									echo "<strong> Usuario: </strong>" . $VehiculoPlaca . "<br>";
									echo "<strong> Contraseña: </strong>" . $ServicioToken;								

									$stmt = $conn->prepare("INSERT INTO `ServicioActivo` (`idServicioActivo`, `ServicioToken`, `idVehiculo`, `idEstablecimiento`, `idAparcaCoches`, `FechaHoraRecepcion`) 
															VALUES ('0', '$ServicioToken', '$idVehiculo', '$idEstablecimiento', '$idAparcaCoches', CONVERT_TZ(NOW(),'UTC','America/Bogota'));");

									$stmt->execute();
								}
							}
						}

						catch(PDOException $e){

							echo "<br> Error al conectar a la base de datos <br>" . $e->getMessage();

						}
						$conn = null;
					}
				}							

			?>

			<style>
				body {
					background: #fafafa url(http://jackrugile.com/images/misc/noise-diagonal.png);
			  		color: #444;
			  		font: 100%/30px 'Helvetica Neue', helvetica, arial, sans-serif;
			  		text-shadow: 0 1px 0 #fff;
				}
	
				.elegant-aero {
				    margin-left:auto;
				    margin-right:auto;

				    max-width: 500px;
				    background: #D2E9FF;
				    padding: 20px 20px 20px 20px;
				    font: 12px Arial, Helvetica, sans-serif;
				    color: #666;
				}
				.elegant-aero h1 {
				    font: 24px "Trebuchet MS", Arial, Helvetica, sans-serif;
				    padding: 10px 10px 10px 20px;
				    display: block;
				    background: #C0E1FF;
				    border-bottom: 1px solid #B8DDFF;
				    margin: -20px -20px 15px;
				}
				.elegant-aero h1>span {
				    display: block;
				    font-size: 11px;
				}

				.elegant-aero label>span {
				    float: left;
				    margin-top: 10px;
				    color: #5E5E5E;
				}
				.elegant-aero label {
				    display: block;
				    margin: 0px 0px 5px;
				}
				.elegant-aero label>span {
				    float: left;
				    width: 20%;
				    text-align: right;
				    padding-right: 15px;
				    margin-top: 10px;
				    font-weight: bold;
				}
				.elegant-aero input[type="text"], .elegant-aero input[type="email"], .elegant-aero textarea, .elegant-aero select {
				    color: #888;
				    width: 70%;
				    padding: 0px 0px 0px 5px;
				    border: 1px solid #C5E2FF;
				    background: #FBFBFB;
				    outline: 0;
				    -webkit-box-shadow:inset 0px 1px 6px #ECF3F5;
				    box-shadow: inset 0px 1px 6px #ECF3F5;
				    font: 200 12px/25px Arial, Helvetica, sans-serif;
				    height: 30px;
				    line-height:15px;
				    margin: 2px 6px 16px 0px;
				}
				.elegant-aero textarea{
				    height:100px;
				    padding: 5px 0px 0px 5px;
				    width: 70%;
				}
				.elegant-aero select {
				    background: #fbfbfb url('down-arrow.png') no-repeat right;
				    background: #fbfbfb url('down-arrow.png') no-repeat right;
				   appearance:none;
				    -webkit-appearance:none; 
				   -moz-appearance: none;
				    text-indent: 0.01px;
				    text-overflow: '';
				    width: 70%;
				}
				.elegant-aero .button{
				    padding: 10px 30px 10px 30px;
				    background: #66C1E4;
				    border: none;
				    color: #FFF;
				    box-shadow: 1px 1px 1px #4C6E91;
				    -webkit-box-shadow: 1px 1px 1px #4C6E91;
				    -moz-box-shadow: 1px 1px 1px #4C6E91;
				    text-shadow: 1px 1px 1px #5079A3;
				    
				}
				.elegant-aero .button:hover{
				    background: #3EB1DD;
				}
			</style>
			
			<form name= "Registro de Servicio" method="POST" class="elegant-aero" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $_GET['id']; ?>">
				<h1>Registro de aparcacoches nuevo</h1>
				<label>
					<span>Placa: </span>
					<input id="VehiculoPlaca" type="text" name="VehiculoPlaca">
				</label>
				<label>
					<span>Marca: </span>
					<input id="VehiculoMarca" type="text" name="VehiculoMarca">
				</label>
				<label>
					<span>Línea: </span>
					<input id="VehiculoLinea" type="text" name="VehiculoLinea">
				</label>
				<label>
					<span>Color: </span>
					<input id="VehiculoColor" type="text" name="VehiculoColor">
				</label>
				<input type="submit" class="button" value="Registrar">
			</form>

		</body>
	<?php } else { ?> 
		<h1>Acceso denegado!</h1>
		<a href="/">Volver a inicio</a>
	<?php } ?>
</html>