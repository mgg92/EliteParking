<html>
	<head>
		<meta charset="UTF-8">
		<title>Registro de Servicio</title>
		<h2>EliteParking</h2>
	</head>

	<body>

		<?php

				function test_input($data) {
					  $data = trim($data);
					  $data = stripslashes($data);
					  $data = htmlspecialchars($data);
					  return $data;
				}


				$servername = "localhost";
				$username = "u511611292_root";
				$password = "t00rep";
				$dbname = "u511611292_ep";

				try {

					$placa = $marca = $color = $idVehiculo = $idEstablecimiento = $idAparcaCoches = $fechaHora = "";

					if ($_SERVER["REQUEST_METHOD"] == "POST") {

						$placa = test_input($_POST["placa"]);			  								
						$marca = test_input($_POST["marca"]);						
						$color = test_input($_POST["color"]);
						$idAparcacoches = test_input($_POST["idAparcacoches"]);
						$idEstablecimiento = test_input($_POST["idEstablecimiento"]);
						$fechaHora = test_input($_POST["fechaHora"]);

						if(empty($placa)) {
							echo "Ingrese placa <br>";
						}


						if(empty($marca)) {
							echo "Ingrese marca <br>";							
						}


						if(empty($color)) {
							echo "Ingrese color <br>";							
						} else {

						    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
						    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

							$stmt = $conn->prepare("INSERT INTO Vehiculo VALUES ('0', '$placa', '$marca', '$color')");
							$stmt->execute();

							echo "Datos recibidos <br>\n Su numero de servicio es: ";
							$token = rand();
							echo $token;


							$stmt = $conn->prepare("SELECT idVehiculo FROM Vehiculo WHERE VehiculoPlaca = '$placa'");

							$stmt->execute();

							$tmp = $stmt->fetchAll(PDO::FETCH_ASSOC);

							$idVehiculo = $tmp[0]['idVehiculo'];


							$stmt = $conn->prepare("INSERT INTO `ServicioActivo` (`idServicioActivo`, `ServicioToken`, `idVehiculo`, `idEstablecimiento`, `idAparcaCoches`, `FechaHoraRecepcion`) 
								VALUES ('0', '$token', '$idVehiculo', $idEstablecimiento, $idAparcaCoches, ?)");

							$stmt->execute();

						}
					}
				}
					
				catch(PDOException $e){

					echo "<br> Error al conectar a la base de datos <br>" . $e->getMessage();
				}

				$conn = null;
			?>


		<style>
			form {
			    width: 300px ;
			    margin: 0 auto;
			    border: 3px solid #ccc;
			    padding: 10px;
			}
			label { 
			    text-align:right; 
			    width:100px; 
			}
			input { 
			    margin-left: 10px; 
			}
			label.check, label.radio { 
			    text-align:left; 
			}
		</style>
		
		<form name= "Registro de Servicio" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			Placa: <br> <input type="text" name="placa"><br><br>
			Vehiculo: <br> <input type="text" name="marca"><br><br>
			Color: <br> <input type="text" name="color"><br><br>
			idAparcaCoches: <br> <input type="number" name="idAparcacoches"><br><br>
			idEstablecimiento: <br> <input type="number" name="idEstablecimiento"><br><br>
			FechaHora: <br><input type="datetime-local" name="fechaHora"><br><br>
			<input type="submit" value="Ingresar" onClick="clearform();">
		</form>

	</body>
</html>