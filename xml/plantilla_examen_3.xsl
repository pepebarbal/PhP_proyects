<?xml version="1.0" encoding="UTF-8"?>
<!--
***********************************************************************
*********  Plantilla para convocatoria de noviembre y diciembre *******
** Con el elemento Calendario -> Plantilla para subir aulas
** Sin el elemento Calendario -> Publicar en página web
***********************************************************************
-->
<!--
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:fo="http://www.w3.org/1999/XSL/Format" version="1.0">
-->
<xsl:stylesheet version="1.0"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:fo="http://www.w3.org/1999/XSL/Format"
  xmlns:msxsl="urn:schemas-microsoft-com:xslt"
  xmlns:user="http://tempuri.org/msxsl"
>


<xsl:output indent="yes" />

<xsl:template match="/">
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
		</head>
		<body>
			<xsl:choose>
				<xsl:when test="//Calendario">
					<table border="2" style='font-size: 10pt;' width="95%" align="center">
						<tr>
							<td width="30%" style="text-align: center;">
								<img src="membrete_pdf.png" />
							</td>
							<td width="40%" style="text-align: center;font-size: 12pt;">
								CALENDARIO DE EXÁMENES<br/>
								CONVOCATORIAS TERCERA Y EXTRAORDINARIA
							</td>
							<td width="20%" style="text-align: center;font-size: 15pt;"><xsl:value-of select="//Curso/@Año" /></td>
						</tr>
					</table>
					<p/>
					
					<table border="2" style='font-size: 10pt; page-break-inside: avoid;' width="95%" align="center">
						<tr><th colspan="11">Convocatoria de Noviembre</th></tr>
						<tr>
							<th width="10%" style="background-color: #cccccc;">Calendario</th>
							<th width="10%" style="background-color: #cccccc;">CÓDIGO</th>
							<th width="40%" style="background-color: #cccccc; text-align: justify;">ASIGNATURA</th>
							<th width="10%" style="background-color: #cccccc;">CURSO</th>
							<th width="10%" style="background-color: #cccccc;">Día</th>
							<th width="5%" style="background-color: #cccccc;">Mes</th>
							<th width="5%" style="background-color: #cccccc;">Hora</th>
							<th width="5%" style="background-color: #cccccc;">Convocatoria</th>
							<th width="5%" style="background-color: #cccccc;">Año</th>
							<th width="5%" style="background-color: #cccccc;">Aula</th>
							<th width="5%" style="background-color: #cccccc;">Tipo</th>
						</tr>
						<xsl:for-each select="//Titulacion">
							<xsl:if test="current()//Asignatura[contains(@Convocatoria, 'Noviembre')]">
								<tr><td>*</td></tr>
								<xsl:call-template name="horario_aula">
									<xsl:with-param name="titulacion" select="@nombre" />
									<xsl:with-param name="id" select="@id" />
									<xsl:with-param name="conv">Noviembre</xsl:with-param>
									<xsl:with-param name="nodo" select="current()//Asignatura[contains(@Convocatoria, 'Noviembre')][not (@cod_asig = following::Asignatura[contains(@Convocatoria, 'Noviembre')]/@cod_asig)]" />
								</xsl:call-template>
							</xsl:if>
						</xsl:for-each>
					</table>
					<table border="2" style='font-size: 10pt; page-break-inside: avoid;' width="95%" align="center">
						<tr><th colspan="11">Convocatoria de Diciembre</th></tr>
						<tr>
							<th width="10%" style="background-color: #cccccc;">Calendario</th>
							<th width="10%" style="background-color: #cccccc;">CÓDIGO</th>
							<th width="40%" style="background-color: #cccccc; text-align: justify;">ASIGNATURA</th>
							<th width="10%" style="background-color: #cccccc;">CURSO</th>
							<th width="10%" style="background-color: #cccccc;">Día</th>
							<th width="5%" style="background-color: #cccccc;">Mes</th>
							<th width="5%" style="background-color: #cccccc;">Hora</th>
							<th width="5%" style="background-color: #cccccc;">Convocatoria</th>
							<th width="5%" style="background-color: #cccccc;">Año</th>
							<th width="5%" style="background-color: #cccccc;">Aula</th>
							<th width="5%" style="background-color: #cccccc;">Tipo</th>
						</tr>
						<xsl:for-each select="//Titulacion">
							<xsl:if test="current()//Asignatura[contains(@Convocatoria, 'Diciembre')]">
								<tr><td>*</td></tr>
								<xsl:call-template name="horario_aula">
									<xsl:with-param name="titulacion" select="@nombre" />
									<xsl:with-param name="id" select="@id" />
									<xsl:with-param name="conv">Diciembre</xsl:with-param>
									<xsl:with-param name="nodo" select="current()//Asignatura[contains(@Convocatoria, 'Diciembre')][not (@cod_asig = following::Asignatura[contains(@Convocatoria, 'Diciembre')]/@cod_asig)]" />
								</xsl:call-template>
							</xsl:if>
						</xsl:for-each>
					</table>
				</xsl:when>
				<xsl:when test="count(//Info_Titulacion[not (@Convocatoria = preceding-sibling::Info_Titulacion/@Convocatoria)]) = 1">
					<table border="2" style='font-size: 10pt;' width="95%" align="center">
						<tr>
							<td width="30%" style="text-align: center;">
								<img src="membrete_pdf.png" />
							</td>
							<td width="40%" style="text-align: center;font-size: 12pt;">
								CALENDARIO DE EXÁMENES<br/>
								<xsl:choose>
									<xsl:when test="//Info_Titulacion/@Convocatoria = '4'">
										CONVOCATORIA EXTRAORDINARIA
									</xsl:when>
									<xsl:otherwise>
										TERCERA CONVOCATORIA
									</xsl:otherwise>
								</xsl:choose>
							</td>
							<td width="20%" style="text-align: center;font-size: 15pt;"><xsl:value-of select="//Curso/@Año" /></td>
						</tr>
					</table>
					<p/>
					
					<xsl:for-each select="//Titulacion">
						<xsl:if test="current()//Asignatura[contains(@Convocatoria, 'Noviembre') or contains(@Convocatoria, 'Diciembre')]">
							<xsl:call-template name="horario_una">
								<xsl:with-param name="titulacion" select="@nombre" />
								<xsl:with-param name="id" select="@id" />
								<xsl:with-param name="conv" select="//Info_Titulacion/@Convocatoria" />
								<xsl:with-param name="nodo" select="current()//Asignatura[contains(@Convocatoria, 'Noviembre') or contains(@Convocatoria, 'Diciembre')][not (@cod_asig = following::Asignatura[contains(@Convocatoria, 'Noviembre') or contains(@Convocatoria, 'Diciembre')]/@cod_asig)]" />
							</xsl:call-template>
						</xsl:if>
					</xsl:for-each>
				</xsl:when>
				<xsl:otherwise>
					<table border="2" style='font-size: 10pt;' width="95%" align="center">
						<tr>
							<td width="30%" style="text-align: center;">
								<img src="membrete_pdf.png" />
							</td>
							<td width="40%" style="text-align: center;font-size: 12pt;">
								CALENDARIO DE EXÁMENES<br/>
								CONVOCATORIAS TERCERA Y EXTRAORDINARIA
							</td>
							<td width="20%" style="text-align: center;font-size: 15pt;"><xsl:value-of select="//Curso/@Año" /></td>
						</tr>
					</table>
					<p/>
					
					<xsl:for-each select="//Titulacion">
						<xsl:if test="current()//Asignatura[contains(@Convocatoria, 'extraordinaria') or contains(@Convocatoria, 'Tercera')]">
									<xsl:call-template name="horario">
										<xsl:with-param name="titulacion" select="@nombre" />
										<xsl:with-param name="id" select="@id" />
										<xsl:with-param name="nodo" select="current()//Asignatura[contains(@Convocatoria, 'Noviembre') or contains(@Convocatoria, 'Diciembre')][not (@cod_asig = following::Asignatura[contains(@Convocatoria, 'Noviembre') or contains(@Convocatoria, 'Diciembre')]/@cod_asig)]" />
									</xsl:call-template>
						</xsl:if>
					</xsl:for-each>
				</xsl:otherwise>
			</xsl:choose>
		</body>
	</html>
