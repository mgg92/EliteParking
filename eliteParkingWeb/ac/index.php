<?php
session_start();
?>

<html>
	
	<?php if (isset($_SESSION['idAparcaCoches'])) { ?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">				
			<title>Índice de AparcaCoches</title>
			<h2>EliteParking</h2>
			<h2>Índice de AparcaCoches</h2>
			<a href="../logout">Cerrar sesión</a><br><br>			
		</head>		

		<body>

			<?php

				include_once '../db_conf.php';

				try {

				    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
				}

				catch(PDOException $e) {

					echo "Error al conectar a la base de datos <br>" . $e->getMessage();
				}

				$idAparcaCoches = $_SESSION['idAparcaCoches'];

				$stmt = $conn->prepare("SELECT e.idEstablecimiento, e.EstablecimientoNombre 
										FROM Establecimiento e 
										INNER JOIN AparcaCoches_Establecimiento ace 
										ON e.idEstablecimiento = ace.idEstablecimiento 
										WHERE ace.idAparcaCoches = '$idAparcaCoches'"); 

				$stmt->execute();

				$est = $stmt->fetchAll(PDO::FETCH_ASSOC);	

				$s = "&nbsp;&nbsp;&nbsp;&nbsp;";			

				echo "<h2> ¡Bienvenido " . $_SESSION['AparcaCochesNombre'] . "! </h2>";

				echo "<h3> Establecimientos: </h3>";

				for ($i=0; $i < count($est); $i++) { 
					echo $est[$i]['EstablecimientoNombre'] . $s . $s;					
					echo "<a href='servicios/?id=" . $est[$i]['idEstablecimiento'] . "'>Ver servicios</a>" . $s;
					echo "<a href='nuevo/?id=" . $est[$i]['idEstablecimiento'] . "'>Nuevo servicio</a><br><br>";
				}
			?>
		</body>
	<?php } else { ?> 
		<h1>Acceso denegado!</h1>
		<a href="/">Volver a inicio</a>
	<?php } ?>
</html>