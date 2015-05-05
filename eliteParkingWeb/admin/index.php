<?php
session_start();
?>

<html>
	
	<?php if (isset($_SESSION['idAdministrador'])) { ?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">				
			<title>Índice de Administrador</title>
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
					echo "Establecimientos que usted administra <br><br>";

					for ($i=0; $i < count($est); $i++) { 
						echo $est[$i]['EstablecimientoNombre'] . $s . $s;					
						echo "<a href='ac/?id=" . $est[$i]['idEstablecimiento'] . "'>Ver AparcaCoches</a>" . $s;
						echo "<a href='nuevo/?id=" . $est[$i]['idEstablecimiento'] . "'>Nuevo AparcaCoches</a><br><br>";
					}
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