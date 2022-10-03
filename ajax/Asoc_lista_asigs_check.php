<?php
require_once '../autoload/autoload.php';
?>

<?php

$query = "select * from asignaturas_relacionadas";
$con = Database::getConnection();
$result = $con->query($query);
$result->fetch_all(MYSQLI_ASSOC);

if($result){
  if( ($result->num_rows) == 0 ){
        echo "<div align='center'>No hay asignaturas asociadas actualmente</div>";
  }else{

    foreach($result as $tupla){

      $Asig1="select b.nombre from asignatura_grado a inner join asignatura b on a.asignatura_id=b.id where a.codigo=".$tupla["Cod_asig_1"];
      $Asig1 = $con->query($Asig1);
      $Asig1=$Asig1->fetch_all(MYSQLI_ASSOC);

      $Asig2 = "select b.nombre from asignatura_grado a inner join asignatura b on a.asignatura_id=b.id where a.codigo=".$tupla["Cod_asig_2"];
      $Asig2 = $con->query($Asig2);
      $Asig2=$Asig2->fetch_all(MYSQLI_ASSOC);

      if($Asig1 && $Asig2){
        echo'<div class="row">';
        echo"<div class='col-md-6'>".$Asig1[0]["nombre"].' -  '.$tupla["Cod_asig_1"].' <-> '.$Asig2[0]["nombre"].' -  '.$tupla["Cod_asig_2"].'</div>';
        echo'<div class="col-md-6"> <input type="checkbox" name="par[]" value='.$tupla["Cod_asig_1"].'-'.$tupla["Cod_asig_2"].'></div>';
        echo"</div>";
      }
      


    }

  }
}
?>
