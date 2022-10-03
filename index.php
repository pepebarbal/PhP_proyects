<?php
require_once 'autoload/autoload.php';
require_once '../../../lib/_autoload.php'; 
$as = new SimpleSAML_Auth_Simple('default-sp');
$as->requireAuth();
$attributes = $as->getAttributes();
$_SESSION['Correo'] = $attributes["irisMailMainAddress"][0];	
$Correo=Saml::is_admin($_SESSION['Correo']);
if($Correo==0){
    header("location:./Desactivado.php?opcion=1");	
}
unset($_SESSION["convocatoria"]);
//$_GET["year"]=2016;
if(is_numeric($_GET["year"])){
    $_SESSION["year"]=$_GET["year"];
}else{
    $_SESSION["year"]=0;
}
  
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendario de Exámenes</title>
    <link href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/sticky-footer.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>

    <script
            src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script
            src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
            crossorigin="anonymous"></script>
    <script src="./js/calendario.js"></script>
    <script src="./js/eventos.js"></script>
    <script src="./js/timeout.js"></script>
</head>
<body>
<script> its_session_ok();  </script>

<!-- Header -->
<nav class="navbar navbar-static-top bg-faded" style="background-color: #800000;">
    <a class="navbar-brand" href="../Cpanel.php"><font color="white"><< Ir al Panel de Control </font></a>	 
</nav>
<!-- Inicio Horario-->
<div class="row">
    <div class="col-md-8 col-md-offset-2" style="background-color:#4d88ff">
        <center><h3><font color="#e6e6e6">Calendario de Exámenes</h3></center>
        </font></div>
</div>
<div class="col-md-12"><br><br></div>
		
		
		<div class="col-md-8 col-md-offset-2" style="background-color:#ff6666;" id="div_recarga">
			<p id="recarga">Cargando módulo de Caché....</p>
		</div>

<div class="col-md-12"><br><br></div>
<?php /*** HT00 - Seleccionar titulaciones ***/

$Titulacion = Database::get_titulaciones();
$cals= Database::consultaCals($_SESSION["year"]);
?>

<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default" align="center">
        <div class="panel-heading" style="background-color:#b3b3b3">
            <h4>Seleccione Titulación y cuatrimestre</h4>
        </div>

        <div class="panel-body" style="background-color:#cccccc">
        <div class="row">

          <div class="col-md-6" align="left">
            <label>Calendario: </label>
            <select name="Calendario_academico" id="calendario">
                <option value="nulo" selected/>
                -- Seleccione un calendario (año <?php echo $_SESSION["year"]; ?>) -- </option>

                <?php
                foreach($cals as $calendario) {
                    echo '<option value="' .$calendario["id"] . '"';
                    echo '>' . $calendario["nombre"] . '</option>';

                }
                ?>

            </select>
          </div>

          <div class="col-md-6" align="left">
            <label>Convocatoria: </label>
              <select name="Cuatrimestre" id="Cuatr">
                  <option value="1" selected> -- Febrero / Primera Convocatoria -- </option>
                  <option value="2" > -- Junio / Primera Convocatoria -- </option>
                  <option value="3" > -- Segunda Convocatoria -- </option>
                  <option value="4" > -- Noviembre / Convocatoria Extraordinaria -- </option>
                  <option value="5" > -- Diciembre / Tercera Convocatoria -- </option>
                  <option value="6" > -- Febrero / Primera Convocatoria - Incidencias -- </option>
                  <option value="7" > -- Junio / Primera Convocatoria - Incidencias  -- </option>
                  <option value="8" > -- Segunda Convocatoria - Incidencias  -- </option>
              </select>
          </div>

        </div>

        <div class="row">

          <div class="col-md-6" align="left">
            <label>Titulación: </label>
            <select name="Titulacion" id="tit1">
                <option value="nulo" selected/>
                -- Seleccione una Titulación -- </option>
                <?php foreach($Titulacion as $tit) {

                    echo '<option value="' . $tit["id"] . '"';
                    if (isset($_GET["tit"])) {
                        if (explode('-', $_GET["tit"])[0] == $tit["id"]) {
                            echo " selected";
                        }
                    }
                    echo '>' . $tit["nombre"] . '</option>';

                }
                ?>
            </select>
          </div>

          <div class="col-md-6" align="left">
            <label>Curso: </label>
              <select name="Curso_1" id="Curso_1">
                  <option value="1" selected> Primero </option>
                  <option value="2" > Segundo </option>
                  <option value="3" > Tercero </option>
                  <option value="4" > Cuarto </option>
                  <option value="5" > Quinto </option>
              </select>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<div class="row">
    <div class="col-md-12"><br></div>
</div>

<div class=row>
    <div class="col-md-8 col-md-offset-2" align="center">
    <!-- Botones que llevar a la Aplicación de asociar asignaturas y cargan la tabla de dias respectivamente -->
        <button id="Asoc_asig" type="button" class="btn btn-info" style="width:60%">Aplicación Asociación de asignaturas</button>
        <br><br>
        <button id="Subida_csv" type="button" class="btn btn-info" style="width:60%">Subida Aulas CSV</button>
        <br><br>
        <button id="del_exa" type="button" class="btn btn-danger" style="width:60%">Eliminar exámenes del <strong>calendario y convocatoria</strong> seleccionados</button>
        <br><br>
        <button id="cargarTablas" type="button" class="btn btn-success" style="width:60%">Cargar datos</button>
    </div>
</div>

<div class="row">
    <div class="col-md-12"><br><br></div>
</div>

<div class="row">
    <div class="col-md-12" id="del_exam_cal_id"></div>
    <div class="col-md-12" id="tabla_dias"></div>
</div>


<div class="row">
    <div class="col-md-12"><br><br></div>
</div>

<footer class="footer">
    <div class="container"><!-- Contenido del pie de Página--></div>
</footer>
</body>
</html>
