<?php

class PdfController {

  protected $grado;
  protected $calendario;

  /**
   * PdfController constructor.
   * @param $grado
   * @param $calendario
   */
  public function __construct($grado, $calendario)
  {
    $this->grado = $grado;
    $this->calendario = $calendario;
  }

  /**
   * @return mixed
   */
  public function getCalendario()
  {
    return $this->calendario;
  }

  /**
   * @return mixed
   */
  public function getGrado()
  {
    return $this->grado;
  }

  /* Las columnas deben de ser XS, ya que la resolucion del documento en menor que la de una pantalla normal, y por lo tanto el
  responsive crearia un stack con los elementos - jc Mora. */
  public function get_header() { //./membrete_pdf.jpg
    $titulacion = Database::get_nombre_titulacion($this->grado);
    if (!empty($titulacion)) {
      $titulacion = reset(reset($titulacion));
    }
    $year = Database::getYearCalendar($this->calendario);
    if (!empty($year)) {
      $year = reset(reset($year));
    }
    $output = '<table style="width:100%;border:solid; border-width:3px;" class="header">
                    <tr class="text-center">
                        <td style="height:75px;width:325px;border-right:solid;border-width:3px;"> <img src="./membrete_pdf.jpg"></img></td>
                        <td style="height:75px;width:525px;border-right:solid;border-width:3px;" class="titulacion">
                            <strong> EXÁMENES GRADO EN ' . $titulacion . ' <br> ITINERARIOS</strong>
                        </td>
                        <td style="height:75px;"><strong>Curso ' . $year . '/' . ($year + 1) . '</strong></td>
                    </tr>
                  </table>';

    return $output;
  }

  public function get_separation($pixeles) {
    $output = '<table style="width:100%;height:' . $pixeles . 'px;"><tr><td></td></tr></table>';
    return $output;
  }

