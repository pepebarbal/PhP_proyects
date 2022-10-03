<?php
require_once '../autoload/autoload.php';
?>

<?php /*** HT00 - Asignaturas ***/

if(isset($_POST["Titulacion"])){
  if(is_numeric($_POST["Titulacion"])){

      $asignaturas=Database::get_asigs_tit($_POST["Titulacion"]);
      echo '<select id="Asignatura_'.$_POST["Asignatura"].'" style="width:100%">';
      foreach($asignaturas as $asig) {
          echo '<option value="' .$asig["codigo"]. '" >'.$asig["nombre"].'</option>';
      }
      echo'</select>';
  }
}

?>