</xsl:template>
<!-- -->
<xsl:template name="horario">
	<xsl:param name="titulacion" />
	<xsl:param name="id" />
	<xsl:param name="nodo" />
	
	<table border="2" style='font-size: 10pt; page-break-inside: avoid;' width="95%" align="center">
		<tr>
			<th colspan="10" >
				<xsl:value-of select="$titulacion" />
				<xsl:if test="//Info_Titulacion[@Id_tit=$id]/@Definitiva='0'">
					- Provisional
				</xsl:if>
			</th>
		</tr>
		<tr>
			<th width="10%" rowspan="2" style="background-color: #cccccc;">CÓDIGO</th>
			<th width="40%" rowspan="2" style="background-color: #cccccc; text-align: justify;">ASIGNATURA</th>
			<th width="10%" rowspan="2" style="background-color: #cccccc;">CURSO</th>
			<th width="10%" rowspan="2" style="background-color: #cccccc;">FIN/PAR</th>
			<th width="15%" colspan="3" style="background-color: #cccccc;">Conv. Extraordinaria</th>
			<th width="15%" colspan="3" style="background-color: #cccccc;">Tercera Conv.</th>
		</tr>
		<tr>
			<th width="5%" style="background-color: #cccccc;">DÍA</th>
			<th width="5%" style="background-color: #cccccc;">HORA</th>
			<th width="5%" style="background-color: #cccccc;">AULA</th>
			<th width="5%" style="background-color: #cccccc;">DÍA</th>
			<th width="5%" style="background-color: #cccccc;">HORA</th>
			<th width="5%" style="background-color: #cccccc;">AULA</th>
		</tr>
		<xsl:for-each select="$nodo">
			<xsl:sort select="@Curso" />
			<xsl:sort select="@cod_asig" />
			<xsl:variable name="color">
				<xsl:choose>
					<xsl:when test="position() mod 2 = 0">#ffffaa</xsl:when>
					<xsl:otherwise>#ffffee</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			
			<tr style="background-color: {$color};">
				<td style="text-align: center;"><xsl:value-of select="@cod_asig" /> </td>
				<td><xsl:value-of select="@nombre" /> </td>
				<td style="text-align: center;"><xsl:value-of select="@Curso" /> </td>
				<td style="text-align: center;"><xsl:value-of select="@Tipo" /> </td>
				<td style="text-align: center;">
					<xsl:choose>
						<xsl:when test="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Noviembre')]">
							<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Noviembre')]/@Dia" />/<xsl:call-template name="obtener_mes">
								<xsl:with-param name="mes" select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Noviembre')]/@Mes" />
							</xsl:call-template>
						</xsl:when>
						<xsl:otherwise>--</xsl:otherwise>
					</xsl:choose>
				</td>
				<td style="text-align: center;">
					<xsl:choose>
						<xsl:when test="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Noviembre')]">
							<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Noviembre')]/@Hora" />
						</xsl:when>
						<xsl:otherwise>--</xsl:otherwise>
					</xsl:choose>
				</td>
				<td style="text-align: center;">
					<xsl:choose>
						<xsl:when test="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Noviembre')]">
							<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Noviembre')]/@Aula" />
						</xsl:when>
						<xsl:otherwise>--</xsl:otherwise>
					</xsl:choose>
				</td>
				<td style="text-align: center;">
					<xsl:choose>
						<xsl:when test="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Diciembre')]">
							<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Diciembre')]/@Dia" />/<xsl:call-template name="obtener_mes">
								<xsl:with-param name="mes" select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Diciembre')]/@Mes" />
							</xsl:call-template>
						</xsl:when>
						<xsl:otherwise>--</xsl:otherwise>
					</xsl:choose>
				</td>
				<td style="text-align: center;">
					<xsl:choose>
						<xsl:when test="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Diciembre')]">
							<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Diciembre')]/@Hora" />
						</xsl:when>
						<xsl:otherwise>--</xsl:otherwise>
					</xsl:choose>
				</td>
				<td style="text-align: center;">
					<xsl:choose>
						<xsl:when test="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Diciembre')]">
							<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, 'Diciembre')]/@Aula" />
						</xsl:when>
						<xsl:otherwise>--</xsl:otherwise>
					</xsl:choose>
				</td>
			</tr>
		</xsl:for-each>
	</table>

