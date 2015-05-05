<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>EliteParking</title>
		<img src="images/valet-parking-512.png" alt="EliteParking" height="100" width="100">
		<h2>EliteParking</h2>
	</head>

	<body>

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
		
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			Usuario: <br> <input type="text" name="user"><br>
			Contraseña: <br> <input type="password" name="pw"><br><br>
			<input type="submit" value="Ingresar">
		</form>

		<?php

			session_start();

			$s = "&nbsp&nbsp";			

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

			$user = $pw = "";

			if ($_SERVER["REQUEST_METHOD"] == "POST") {

			 	$user = test_input($_POST["user"]);
			  	$pw = test_input($_POST["pw"]);

				$servername = "localhost";
				$username = "u511611292_root";
				$password = "t00rep";
				$dbname = "u511611292_ep";

				try {

				    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
				    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				     				 
				}
				catch(PDOException $e){

					echo "Error al conectar a la base de datos <br>" . $e->getMessage();
				}

				
				$stmt = $conn->prepare("SELECT rootNombre FROM Root WHERE rootUser =  '" . $user ."' AND rootContrasena = '" . $pw ."'");				

				$stmt->execute();
	    		
	    		$root = $stmt->fetchAll(PDO::FETCH_ASSOC);		

	    		if (count($root)) { 
	    			
	    			echo "<h3> Bienvenido! <br>" . $root[0]['rootNombre'] . "</h3><br><br><br>";

	    			$stmt = $conn->prepare("SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = '$dbname'");

	    			$stmt->execute();

	    			$tablas = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    			for ($i=0; $i < count($tablas); $i++) { 

	    				$tblname = $tablas[$i]['TABLE_NAME'];

	    				if ($tblname != "Root"){

		    				echo "<h3> Tabla $tblname </h3>";

		    				$stmt = $conn->prepare("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
		    				WHERE TABLE_SCHEMA = '$dbname' 
		    				AND TABLE_NAME = '$tblname'");

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

		    	} else {

		    		$stmt = $conn->prepare("SELECT idAdministrador, administradorNombre, administradorPrimerApellido FROM Administrador WHERE administradorContrasena = '" . $user . "' AND administradorContrasena = '" . $pw . "'"); 
	    			$stmt->execute();
	    		
	    			$admin = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    			if(count($admin)) {

	    				$idAdmin = $admin[0]['idAdministrador'];
	    				echo "<h3> Bienvenido! <br>" . $admin[0]['administradorNombre'] . " " . $admin[0]['administradorPrimerApellido'] . "</h3><br><br><br>";

	    				$stmt = $conn->prepare("SELECT * FROM Establecimiento e INNER JOIN Administrador_Establecimiento ae ON e.idEstablecimiento = ae.idEstablecimiento WHERE ae.idAdministrador = $idAdmin"); 
	    				$stmt->execute();

	    				$est = $stmt->fetchAll(PDO::FETCH_ASSOC);

	    				$stmt = $conn->prepare("SELECT * FROM AparcaCoches ac INNER JOIN AparcaCoches_Establecimiento ace ON ac.idAparcacoches = ace.idAparcacoches WHERE ace.idEstablecimiento = 1"); 
	    				$stmt->execute();

	    				$apc = $stmt->fetchAll(PDO::FETCH_ASSOC);
						
						echo "Establecimientos que usted administra <br>";

						for ($i=0; $i < count($est); $i++) { 
							echo $est[$i]['EstablecimientoNombre'];
							echo $s . "<a href='verServicios.php' target='_blank' onclick='prueba()'>Ver servicios</a><script>";
							echo "<br>";
						}

	    			} else {

						echo "<h2>Acceso denegado!</h2>";
	    			}
	    		}
				$conn = null;
			}			
		?>
		<script>
			function prueba(){
				alert("<?php a(); ?>")
			}
		</script>
	</body>
</html>