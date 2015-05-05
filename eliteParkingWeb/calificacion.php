<?php

			function test_input($data) {
			  $data = trim($data);
			  $data = stripslashes($data);
			  $data = htmlspecialchars($data);
			  return $data;
			}

			$placa = $calificacion = $observacion = $idVehiculo = "";

			if ($_SERVER["REQUEST_METHOD"] == "GET") {

			 	$placa = test_input($_GET["placa"]);
			 	$calificacion = test_input($_GET["calificacion"]);
			 	$observacion = test_input($_GET["observacion"]);

				$servername = "localhost";
				$username = "u511611292_root";
				$password = "t00rep";
				$dbname = "u511611292_ep";

				try {
				    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					$stmt = $conn->prepare("SELECT idVehiculo FROM Vehiculo WHERE VehiculoPlaca = $placa"); 
		    		$stmt->execute();

		    		$sa = $stmt->fetchAll(PDO::FETCH_ASSOC);

		    		$idVehiculo = $sa[0]["idVehiculo"];

					$stmt = $conn->prepare("INSERT INTO `Calificacion`(`idCalificacion`, `idVehiculo`, `Calificacion`, `Observacion`) 
						VALUES (0,$idVehiculo,$calificacion,$observacion)"); 
		    		$stmt->execute();		    		
				}
				catch(PDOException $e) {
					echo "Error al conectar a la base de datos <br>" . $e->getMessage();
				}
				$conn = null;
			}			
?>