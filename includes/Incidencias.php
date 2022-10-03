<?php

class Incidencias{

	private function __construct(){}

	public static	function Get_semana_incidencias($Last_date_of_conv)
	{
				$salida=[];
				$indice_array=0;
				if(!empty($Last_date_of_conv)){
						$array_mes=array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
						$meses_year=array('',31,28,31,30,31,30,31,31,30,31,30,31);

						$dia=$Last_date_of_conv["dia"];
				    $mes=$Last_date_of_conv["mes"];
				    $year=$Last_date_of_conv["year"];

						if($mes<3 && $mes>0){
							if (Year::bisiesto($year)){
							    ++$meses_year[2];	/** si es bisiesto, 29 dias **/
							}
						}

						$contador_dia= Year::saber_dia($dia.'-' .$mes. '-' .$year);

						/**Si el ultimo dia del periodo de examenes es lunes, avanzamos una posicion en la semana para buscar el siguiente */
				    if($contador_dia== 'L'){
				        if( $dia == $meses_year[$mes]){
				            $dia=1;
				            $mes++;
				        }
				        $contador_dia= Year::saber_dia($year.'-' .$mes. '-' .$dia);
				    }

				    while($contador_dia != 'L'){ //mientras no sea lunes
				        /* Hay que tener en cuenta -> Fin de mes, fin de año*/
				        if( $dia > $meses_year[$mes]){  /* si es el ultimo dia del mes +1, el dia no existe, y pasamos al primero del siguiente */
				            $dia=0; //0, con el preincremento queda en 1 al calcular el dia.
				            $mes++;
				        }

				        $contador_dia= Year::saber_dia($year.'-' .$mes. '-' .++$dia);
				    }
						$week_counter=0;
						/* recorremos sumando  hasta que tenemos la semana completa */
						while($week_counter<2){
								if(Year::saber_dia($year.'-' .$mes. '-' .$dia)!='S' && Year::saber_dia($year.'-' .$mes. '-' .$dia)!='D'){

				        if( ($dia > $meses_year[$mes]) && ($mes<12)){
				            $dia=0;
				            $mes++;
				        }

				        $salida[$indice_array]["year"]=$year;
								$salida[$indice_array]["mes"]=$mes;
								$salida[$indice_array]["dia"]=$dia++;
								$indice_array++;
							}else{
								if(Year::saber_dia($year.'-' .$mes. '-' .$dia)!='S'){
										$week_counter++;
								}

								if( ($dia > $meses_year[$mes]) && ($mes<12) ){
				            $dia=0;
				            $mes++;
				        }

								$dia++;
							}
				    }
				}
				return $salida;
	}

