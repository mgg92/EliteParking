<?php 
	
	session_start(); 

	if (isset($_SESSION['idAparcaCoches'])) {

		$idSa = $_GET['id'];
		$idAparcaCoches = $_SESSION['idAparcaCoches'];


		include_once '../../../db_conf.php';

		try {

		    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

    		$stmt = $conn->prepare("INSERT INTO `ServicioTerminado`(`idServicioTerminado`, `idServicioActivo`, `idAparcaCochesEntrega`, `FechaHoraEntrega`) 
			VALUES (0,'$idSa','$idAparcaCoches',CONVERT_TZ(NOW(),'UTC','America/Bogota'))");

			$stmt->execute();
		}

		catch(PDOException $e) {

			echo "Error al conectar a la base de datos <br>" . $e->getMessage();
		}

		$conn = null;

		echo "<script>window.history.back();</script>";
	}else{

		echo "<h1>Acceso denegado!</h1>";
		echo "<a href="/">Volver a inicio</a>";
	}
?>
