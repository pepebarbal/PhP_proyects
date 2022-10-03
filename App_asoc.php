<?php
require_once 'autoload/autoload.php';
require_once '../../../lib/_autoload.php'; 
$as = new SimpleSAML_Auth_Simple('default-sp');
$as->requireAuth();
$attributes = $as->getAttributes();
$_SESSION['Correo']  = $attributes["irisMailMainAddress"][0];

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Calendario Académico</title>
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
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-static-top bg-faded" style="background-color: #800000;"></nav>
<!-- Inicio Horario-->
<div class="row">
    <div class="col-md-8 col-md-offset-2" style="background-color:#4d88ff">
        <center><h3><font color="#e6e6e6">Asociación de Asignaturas</h3></center>
        </font></div>
</div>
<div class="row">
    <div class="col-md-12"><br><br></div>
</div>

<?php /*** HT00 - Seleccionar titulaciones ***/

$is_valid=Saml::is_admin($_SESSION['Correo']);
if($is_valid==1){

$Titulacion = Database::get_titulaciones();

/* Vemos si esta en $_POST la varoable que contiene las asignaturas a borrar*/

if(isset($_POST["par"])){
    Comprobar_titulaciones::Borrar_asignaturas_asociadas($_POST["par"]);
}


?>

<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default" align="center">
        <div class="panel-heading" style="background-color:#b3b3b3">
            <h4>Titulo panel</h4>
        </div>

        <div class="panel-body" style="background-color:#cccccc">
        <div class="row">
          <div class="col-md-12">
            <label>Titulación 1: </label>
            <select name="Titulacion_1" id="tit_asoc_1">
                <option value="nulo" selected disabled/>
                -- Seleccione Titulación 1 -- </option>
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
        </div>

        <div class="row">
          <div class="col-md-12">
            <label>Titulación 2: </label>
            <select name="Titulacion_2" id="tit_asoc_2">
                <option value="nulo" selected disabled/>
                -- Seleccione Titulación 2 -- </option>
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
        </div>

      </div>
    </div>
  </div>
  <!-- Carga de asignaturas -->
</div>
<div class="row">
    <div class="col-md-4 col-md-offset-2">
        <div class="panel panel-default" align="center">
            <div class="panel-heading" style="background-color:#b3b3b3">
                <h4>Asignatura 1</h4>
            </div>
            <div class="panel-body" style="background-color:#cccccc">
              <div class="row">
                <div class="col-md-12" id="tabla_asig_1"></div>
              </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
      <div class="panel panel-default" align="center">
          <div class="panel-heading" style="background-color:#b3b3b3">
              <h4>Asignatura 2</h4>
          </div>

          <div class="panel-body" style="background-color:#cccccc">
            <div class="row">
              <div class="col-md-12" id="tabla_asig_2"></div>
            </div>
          </div>
      </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12"><br><br></div>
</div>

<div class="row">
    <div class="col-md-8 col-md-offset-2" align="center">
    <!-- la tabla de la derecha en principio no va a cambiar y solo va a ser de referencia, los cambios solo se aplicarán a la de la izquierda,
     lo ideal seria que fueran independientes en dos "cajas" separadas par no tener que recargar las dos cada vez que se introduce un cambio -->

        <button id="Boton_asociar_asig" type="button" class="btn btn-success" style="width:60%">Asociar</button>

    </div>
</div>
<div class="row">
    <div class="col-md-12" id="Insertar_asigs"></div>
</div>
<div class="row">
    <div class="col-md-12"><br><br></div>
</div>

<div class="row">
  <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default" align="center">

            <div class="panel-heading" style="background-color:#b3b3b3">
                <h4>Asignaturas asociadas</h4>
            </div>

                  <div class="panel-body" style="background-color:#cccccc" >
                    <form action="./App_asoc.php" method="post">
                      <fieldset>
                        <div class="row">
                          <div class="col-md-12" id="Lista_asoc" align="left"></div>
                        </div>

                        <br><br>
                        <button id="Borrar_asig_asoc" type="submit" class="btn btn-success" style="width:60%">Borrar seleccionadas</button>
                        <br>
                      </fieldset>
                    </form>
                  </div>
      </div>
  </div>
</div>

<?php

}else{
    echo '
    <div class="row">
        <div class="col-md-8 col-md-offset-2" style="background-color:RED">
            <h1 align="center">No tiene permiso para acceder a esta sección.</h1>
        </div>
    </div>
    ';
}

?>

<div class="row">
    <div class="col-md-12"><br><br></div>
</div>

<footer class="footer">
    <div class="container"><!-- Contenido del pie de Página--></div>
</footer>
</body>
</html>
