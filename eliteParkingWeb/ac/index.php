<?php
session_start();
?>

<html>
	
	<?php if (isset($_SESSION['idAparcaCoches'])) { ?>

		<head>

			<style>

			body {
		  		background: #fafafa url(http://jackrugile.com/images/misc/noise-diagonal.png);
		  		color: #444;
		  		font: 100%/30px 'Helvetica Neue', helvetica, arial, sans-serif;
		  		text-shadow: 0 1px 0 #fff;
			}

			table {
			  background: #f5f5f5;
			  border-collapse: separate;
			  box-shadow: inset 0 1px 0 #fff;
			  font-size: 12px;
			  line-height: 24px;
			  text-align: left;
			  width: 800px;
			}


			</style>


			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">				
			<title>Índice de AparcaCoches</title>
			<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
			<img src="../images/valet-parking-512.png" alt="EliteParking" height="100" width="100">
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  			<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
			<h2>EliteParking</h2>
			<h2>Índice de AparcaCoches</h2>
			<a href="../logout">Cerrar sesión</a><br><br>			
		</head>		

		<body>

			<script src="http://code.jquery.com/jquery.js"></script>
			<script src="js/bootstrap.min.js"></script>	

			<?php

				include_once '../db_conf.php';

				try {

				    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
				}

				catch(PDOException $e) {

					echo "Error al conectar a la base de datos <br>" . $e->getMessage();
				}

				$idAparcaCoches = $_SESSION['idAparcaCoches'];

				$stmt = $conn->prepare("SELECT e.idEstablecimiento, e.EstablecimientoNombre 
										FROM Establecimiento e 
										INNER JOIN AparcaCoches_Establecimiento ace 
										ON e.idEstablecimiento = ace.idEstablecimiento 
										WHERE ace.idAparcaCoches = '$idAparcaCoches'"); 

				$stmt->execute();

				$est = $stmt->fetchAll(PDO::FETCH_ASSOC);

				$s = "&nbsp;&nbsp;&nbsp;&nbsp;";			

				echo "<h2> ¡Bienvenido " . $_SESSION['AparcaCochesNombre'] . "! </h2>";

				echo "<h3> Establecimientos: </h3>";

				echo "<table>";				
				
				for ($i=0; $i < count($est); $i++) {

					$idEstablecimiento = $est[$i]['idEstablecimiento'];

					echo "<tr>" . $est[$i]['EstablecimientoNombre'];

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

					$numChats = $stmt->fetchAll(PDO::FETCH_ASSOC);
					$numChats = $numChats[0]['COUNT(idChat)'];

					$stmt = $conn->prepare("SELECT c.idChat, c.Estado 
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

					
					
					if($numChats){
						
						$numChatsPendientes=0;						
						
						for ($j=0; $j < $numChats; $j++) {

							$estado = $chat[$j]['Estado'];

							if(!$estado){
								$numChatsPendientes++;
							}							
						}
						
						if($numChatsPendientes == 1){
							echo "<br><font color='red'><strong>Tienes un mensaje pendiente!</strong></font>";
						}
						
						if($numChatsPendientes > 1){
							echo "<br><strong>Tienes <font color='red'>" . $numChatsPendientes . "</font> mensajes pendientes!</strong>";	
						}
						
					}

					$estado = $chat[0]['Estado'];

					echo "<center><a href='servicios/?id=" . $idEstablecimiento . "'>Ver servicios</a></center>" ;
					echo "<center><a href='nuevo/?id=" . $idEstablecimiento . "'>Nuevo servicio</a></center></tr>";				
				}

				echo "</table>";
			?>
		</body>

	<?php }
	 else 
	 	{ ?> 
		<h1>Acceso denegado!</h1>
		<a href="/">Volver a inicio</a>
	<?php } ?>
</html>