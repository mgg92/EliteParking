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
			 	$s = test_input($_GET["s"]);

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

					$stmt = $conn->prepare("INSERT INTO  `u511611292_ep`.`Chat` (
																				`idChat` ,
																				`idServicioActivo` ,
																				`Mensaje` ,
																				`FechaHora` ,
																				`Estado`
																				)
																				VALUES ('0','$token','$s', CONVERT_TZ(NOW(),'UTC','America/Bogota'), '0')"); 
		    		$stmt->execute();		    		
				}
				catch(PDOException $e) {
					echo "Error al conectar a la base de datos <br>" . $e->getMessage();
				}
				$conn = null;
			}			
?>