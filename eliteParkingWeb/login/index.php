<?php

	session_start();

	if (($_SERVER['REQUEST_METHOD'] = "POST") && ($_SESSION['login'] = 1)) {

		$user = $_POST['user'];
		$pw = $_POST['pw'];

		include_once '../db_conf.php';

		try {

			$conn = new PDO("mysql:host=" . HOST . ";dbname=" . DATABASE, USER, PASSWORD);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
		}

		catch(PDOException $e) {

			echo "Error al conectar a la base de datos <br>" . $e->getMessage();
		}

		$stmt = $conn->prepare("SELECT  `rootNombre` 
								FROM  `Root` 
								WHERE  `rootUser` =  '$user'
								AND  `rootContrasena` =  '$pw'"); 
		$stmt->execute();
		
		$root = $stmt->fetchAll(PDO::FETCH_ASSOC);

		if(count($root)) {
			
			$_SESSION['rootNombre'] = $root[0]['rootNombre'];
			header("Location: ../config");
			die();		
		}
		
		$stmt = $conn->prepare("SELECT `idAparcaCoches`, `AparcaCochesNombre`
								FROM `AparcaCoches` 
								WHERE `AparcaCochesContrasena` = '$pw'"); 
		$stmt->execute();
		
		$ac = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($ac)) {

			$_SESSION['idAparcaCoches'] = $ac[0]['idAparcaCoches'];
			$_SESSION['AparcaCochesNombre'] = $ac[0]['AparcaCochesNombre'];
			header("Location: ../ac");
			die();	
		}

		$stmt = $conn->prepare("SELECT `idAdministrador`, `administradorNombre` 
								FROM `Administrador` 
								WHERE `administradorContrasena` = '$pw'"); 
		$stmt->execute();
		
		$adm = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		if(count($adm)) {

			$_SESSION['idAdministrador'] = $adm[0]['idAdministrador'];
			$_SESSION['AdministradorNombre'] = $adm[0]['administradorNombre'];
			header("Location: ../admin");
			die();	
		}
	}
	session_unset();
	session_destroy();
	echo '<html><head><meta charset="utf-8"></head><body><script type="text/javascript">'; 
	echo 'alert("Usuario y/o contraseña incorrecta!"); history.back();'; 
	echo '</script></body></html>';		
?>