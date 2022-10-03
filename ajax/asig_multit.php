<?php
require_once '../autoload/autoload.php';
session_start();
if( isset($_SESSION["year"]) && isset($_POST["Codigo"]) && isset($_SESSION["convocatoria"]) ){

?>

<div class="row text-center">
      <div class="col-md-2"></div>
        <div class="col-md-8  text-center " style="background-color:#b3cccc;border-radius: 25px; padding: 25px 0 25px 0;">
          <h4><strong style="color:#003366">- Asignaturas que deben de ser planificadas en fechas no coincidentes -</strong></h4>
        <?php
            $year=$_SESSION["year"]+1;
            $conv=$_SESSION["convocatoria"];
            if(($conv==4)||($conv==5)){ //
              Extraordinarias::get_alumnos_extraordinarias($_POST["Codigo"],$year,$conv);
            }
            if(($conv==6)||($conv==7)||($conv==8)){ //
              Incidencias::get_alumnos_por_examenes_incidencias($_POST["Codigo"],$year,$conv);
            }
            $id=Comprobar_titulaciones::Get_asig_id($_POST["Codigo"]);
            $titulaciones=Comprobar_titulaciones::Get_titulaciones_from_id($id);
            Comprobar_titulaciones::Array_titulaciones_Tostring($titulaciones,$id,$year,$_POST["Codigo"],$conv);
        ?>
      </div>
      <div class="col-md-2"></div>
</div>


<?php
}
?>
