<?php
	session_start();
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">				
		<title>AparcaCoches Registrados</title>
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
				$_SESSION['idEstablecimiento'] = $idEstablecimiento;

				$stmt = $conn->prepare("SELECT ac.idAparcaCoches, ac.AparcaCochesNombre, ac.AparcaCochesPrimeroApellido, ac.AparcaCochesSegundoApellido, ac.AparcaCochesCedula, ac.AparcaCochesCelular
										FROM AparcaCoches ac
										INNER JOIN AparcaCoches_Establecimiento ace
										WHERE ac.idAparcaCoches = ace.idAparcaCoches
										AND ace.idEstablecimiento = $idEstablecimiento
										"); 

				$stmt->execute();

				$ac = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
			echo "<th>ID</th>";
			echo "<th>Nombre</th>";
			echo "<th>Apellido</th>";
			echo "<th>Segundo apellido</th>";
			echo "<th>Cédula</th>";
			echo "<th>Celular</th>";
			echo "</tr>";  

			for ($i=0 ; $i < count($ac) ; $i++ ) { 
				array_push($ac[$i], "<a href='borrar/?id=" . $ac[$i]['idAparcaCoches'] . "'>Borrar</a>");
			}						

			foreach(new TableRows(new RecursiveArrayIterator($ac)) as $k=>$v) { 
				
				echo $v;

		    }

			echo "</table>";
						    		
			$conn = null;
		?>
	</body>
</html>