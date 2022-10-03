<?php
require_once '../autoload/autoload.php';
session_start();
unset($_SESSION["convocatoria"]);
$_SESSION["convocatoria"]=$_POST["Cuatrimestre"];
if(isset($_POST["Calendario"])){
  if(is_numeric($_POST["Calendario"])){
    $_SESSION["id_cal"]=$_POST["Calendario"];
  }
}

if(isset($_POST["provdef"])){
  Database::update_estado_cexamenes($_SESSION["id_cal"],$_SESSION["convocatoria"],$_POST["Titulacion_izq"]);
}


$calendarios = Database::consultaExamenes(20,$_SESSION["id_cal"]);
$cuatrimestre;
$dias_por_mes =array();

/** Separamos dias por convocatoria 0=1ºC 1=2ºC 2=Sept **/
/** Aqui pediremos el primer y ultimo dia del cuatrimestre correspondiente */
foreach ($calendarios as $calendario) {
    /* sera entre el primer dia del primer cuatrimestre y el ultimo, no buscaremos en 3 meses */
        $dias_por_mes[0][] = $calendario;
        $cuatrimestre=1;
}

$calendarios = Database::consultaExamenes(21,$_SESSION["id_cal"]);

foreach ($calendarios as $calendario) {
        $dias_por_mes[1][] = $calendario;
        $cuatrimestre=2;
}

/*Segunda convocatoria*/
$calendarios = Database::consultaExamenes(17,$_SESSION["id_cal"]);

foreach ($calendarios as $calendario) {
     $dias_por_mes[2][] = $calendario;
     $cuatrimestre=3;
}

/*Aqui se busca noviembre, diciembre, y el dia especial "noviembre/diciembre"
value="9" ->Exámenes de Noviembre/Diciembre
value="18" ->Exámenes de Noviembre
value="19" ->Exámenes de Diciembre
*/

$calendarios = Database::consultaExamenes("18' or estado='9",$_SESSION["id_cal"]);
foreach ($calendarios as $calendario) {
        $dias_por_mes[3][] = $calendario;
        $cuatrimestre="4";
}
//var_dump($dias_por_mes[3]);
$calendarios = Database::consultaExamenes("19' or estado='9",$_SESSION["id_cal"]);
foreach ($calendarios as $calendario) {
        $dias_por_mes[4][] = $calendario;
        $cuatrimestre="5";
}
//var_dump($dias_por_mes[4]);
/*Obtenemos itinerarios*/
$itinerarios=Database::get_itinerarios($_POST["Titulacion_izq"]);
$id_itinerarios_titulacion=[];
if($itinerarios){
  foreach ($itinerarios as $value) {
    $id_itinerarios_titulacion[]=$value["id"];
  }
}

/* Puede que haya alguna convocatoria sin dias, y por lo tanto hay que comprobar que este seteado*/
/** Incidencias - Obtenemos la semana siguiente a la convocatoria oficial. */
$contador_dias_conv=0;
$incidencias=[];
while($contador_dias_conv<5){
  if(isset($dias_por_mes[$contador_dias_conv])){
      $incidencias[$contador_dias_conv]=Incidencias::Get_semana_incidencias(end($dias_por_mes[$contador_dias_conv]));
  }
  $contador_dias_conv++;
}

unset($contador_dias_conv);



