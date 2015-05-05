<?php
	session_start();
?>

<!DOCTYPE html>
<html>

	<?php if (isset($_SESSION["rootNombre"])) { ?>

		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">		
			<title>EliteParking | Vista de Administrador</title>
			<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
			<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
	  		<link rel="icon" href="../favicon.ico" type="image/x-icon">
			<img src="../images/valet-parking-512.png" alt="EliteParking" height="100" width="100">
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
			<h2>EliteParking</h2>		
		</head>
		
		<body>
			<script src="http://code.jquery.com/jquery.js"></script>
			<script src="js/bootstrap.min.js"></script>

			<?php
						
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

				function test_input($data) {
				 
				  $data = trim($data);
				  $data = stripslashes($data);
				  $data = htmlspecialchars($data);
				  
				  return $data;
				}

			  	include_once '../db_conf.php';

				try {

				    $conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
				}

				catch(PDOException $e) {

					echo "Error al conectar a la base de datos <br>" . $e->getMessage();
				}

				echo "<h3> Bienvenido! <br>" . $_SESSION['rootNombre']. "</h3>";
				echo "<a href='../logout'>Cerrar sesión</a>";

				$stmt = $conn->prepare("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '" . DATABASE . "'");
				$stmt->execute();

				$tablas = $stmt->fetchAll(PDO::FETCH_ASSOC);

				for ($i=0; $i < count($tablas); $i++) { 

					$tblname = $tablas[$i]['TABLE_NAME'];

					if ($tblname != "Root"){

						echo "<h3> Tabla $tblname </h3>";

						$stmt = $conn->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
												WHERE TABLE_SCHEMA = '" . DATABASE . "' AND TABLE_NAME = '$tblname'");

						$stmt->execute();

						$col = $stmt->fetchAll(PDO::FETCH_ASSOC);

						echo "<table style='border: solid 1px black;'>";
						echo "<tr>";

						for ($j=0; $j < count($col); $j++) { 

							echo "<th>" . $col[$j]['COLUMN_NAME'] . "</th>";
						}

						echo "</tr>";

						$stmt = $conn->prepare("SELECT * FROM $tblname"); 

						$stmt->execute();	    			

			    		foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll(PDO::FETCH_ASSOC))) as $k=>$v) { 

			        		echo $v;    
					    }

						echo "</table>";
					}		    		
				}
				$conn = null;
		?>				
	</body>

	<?php } else {  ?>

	<h1> Acceso denegado! </h1>
	<a href="/">Volver a inicio</a>
	
	<?php } ?>
</html>