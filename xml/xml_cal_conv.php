<?php
require_once '../autoload/autoload.php';
header("Content-type: text/xml");
ini_set('max_execution_time', 300); //configuramos el tiempo maximo que puede tardar en generar el xml, por si se cuelga, 5 minutos
include 'xml_struct_total.php'; //incluimos la estructura general que seguira el xml, que esta en el archivo xml_struct
session_start(); //iniciamos sesion ?
$documento= new SimpleXMLElement($xmlstr); /*Udsaremos un objeto de tipo SimpleXMLElement para generar en xml*/

if(isset($_GET['year'])){
    if(is_numeric($_GET['year'])){
      $year=$_GET['year'];
    }else{
      $year=0;
    }
}

if(isset($_GET['cuatr'])){
    if(is_numeric($_GET['cuatr'])){
      $cuatr=$_GET['cuatr'];
    }else{
      $cuatr=0;
    }
}

if(isset($_GET['id_cal'])){
    if(is_numeric($_GET['id_cal'])){
      $id_cal=$_GET['id_cal'];
    }else{
      $id_cal=0;
    }
}

if($year==0){
  //no es legal la titulacion
}else{
$documento->addChild('Curso');
$documento->Curso->addAttribute('Año', ($year-1).'-'.$year);
$Titulaciones=[];
$Titulaciones=Database::get_titulaciones();
$contador_titulacion=0;
$documento->addChild('Convocatoria');
$contador_tit=0;
foreach($Titulaciones as $Titulacion_actual){
        $not_show=0;
        $itinerarios=Database::get_itinerarios($Titulacion_actual["id"]);
        if(!empty($itinerarios)){
            foreach ($itinerarios as $valor) {
                
                
                $Asignaturas_bd=Database::get_conv_xml($id_cal,$valor["id"],$cuatr);
                

                if(!empty($Asignaturas_bd)){
                    $not_show++;
                }
            }
            unset($valor);
            unset($Asignaturas_bd);
        }

        $estado=Database::get_estados_titulacion($id_cal,$Titulacion_actual["id"]);

        foreach($estado as $valor){
          if (($cuatr==4) ||($cuatr==5)) {
              if(($valor["Cuatrimestre"]==4) || ($valor["Cuatrimestre"]==5)){
                $documento->Convocatoria->addChild('Info_Titulacion');
                $documento->Convocatoria->Info_Titulacion[$contador_tit]->addAttribute('Id_tit',$Titulacion_actual["id"]);
                $documento->Convocatoria->Info_Titulacion[$contador_tit]->addAttribute('Convocatoria',$valor["Cuatrimestre"]);
                $documento->Convocatoria->Info_Titulacion[$contador_tit]->addAttribute('Definitiva',$valor["prov_def"]);
                $contador_tit++;
              }
            }
        }

        if (($cuatr==4) ||($cuatr==5)) {
          $Asignaturas_bd=Database::get_conv_xml($id_cal,$Titulacion_actual["id"],$cuatr);
        }

        if(!empty($Asignaturas_bd)){
            $not_show++;
        }
        unset($Asignaturas_bd);

        if($not_show){
        $documento->addChild('Titulacion');

        /*Añadimos nombre de la titulacion e itinerarios*/
        $documento->Titulacion[$contador_titulacion]->addAttribute('id',$Titulacion_actual["id"]);
        $documento->Titulacion[$contador_titulacion]->addAttribute('nombre',$Titulacion_actual["nombre"]);
        unset($nombre_tit);
        /*Vemos si tiene itinerarios y los volcamos*/
        $itinerarios=Database::get_itinerarios($Titulacion_actual["id"]);
        if(!empty($itinerarios)){
        $contador_itinerarios=0;

        foreach ($itinerarios as $valor) {
            $contador_asignaturas=0;
            $documento->Titulacion[$contador_titulacion]->addChild('Itinerario');
            $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->addAttribute('id',$valor["id"]);
            $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->addAttribute('nombre',$valor["nombre"]);

            if (($cuatr==4) ||($cuatr==5)) {
              $Asignaturas_bd=Database::get_conv_xml($id_cal,$valor["id"],$cuatr);
            }else{
                $Asignaturas_bd=Database::get_conv_xml($id_cal,$valor["id"],$cuatr);
            }

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
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->addChild('Asignatura');
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('nombre',$value["nombre"]);
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Dia',$value["Dia"]);
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Mes',$value["Mes"]);
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('cod_asig',$value["cod_asig"]);
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Convocatoria',$value["Convocatoria"]);
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Tipo',$value["Tipo"]);
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Hora',$value["Hora"]);
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Aula',$value["Aula"]);
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Curso',$value["curso"]);
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Caracter',$value["caracter"]);
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Creditos',$value["creditos"]);
                    if($value["cuatrimestre"]==3){
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('duracion','Anual');
                    }else{
                    $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('duracion','Cuatrimestral');
                    }
                    $contador_asignaturas++;
                }
                unset($value);
            }else{
                $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->addChild('Asignatura');
                $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->Asignatura[$contador_asignaturas]->addAttribute('Aviso',"No contiene asignaturas");
                /*Si no tiene asignaturas, quedas vacio*/
            }
            $documento->Titulacion[$contador_titulacion]->Itinerario[$contador_itinerarios]->addAttribute('Asignaturas',$contador_asignaturas);
            $contador_itinerarios++;
        }

        $documento->Titulacion[$contador_titulacion]->addAttribute('Itinerarios',$contador_itinerarios);

        }else{
        $documento->Titulacion[$contador_titulacion]->addAttribute('Itinerarios',"No tiene itinerarios");
        }
        unset($itinerarios);
        unset($contador_itinerarios);
        unset($contador_asignaturas);
        /*Procedemos a consultar las asignaturas de la titulacion,año y convocatoria seleccionadas*/


        if (($cuatr==4) ||($cuatr==5)) {
          $Asignaturas_bd=Database::get_conv_xml($id_cal,$Titulacion_actual["id"],$cuatr);
        }else{
            $Asignaturas_bd=Database::get_conv_xml($id_cal,$Titulacion_actual["id"],$cuatr);
        }

        $documento->Titulacion[$contador_titulacion]->addChild('Comunes');
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
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->addChild('Asignatura');
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('nombre',$value["nombre"]);
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Dia',$value["Dia"]);
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Mes',$value["Mes"]);
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('cod_asig',$value["cod_asig"]);
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Convocatoria',$value["Convocatoria"]);
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Tipo',$value["Tipo"]);
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Hora',$value["Hora"]);
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Aula',$value["Aula"]);
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Curso',$value["curso"]);
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Caracter',$value["caracter"]);
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Creditos',$value["creditos"]);
                if($value["cuatrimestre"]==3){
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('duracion','Anual');
                }else{
                $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('duracion','Cuatrimestral');
                }
                $contador_asignaturas++;
            }
            unset($value);
        }else{
            $documento->Titulacion[$contador_titulacion]->Comunes[0]->addChild('Asignatura');
            $documento->Titulacion[$contador_titulacion]->Comunes[0]->Asignatura[$contador_asignaturas]->addAttribute('Aviso',"No contiene asignaturas");
            /*Si no tiene asignaturas, quedas vacio*/
        }
        $documento->Titulacion[$contador_titulacion]->Comunes[0]->addAttribute('Asignaturas',$contador_asignaturas);
        unset($contador_asignaturas);
        unset($Asignaturas_bd);
        $contador_titulacion++;

        }else{

        }

    }

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
