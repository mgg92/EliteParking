<?php
session_start();
?>

<html>
	
	<?php if (isset($_GET['id']) && isset($_SESSION['idAdministrador'])) { ?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">				
			<title>Registro de AparcaCoches</title>
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
				    
					$stmt = $conn->prepare("SELECT `EstablecimientoNombre` 
											FROM  `Establecimiento` 
											WHERE  `idEstablecimiento` =  '$id'");
					$stmt->execute();

					$EstablecimientoNombre = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$EstablecimientoNombre = $EstablecimientoNombre[0]['EstablecimientoNombre'];

					echo $EstablecimientoNombre;

			?>  Nuevo AparcaCoches </h2>
			<a href="../../logout">Cerrar sesión</a><br><br>
			<a href="../">Volver al índice</a>
		</head>		

		<body>

			<script src="http://code.jquery.com/jquery.js"></script>
			<script src="js/bootstrap.min.js"></script>	

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
					$AparcaCochesSegundoApellido = test_input($_POST["segundoApellido"]);
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
			
			<form name= "Registro de AparcaCoches" method="POST" class="elegant-aero" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $_GET['id']; ?>">
				<h1>Registro de aparcacoches nuevo</h1>
				<label>
					<span>Primer nombre: </span>
					<input id="primerNombre" type="text" name="primerNombre">
				</label>
				<label>
					<span>Segundo nombre: </span>
					<input id="segundoNombre" type="text" name="segundoNombre">
				</label>
				<label>
					<span>Primer apellido: </span>
					<input id="primerApellido" type="text" name="primerApellido">
				</label>
				<label>
					<span>Segundo apellido: </span>
					<input id="segundoApellido" type="text" name="segundoApellido">
				</label>
				<label>
					<span>Cédula: </span>
					<input id="cedula" type="text" name="cedula">
				</label>
				<label>
					<span>Teléfono celular: </span>
					<input id="telefonoCelular" type="text" name="telefonoCelular">
				</label>
				<input type="submit" class="button" value="Registrar">
			</form>

		</body>
	<?php } else { ?> 
		<h1>Acceso denegado!</h1>
		<a href="/">Volver a inicio</a>
	<?php } ?>
</html>