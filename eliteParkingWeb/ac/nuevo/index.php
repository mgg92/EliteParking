<?php
session_start();
?>

<html>
	
	<?php if ((isset($_GET['id'])) && isset($_SESSION['idAparcaCoches'])) { ?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">				
			<title>Registro de Servicio</title>
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

			?> | Nuevo servicio </h2>
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

				function token () {


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
				form {
				    width: 300px ;
				    margin: 0 auto;
				    border: 3px solid #ccc;
				    padding: 10px;
				}
				label { 
				    text-align:right; 
				    width:100px; 
				}
				input { 
				    margin-left: 10px; 
				}
				label.check, label.radio { 
				    text-align:left; 
				}
			</style>
			
			<form name= "Registro de Servicio" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $_GET['id']; ?>">
				Placa: <br> <input type="text" name="VehiculoPlaca"><br><br>
				Marca: <br> <input type="text" name="VehiculoMarca"><br><br>
				Línea: <br> <input type="text" name="VehiculoLinea"><br><br>
				Color: <br> <input type="text" name="VehiculoColor"><br><br>
				<input type="submit" value="Registrar">
			</form>				
		</body>
	<?php } else { ?> 
		<h1>Acceso denegado!</h1>
		<a href="/">Volver a inicio</a>
	<?php } ?>
</html>