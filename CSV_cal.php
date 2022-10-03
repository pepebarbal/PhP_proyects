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
	<script> its_session_ok();  </script>
	<!-- Header -->
	<nav class="navbar navbar-static-top bg-faded" style="background-color: #800000;">
		<a class="navbar-brand" href="./index.php?year=<?php echo $year; ?>"><font color="white"><< Ir al Calendario de exámenes </font></a>
	</nav>
	<!-- Cabecera -->
	<div class="row" align="center">
		<div class="col-md-8 col-md-offset-2" style="background-color:#4d88ff">
				<font color="#e6e6e6">
					<h3>Importar desde CSV: </h3>
				</font>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12"><br></div>	
	</div>
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8 text-center">	
	<div class="panel panel-default text-center">
				<div class="panel-heading" style="background-color:#b3b3b3">
					<h4> Resultado de Volcado de CSV </h4>
				</div>
				<div class="panel-body" style="background-color:#cccccc">
		<?php

					if (isset($_POST['enviar']))
					{
												
						$filename=$_FILES["file"]["name"];
						$info = new SplFileInfo($filename);
						$extension = pathinfo($info->getFilename(), PATHINFO_EXTENSION);

						if($extension == 'csv'){
							$filename = $_FILES['file']['tmp_name'];
							$handle = fopen($filename, "r");
							$contador=0;
							while( ($data = fgetcsv($handle, 10000, ";") ) !== FALSE ) //Fgetcsv - resource,length,delimiter
								{	$String=array();
									foreach($data as $valor){
										$String[]=$valor;
									}        

									$id_cal=htmlspecialchars($String[0], ENT_QUOTES);
									$cod_asig=htmlspecialchars($String[1], ENT_QUOTES);
									$Dia=htmlspecialchars($String[2], ENT_QUOTES);
									$Mes=htmlspecialchars($String[3], ENT_QUOTES);
									$Hora=htmlspecialchars($String[4], ENT_QUOTES);
									$Convocatoria=htmlspecialchars($String[5], ENT_QUOTES);
									$year=htmlspecialchars($String[6], ENT_QUOTES);
									$Aula=htmlspecialchars($String[7], ENT_QUOTES);
									$Tipo=htmlspecialchars($String[8], ENT_QUOTES);

									if($id_cal=="id_cal"){
											//do nothing -> es la primera fila
										}else{
											$con = Database::getConnection();

											$query = "select * from test_cal_examenes where (id_cal='".mysqli_real_escape_string($con,$id_cal)."') and (cod_asig='".mysqli_real_escape_string($con,$cod_asig)."') and
											(Dia='".mysqli_real_escape_string($con,$Dia)."') and (Mes='".mysqli_real_escape_string($con,$Mes)."') and (Hora='".mysqli_real_escape_string($con,$Hora)."')
											and (Convocatoria='".mysqli_real_escape_string($con,$Convocatoria)."') and (year='".mysqli_real_escape_string($con,$year)."') and (Tipo='".mysqli_real_escape_string($con,$Tipo)."')";
											$result=$con->query($query);
											if($result->num_rows!=0){

												$query = "update test_cal_examenes set Aula='".mysqli_real_escape_string($con,$Aula)."'  where (id_cal='".mysqli_real_escape_string($con,$id_cal)."') and (cod_asig='".mysqli_real_escape_string($con,$cod_asig)."') and
												(Dia='".mysqli_real_escape_string($con,$Dia)."') and (Mes='".mysqli_real_escape_string($con,$Mes)."') and (Hora='".mysqli_real_escape_string($con,$Hora)."')
												and (Convocatoria='".mysqli_real_escape_string($con,$Convocatoria)."') and (year='".mysqli_real_escape_string($con,$year)."') and (Tipo='".mysqli_real_escape_string($con,$Tipo)."')";
																					
												if($con->query($query) === TRUE){
														echo "<strong>Fila ".$contador." modificada con exito en la base de datos.<br></strong>";
												}else{
													echo '<color="red"> Error en la fila numero '.$contador.'<br></color>';
												}

											}else{
												echo '<span style="color:red"><strong> Error en la fila numero '.$contador.'<br> </strong></color>';
											}
										}
										
										unset($string);
										$contador++;
								}
						}
						//mysql_close($link);  

						exit;
					}

		?></div></div>
	</div>
	<div class="col-md-2"></div>
</div>
<div class="row">
	<div class="col-md-12">
		<br>
	</div>		
</div>
	
	
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