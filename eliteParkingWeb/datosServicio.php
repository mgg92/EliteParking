<?php

			function test_input($data) {
			  $data = trim($data);
			  $data = stripslashes($data);
			  $data = htmlspecialchars($data);
			  return $data;
			}

			$placa="";

			if ($_SERVER["REQUEST_METHOD"] == "GET") {

			 	$placa = test_input($_GET["placa"]);

				$servername = "localhost";
				$username = "u511611292_root";
				$password = "t00rep";
				$dbname = "u511611292_ep";

				try {
				    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				    $stmt = $conn->prepare("SELECT FechaHoraRecepcion FROM ServicioActivo sa INNER JOIN Vehiculo v ON v.idVehiculo = sa.idVehiculo WHERE v.VehiculoPlaca = $placa"); 
		    		$stmt->execute();

					header('Content-type: application/json; charset=utf-8');					

					$row = $stmt->fetch(PDO::FETCH_OBJ);
					$main = array('ServicioActivo'=>array($row));
					echo json_encode($main);
				}
				catch(PDOException $e) {
					echo "Error al conectar a la base de datos <br>" . $e->getMessage();
				}
				$conn = null;
			}			
?>