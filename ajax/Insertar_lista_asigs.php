<?php
require_once '../autoload/autoload.php';
?>

<?php /*** HT00 - Asignaturas ***/

if(isset($_POST["Asig_1"]) && isset($_POST["Asig_2"])){
  if(is_numeric($_POST["Asig_1"]) && is_numeric($_POST["Asig_2"])){
      Comprobar_titulaciones::Insertar_asignaturas_asociadas($_POST["Asig_1"],$_POST["Asig_2"]);
  }
}

?>