	public static	function Get_asignaturas_incidencias($year,$convocatoria){
			$convocatoria_tabla=0;
			switch($convocatoria){
					case 6 :	$convocatoria_tabla=1;
										break;
					case 7 :	$convocatoria_tabla=2;
										break;
					case 8 :	$convocatoria_tabla=3;
										break;
					default: 	return false;
			}

			$array_asignaturas;

			$Query = "select * from examen_incidencia where year='".$year."' and Convocatoria='".$convocatoria_tabla."' and valido='1' ";
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
								$salida[]=$value["codigo_incidencia"];
						}
						return $salida;
					}else{
						return false;
					}
			}
	}

	public static	function get_alumnos_por_examenes_incidencias($codigo,$year,$convocatoria){
			$convocatoria_tabla=0;
			switch($convocatoria){
					case 6 :	$convocatoria_tabla=1;
										break;
					case 7 :	$convocatoria_tabla=2;
										break;
					case 8 :	$convocatoria_tabla=3;
										break;
					default: 	return false;
			}

			$Query = "select distinct alumno, dni_alumno from examen_incidencia where year='".$year."' and Convocatoria='".$convocatoria_tabla."' and valido='1' and codigo_incidencia like '%".$codigo."%'";
			$con = Database::getConnection();
			$con->query("start transaction;");
			$result = $con->query($Query);
			$con->query("Commit;");

			$resultado=$result->fetch_all(MYSQLI_ASSOC);

			foreach ($resultado as $valor) {
				$Query = "select distinct codigo_incidencia from examen_incidencia where year='".$year."' and Convocatoria='".$convocatoria_tabla."' and valido='1' and dni_alumno='".$valor["dni_alumno"]."'";
				$con->query("start transaction;");
				$result = $con->query($Query);
				$con->query("Commit;");
				$result->fetch_all(MYSQLI_ASSOC);

				foreach($result as $valor_codigos){

						$con->query("start transaction;");
						$Query ="Select * from test_cal_examenes where cod_asig='".explode(';',$valor_codigos["codigo_incidencia"])[0]."' and year='".$year."' and convocatoria='".$convocatoria."' " ;
						$result2 = $con->query($Query);
						$con->query("Commit;");
						/* Buscamos las entradas en el año correspondiente */

						echo "<strong>Alumno: ".$valor["alumno"]."</strong> - <span style='color:red'> Asignatura: ".

						Comprobar_titulaciones::Get_nombre_asig(explode(';',$valor_codigos["codigo_incidencia"])[0])." - ";
						if($result2->num_rows!=0){
								$result2=$result2->fetch_assoc();
								echo " Dia ".$result2["Dia"]." Mes ".$result2["Mes"]." Hora ".$result2["Hora"];
								if($result2["Tipo"]==0){
										echo " Final.";
								}else{
									echo " Parcial.";
								}
						}else{
							echo " Fecha de Exámen no fijada aún";
						}


						echo "</span><br>";

				}

			}

	}


	/**/
	public static	function print_incidencias($array_dias,$cuatrimestre,$titulacion,$curso,$Asignaturas_en_bd,$id_cal)
	{
				$contador=0;
				$contador_asig=0;


					while (!empty($array_dias[$contador])) {


							$fecha = date('N', strtotime($array_dias[$contador]["year"]."-".$array_dias[$contador]["mes"]."-".$array_dias[$contador]["dia"]));
							echo "<tr><td><strong>".$array_dias[$contador]["dia"]."/".$array_dias[$contador]["mes"]."/".$array_dias[$contador]["year"]."</strong></td>";

							$Query="select estado from calendario where dia='".$array_dias[$contador]["dia"]."' and mes='".$array_dias[$contador]["mes"]."' and year='".$array_dias[$contador]["year"]."'";
							$con = Database::getConnection();
			        $result = $con->query($Query);
			        if($result){
			           	$result=mysqli_fetch_array($result,MYSQLI_ASSOC);
									if(!empty($result["estado"])){
										if(($result["estado"]==2) || ($result["estado"]==3) || ($result["estado"]==1) || ($result["estado"]==10) || ($result["estado"]==13) || ($result["estado"]==14) || ($result["estado"]==11)){
											echo"<td class='Fiesta'>";
											if($result["estado"]==2){
													echo"<span class='text-center'><strong> No Lectivo </strong></span>";
											}else if($result["estado"]==3){
													echo"<span class='text-center'><strong> Festivo </strong></span>";
											}else if($result["estado"]==10){
													echo"<span class='text-center'><strong> Festivo Romería Rocio </strong></span>";
											}else if($result["estado"]==13){
													echo"<span class='text-center'><strong> Festivo Navidad </strong></span>";
											}else if($result["estado"]==14){
													echo"<span class='text-center'><strong> Vacaciones Navidad </strong></span>";
											}else if($result["estado"]==1){
													echo"<span class='text-center'><strong> Festivo Semana santa </strong></span>";
											}else if($result["estado"]==11){
													echo"<span class='text-center'><strong> Vacaciones Generales </strong></span>";
											}
										}else if( ($result["estado"]==4) || ($result["estado"]==5) || ($result["estado"]==6) || ($result["estado"]==7) ){
												echo"<td class='Ini-fin'>";
												if($result["estado"]==4){
														echo"<span class='text-center'><strong> Inicio Primer cuatrimestre </strong></span>";
												}else if($result["estado"]==5){
														echo"<span class='text-center'><strong> Inicio Segundo cuatrimestre </strong></span>";
												}else if($result["estado"]==6){
														echo"<span class='text-center'><strong> Fin Primer cuatrimestre </strong></span>";
												}else if($result["estado"]==7){
														echo"<span class='text-center'><strong> Fin Segundo cuatrimestre </strong></span>";
												}


										}else{
											echo"<td>";
										}
									}else{
											echo"<td>";
									}
			        }else{
									echo"<td>";
							}

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
	/**/

}

?>
