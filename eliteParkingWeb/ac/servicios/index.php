<?php
	session_start();
?>

<html>
	<head>

		<style>

		body {
  		background: #fafafa url(http://jackrugile.com/images/misc/noise-diagonal.png);
  		color: #444;
  		font: 100%/30px 'Helvetica Neue', helvetica, arial, sans-serif;
  		text-shadow: 0 1px 0 #fff;
		}

		strong {
  		font-weight: bold;
		}

		em {
		  font-style: italic;
		}

		table {
		  background: #f5f5f5;
		  border-collapse: separate;
		  box-shadow: inset 0 1px 0 #fff;
		  font-size: 12px;
		  line-height: 24px;
		  margin: 30px auto;
		  text-align: left;
		  width: 800px;
		  min-width: 300px;
		}

		th {
		  background: url(http://jackrugile.com/images/misc/noise-diagonal.png), linear-gradient(#777, #444);
		  border-left: 1px solid #555;
		  border-right: 1px solid #777;
		  border-top: 1px solid #555;
		  border-bottom: 1px solid #333;
		  box-shadow: inset 0 1px 0 #999;
		  color: #fff;
		  font-weight: bold;
		  padding: 10px 15px;
		  position: relative;
		  text-shadow: 0 1px 0 #000;
		}

		th:after {
		  background: linear-gradient(rgba(255, 255, 255, 0), rgba(255, 255, 255, .08));
		  content: '';
		  display: block;
		  height: 25%;
		  left: 0;
		  margin: 1px 0 0 0;
		  position: absolute;
		  top: 25%;
		  width: 100%;
		}

		th:first-child {
		  border-left: 1px solid #777;
		  box-shadow: inset 1px 1px 0 #999;
		}

		th:last-child {
		  box-shadow: inset -1px 1px 0 #999;
		}

		td {
		  border-right: 1px solid #fff;
		  border-left: 1px solid #e8e8e8;
		  border-top: 1px solid #fff;
		  border-bottom: 1px solid #e8e8e8;
		  padding: 10px 15px;
		  position: relative;
		  transition: all 300ms;
		}

		td:first-child {
		  box-shadow: inset 1px 0 0 #fff;
		}

		td:last-child {
		  border-right: 1px solid #e8e8e8;
		  box-shadow: inset -1px 0 0 #fff;
		}

		tr {
		  background: url(http://jackrugile.com/images/misc/noise-diagonal.png);
		}

		tr:nth-child(odd) td {
		  background: #f1f1f1 url(http://jackrugile.com/images/misc/noise-diagonal.png);
		}

		tr:last-of-type td {
		  box-shadow: inset 0 -1px 0 #fff;
		}

		tr:last-of-type td:first-child {
		  box-shadow: inset 1px -1px 0 #fff;
		}

		tr:last-of-type td:last-child {
		  box-shadow: inset -1px -1px 0 #fff;
		}

		table:hover td {
		  color: transparent;
		  text-shadow: 0 0 3px #aaa;
		}

		table:hover tr:hover td {
		  color: #444;
			text-shadow: 0 1px 0 #fff;
		}

		h2 {
			font-size: 15;
			text-align: center;
			font-family: arial;
		}

		h3 {
			font-size: 10;
			text-align: center;
			font-family: arial;
		}

		</style>

		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">				
		<title>Servicios Activos</title>
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
	  	<link rel="icon" href="../favicon.ico" type="image/x-icon">
		<div style="text-align: center"><img src="../../images/valet-parking-512.png" alt="EliteParking" height="100" width="100"></div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>  			<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<h1>EliteParking</h1>
		<a href="../../logout">Cerrar sesión</a><br><br>
		<a href="../">Volver al índice</a>
	</head>	
	<body>

		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>	

		<?php

			try {
				
				include_once '../../db_conf.php';
			    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
			    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    			$idEstablecimiento = $_GET['id'];
    			$_SESSION['idEstablecimiento'] = $idEstablecimiento;
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

				$stmt = $conn->prepare("SELECT COUNT(idChat)
										FROM Chat c
										INNER JOIN ServicioActivo sa
										WHERE sa.idServicioActivo 
										NOT IN (SELECT idServicioActivo FROM ServicioTerminado st) 
										AND sa.idAparcaCoches = '$idAparcaCoches'
										AND sa.idEstablecimiento = '$idEstablecimiento'
										AND c.idServicioActivo = sa.ServicioToken
										"); 

				$stmt->execute();

				$chat = $stmt->fetchAll(PDO::FETCH_ASSOC);					
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

			echo "<table>";
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
				array_push($sa[$i], "<a href='chat/?id=" . $sa[$i]['idServicioActivo'] . "'>[" . $chat[$i]['COUNT(idChat)'] . "]</a>");
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