  public function get_tabla_asigs() { //./membrete_pdf.jpg
    static $row = 0;
    $asigs = Database::getAsigsPdf($this->grado);

    $cal_data = array();
    foreach ($asigs as $asig) {
      if(!empty($cal_data[$asig['curso']][$asig['codigo']]['examenes'][$asig['Convocatoria']])){

        $convocatoria_bis=10;
        switch($asig['Convocatoria']){
          case 1 :	$convocatoria_bis=11;
                    break;
          case 2 :	$convocatoria_bis=12;
                    break;
          case 3 :	$convocatoria_bis=13;
                    break;
        }
        /*if($asig['codigo']=="606110102"){
         echo "1".$asig['Dia'].'-'.$asig['Mes'].'-'.$asig['Hora'].'-'.$asig['Aula'].'-'.$asig['Tipo']."<br>";
       }*/
        $cal_data[$asig['curso']][$asig['codigo']]['examenes'][$convocatoria_bis] = [ 'dia' => $asig['Dia'],
        'mes' => $asig['Mes'],
        'hora' => $asig['Hora'],
        'aula' => $asig['Aula'],
        'Tipo' => $asig['Tipo'],
        ];

      }else{
          if ((!isset($cal_data[$asig['curso']])) || (!isset($cal_data[$asig['curso']][$asig['codigo']]))) {
            /*if($asig['codigo']=="606110102"){
             echo "2".$asig['Dia'].'-'.$asig['Mes'].'-'.$asig['Hora'].'-'.$asig['Aula'].'-'.$asig['Tipo']."<br>";
           }*/
            $cal_data[$asig['curso']][$asig['codigo']] = array(
              'codigo' => $asig['codigo'],
              'curso' => $asig['curso'],
              'nombre' => $asig['nombre'],
              'caracter' => $asig['caracter'],
              'creditos' => $asig['creditos'],
              'cuatrimestre' => $asig['cuatrimestre'],
              'ac' => ($asig['cuatrimestre'] == 3) ? 'A' : ($asig['cuatrimestre'] . 'C'),
              'examenes' => [
                $asig['Convocatoria'] => [//aqui esta el fallo, si existe la convocatoria se debe de poder duplicar los datos.
                                          //maximo son 2 examenes por convocatoria. ideal es "si existe, se guardan 2"
                  'dia' => $asig['Dia'],
                  'mes' => $asig['Mes'],
                  'hora' => $asig['Hora'],
                  'aula' => $asig['Aula'],
                  'Tipo' => $asig['Tipo'],
                ],
              ],
            );
          }else {
            $cal_data[$asig['curso']][$asig['codigo']]['examenes'][$asig['Convocatoria']] = array(
              'dia' => $asig['Dia'],
              'mes' => $asig['Mes'],
              'hora' => $asig['Hora'],
              'aula' => $asig['Aula'],
              'Tipo' => '',
            );
          }
      }
    }

    $output = '';
    foreach ($cal_data as $curso => $asignaturas) {
      //var_dump($asignaturas);
      if($curso!=1){
        $output .= '<div style="page-break-after: always;"></div>';
      }
      $output .= '<table style="width:100%;" class="asignaturas">
                    <thead>
                      <tr class="text-center">
                        <td rowspan="3" class="header_tabla_borders">Código</td>
                        <td rowspan="3" class="header_tabla_borders">Asignatura</td>
                        <td rowspan="3" class="header_tabla_borders">Curso</td>
                        <td rowspan="3" class="header_tabla_borders">A/C</td>
                        <td rowspan="3" class="header_tabla_borders">Carácter</td>
                        <td rowspan="3" class="header_tabla_borders">ECTS</td>
                        <td colspan="9">Convocatoria</td>
                      </tr>
                      <tr>
                          <td colspan="3">Febrero</td>
                          <td colspan="3">Junio</td>
                          <td colspan="3">Septiembre</td>
                      </tr>
                      <tr>
                          <td>Día</td>
                          <td>Hora</td>
                          <td>Aula</td>
                          <td>Día</td>
                          <td>Hora</td>
                          <td>Aula</td>
                          <td>Día</td>
                          <td>Hora</td>
                          <td>Aula</td>
                      </tr>
                    </thead>';
      $cuatrimestre = 1;
      foreach ($asignaturas as $asignatura) {
        $styleRow = '';
        if ($cuatrimestre != $asignatura['cuatrimestre']) {
          $styleRow = 'border-top: 3px solid blue';
          $cuatrimestre = $asignatura['cuatrimestre'];
        }
        $output .= '<tr class="text-center content_asig ' . (($row % 2) ? ('par' . $curso) : ('impar' . $curso)) . '" style="' . $styleRow . '">
                    <td class="content_asig_celdas_right content_asig_celdas_bottom">' . $asignatura['codigo'] . '</td>
                    <td class="text-left content_asig_celdas_right content_asig_celdas_bottom">' . $asignatura['nombre'] . '</td>
                    <td class="content_asig_celdas_right content_asig_celdas_bottom">' . $asignatura['curso'] . '</td>
                    <td class="content_asig_celdas_right content_asig_celdas_bottom">' . $asignatura['ac'] . '</td>
                    <td class="content_asig_celdas_right content_asig_celdas_bottom">' . $asignatura['caracter'] . '</td>
                    <td class="content_asig_celdas_right content_asig_celdas_bottom">' . $asignatura['creditos'] . '</td>';
        for ($i = 1; $i <= 3; $i++) {
          $dia = '';
          $hora = '';
          $aula = '';
          if (isset($asignatura['examenes'][$i])) {
            $examen = $asignatura['examenes'][$i];
            $dia .= '<p>';
            if($examen['Tipo']){
                if($cuatrimestre == 1){
                  $dia .= ' 1P | ';
                }else{
                  $dia .= ' 2P | ';
                }
            }else if($examen['Tipo']==0){
                $dia .= ' F |';
            }
            $dia .= sprintf("%3d", $examen['dia']) . '/' . DateTime::createFromFormat('!m', $examen['mes'])->format('M');
            $hora .= '<p>' . $examen['hora'] . '</p>';
            $aula .= '<p>' . $examen['aula'] . '</p>';
            $dia .= '</p>';

            if(isset($asignatura['examenes'][($i+10)])) {

              $examen = $asignatura['examenes'][($i+10)];

              $dia .= '<p>';
              if($examen['Tipo']){
                  if($cuatrimestre == 1){
                    $dia .= '1P|';
                  }else{
                    $dia .= '2P|';
                  }
              }else if($examen['Tipo']==0){
                  $dia .= 'F |';
              }

              $dia .= sprintf("%3d", $examen['dia']) . '/' . DateTime::createFromFormat('!m', $examen['mes'])->format('M');
              if($asignatura['codigo']=="606110102"){
                echo "<br>";
               //var_dump($asignatura['examenes']);
               //echo $dia."<br>";
              }
              $hora .= '<p>' .$examen['hora'] . '</p>';
              $aula .= '<p>' .$examen['aula'] . '</p>';
              $dia .= '</p>';
            }

          }

          $output .= '<td class="content_asig_celdas_right content_asig_celdas_bottom"><span style="font-size:x-small">';
          $output .= $dia.'</span></td>';
          $output .= '<td class="content_asig_celdas_right content_asig_celdas_bottom">' . $hora . '</td>
                      <td class="content_asig_celdas_right content_asig_celdas_bottom">' . $aula . '</td>';
        }
        $output .= '</tr>';
        $row++;
      }
      $row = 0;
      $output .= '</table>';

      $output .= self::get_separation(100);
    }

    return $output;
  }

}

?>