/* Obtenemos asignaturas de las titulaciones seleccionadas */
if(($_SESSION["convocatoria"]==6)||($_SESSION["convocatoria"]==7)||($_SESSION["convocatoria"]==8)){
  /* Incidencias, febrero, junio y septiembre */
  $Asignaturas = Incidencias::Get_asignaturas_incidencias($_SESSION["year"]+1,$_SESSION["convocatoria"]);
  if($Asignaturas){
      $Asignaturas_1 = Database::get_asig($_POST["Titulacion_izq"],$_POST["Cuatrimestre"],$_POST["Curso_izq"],$_SESSION["year"]); //Asignaturas desplegable
      $Copia_Asignaturas_1=[]; // obtenemos el codigo de las asignaturas de la titulacion para comparar con las de la tabla de Raúl de incidencias

      foreach ($Asignaturas_1 as $valor) {
          if(in_array($valor["codigo"],$Asignaturas)){
            //esta en el array, conservamos la asigantura
            $Copia_Asignaturas_1[] = $valor;
          }else{
            //no esta, no la guardamos
          }
      }

      $Asignaturas_1=$Copia_Asignaturas_1; //volcamos la que estan dentro del array original, sobreescribiendo en el proceso
      unset($Copia_Asignaturas_1);
      unset($Asignaturas);
  }
}else if($_SESSION["convocatoria"]==4 || $_SESSION["convocatoria"]==5){
    $Asignaturas = Extraordinarias::Get_asignaturas_extraordinarias($_SESSION["year"]+1,$_SESSION["convocatoria"],$_POST["Titulacion_izq"]);

    //asignaturas noviembre
    if($Asignaturas){
        $Asignaturas_1 = Database::get_asig($_POST["Titulacion_izq"],$_POST["Cuatrimestre"],$_POST["Curso_izq"],$_SESSION["year"]); //Asignaturas desplegable
        $Copia_Asignaturas_1=[]; // obtenemos el codigo de las asignaturas de la titulacion para comparar con las de la tabla de Raúl de incidencias

        foreach ($Asignaturas_1 as $valor) {
            if(in_array($valor["codigo"],$Asignaturas)){
              //esta en el array, conservamos la asigantura
              $Copia_Asignaturas_1[] = $valor;
            }else{
              //no esta, no la guardamos
            }
        }

        $Asignaturas_1=$Copia_Asignaturas_1; //volcamos la que estan dentro del array original, sobreescribiendo en el proceso
        unset($Copia_Asignaturas_1);
        unset($Asignaturas);
    }
}else{
    //Esto será distinto, el else son las asignaturas que estan en las tablas de noviembre y diciembre.
    $Asignaturas_1 = Database::get_asig($_POST["Titulacion_izq"],$_POST["Cuatrimestre"],$_POST["Curso_izq"],$_SESSION["year"]); //Asignaturas desplegable
}


if(isset($_POST["Insertar"])){
    /* Sanitizar lo que se pasa por post sera necesario¿?*/
    Database::Insertar_asig($_SESSION["id_cal"],$_POST["Dia"],$_POST["Mes"],$_POST["Codigo"],$_POST["Year"],
        $_POST["Cuatrimestre"], $_POST['Hora'], $_POST['Aula'],$_POST['Tipo']);

}
if (isset($_POST['Borrar'])) {
    Database::Borrar_asig($_SESSION["id_cal"],$_POST['Codigo'], $_POST['Cuatrimestre'], $_POST['year'],$_POST['Tipo']);
}

if (isset($_POST['Editar'])) {
    Database::Editar_asig($_SESSION["id_cal"],$_POST["Codigo"],$_POST["Year"],$_POST["Cuatrimestre"], $_POST['Hora'], $_POST['Aula'],$_POST['Tipo']);
}

$Asignaturas_en_bd= Database::get_asig_dia($_SESSION["id_cal"],$_POST["Titulacion_izq"],($_SESSION["year"]+1),$_POST["Cuatrimestre"],$_POST["Curso_izq"]); // Obtenemos las asignaturas en la BD para mostrarlas en la tabla

$Asignaturas_bd_noviembre_dic= Database::get_asig_dia($_SESSION["id_cal"],$_POST["Titulacion_izq"],$_SESSION["year"],$_POST["Cuatrimestre"],$_POST["Curso_izq"]); // Noviembre es en año -1
?>


<?php /**** HM00 - Primer Cuatrimestre***/ ?>
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4 text-center">
    <h4><strong>
    <?php
        if((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==1)){
            echo'Primer Cuatrimestre / Primera Convocatoria';
        }else if((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==2) ){
            echo"Segundo Cuatrimestre / Primera Convocatoria";
        }else if ((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==3)){
            echo"Segunda Convocatoria ";
        }else if ((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==4)){
            echo"Noviembre / Convocatoria Extraordinaria";
        }else if ((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==5)){
            echo"Diciembre / Tercera Convocatoria";
        }else if((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==6)){
            echo'Primer Cuatrimestre / Primera Convocatoria - Incidencias';
        }else if((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==7)){
            echo"Segundo Cuatrimestre / Primera Convocatoria  - Incidencias";
        }else if((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==8)){
            echo"Segunda Convocatoria  - Incidencias ";
        }

        echo'<br>';
        echo '¿Provisional? ';
        /*Consulta para comprobar provisionalidad*/

        $estado=Database::get_estado_cexamenes($_SESSION["id_cal"],$_POST["Cuatrimestre"],$_POST["Titulacion_izq"]);
        if(!$estado){
          $salida=Database::insert_estado_cexamenes($_SESSION["id_cal"],$_POST["Cuatrimestre"],$_POST["Titulacion_izq"]);
        }

        $estado=Database::get_estado_cexamenes($_SESSION["id_cal"],$_POST["Cuatrimestre"],$_POST["Titulacion_izq"]);
        if($estado){
          if($estado[0]["prov_def"]==0){
            echo '<strong>Si</strong>';
          }else{
            echo '<strong>No</strong>';
          }
        }
        /* Boton para cambiar*/
        echo '&nbsp;&nbsp;<button class="btn_prov_defi"
        data-cuat="'.($_POST["Cuatrimestre"]).'" data-idcal="'.$_SESSION["id_cal"].'"
        data-tit="'.$_POST["Titulacion_izq"].'"  data-cur-izq="'. $_POST["Curso_izq"].'"
        style="color: black;">Cambiar</button>';
        ?>
        </strong></h4>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-12"><br><br></div>
