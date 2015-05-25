<?php 
	
	session_start(); 

	if (isset($_SESSION['idAparcaCoches'])) {

		$idSa = $_GET['id'];
		$idAparcaCoches = $_SESSION['idAparcaCoches'];
		$idEstablecimiento = $_SESSION['idEstablecimiento'];


		include_once '../../../db_conf.php';

		try {

		    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		    $stmt = $conn->prepare("UPDATE  Chat c 
									INNER JOIN ServicioActivo sa 
									ON  c.idServicioActivo = sa.ServicioToken
									SET  Estado =  '1'
									WHERE sa.idServicioActivo = '$idSa' 
		    						;");

		    $stmt->execute();

    		$stmt = $conn->prepare("SELECT *
										FROM Chat c
										INNER JOIN ServicioActivo sa
										INNER JOIN Vehiculo v
										ON sa.idVehiculo = v.idVehiculo
										WHERE sa.idServicioActivo 
										NOT IN (SELECT idServicioActivo FROM ServicioTerminado st) 
										AND sa.idAparcaCoches = '$idAparcaCoches'
										AND sa.idEstablecimiento = '$idEstablecimiento'
										AND c.idServicioActivo = sa.ServicioToken
										"); 

			$stmt->execute();

			$chat = $stmt->fetchAll(PDO::FETCH_ASSOC);

			for ($i=0; $i < count($chat); $i++) { 
				echo "<br>" . $chat[$i]['Mensaje'];				
			}
			
		}

		catch(PDOException $e) {

			echo "Error al conectar a la base de datos <br>" . $e->getMessage();
		}

		$conn = null;

	}else{

		echo "<h1>Acceso denegado!</h1>";
		echo "<a href="/">Volver a inicio</a>";
	}
?>
