<?php
session_start();
?>

<html>
	
	<?php if (isset($_SESSION['idAdministrador'])) { ?>

		<head>

			<style>

			body {
		  		background: #fafafa url(http://jackrugile.com/images/misc/noise-diagonal.png);
		  		color: #444;
		  		font: 100%/30px 'Helvetica Neue', helvetica, arial, sans-serif;
		  		text-shadow: 0 1px 0 #fff;
			}

			table {
			  background: #f5f5f5;
			  border-collapse: separate;
			  box-shadow: inset 0 1px 0 #fff;
			  font-size: 12px;
			  line-height: 24px;
			  text-align: left;
			  width: 800px;
			}

			</style>

			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">				
			<title>Índice de Administrador</title>
			<img src="../images/valet-parking-512.png" alt="EliteParking" height="100" width="100">
			<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
			<h2>EliteParking</h2>
			<a href="../logout">Cerrar sesión</a><br><br>
		</head>		

		<body>

			<script src="http://code.jquery.com/jquery.js"></script>
			<script src="js/bootstrap.min.js"></script>

			<?php
				try {

					include_once '../db_conf.php';

				    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	


					$idAdmin = $_SESSION['idAdministrador'];

					$stmt = $conn->prepare("SELECT e.idEstablecimiento, e.EstablecimientoNombre 
											FROM Establecimiento e 
											INNER JOIN Administrador_Establecimiento ae 
											ON e.idEstablecimiento = ae.idEstablecimiento 
											WHERE ae.idAdministrador = '$idAdmin'"); 

					$stmt->execute();

					$est = $stmt->fetchAll(PDO::FETCH_ASSOC);

					$s = "&nbsp;&nbsp;&nbsp;&nbsp;";				
					
					echo "<h2> ¡Bienvenido " . $_SESSION['AdministradorNombre'] . "! </h2>";
					echo "<h3>Establecimientos que usted administra</h3> <br><br>";

					echo "<table>";

					for ($i=0; $i < count($est); $i++) { 
						echo "<tr>" . $est[$i]['EstablecimientoNombre'] . $s . $s;					
						echo "<center><a href='ac/?id=" . $est[$i]['idEstablecimiento'] . "'>Ver AparcaCoches</a></center>" . $s;
						echo "<center><a href='nuevo/?id=" . $est[$i]['idEstablecimiento'] . "'>Nuevo AparcaCoches</a></center></tr>";
					}

					echo "</table>";
					
					$conn = null;
				}

				catch(PDOException $e) {

					echo "Error al conectar a la base de datos <br>" . $e->getMessage();
				}
			?>
		</body>
	<?php } else { ?> 
		<h1>Acceso denegado!</h1>
		<a href="/">Volver a inicio</a>
	<?php } ?>
</html>