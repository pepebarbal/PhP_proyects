<?php

/**
 * En esta clase tendremos métodos por cada query
 * que necesitemos hacer, intentando generalizarlo lo máximo posible.
 * Las tablas utilizadas en el ejemplo són:
 *  * grado
 *  * c_examenes
 *  * asignaturas_grado
 *  * asignatura
 * Quizás pueda interesar crear una clase por cada una para
 * cargar cada fila cómo un objeto(aunque quizá complique más las cosas).
 *
 * Class Database.
 */


/**
 * Class to intercact with database.
 *
 * Class Database.
 */
class Database
{

    /**
     * Private to avoid init of the class
     *
     * Database constructor.
     */
    private function __construct()
    {
    }

    /**
     * Singleton instance for db.
     *
     * @var mysqli $dbConnection.
     */
    protected static $dbConnection = null;

    /**
     * Get Singleton instance for database.
     *
     * @return mysqli
     */
    public static function getConnection()
    {
        if (empty(self::$dbConnection)) {
            $config = DatabaseConfiguration::getInstance();
            self::$dbConnection = new mysqli(
                $config->getBdHost(),
                $config->getBdUser(),
                $config->getDbPass(),
                $config->getDbName()
            );
            self::$dbConnection->set_charset('utf8');
        }
        return self::$dbConnection;
    }

    /*
     * Experimento para más adelante
    private function query($table, $fields = array(), $conditions = array()) {
        $connection = self::getConnection();
        $fieldsProcessed = empty($fields) ? '*' : implode(',', $fields);
        foreach ($conditions as $operator => $value){

        }
        $query = "select ? from ? where ";
        $connection->prepare($query);
    }*/

    /**
     * Devuelve las fechas de los exámenes.
     *
     * @return array.
     */

    public static function get_estado_cexamenes($id_cal,$cuatrimestre,$titulacion) {
        $query = "select * from c_examenes_prov_def where (id_cal='".$id_cal."') and Cuatrimestre='".$cuatrimestre."' and id_grado='".$titulacion."' ";
        $con = self::getConnection();
        $result = $con->query($query);
        if($result){
          return $result->fetch_all(MYSQLI_ASSOC);
        }else{
          return false;
        }
    }

    public static function get_estados_titulacion($id_cal,$titulacion) {
        $query = "select * from c_examenes_prov_def where (id_cal='".$id_cal."') and id_grado='".$titulacion."' ";
        $con = self::getConnection();
        $result = $con->query($query);
        if($result){
          return $result->fetch_all(MYSQLI_ASSOC);
        }else{
          return false;
        }
    }

    public static function update_estado_cexamenes($id_cal,$cuatrimestre,$titulacion) {
        $estado=self::get_estado_cexamenes($id_cal,$cuatrimestre,$titulacion);
        $estado=$estado[0]["prov_def"];
        if($estado){
          $query = "update c_examenes_prov_def set prov_def='0' where (id_cal='".$id_cal."') and Cuatrimestre='".$cuatrimestre."' and id_grado='".$titulacion."' ";
        }else{
          $query = "update c_examenes_prov_def set prov_def='1' where (id_cal='".$id_cal."') and Cuatrimestre='".$cuatrimestre."' and id_grado='".$titulacion."' ";
        }
        $con = self::getConnection();
        $result = $con->query($query);
        return $result;
    }

    public static function insert_estado_cexamenes($id_cal,$cuatrimestre,$titulacion) {
        if($id_cal!=0){
          $query = "insert into c_examenes_prov_def (id_cal,id_grado,Cuatrimestre,prov_def) values ('".$id_cal."','".$titulacion."','".$cuatrimestre."','0')";
          $con = self::getConnection();
          $result = $con->query($query);
          return $result;
        }else{
          return false;
        }
    }

    public static function consultaExamenes($estado,$id_cal) {
        /* En realidad queremos los dias, para despues discrimarlos obteniendo el dia de inicio y fin del cuatrimestre correspondiente con otra funcion*/
        /* para obtener el primer dia del cuatrimestre -> primer cuatrimestre = estado 4 // segundo cuatrimetres = estado 5 */
        /* para obtener el dia de fin del cuatrimestre -> primer cuatrimestre = estado 6 // segundo cuatrimetres = estado 7 */
        /*Dias de examenes, Estado=8*/
        $query = "select * from calendario where (estado='".$estado."') and id='".$id_cal."' order by mes,dia";
        $con = self::getConnection();
        $result = $con->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }


