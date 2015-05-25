<?php

			function test_input($data) {
			  $data = trim($data);
			  $data = stripslashes($data);
			  $data = htmlspecialchars($data);
			  return $data;
			}

			$placa=$token="";

			if ($_SERVER["REQUEST_METHOD"] == "GET") {

			 	$placa = test_input($_GET["p"]);
			 	$token = test_input($_GET["t"]);

				$servername = "localhost";
				$username = "u511611292_root";
				$password = "t00rep";
				$dbname = "u511611292_ep";

				try {
				    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				    $stmt = $conn->prepare("SELECT *
										    FROM ServicioTerminado st 				    
										    INNER JOIN ServicioActivo sa ON sa.idServicioActivo = st.idServicioActivo
										    INNER JOIN Vehiculo v ON v.idVehiculo = sa.idVehiculo				    
										    WHERE sa.ServicioToken = $token
										    AND v.VehiculoPlaca = $placa"); 				    
				    
		    		$stmt->execute();

					$row = $stmt->fetch(PDO::FETCH_OBJ);	    		

					if($row){
						header('Content-type: application/json; charset=utf-8');
						$main = array("ServicioActivo"=>array(true));
						echo json_encode($main, JSON_UNESCAPED_UNICODE);
					}else{
				    
					    $stmt = $conn->prepare("SELECT sa.FechaHoraRecepcion, e.EstablecimientoNombre, ac.AparcaCochesNombre, ac.AparcaCochesPrimeroApellido, ac.AparcaCochesSegundoApellido, ac.AparcaCochesCedula, ac.AparcaCochesCelular 
											    FROM ServicioActivo sa 
											    INNER JOIN AparcaCoches ac ON ac.idAparcaCoches = sa.idAparcaCoches 
											    INNER JOIN Establecimiento e ON e.idEstablecimiento = sa.idEstablecimiento 
											    INNER JOIN Vehiculo v ON v.idVehiculo = sa.idVehiculo 
											    WHERE v.VehiculoPlaca = $placa AND sa.ServicioToken = $token"); 

			    		$stmt->execute();

						header('Content-type: application/json; charset=utf-8');					

						$row = $stmt->fetch(PDO::FETCH_OBJ);					

						$main = array("ServicioActivo"=>array($row));

						echo json_encode($main, JSON_UNESCAPED_UNICODE);
					}
				}
				catch(PDOException $e) {
					echo "Error al conectar a la base de datos <br>" . $e->getMessage();
				}
				$conn = null;
			}			
?>