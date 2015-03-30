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


					$stmt = $conn->prepare("SELECT * FROM ServicioActivo sa INNER JOIN Vehiculo v ON v.idVehiculo = sa.idVehiculo WHERE v.VehiculoPlaca = $placa"); 
		    		$stmt->execute();

		    		$sa = $stmt->fetchAll(PDO::FETCH_ASSOC);

		    		$idVehiculo = $sa[0]["idVehiculo"];
		    		$idEstablecimiento = $sa[0]["idEstablecimiento"];
		    		$idAparcaCoches = $sa[0]["idAparcaCoches"];

		    		$stmt = $conn->prepare("SELECT * FROM AparcaCoches WHERE idAparcaCoches = $idAparcaCoches"); 
		    		$stmt->execute();
					
					$row = $stmt->fetch(PDO::FETCH_OBJ);
					$main = array('AparcaCoches'=>array($row));
					echo json_encode($main);				

		    		/*$stmt = $conn->prepare("SELECT EstablecimientoNombre FROM Establecimiento WHERE idEstablecimiento = $idEstablecimiento"); 
		    		$stmt->execute();
					
					$row = $stmt->fetch(PDO::FETCH_OBJ);
					$main = array('Establecimiento'=>array($row));
					echo json_encode($main);*/

				}
				catch(PDOException $e) {
					echo "Error al conectar a la base de datos <br>" . $e->getMessage();
				}
				$conn = null;
			}			
?>