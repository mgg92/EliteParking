<?php

			function test_input($data) {
			  $data = trim($data);
			  $data = stripslashes($data);
			  $data = htmlspecialchars($data);
			  return $data;
			}

			$placa = $calificacion = $observacion = $idServicioActivo = "";

			if ($_SERVER["REQUEST_METHOD"] == "GET") {

			 	$token = test_input($_GET["t"]);
			 	$calificacion = test_input($_GET["c"]);
			 	$observacion = test_input($_GET["o"]);

				$servername = "localhost";
				$username = "u511611292_root";
				$password = "t00rep";
				$dbname = "u511611292_ep";

				try {
				    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

					$stmt = $conn->prepare("SELECT idServicioActivo FROM ServicioActivo sa WHERE sa.ServicioToken = $token"); 
		    		$stmt->execute();

		    		$sa = $stmt->fetchAll(PDO::FETCH_ASSOC);

		    		$idServicioActivo = $sa[0]["idServicioActivo"];

					$stmt = $conn->prepare("INSERT INTO `Calificacion`(`idCalificacion`, `idServicioActivo`, `Calificacion`, `Observacion`, `FechaHora`) 
						VALUES (0,$idServicioActivo,$calificacion,$observacion, CONVERT_TZ(NOW(),'UTC','America/Bogota'))"); 
		    		$stmt->execute();		    		
				}
				catch(PDOException $e) {
					echo "Error al conectar a la base de datos <br>" . $e->getMessage();
				}
				$conn = null;
			}			
?>