<?php
require_once 'autoload/autoload.php';
require_once '../../../lib/_autoload.php'; 
$as = new SimpleSAML_Auth_Simple('default-sp');
$as->requireAuth();
$attributes = $as->getAttributes();
if(isset($_SESSION["year"])){
    $year=$_SESSION["year"];
}else{
    $year=0;
}

$_SESSION['Correo'] = $attributes["irisMailMainAddress"][0];	
$Correo=Saml::is_admin($_SESSION['Correo']);
if($Correo==0){
    header("location:./Desactivado.php?opcion=1");	
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Calendario de exámenes - CSV">
    <meta name="author" content="Jc Mora Herrera">

    <title>Calendario de exámenes - CSV</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
	<link href="css/sticky-footer.css" rel="stylesheet">
	<script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
	<script src="./js/Cpanel.js"></script>
	<script src="./js/jquery.matchHeight.js"></script>
    <script src="./js/timeout.js"></script>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
</head>

	<?php

			/*if(empty($_SESSION["Correo"]) || !isset($_SESSION["Correo"])){ 
				header("Location: https://www.uhu.es/etsi/simplesml/www/Login_etsi.php");
				die();
			} 


			if(!empty($_SESSION["Correo"])){
				if(Saml::Check_for_samldata($_SESSION["Correo"])){*/
?>

<body>
	<script> its_session_ok();  </script>
	<!-- Header -->
	<nav class="navbar navbar-static-top bg-faded" style="background-color: #800000;">
		<a class="navbar-brand" href="./index.php?year=<?php echo $year; ?>"><font color="white"><< Ir al Calendario de exámenes </font></a>
	</nav>
	<!-- Cabecera -->
	<div class="row" align="center">
		<div class="col-md-8 col-md-offset-2" style="background-color:#4d88ff">
				<font color="#e6e6e6">
					<h3>Volcado de CSV</h3>
				</font>
		</div>
	</div>

	<div class="row" >
		<div class="col-md-12"><br></div>
			<div class="col-md-8 col-md-offset-2" style="background-color:#ff6666;" id="div_recarga">
				<p id="recarga">Cargando módulo de Caché....</p>
			</div>
		</div>
	</div>

	<div class="col-md-12">
		<br>
	</div>	
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default text-center">
				<div class="panel-heading" style="background-color:#b3b3b3">
					<h4> Importar desde CSV: </h4>
				</div>
				<div class="panel-body" style="background-color:#cccccc">
					<div class="row">
						<p> Seleccione el archivo CSV a subir, el archivo debe de contener en la primera linea lo siguiente : "id_cal;cod_asig;Dia;Mes;Hora;Convocatoria;year;Aula;Tipo" indicando los nombres de los campos, y a partir de esa linea, cada tupla con el Aula a modificar, con todos los campos separados por puntos y comas (;). <br><br>
							Por ejemplo:<br>
							id_cal;cod_asig;Dia;Mes;Hora;Convocatoria;year;Aula;Tipo<br>
							13;606010108;4;2;10:00;1;2020;test_aula_csv;0<br>
							13;999999999;4;2;10:00;1;2020;test_aula_csv;0<br>
						</p>
					</div>
					<div class="row">
						<form enctype="multipart/form-data" method="post" action="./CSV_cal.php" target="_blank">
							<input type="file" name="file" id="file" required>
							<input type="submit" value="Enviar" name="enviar">
						</form>
					</div>

				</div>	
			</div>
		</div>
	</div>		
		
	<div class="col-md-12"><br></div>		
	
	
	
	<div class="row">
		<div class="col-md-12"><br></div>
		<div class="col-md-12"><hr width="80%" style="border-top:1px solid"></div>
		<div class="col-md-12"><br></div>
		<!---	ajax	-->
		<div class="row"><p id="stats"></p></div>
	</div>
	
	
</body>

</html>

<?php 
    /*
	}else{ 
		header("Location: https://www.uhu.es/etsi/simplesml/www/app_gestion_cursos/Cpanel.php");
		die();
	}
}*/

?>