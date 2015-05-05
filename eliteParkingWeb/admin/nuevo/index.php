<?php
session_start();
?>

<html>
	
	<?php if (isset($_GET['id']) && isset($_SESSION['idAdministrador'])) { ?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">				
			<title>Registro de AparcaCoches</title>
			<h2>EliteParking</h2>
			<h2><?php 
			
					include_once '../../db_conf.php';
					$conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

				    $id = $_GET['id'];
				    
					$stmt = $conn->prepare("SELECT `EstablecimientoNombre` 
											FROM  `Establecimiento` 
											WHERE  `idEstablecimiento` =  '$id'");
					$stmt->execute();

					$EstablecimientoNombre = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$EstablecimientoNombre = $EstablecimientoNombre[0]['EstablecimientoNombre'];

					echo $EstablecimientoNombre;

			?> | Nuevo AparcaCoches </h2>
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
					
					$AparcaCochesNombre = test_input($_POST["primerNombre"]);						
					$AparcaCochesSegundoNombre = test_input($_POST["segundoNombre"]);
					$AparcaCochesPrimeroApellido = test_input($_POST["primerApellido"]);
					$AparcaCochesSegundoApellido = test_input($_POST["segundoNombre"]);
					$AparcaCochesCedula = test_input($_POST["cedula"]);			  								
					$AparcaCochesCelular = test_input($_POST["celular"]);			  								

					$idEstablecimiento = $_GET['id'];
					$idAparcaCoches = $_SESSION['idAparcaCoches'];

					if(empty($AparcaCochesNombre) || empty($AparcaCochesPrimeroApellido) || empty($AparcaCochesSegundoApellido) || empty($AparcaCochesCedula) || empty($AparcaCochesCelular)) {

						echo "<br><h1>Ingrese un valor en los campos vacíos</h1><br>";
					} else {						

						try {						  
						    
							$stmt = $conn->prepare("SELECT `idAparcaCoches` 
													FROM  `AparcaCoches` 
													WHERE  `AparcaCochesCedula` =  '$AparcaCochesCedula'");
							$stmt->execute();

							$idAparcaCoches = $stmt->fetchAll(PDO::FETCH_ASSOC);

							if (count($idAparcaCoches)) {

								echo "Este AparcaCoches ya está registrado!";

							} else {


								    $stmt = $conn->prepare("INSERT INTO `AparcaCoches`(`idAparcaCoches`, 
								    													`AparcaCochesCedula`, 
								    													`AparcaCochesNombre`, 
								    													`AparcaCochesSegundoNombre`, 
								    													`AparcaCochesPrimeroApellido`, 
								    													`AparcaCochesSegundoApellido`, 
								    													`AparcaCochesContrasena`, 
								    													`AparcaCochesCelular`, 
								    													`AparcaCochesFechaHoraRegistro`) 
								    						VALUES (0,
								    							$AparcaCochesCedula,
								    							'$AparcaCochesNombre',
								    							'$AparcaCochesSegundoNombre',
								    							'$AparcaCochesPrimeroApellido',
								    							'$AparcaCochesSegundoApellido',
								    							$AparcaCochesCedula,
								    							$AparcaCochesCelular,
								    							CONVERT_TZ(NOW(),'UTC','America/Bogota'))");
									$stmt->execute();

									$idAparcaCoches = $conn ->lastInsertId(); 
									
									$stmt = $conn->prepare("INSERT INTO `AparcaCoches_Establecimiento`(`idAparcaCoches_Establecimiento`, 
																										`idAparcaCoches`,
																										`idEstablecimiento`) 
															VALUES (0,
																	$idAparcaCoches,
																	$id)");

									$stmt->execute();

									echo "<h3>AparcaCoches registrado con éxito!</h3>";
									echo "<strong>Usuario/Contraseña:</strong> " . $AparcaCochesCedula;
								
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
			
			<form name= "Registro de AparcaCoches" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $_GET['id']; ?>">
				Primer nombre: <br> <input type="text" name="primerNombre"><br><br>
				Segundo nombre: <br> <input type="text" name="segundoNombre"><br><br>
				Primer apellido: <br> <input type="text" name="primerApellido"><br><br>
				Segundo apellido: <br> <input type="text" name="segundoApellido"><br><br>
				Cédula: <br> <input type="text" name="cedula"><br><br>
				Teléfono celular: <br> <input type="text" name="celular"><br><br>
				<input type="submit" value="Registrar">
			</form>				
		</body>
	<?php } else { ?> 
		<h1>Acceso denegado!</h1>
		<a href="/">Volver a inicio</a>
	<?php } ?>
</html>