</div>

<div class="row">
    <div class="col-md-10 col-md-offset-1" align="center">
      <?php
      echo'<label>Asignaturas a Introducir:&nbsp;&nbsp; </label>';
      echo'<select id="asignatura_codigo" class="asigselect">';
      echo'<option value="nulo" disabled selected>seleccione una asignatura</option>';
        foreach($Asignaturas_1 as $asig) {
          echo '<option value="' . $asig["codigo"] . '"';
          echo '>' . $asig["nombre"] . ' - ' . $asig["caracter"];
          if(!empty($id_itinerarios_titulacion)){
            if(in_array($asig["grado_id"],$id_itinerarios_titulacion)){
              echo ' - '.Database::get_nombre_titulacion($asig["grado_id"])[0]["nombre"];
            }
          }

          echo '</option>';
        }
      echo'</select>';
      unset($Asignaturas_1);
      unset($id_itinerarios_titulacion);
      ?>
    </div>
</div>
<div class="row">
<div class="col-md-12"><br></div>
<div class="col-md-12" id="get_asig_multit"></div>
<div class="col-md-12"><br></div>
</div>
<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <!-- Las columnas deben de ser de mismo ancho -->
                <table class="table-striped">
                    <tr>
                        <th>dia</th>
                        <th><?php

                        if( isset($_POST["Titulacion_izq"]) && isset($_POST["Cuatrimestre"])
                        && ($_POST["Cuatrimestre"]==1 || $_POST["Cuatrimestre"]==2 || $_POST["Cuatrimestre"]==3)){ //Primera y segunda convocatoria
                            echo Database::get_nombre_titulacion($_POST["Titulacion_izq"])[0]["nombre"];
                             echo '&nbsp;&nbsp;<button class="btnxml"
                             data-tit="'.$_POST["Titulacion_izq"].'"  data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" style="color: black;">Calendario de la Titulación</button>';

                             echo '&nbsp;&nbsp;<button class="btnxml_id_cal"
                             data-tit="'.$_POST["Titulacion_izq"].'"  data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" style="color: black;">Plantilla para subir aulas</button>';

                             echo '&nbsp;&nbsp;<button class="btnxml2"
                             data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" data-cuatrimestre="'.$_POST["Cuatrimestre"].'" style="color: black;">Ocupación de aulas</button>';

                             echo '&nbsp;&nbsp;<button class="btnxml2_id_cal"
                             data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" data-cuatrimestre="'.$_POST["Cuatrimestre"].'" style="color: black;">Plantilla para subir aulas (total)</button>';
                        }else if (($_POST["Cuatrimestre"]==4 || $_POST["Cuatrimestre"]==5)) { //noviembre y diciembre
                          echo Database::get_nombre_titulacion($_POST["Titulacion_izq"])[0]["nombre"];
                          echo '&nbsp;&nbsp;<button class="btnxml2"
                          data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" data-cuatrimestre="'.$_POST["Cuatrimestre"].'" style="color: black;">Calendario Conjunto de convocatorias</button>';

                          echo '&nbsp;&nbsp;<button class="btnxml3"
                          data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" data-cuatrimestre="'.$_POST["Cuatrimestre"].'" style="color: black;">Calendario de la Convocatoria</button>';


                          echo '&nbsp;&nbsp;<button class="btnxml2_id_cal"
                          data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" data-cuatrimestre="'.$_POST["Cuatrimestre"].'" style="color: black;">Plantilla para subir aulas (ambas convocatorias)</button>';

                        }else if (($_POST["Cuatrimestre"]==6 || $_POST["Cuatrimestre"]==7 || $_POST["Cuatrimestre"]==8)){ //incidencias
                          echo Database::get_nombre_titulacion($_POST["Titulacion_izq"])[0]["nombre"];
                          echo '&nbsp;&nbsp;<button class="btnxml2"
                          data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" data-cuatrimestre="'.$_POST["Cuatrimestre"].'" style="color: black;">Calendario de Incidencias</button>';

                          echo '&nbsp;&nbsp;<button class="btnxml2_id_cal"
                          data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" data-cuatrimestre="'.$_POST["Cuatrimestre"].'" style="color: black;">Plantilla para subir aulas</button>';

                        }


                                /*if( isset($_POST["Titulacion_izq"]) && isset($_POST["Cuatrimestre"])
                                && $_POST["Cuatrimestre"]!=4 && $_POST["Cuatrimestre"]!=5 && $_POST["Cuatrimestre"]!=6
                                && $_POST["Cuatrimestre"]!=7 && $_POST["Cuatrimestre"]!=8){
                                    /* Sera necesario escapar la titulacion, aunque no creo que nos pasen cosas raras por post */
                                /*   echo Database::get_nombre_titulacion($_POST["Titulacion_izq"])[0]["nombre"];
                                   echo '&nbsp;&nbsp;<button class="btnxml"
                                   data-tit="'.$_POST["Titulacion_izq"].'"  data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" style="color: black;">Generar XML</button>';

                                   echo '&nbsp;&nbsp;<button class="btnxml_id_cal"
                                   data-tit="'.$_POST["Titulacion_izq"].'"  data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" style="color: black;">Generar XML calendario</button>';
                                }else{
                                  echo Database::get_nombre_titulacion($_POST["Titulacion_izq"])[0]["nombre"];
                                }

                                   echo '&nbsp;&nbsp;<button class="btnxml2"
                                   data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" data-cuatrimestre="'.$_POST["Cuatrimestre"].'" style="color: black;">Generar XML Titulaciones</button>';

                                   echo '&nbsp;&nbsp;<button class="btnxml2_id_cal"
                                   data-year="'.($_SESSION["year"]+1).'" data-idcal="'.$_SESSION["id_cal"].'" data-cuatrimestre="'.$_POST["Cuatrimestre"].'" style="color: black;">Generar XML calendario Titulaciones</button>';
                                */

                            ?>
                        </th>
                    </tr>
                    <tr style="border:none"><td colspan="2" style="border:none"></td></tr>
                    <?php
                    if((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==1)){
                      if(!isset($dias_por_mes[0])){ $dias_por_mes[0]=""; }
                      Database::print_examenes($dias_por_mes[0],$_POST["Cuatrimestre"],$_POST["Titulacion_izq"],$_POST["Curso_izq"],$Asignaturas_en_bd);
                    }else if((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==2)){
                      if(!isset($dias_por_mes[1])){ $dias_por_mes[1]=""; }
                      Database::print_examenes($dias_por_mes[1],$_POST["Cuatrimestre"],$_POST["Titulacion_izq"],$_POST["Curso_izq"],$Asignaturas_en_bd);
                    }else if((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==3)){
                      if(!isset($dias_por_mes[2])){ $dias_por_mes[2]=""; }
                      Database::print_examenes($dias_por_mes[2],$_POST["Cuatrimestre"],$_POST["Titulacion_izq"],$_POST["Curso_izq"],$Asignaturas_en_bd);
                    }else if((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==4)){
                      if(!isset($dias_por_mes[3])){ $dias_por_mes[3]=""; }
                      Database::print_examenes($dias_por_mes[3],$_POST["Cuatrimestre"],$_POST["Titulacion_izq"],$_POST["Curso_izq"],$Asignaturas_bd_noviembre_dic);
                    }else if((isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==5)){
                      if(!isset($dias_por_mes[4])){ $dias_por_mes[4]=""; }
                      Database::print_examenes($dias_por_mes[4],$_POST["Cuatrimestre"],$_POST["Titulacion_izq"],$_POST["Curso_izq"],$Asignaturas_bd_noviembre_dic);
                    }else if( (isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==6) ){
                      if(!isset($incidencias[0])){ $incidencias[0]=""; }
                      Incidencias::print_incidencias($incidencias[0],$_POST["Cuatrimestre"],$_POST["Titulacion_izq"],$_POST["Curso_izq"],$Asignaturas_en_bd,$_SESSION["id_cal"]);
                    }else if( (isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==7) ){
                      if(!isset($incidencias[0])){ $incidencias[0]=""; }
                      Incidencias::print_incidencias($incidencias[1],$_POST["Cuatrimestre"],$_POST["Titulacion_izq"],$_POST["Curso_izq"],$Asignaturas_en_bd,$_SESSION["id_cal"]);
                    }else if( (isset($_POST["Cuatrimestre"])) && ($_POST["Cuatrimestre"]==8) ){
                      if(!isset($incidencias[0])){ $incidencias[0]=""; }
                      Incidencias::print_incidencias($incidencias[2],$_POST["Cuatrimestre"],$_POST["Titulacion_izq"],$_POST["Curso_izq"],$Asignaturas_en_bd,$_SESSION["id_cal"]);
                    }
                    ?>

                </table>
    </div>
</div>
