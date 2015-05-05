<?php
	session_start();
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">				
		<title>Servicios Activos</title>
		<h2>EliteParking</h2>
		<a href="../../logout">Cerrar sesión</a><br><br>
		<a href="../">Volver al índice</a>
	</head>	
	<body>
		<?php

			try {
				
				include_once '../../db_conf.php';
			    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    			$idEstablecimiento = $_GET['id'];
				$idAparcaCoches = $_SESSION['idAparcaCoches'];

				$stmt = $conn->prepare("SELECT sa.idServicioActivo, sa.ServicioToken, v.VehiculoPlaca, v.VehiculoMarca, v.VehiculoLinea, v.VehiculoColor ,sa.FechaHoraRecepcion
										FROM ServicioActivo sa
										INNER JOIN Vehiculo v
										WHERE sa.idServicioActivo 
										NOT IN (SELECT idServicioActivo FROM ServicioTerminado st) 
										AND sa.idAparcaCoches = '$idAparcaCoches'
										AND sa.idEstablecimiento = '$idEstablecimiento'
										AND v.idVehiculo = sa.idVehiculo
										"); 

				$stmt->execute();

				$sa = $stmt->fetchAll(PDO::FETCH_ASSOC);					
			}

			catch(PDOException $e) {

				echo "Error al conectar a la base de datos <br>" . $e->getMessage();
			}

			class TableRows extends RecursiveIteratorIterator { 
						    
			    function __construct($it) { 
			        parent::__construct($it, self::LEAVES_ONLY); 
			    }

			    function current() {
			        return "<td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>";
			    }

			    function beginChildren() { 
			        echo "<tr>"; 
			    }

			    function endChildren() { 
			        echo "</tr>" . "\n";
			    } 
			}

			echo "<table style='border: solid 1px black;'>";
			echo "<tr>";

			echo "<th>idServicio</th>";
			echo "<th>TokenServicio</th>";
			echo "<th>PlacaVehículo</th>";
			echo "<th>MarcaVehículo</th>";
			echo "<th>LíneaVehículo</th>";
			echo "<th>ColorVehículo</th>";
			echo "<th>FechaHoraRecepción</th>";
			echo "<th>Alertas</th>";
			echo "<th>Acción</th>";			

			echo "</tr>";  

			for ($i=0 ; $i < count($sa) ; $i++ ) { 
				array_push($sa[$i], "<a href='chat/?id=" . $sa[$i]['idServicioActivo'] . "'>[" . $chat[$i]['idChat'] . "]</a>");
				array_push($sa[$i], "<a href='terminar/?id=" . $sa[$i]['idServicioActivo'] . "'>Terminar Servicio</a>");				
			}						

			foreach(new TableRows(new RecursiveArrayIterator($sa)) as $k=>$v) { 
				
				echo $v;

		    }

			echo "</table>";
						    		
			$conn = null;
		?>
	</body>
</html>