    public static function consultaCals($year) {
      $query = "SELECT id,nombre FROM identificador_calendario where year='".$year."'";
      $con = self::getConnection();
      $result = $con->query($query);
      return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function getYearCalendar($calendario) {
        $query = "SELECT year FROM identificador_calendario where id='".$calendario."'";
        $con = self::getConnection();
        $result = $con->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function get_titulaciones(){
        $query = "SELECT id,nombre FROM grado where padre_id is NULL";
        $con = self::getConnection();
        $result = $con->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function get_asigs_tit($id_tit){
      $query ="select a.nombre,b.codigo from asignatura a inner join asignatura_grado b on a.id=b.asignatura_id where b.grado_id =".$id_tit.";";
      $con = self::getConnection();
      $result = $con->query($query);
      return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function get_itinerarios($id){
      $query = "SELECT id,nombre FROM grado where padre_id='".$id."'";
      $con = self::getConnection();
      $result = $con->query($query);
      return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function get_asig_desactivadas($year){
  		$con= self::getConnection();
  		settype($year,"integer");
  		$con->query("Start transaction");
  		$consulta= $con->query("SELECT a.Codigo,c.nombre from asignatura_desactivada a inner join asignatura_grado b on a.Codigo=b.codigo inner join asignatura c on b.asignatura_id=c.id where a.year='".$year."' order by c.nombre ASC");
  		$con->query("commit");

      $asigs="";

      if($consulta){
    		while($valor=$consulta->fetch_object()){
    			$asigs.=$valor->Codigo.',';
    		}
      }

      $consulta->close();
      unset($link);

  		$asigs=substr($asigs, 0, -1);

      return $asigs;
	}

    public static function get_asig($id_tit,$cuat,$curso,$year){
        if($cuat=='1'){
            $conv="'1' or c.cuatrimestre='3'";
        }else if($cuat=='2'){
            $conv="'2' or c.cuatrimestre='3'";
        }else{
            $conv="'1' or c.cuatrimestre='2' or c.cuatrimestre='3'";
        }

        $desactivadas=self::get_asig_desactivadas($year);
        if(empty($desactivadas)){
          $desactivadas="' '";
        }

        $query = "SELECT b.codigo,c.nombre,c.caracter,b.grado_id FROM grado a inner join asignatura_grado b on a.id=b.grado_id inner join asignatura c on c.id=b.asignatura_id where
        (a.padre_id='".$id_tit."' or a.id='".$id_tit."') and (b.curso='".$curso."') and (c.cuatrimestre=".$conv.") and b.codigo NOT IN (".$desactivadas.") ";
        $con = self::getConnection();
        $result = $con->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /* Segun el tipo, un color distinto */
    public static function get_asig_dia($id_cal,$id_tit,$year,$conv,$curso){
        
          $query = "SELECT c.nombre,d.Dia,d.Mes,d.cod_asig,d.Tipo,d.Hora,d.Aula  FROM grado a inner join asignatura_grado b on a.id=b.grado_id inner join asignatura c on c.id=b.asignatura_id
          inner join test_cal_examenes d on d.Cod_asig=b.codigo
          where (a.padre_id='".$id_tit."' or a.id='".$id_tit."') and d.Convocatoria='".$conv."' and b.curso='".$curso."' and d.id_cal='".$id_cal."' ";
        

        $con = self::getConnection();
        $result = $con->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function get_asig_xml($id_cal,$id_tit){
        $query = "SELECT c.nombre,d.Dia,d.Mes,d.cod_asig,d.Convocatoria,b.grado_id,d.Tipo,d.Hora,d.Aula,b.curso,c.caracter,c.creditos,c.cuatrimestre  FROM grado a inner join asignatura_grado b on a.id=b.grado_id inner join asignatura c on c.id=b.asignatura_id
        inner join test_cal_examenes d on d.Cod_asig=b.codigo
        where b.grado_id='".$id_tit."' and d.id_cal='".$id_cal."'";
        $con = self::getConnection();
        $result = $con->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function get_conv_xml($id_cal,$id_tit,$conv){
        $query = "SELECT c.nombre,d.Dia,d.Mes,d.cod_asig,d.Convocatoria,b.grado_id,d.Tipo,d.Hora,d.Aula,b.curso,c.caracter,c.creditos,c.cuatrimestre  FROM grado a inner join asignatura_grado b on a.id=b.grado_id inner join asignatura c on c.id=b.asignatura_id
        inner join test_cal_examenes d on d.Cod_asig=b.codigo
        where b.grado_id='".$id_tit."' and d.id_cal='".$id_cal."' and d.Convocatoria='".$conv."' ";
        $con = self::getConnection();
        $result = $con->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function get_conv_nov_dic_xml($id_cal,$id_tit){
        $query = "SELECT c.nombre,d.Dia,d.Mes,d.cod_asig,d.Convocatoria,b.grado_id,d.Tipo,d.Hora,d.Aula,b.curso,c.caracter,c.creditos,c.cuatrimestre  FROM grado a inner join asignatura_grado b on a.id=b.grado_id inner join asignatura c on c.id=b.asignatura_id
        inner join test_cal_examenes d on d.Cod_asig=b.codigo
        where b.grado_id='".$id_tit."' and d.id_cal='".$id_cal."' and (d.Convocatoria='4' or d.Convocatoria='5') ";
        $con = self::getConnection();
        $result = $con->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function get_nombre_titulacion($id){
        $query = "SELECT nombre FROM grado where id='".$id."'";
        $con = self::getConnection();
        $result = $con->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    /* Falta agregar Tipo en Insertar_asig */
    public static function Insertar_asig($id_cal,$Dia, $Mes, $Codigo, $Year, $Cuatri, $hora, $aula,$Tipo){

        if($Tipo=="false"){
          $Tipo=0;
        }else{
          $Tipo=1;
        }

        $con = self::getConnection();
        $query = "select * from test_cal_examenes where (year='".$Year."') and (Cod_asig='".$Codigo."') and (Convocatoria='".$Cuatri."') and (id_cal='".$id_cal."') and Tipo='".$Tipo."'  order by mes,dia";
        $result=$con->query($query);
        $num_rows=$result->num_rows;
        if($num_rows==0){
            $con = self::getConnection();
            $con->query("start transaction;");
            $con->query("Insert into test_cal_examenes (id_cal,Cod_asig,Dia,Mes,Hora,Convocatoria,Year, Aula,Tipo) values ('".$id_cal."','".$Codigo."','".$Dia."','".$Mes
                ."','" . $hora . "','".$Cuatri."','".$Year."','".$aula."','".$Tipo."')");
            $con->query("Commit;");
        }else{
            echo"<script>Calendario.ui.aviso(1);</script>";
            /*
            no se si deberiamos de borrarla, hacer un update, o simplemente avisar, de momento dejo comentada la opcion del update y un aviso con un alert activo*/
            /*$con = self::getConnection();
            $con->query("start transaction;");
            $con->query("Update test_cal_examenes set Dia='".$Dia."'',Mes='".$Mes."'',Hora='' where ((id_cal='".$id_cal."') and (Cod_asig='".$Codigo."') and (Year='".$Year."') and (Convocatoria='".$Cuatri."'))");
            $con->query("Commit;");*/
        }
    }

    public static function Editar_asig($id_cal,$Codigo, $Year, $Cuatri, $hora, $aula,$Tipo){

        if($Tipo=="false"){
          $Tipo=0;
        }else{
          $Tipo=1;
        }

        $con = self::getConnection();
        $query = "select * from test_cal_examenes where (year='".$Year."') and (Cod_asig='".$Codigo."') and (Convocatoria='".$Cuatri."') and (id_cal='".$id_cal."') order by mes,dia";
        $result=$con->query($query);
        $num_rows=$result->num_rows;
        if($num_rows==0){
            echo "cero";
        }if($num_rows==2){
              echo"<script>Calendario.ui.aviso(1);</script>";
        }
        else{
            $con = self::getConnection();
            $con->query("start transaction;");
            $con->query("Update test_cal_examenes set Hora='".$hora."', Aula='".$aula."',Tipo='".$Tipo."' where ((id_cal='".$id_cal."') and (Cod_asig='".$Codigo."') and (Year='".$Year."') and (Convocatoria='".$Cuatri."'))");
            $con->query("Commit;");
        }
    }

    /* Falta el tipo -> ahora mismo borra todas*/
    public static function Borrar_asig($id_cal,$codAsig, $cuatrimestre, $year,$Tipo) {
        $deleteQuery = "delete from test_cal_examenes where Cod_asig='$codAsig' and Convocatoria='$cuatrimestre' and year='$year' and id_cal='".$id_cal."' and Tipo='".$Tipo."' ";
        $con = self::getConnection();
        $con->query("start transaction;");
        $result = $con->query($deleteQuery);
        if (!$result) {
            $con->query("Commit;");
            echo"<script>Calendario.ui.aviso(2);</script>";
        }else{
            echo"<script>Calendario.ui.aviso(4);</script>";
        }
    }

    public static function Borrar_exam_cal($id_cal,$conv) {
        $deleteQuery = "delete from test_cal_examenes where id_cal='".$id_cal."' and Convocatoria='".$conv."' ";
        $con = self::getConnection();
        $con->query("start transaction;");
        $result = $con->query($deleteQuery);
        if (!$result) {
            $con->query("Commit;");
            echo"<script>Calendario.ui.aviso(2);</script>";
        }else{
            echo"<script>Calendario.ui.aviso(3);</script>";
        }
    }

    public static	function print_examenes($array_dias,$cuatrimestre,$titulacion,$curso,$Asignaturas_en_bd)
  	{
  				$contador=0;
  				$contador_asig=0;


  					while (!empty($array_dias[$contador])) {
  							$fecha = date('N', strtotime($array_dias[$contador]["year"]."-".$array_dias[$contador]["mes"]."-".$array_dias[$contador]["dia"]));
  							echo "<tr><td><strong>".$array_dias[$contador]["dia"]."/".$array_dias[$contador]["mes"]."/".$array_dias[$contador]["year"]."</strong></td>
  							<td>";
  								/*Asignaturas*/
  								if(!empty($Asignaturas_en_bd)){
  									echo"<table style='border:none;height:0'>";
  									foreach ($Asignaturas_en_bd as $valor) {
  											if(($valor["Dia"]==$array_dias[$contador]["dia"])&&($valor["Mes"]==$array_dias[$contador]["mes"])){
  													if(($contador_asig%2)==0){ echo"<tr style='border:none;height:100%'><td style='border:none;'>";}
  													if(($contador_asig%2)==1){ echo"<td style='border:none;'>";}
  													echo'<table border="1" style="border:none; color: #000000;';
  													if($valor["Tipo"]=='0'){
  														echo ' background-color: #70db70">';
  													}else{
  														echo ' background-color: #db7f29">';
  													}
  													echo'<tr style="border:none;"><td align="center" style="border:none;"><strong style="font-size:large">'.$valor["nombre"].'</strong><br> Hora : ';
  													if(empty($valor["Hora"])){ echo "Dato no Introducido";  }else{ echo $valor["Hora"]; }
  													echo '<br>Aula : ';
  														if(empty($valor["Aula"])){ echo "Dato no Introducido";  }else{ echo $valor["Aula"]; }
  													echo'<br><button class="btnEliminar" data-cod-asig="' . $valor['cod_asig'] . '"
  															data-cuatrimestre="' . $cuatrimestre . '" data-year="' . $array_dias[$contador]["year"] . '"
                                data-Tipo="' . $valor["Tipo"] . '"
  															style="color: black;">Eliminar</button>

                                <br><button class="btnEditar" data-cod-asig="' . $valor['cod_asig'] . '"
      													data-cuatrimestre="' . $cuatrimestre . '" data-year="' . $array_dias[$contador]["year"] . '"
                                data-Tipo="' . $valor["Tipo"] . '" data-hora="' . $valor["Hora"] . '" data-Aula="' . $valor["Aula"] . '"
                                data-tit-izq="' . $titulacion . '" data-cur-izq="' . $curso . '"
      													style="color: black;">Editar</button>

  													</td></tr></table>';
  													echo"</td>";
  													if(($contador_asig%2)==1){ echo"</tr>";}
  													$contador_asig++;
  											}


  									}
  									echo"</table>";
  									unset($valor);
  								}

  								echo'<br><button class="btnInsertar"
  							 data-dia="' . $array_dias[$contador]["dia"]. '" data-mes="' . $array_dias[$contador]["mes"] . '"
  							 data-year="' . $array_dias[$contador]["year"] . '" data-cuatrimestre="' . $cuatrimestre . '"
  							 data-tit-izq="' . $titulacion . '"
  							 data-cur-izq="' . $curso . '" style="color: black;">Insertar</button><br><br>
  							</td></tr>';

  							if($fecha==5){
  									if( isset($array_dias[$contador+1]["dia"])){
  											if( $array_dias[$contador+1]["dia"]==(($array_dias[$contador]["dia"])+1) ){
  												//do nothing
  											}else{
  												echo "<tr><td colspan='2' Align='center' class='Finde'><strong> Fin de Semana </strong></td></tr>";
  											}
  									}else{
  										echo "<tr><td colspan='2' Align='center' class='Finde'><strong> Fin de Semana </strong></td></tr>";
  									}
  							}
  							if($fecha==6){
  								echo "<tr><td colspan='2' Align='center' class='Finde'><strong> Domingo </strong></td></tr>";
  							}
  							$contador++;
  							$contador_asig=0;
  					}

  	}

  	public static function getAsigsPdf($grado) {
      $sql = 'SELECT * FROM test_cal_examenes INNER JOIN asignatura_grado ON(cod_asig = codigo) INNER JOIN asignatura on(asignatura_id = id) where grado_id = ' . $grado . ' order by cuatrimestre ASC';
      $con = self::getConnection();
      $result = $con->query($sql);
      return $result->fetch_all(MYSQLI_ASSOC);
    }

  	public static function getDetallesAsignatura($asignatura) {
      $con = self::getConnection();
      $sql = "SELECT * FROM asignatura_grado INNER JOIN asignatura ON (asignatura_id=id) WHERE codigo='" . $asignatura . "'";
      $result = $con->query($sql);
      return $result->fetch_assoc();
    }

}