</xsl:template>

<xsl:template name="horario_una">
	<xsl:param name="titulacion" />
	<xsl:param name="id" />
	<xsl:param name="conv" />
	<xsl:param name="nodo" />
	
	<xsl:variable name="conv_nombre">
		<xsl:choose>
			<xsl:when test="$conv = '1'">Febrero</xsl:when>
			<xsl:when test="$conv = '2'">Junio</xsl:when>
			<xsl:when test="$conv = '3'">Septiembre</xsl:when>
			<xsl:when test="$conv = '4'">Noviembre</xsl:when>
			<xsl:when test="$conv = '5'">Diciembre</xsl:when>
			<xsl:when test="$conv = '6'">Primer Cuatrimestre</xsl:when>
			<xsl:when test="$conv = '7'">Segunda Cuatrimestre</xsl:when>
			<xsl:when test="$conv = '8'">Incidencias</xsl:when>
		</xsl:choose>
	</xsl:variable>
	<table border="2" style='font-size: 10pt; page-break-inside: avoid;' width="95%" align="center">
		<tr>
			<th colspan="10" >
				<xsl:value-of select="$titulacion" />
				<xsl:if test="//Info_Titulacion[@Id_tit=$id]/@Definitiva='0'">
					- Provisional
				</xsl:if>
			</th>
		</tr>
		<tr>
			<th width="10%" style="background-color: #cccccc;">CÓDIGO</th>
			<th width="40%" style="background-color: #cccccc; text-align: justify;">ASIGNATURA</th>
			<th width="10%" style="background-color: #cccccc;">CURSO</th>
			<th width="10%" style="background-color: #cccccc;">FIN/PAR</th>
			<th width="10%" style="background-color: #cccccc;">DÍA</th>
			<th width="10%" style="background-color: #cccccc;">HORA</th>
			<th width="10%" style="background-color: #cccccc;">AULA</th>
		</tr>
		<xsl:for-each select="$nodo">
			<xsl:sort select="@Curso" />
			<xsl:sort select="@cod_asig" />
			<xsl:variable name="color">
				<xsl:choose>
					<xsl:when test="position() mod 2 = 0">#ffffaa</xsl:when>
					<xsl:otherwise>#ffffee</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			
			<tr style="background-color: {$color};">
				<td style="text-align: center;"><xsl:value-of select="@cod_asig" /> </td>
				<td><xsl:value-of select="@nombre" /> </td>
				<td style="text-align: center;"><xsl:value-of select="@Curso" /> </td>
				<td style="text-align: center;"><xsl:value-of select="@Tipo" /> </td>
				<td style="text-align: center;">
					<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig and contains(@Convocatoria, $conv_nombre)]/@Dia" />/<xsl:call-template name="obtener_mes">
								<xsl:with-param name="mes" select="//Asignatura[@cod_asig=current()/@cod_asig and contains(@Convocatoria, $conv_nombre)]/@Mes" />
							</xsl:call-template>
				</td>
				<td style="text-align: center;">
					<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig and contains(@Convocatoria, $conv_nombre)]/@Hora" />
				</td>
				<td style="text-align: center;">
					<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig and contains(@Convocatoria, $conv_nombre)]/@Aula" />
				</td>
			</tr>
		</xsl:for-each>
	</table>

