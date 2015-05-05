<?php 
	
	session_start(); 

	if ((isset($_SESSION['idAdministrador'])) && (isset($_SESSION['idEstablecimiento']))) {

		$idAc = $_GET['id'];
		$idEst = $_SESSION['idEstablecimiento'];


		try {

			include_once '../../../db_conf.php';

		    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	

    		$stmt = $conn->prepare("DELETE FROM `AparcaCoches_Establecimiento` 
    								WHERE `idAparcaCoches` = $idAc
    								AND `idEstablecimiento` = $idEst");

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
