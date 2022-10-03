<?php
require_once '../autoload/autoload.php';
header("Content-type: text/xml");
ini_set('max_execution_time', 300); //configuramos el tiempo maximo que puede tardar en generar el xml, por si se cuelga, 5 minutos
include 'xml_struct.php'; //incluimos la estructura general que seguira el xml, que esta en el archivo xml_struct
session_start(); //iniciamos sesion ?
$documento= new SimpleXMLElement($xmlstr); /*Udsaremos un objeto de tipo SimpleXMLElement para generar en xml*/

if(isset($_GET['tit'])){
    if(is_numeric($_GET['tit'])){
      $tit=$_GET['tit'];
    }else{
      $tit=0;
    }
}

if(isset($_GET['year'])){
    if(is_numeric($_GET['year'])){
      $year=$_GET['year'];
    }else{
      $year=0;
    }
}

if(isset($_GET['id_cal'])){
    if(is_numeric($_GET['id_cal'])){
      $id_cal=$_GET['id_cal'];
    }else{
      $id_cal=0;
    }
}

if( ($tit==0)||($year==0)){
  //no es legal la titulacion
}else{

    /*Aqui vemos si es definitivo el calendario o provisional para los itinerarios*/

    $estado=Database::get_estados_titulacion($id_cal,$tit);
    $contador_convocatorias=0;
    foreach($estado as $valor){
      $documento->addChild('Convocatoria');
      $documento->Convocatoria[$contador_convocatorias]->addAttribute('numero',$valor["Cuatrimestre"]);
      $documento->Convocatoria[$contador_convocatorias]->addAttribute('Definitiva',$valor["prov_def"]);
      $contador_convocatorias++;
    }

  $documento->addChild('Curso');
  $documento->Curso->addAttribute('Año', ($year-1).'-'.$year);
  $documento->addChild('Calendario');
  $documento->Calendario[0]->addAttribute('Id_cal',$id_cal);
/*Añadimos nombre de la titulacion e itinerarios*/
$nombre_tit=Database::get_nombre_titulacion($tit);
$documento->Titulacion[0]->addAttribute('id',$tit);
$documento->Titulacion[0]->addAttribute('nombre',$nombre_tit[0]["nombre"]);
unset($nombre_tit);
/*Vemos si tiene itinerarios y los volcamos*/
$itinerarios=Database::get_itinerarios($tit);
if(!empty($itinerarios)){
  $contador_itinerarios=0;

  foreach ($itinerarios as $valor) {
      $contador_asignaturas=0;
      $documento->Titulacion[0]->addChild('Itinerario');
      $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->addAttribute('id',$valor["id"]);
      $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->addAttribute('nombre',$valor["nombre"]);

      $Asignaturas_bd=Database::get_asig_xml($id_cal,$valor["id"]);
      if(!empty($Asignaturas_bd)){
          foreach ($Asignaturas_bd as $value) {
            if($value["Tipo"]==0){ $value["Tipo"]="Final"; } else { $value["Tipo"]="Parcial"; };
            if($value["Convocatoria"]==1){ $value["Convocatoria"]="Febrero / Primera convocatoria"; }
            else if($value["Convocatoria"]==2){ $value["Convocatoria"]="Junio / Primera convocatoria"; }
            else if($value["Convocatoria"]==3){ $value["Convocatoria"]="Segunda convocatoria"; }
            else if($value["Convocatoria"]==4){ $value["Convocatoria"]="Noviembre / convocatoria extraordinaria "; }
            else if($value["Convocatoria"]==5){ $value["Convocatoria"]="Diciembre/ convocatoria extraordinaria "; }
            else if($value["Convocatoria"]==6){ $value["Convocatoria"]="Primer Cuatrimestre / Primera Convocatoria - Incidencias"; }
            else if($value["Convocatoria"]==7){ $value["Convocatoria"]="Segundo Cuatrimestre / Primera Convocatoria  - Incidencias"; }
            else if($value["Convocatoria"]==8){ $value["Convocatoria"]="Segunda Convocatoria  - Incidencias "; }
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->addChild('Asignatura');
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('nombre',$value["nombre"]);
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Dia',$value["Dia"]);
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Mes',$value["Mes"]);
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('cod_asig',$value["cod_asig"]);
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Convocatoria',$value["Convocatoria"]);
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Tipo',$value["Tipo"]);
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Hora',$value["Hora"]);
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Aula',$value["Aula"]);
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Curso',$value["curso"]);
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Caracter',$value["caracter"]);
            $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Creditos',$value["creditos"]);
            if($value["cuatrimestre"]==3){
              $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('duracion','Anual');
            }else{
              $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('duracion','Cuatrimestral');
            }
            $contador_asignaturas++;
          }
          unset($value);
      }else{
        $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->addChild('Asignatura');
        $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Aviso',"No contiene asignaturas");
        /*Si no tiene asignaturas, quedas vacio*/
      }
      $documento->Titulacion[0]->Itinerario[$contador_itinerarios]->addAttribute('Asignaturas',$contador_asignaturas);
      $contador_itinerarios++;
  }

  $documento->Titulacion[0]->addAttribute('Itinerarios',$contador_itinerarios);

}else{
  $documento->Titulacion[0]->addAttribute('Itinerarios',"No tiene itinerarios");
}
unset($itinerarios);
unset($contador_itinerarios);
unset($contador_asignaturas);
/*Procedemos a consultar las asignaturas de la titulacion,año y convocatoria seleccionadas*/

$Asignaturas_bd=Database::get_asig_xml($id_cal,$tit);
$documento->Titulacion[0]->addChild('Comunes');
$contador_asignaturas=0;
if(!empty($Asignaturas_bd)){
      foreach ($Asignaturas_bd as $value) {
        if($value["Tipo"]==0){ $value["Tipo"]="Final"; } else { $value["Tipo"]="Parcial"; };
        if($value["Convocatoria"]==1){ $value["Convocatoria"]="Febrero / Primera convocatoria"; }
        else if($value["Convocatoria"]==2){ $value["Convocatoria"]="Junio / Primera convocatoria"; }
        else if($value["Convocatoria"]==3){ $value["Convocatoria"]="Segunda convocatoria"; }
        else if($value["Convocatoria"]==4){ $value["Convocatoria"]="Noviembre / convocatoria extraordinaria "; }
        else if($value["Convocatoria"]==5){ $value["Convocatoria"]="Diciembre/ convocatoria extraordinaria "; }
        else if($value["Convocatoria"]==6){ $value["Convocatoria"]="Primer Cuatrimestre / Primera Convocatoria - Incidencias"; }
        else if($value["Convocatoria"]==7){ $value["Convocatoria"]="Segundo Cuatrimestre / Primera Convocatoria  - Incidencias"; }
        else if($value["Convocatoria"]==8){ $value["Convocatoria"]="Segunda Convocatoria  - Incidencias "; }
        $documento->Titulacion[0]->Comunes[0]->addChild('Asignatura');
        $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('nombre',$value["nombre"]);
        $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Dia',$value["Dia"]);
        $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Mes',$value["Mes"]);
        $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('cod_asig',$value["cod_asig"]);
        $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Convocatoria',$value["Convocatoria"]);
        $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Tipo',$value["Tipo"]);
        $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Hora',$value["Hora"]);
        $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Aula',$value["Aula"]);
        $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Curso',$value["curso"]);
        $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Caracter',$value["caracter"]);
        $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Creditos',$value["creditos"]);

        if($value["cuatrimestre"]==3){
          $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('duracion','Anual');
        }else{
          $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('duracion','Cuatrimestral');
        }
        $contador_asignaturas++;
      }
      unset($value);
  }else{
    $documento->Titulacion[0]->Comunes[0]->addChild('Asignatura');
    $documento->Titulacion[0]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Aviso',"No contiene asignaturas");
    /*Si no tiene asignaturas, quedas vacio*/
  }
$documento->Titulacion[0]->Comunes[0]->addAttribute('Asignaturas',$contador_asignaturas);
unset($contador_asignaturas);
unset($Asignaturas_bd);
}
/*  */
$dom = new DOMDocument('1.0','UTF-8');
$dom->preserveWhiteSpace = false;
$dom->formatOutput = true;

$dom->loadXML(
  $documento->asXML()
);

//header('Content-Disposition: attachment;filename=' . $name);

echo $dom->saveXML();
?>
