<?php

class Extraordinarias{

	private function __construct(){}

	public static	function Get_asignaturas_extraordinarias($year,$convocatoria,$titulacion){
			$convocatoria_tabla="";

    	switch($convocatoria){
					case 4 :	$convocatoria_tabla="app_noviembre";
										break;
					case 5 :	$convocatoria_tabla="app_diciembre";
										break;
					default: 	return false;
			}

			$array_asignaturas;

			$Query = "select distinct asignatura1 from ".$convocatoria_tabla." where year='".$year."' and ((estado='2') or (estado='6')) and titulacion='".$titulacion."' ";
			$con = Database::getConnection();
			$con->query("start transaction;");
			$result = $con->query($Query);
			$con->query("Commit;");

			if (!$result) {
					return false;
			}else{
					if(($result->num_rows)>0){
						$result->fetch_all(MYSQLI_ASSOC);
						$salida=[];
						foreach( $result as $value){
								$salida[]=$value["asignatura1"];
						}
						return $salida;
					}else{
						return false;
					}
			}
	}

	/*Obtener asignaturas de un alumno por dni, en un año dentro de una tabla especifica de convocatoria extraordinaria*/

	public static	function get_asignaturas_alumno($year,$dni){
			$salida=[];
			$Query = "select distinct asignatura1 from app_noviembre where year='".$year."' and ((estado='2') or (estado='6')) and dni='".$dni."'";
			//asignaturas del alumno por dni, llegados a este punto, tenemos que hacer algun tipo de recursion para obetner todas las asignaturas de los alumnos que
			//tengan dicha asignatura, y todas las asignaturas de los alumnos con una comun a estos, etc etc, en profundidad.
			$con = Database::getConnection();
			$con->query("start transaction;");
			$result = $con->query($Query);
			$con->query("Commit;");
			$result->fetch_all(MYSQLI_ASSOC);

			if (!$result) {

			}else{
					if(($result->num_rows)>0){
						$result->fetch_all(MYSQLI_ASSOC);

						foreach( $result as $value){
							$salida[]=$value["asignatura1"].'_4';
						}
					}
			}

			$Query = "select distinct asignatura1 from app_diciembre where year='".$year."' and ((estado='2') or (estado='6')) and dni='".$dni."'";

			$con->query("start transaction;");
			$result = $con->query($Query);
			$con->query("Commit;");
			$result->fetch_all(MYSQLI_ASSOC);

			if (!$result) {

			}else{
					if(($result->num_rows)>0){
						$result->fetch_all(MYSQLI_ASSOC);

						foreach( $result as $value){
							$salida[]=$value["asignatura1"].'_5';
						}
					}
			}

			return $salida;;
	}


	/* obtener datos de alumno por el codigo de una asignatura */
		public static	function get_alumno_porcodigo($codigo,$year,$convocatoria_tabla){
			if($convocatoria_tabla!="app_noviembre" && $convocatoria_tabla!="app_diciembre"){
				$convocatoria_tabla!="app_noviembre";
			}
			$Query = "select distinct nombre, dni from ".$convocatoria_tabla." where year='".$year."' and ((estado='2') or (estado='6')) and asignatura1 like '%".$codigo."%'";
			//datos de los alumnos que tienen la primera asignatura
			$con = Database::getConnection();
			$con->query("start transaction;");
			$result = $con->query($Query);
			$con->query("Commit;");

			$result->fetch_all(MYSQLI_ASSOC);
			return $result;
		}
	/****/

	public static	function print_alumnos_extraordinarias($codigo,$year,$convocatoria,$convocatoria_tabla){
		$convocatoria_interna=$convocatoria;
		$con = Database::getConnection();
		$array_alumnos=array();
		$resultado=self::get_alumno_porcodigo($codigo,$year,$convocatoria_tabla);

		foreach ($resultado as $value) {
			if(!in_array($value,$array_alumnos)){
					$array_alumnos[]=$value;
			}
		}

		// Si hay alumnos en esa asignatura :
		if(!empty($array_alumnos)){

		foreach ($array_alumnos as $valor) { // por cada alumno, las asignaturas.
			//var_dump($valor);
			$result=self::get_asignaturas_alumno($year,$valor["dni"]);

			$convocatoria_interna=$convocatoria;

					foreach($result as $valor_codigos){
							if(($convocatoria_interna==4)||($convocatoria_interna==5) ){

									/* Buscamos las entradas en el año correspondiente*/
									$con->query("start transaction;");
									$Query ="Select * from test_cal_examenes where cod_asig='".explode('_',$valor_codigos)[0]."' and year='".($year-1)."' and convocatoria='".explode('_',$valor_codigos)[1]."' " ;
									//var_dump($Query);
									$result_exa = $con->query($Query);
									$con->query("Commit;");
									/* Buscamos las entradas en el año correspondiente*/

									echo "<strong>Alumno: ".$valor["nombre"]."</strong> - <span style='color:red'> Asignatura: ".
									Comprobar_titulaciones::Get_nombre_asig($valor_codigos)." - ";
									if($result_exa->num_rows!=0){
											$result_exa=$result_exa->fetch_assoc();
											echo " Dia ".$result_exa["Dia"]." Mes ".$result_exa["Mes"]." Hora ".$result_exa["Hora"];
											if($result_exa["Tipo"]==0){
													echo " Final.";
											}else{
												echo " Parcial.";
											}

									}else{
										echo " Fecha de Exámen no fijada aún";
									}

									if(explode('_',$valor_codigos)[1]== 4){
										echo " - Noviembre";
									}else{
										echo " - Diciembre";
									}
									echo "</span><br>";
							}
					}

			}
		}
	}

	/****/
	public static	function get_alumnos_extraordinarias($codigo,$year,$convocatoria){
		$convocatoria_tabla="";

		switch($convocatoria){
				case 4 :	$convocatoria_tabla="app_noviembre";
									break;
				case 5 :	$convocatoria_tabla="app_diciembre";
									break;
				default: 	return false;
		}
				self::print_alumnos_extraordinarias($codigo,$year,$convocatoria,$convocatoria_tabla);
	}
	/****/
}

?>
