<?php

class Comprobar_titulaciones
{

	private function __construct()
	{
	}

	public static function Get_nombre_asig($codAsig) {
			$resultado=self::Get_asig_id($codAsig);
			$Query = "Select nombre,curso, cuatrimestre,caracter from asignatura where id='".$resultado."'";;
			$con = Database::getConnection();
			$con->query("start transaction;");
			$result = $con->query($Query);
			$con->query("Commit;");

			if ($result) {
					$string_result=$result->fetch_assoc();
					$Salida=" ".$string_result["nombre"]." Curso: ".$string_result["curso"]." Cuatrimestre: ".$string_result["cuatrimestre"]." Caracter: ".$string_result["caracter"];
					return $Salida;
			}else{
				return false;
			}
	}

	public static function Get_asig_id($codAsig) {
			$Query = "Select asignatura_id from asignatura_grado where codigo='".$codAsig."'";;
			$con = Database::getConnection();
			$con->query("start transaction;");
			$result = $con->query($Query);
			if ($result) {
					$con->query("Commit;");
					$id=$result->fetch_assoc()["asignatura_id"];
					return $id;
			}else{
				return false;
			}
	}

	public static function Get_titulaciones_from_id($id) {
			$Query ="Select * from asignatura_grado where asignatura_id='".$id."'";
			$con = Database::getConnection();
			$con->query("start transaction;");
			$result = $con->query($Query);
			if ($result) {
					$con->query("Commit;");
					while($conteo=$result->fetch_assoc()){
							$titulaciones[]=$conteo["grado_id"];
					};
					if(!empty($titulaciones)){
						return $titulaciones;
					}
			}else{
				return false;
			}
	}

	public static function Get_codigos_from_comun($id) {
			$Query ="Select * from asignatura_grado where asignatura_id='".$id."'";
			$con = Database::getConnection();
			$con->query("start transaction;");
			$result = $con->query($Query);
			if ($result) {
					$con->query("Commit;");
					while($conteo=$result->fetch_assoc()){
							$salida[]=$conteo["codigo"];
					};
					if(!empty($salida)){
						return $salida;
					}
			}else{
				return false;
			}
	}

	public static function Get_asig_asociadas($codAsig) {
			if(!is_array($codAsig)){
				$identificador_asig=self::Get_asig_id($codAsig);
				$identificador_asig=self::Get_codigos_from_comun($identificador_asig);
				if(count($identificador_asig)>1){
					$codAsig=$identificador_asig;
				}
			}
			if(!is_array($codAsig)){
					$Array_asigs=array();
					$Query ="Select * from asignaturas_relacionadas where (Cod_asig_1='".$codAsig."' or Cod_asig_2='".$codAsig."')";
					//var_dump($Query);
					$con = Database::getConnection();
					$con->query("start transaction;");
					$result = $con->query($Query);
					/*inicializamos el array con el codigo de la propia asignatura que llama la funcion*/
					if($result){
						if ($result->num_rows>0) {
								$Array_asigs[]=$codAsig;
								$con->query("Commit;");
								while($conteo=$result->fetch_assoc()){
										/* Por cada entrada donde este la asignatura asociada, agregamos al array los codigos que no esten dentro ya*/
										if(!in_array($conteo["Cod_asig_1"],$Array_asigs)){
												$Array_asigs[]=$conteo["Cod_asig_1"];
										};
										if(!in_array($conteo["Cod_asig_2"],$Array_asigs)){
												$Array_asigs[]=$conteo["Cod_asig_2"];
										};

								};
										$array_return=self::Get_asig_asociadas($Array_asigs);
										return $array_return;
							}else{
								return false;
							}
						}else{
							return false;
						}
			}else{
				/*Llegados a este punto, el array crece horizontalmente gracias al foreach, lo cual podemos utilizar para ir agregando nuevas entradas al array para realizar mas busquedas
				y extraer mas codigos. Es como explorar un arbol en profundidad*/
				$array_return=$codAsig;
				foreach ($array_return as $value) {
						$Query ="Select * from asignaturas_relacionadas where (Cod_asig_1='".$value."' or Cod_asig_2='".$value."')";
						$con = Database::getConnection();
						$con->query("start transaction;");
						$result = $con->query($Query);
						if ($result) {
							$con->query("Commit;");
							while($conteo=$result->fetch_assoc()){
									if(!in_array($conteo["Cod_asig_1"],$array_return)){
											$array_return[]=$conteo["Cod_asig_1"];
									};
									if(!in_array($conteo["Cod_asig_2"],$array_return)){
											$array_return[]=$conteo["Cod_asig_2"];
									};
							};
						}
				}
				if($array_return===$codAsig){
						return $array_return;
				}else{
					$array_return=self::Get_asig_asociadas($array_return);
					return $array_return;
				}

			}
	}

	public static function Borrar_asignaturas_asociadas($par){
			foreach($par as $asignaturas){
					$asignaturas=explode('-',$asignaturas);
					$con = Database::getConnection();
					$con->query("start transaction;");
					$con->query("delete from asignaturas_relacionadas where Cod_asig_1='".$asignaturas[0]."' and Cod_asig_2='".$asignaturas[1]."';");
					$result_asig=$con->query("Commit;");
					echo'<div class="row">';
					if (!$result_asig) {
							echo"<div align='center'>Ha ocurrido un problema al eliminar la pareja con codigos ".$asignaturas[0]." - ".$asignaturas[1]."</div>";
					}else{	}
					echo "</div><div class='row'><br></div>";
			}
	}