</xsl:template>

<xsl:template name="horario_aula">
	<xsl:param name="titulacion" />
	<xsl:param name="id" />
	<xsl:param name="conv" />
	<xsl:param name="nodo" />
		
	<xsl:for-each select="$nodo">
		<xsl:sort select="@Curso" />
		<xsl:sort select="@cod_asig" />
		<xsl:variable name="color">
			<xsl:choose>
				<xsl:when test="position() mod 2 = 0">#ffffaa</xsl:when>
				<xsl:otherwise>#ffffee</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		
		<tr style="background-color: {$color};">
			<td style="text-align: center;"><xsl:value-of select="//Calendario/@Id_cal" /> </td>
			<td style="text-align: center;"><xsl:value-of select="@cod_asig" /> </td>
			<td><xsl:value-of select="@nombre" /> </td>
			<td style="text-align: center;"><xsl:value-of select="@Curso" /> </td>
			<td style="text-align: center;"><xsl:value-of select="@Dia" /></td>
			<td style="text-align: center;"><xsl:value-of select="@Mes" /></td>
			<td style="text-align: center;"><xsl:value-of select="@Hora" /></td>
			<td style="text-align: center;">
				<xsl:choose>
					<xsl:when test="$conv='Noviembre'">4</xsl:when>
					<xsl:otherwise>5</xsl:otherwise>
				</xsl:choose>
			</td>
			<td style="text-align: center;">
				<xsl:choose>
					<xsl:when test="@Mes &gt; 8">
						<xsl:value-of select="substring-before(//Curso/@Año, '-')" />
					</xsl:when>
					<xsl:otherwise>
						<xsl:value-of select="substring-after(//Curso/@Año, '-')" />
					</xsl:otherwise>
				</xsl:choose>
			</td>
			<td style="text-align: center;"><xsl:value-of select="@Aula" /></td>
			<td style="text-align: center;">
				<xsl:choose>
					<xsl:when test="@Tipo='Final'">0</xsl:when>
					<xsl:otherwise>1</xsl:otherwise>
				</xsl:choose>
			</td>
		</tr>
	</xsl:for-each>
	
</xsl:template>

<xsl:template name="obtener_mes">
	<xsl:param name="mes" />
	<xsl:choose>
		<xsl:when test="$mes=1">ene</xsl:when>
		<xsl:when test="$mes=2">feb</xsl:when>
		<xsl:when test="$mes=3">mar</xsl:when>
		<xsl:when test="$mes=4">abr</xsl:when>
		<xsl:when test="$mes=5">may</xsl:when>
		<xsl:when test="$mes=6">jun</xsl:when>
		<xsl:when test="$mes=7">jul</xsl:when>
		<xsl:when test="$mes=8">ago</xsl:when>
		<xsl:when test="$mes=9">sep</xsl:when>
		<xsl:when test="$mes=10">oct</xsl:when>
		<xsl:when test="$mes=11">nov</xsl:when>
		<xsl:when test="$mes=12">dic</xsl:when>
		<xsl:otherwise>desc</xsl:otherwise>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>
