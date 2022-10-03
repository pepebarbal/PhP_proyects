<?php
require_once '../autoload/autoload.php';
//Si esta el usuario en session etc....
?>

<?php /*** HT00 - funcion del ***/



if(isset($_POST["id_cal"])){
  if(is_numeric($_POST["id_cal"]) ){
      Database::Borrar_exam_cal($_POST["id_cal"],$_POST["conv"]);
  }
}

?>