	public static function Insertar_asignaturas_asociadas($Asig1,$Asig2){

					$con = Database::getConnection();
					$con->query("start transaction;");
					$result=$con->query("select * from asignaturas_relacionadas where (((Cod_asig_1='".$Asig1."') and  (Cod_asig_2='".$Asig2."')) || ((Cod_asig_1='".$Asig2."') and  (Cod_asig_2='".$Asig1."')));");
					$con->query("Commit;");
					$result->fetch_all(MYSQLI_ASSOC);
					if(($result->num_rows) == 1){
						echo"<br>";
						echo'<div class="row">';
								echo"<div align='center'>Pareja ya insertada </div>";
						echo"</div>";
					}else{

						$con->query("start transaction;");
						$con->query("Insert into asignaturas_relacionadas (Cod_asig_1,Cod_asig_2) values ('".$Asig1."','".$Asig2."');");
						$result_asig=$con->query("Commit;");
						echo"<br>";
						echo'<div class="row">';
						if (!$result_asig) {
								echo"<div align='center'>Ha ocurrido un problema al Insertar la pareja con codigos ".$Asig1." - ".$Asig2."</div>";
						}else{
								echo"<div align='center'>Insertada satisfactoriamente la pareja ".$Asig1." - ".$Asig2." de la lista de asignaturas"."</div>";
						}
						echo"</div>";
					}
	}

	public static function Array_titulaciones_Tostring($titulaciones,$id,$year,$codAsig,$conv) {
			/* Mostramos un listado de asignaturas asociadas a la asignatura seleccionada*/
			$result=Comprobar_titulaciones::Get_asig_asociadas($codAsig);
			//var_dump($result);
			if($result){
				echo "<strong>Esta asignatura esta asociada con la(s) siguiente(s) Asignatura(s)</strong><br>";
				foreach ($result as $value) {
						if($value!=$codAsig){
							$Query ="Select b.nombre as nombre_tit,c.nombre as nombre_asignatura from asignatura_grado a inner join grado b on a.grado_id=b.id inner join asignatura c on a.asignatura_id=c.id where a.codigo='".$value."'";
							$con = Database::getConnection();
							$con->query("start transaction;");
							$result_asig = $con->query($Query);
							if ($result_asig) {
									$con->query("Commit;");
									while($datos=$result_asig->fetch_assoc()){
											echo '<font color="Red">'.$datos["nombre_asignatura"]." de la titulacion ".$datos["nombre_tit"].'</font><br>';
									};
									$result_asig->free();
							}
							$con->query("start transaction;");
							$Query ="Select * from test_cal_examenes where cod_asig='".$value."' and year='".($year)."' and convocatoria='".$conv."' " ;
							$result = $con->query($Query);
							$con->query("Commit;");
							/* Buscamos las entradas en el año correspondiente */

							echo " - <span style='color:red'> ";
							if($result->num_rows!=0){
									$result=$result->fetch_assoc();
									echo " Dia ".$result["Dia"]." Mes ".$result["Mes"]." Hora ".$result["Hora"];
									if($result["Tipo"]==0){
											echo " Final.<br>";
									}else{
										echo " Parcial.<br>";
									}
							}else{
								echo " Fecha de Exámen no fijada aún.<br>";
							}
						}
				}
			}
			/* Mostramos en que titulaciones se encuentra, si es que es comun a varias. */
			if(count($titulaciones)>1){
				echo"<strong>Esta asignatura se encuentra en las siguientes titulaciones:</strong><br>";
				foreach ($titulaciones as $value){
						$result=Database::get_nombre_titulacion($value);
						foreach($result as $tit) {
							/* Buscamos el codigo de la asignatura */
							$con = Database::getConnection();
							$con->query("start transaction;");
							$Query ="Select codigo from asignatura_grado where asignatura_id='".$id."' and grado_id='".$value."' ";
							$result = $con->query($Query);
							$con->query("Commit;");
							$result=$result->fetch_assoc();

							/* Buscamos las entradas en el año correspondiente */
							$con->query("start transaction;");
							$Query ="Select * from test_cal_examenes where cod_asig='".$result["codigo"]."' and year='".$year."' and convocatoria='".$conv."' " ;
							$result = $con->query($Query);
							$con->query("Commit;");
							/* Buscamos las entradas en el año correspondiente */

							echo '<font color="Red">'.$tit["nombre"];
							if($result->num_rows!=0){
									$result=$result->fetch_assoc();
									echo " - Dia ".$result["Dia"]." Mes ".$result["Mes"]." Hora ".$result["Hora"];
									if($result["Tipo"]==0){
											echo " Final.";
									}else{
										echo " Parcial.";
									}
							}else{
								echo " - Fecha de Exámen no fijada aún";
							}
								echo '</font><br>';
						}
				}
			}else{
				return false;
			}

	}

}

?>
