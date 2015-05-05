<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<div class='form animated flipInX'>
		<title>EliteParking</title>
		<link href="http://daneden.github.io/animate.css/animate.min.css" rel="stylesheet" media="screen">
		<link href="http://fonts.googleapis.com/css?family=Roboto:400,100,400italic,700italic,700" rel="stylesheet" media="screen">
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  		<link rel="icon" href="favicon.ico" type="image/x-icon">
		<img src="images/valet-parking-512.png" alt="EliteParking" height="100" width="100">	
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
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
		
		<form method="POST" action="<?php echo htmlspecialchars('/login/index.php'); $_SESSION['login'] = 1; ?>">

			<input placeholder='Usuario' type='text' name="user">
    		<input placeholder='Contraseña' type='password' name="pw">
    		<button class='animated infinite pulse'>Ingresar</button>

		</form>
			
		<script src="http://code.jquery.com/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>