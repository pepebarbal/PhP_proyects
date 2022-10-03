<?xml version="1.0" encoding="UTF-8"?>
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

<xsl:variable name="convocatoria">
	<xsl:choose>
		<xsl:when test="//Asignatura[contains(@Convocatoria, 'Incidencia')]">
			<xsl:value-of select="@Convocatoria" />
		</xsl:when>
		<xsl:otherwise>PP</xsl:otherwise>
	</xsl:choose>
</xsl:variable>

<xsl:template match="/">
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
		</head>
		<body>
			<table border="2" style='font-size: 10pt;' width="95%" align="center">
				<tr>
					<td width="30%" style="text-align: center;">
						<img src="membrete_pdf.png" />
					</td>
					<td width="40%" style="text-align: center;font-size: 12pt;">
						CALENDARIO DE EXÁMENES<br/>
						CONVOCATORIAS DE INCIDENCIA
					</td>
					<td width="20%" style="text-align: center;font-size: 15pt;"><xsl:value-of select="//Curso/@Año" /></td>
				</tr>
			</table>
			<p/>
			<xsl:choose>
				<xsl:when test="//Calendario">
					<table border="2" style='font-size: 10pt;' width="95%" align="center">
						<tr>
							<th width="10%" style="background-color: #cccccc;">CALEND.</th>
							<th width="10%" style="background-color: #cccccc;">CÓDIGO</th>
							<th width="40%" style="background-color: #cccccc; text-align: justify;">ASIGNATURA</th>
							<th width="5%" style="background-color: #cccccc;">CURSO</th>
							<th width="5%" style="background-color: #cccccc;">DÍA</th>
							<th width="5%" style="background-color: #cccccc;">MES</th>
							<th width="5%" style="background-color: #cccccc;">HORA</th>
							<th width="5%" style="background-color: #cccccc;">CONV.</th>
							<th width="5%" style="background-color: #cccccc;">AÑO</th>
							<th width="5%" style="background-color: #cccccc;">AULA</th>
							<th width="5%" style="background-color: #cccccc;">TIPO</th>
						</tr>
						<xsl:for-each select="//Titulacion">
							<xsl:if test="current()//Asignatura[contains(@Convocatoria, $convocatoria)]">
								<tr>
									<td></td>
									<td></td>
									<td style="background-color: #FFCCCC;"><xsl:value-of select="@nombre" /></td>
								</tr>
								<xsl:call-template name="horario_aula">
									<xsl:with-param name="titulacion" select="@nombre" />
									<xsl:with-param name="nodo" select="current()//Asignatura[contains(@Convocatoria, $convocatoria)][not (@cod_asig = following::Asignatura[contains(@Convocatoria, $convocatoria)]/@cod_asig)][not (@Aviso='No contiene asignaturas')]" />
								</xsl:call-template>
							</xsl:if>
						</xsl:for-each>
					</table>
				</xsl:when>
				<xsl:otherwise>
					<xsl:for-each select="//Titulacion">
						<xsl:if test="current()//Asignatura[contains(@Convocatoria, $convocatoria)]">
							<xsl:call-template name="horario">
								<xsl:with-param name="titulacion" select="@nombre" />
								<xsl:with-param name="nodo" select="current()//Asignatura[contains(@Convocatoria, $convocatoria)][not (@cod_asig = following::Asignatura[contains(@Convocatoria, $convocatoria)]/@cod_asig)][not (@Aviso='No contiene asignaturas')]" />
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
	<xsl:param name="nodo" />
	
	<table border="2" style='font-size: 10pt;' width="95%" align="center">
		<tr>
			<th colspan="10" ><xsl:value-of select="$titulacion" /> </th>
		</tr>
		<tr>
			<th width="10%" rowspan="2" style="background-color: #cccccc;">CÓDIGO</th>
			<th width="40%" rowspan="2" style="background-color: #cccccc; text-align: justify;">ASIGNATURA</th>
			<th width="10%" rowspan="2" style="background-color: #cccccc;">CURSO</th>
			<th width="10%" rowspan="2" style="background-color: #cccccc;">FIN/PAR</th>
			<th width="30%" colspan="3" style="background-color: #cccccc;">Conv. Incidencia</th>
		</tr>
		<tr>
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
			
			<xsl:if test="not(current()[@Aviso])">
				<tr style="background-color: {$color};">
				<td style="text-align: center;"><xsl:value-of select="@cod_asig" /> </td>
				<td><xsl:value-of select="@nombre" /> </td>
				<td style="text-align: center;"><xsl:value-of select="@Curso" /> </td>
				<td style="text-align: center;"><xsl:value-of select="@Tipo" /> </td>
				<td style="text-align: center;">
					<xsl:choose>
						<xsl:when test="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]">
							<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]/@Dia" />/<xsl:call-template name="obtener_mes">
								<xsl:with-param name="mes" select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]/@Mes" />
							</xsl:call-template>
						</xsl:when>
						<xsl:otherwise>--</xsl:otherwise>
					</xsl:choose>
				</td>
				<td style="text-align: center;">
					<xsl:choose>
						<xsl:when test="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]">
							<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]/@Hora" />
						</xsl:when>
						<xsl:otherwise>--</xsl:otherwise>
					</xsl:choose>
				</td>
				<td style="text-align: center;">
					<xsl:choose>
						<xsl:when test="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]">
							<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]/@Aula" />
						</xsl:when>
						<xsl:otherwise>--</xsl:otherwise>
					</xsl:choose>
				</td>
			</tr>
			</xsl:if>
		</xsl:for-each>
	</table>

</xsl:template>

<xsl:template name="horario_aula">
	<xsl:param name="titulacion" />
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
			<td style="text-align: center;">
				<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]/@Dia" />
			</td>
			<td style="text-align: center;">
				<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]/@Mes" />
			</td>
			<td style="text-align: center;">
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]">
						<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]/@Hora" />
					</xsl:when>
					<xsl:otherwise>--</xsl:otherwise>
				</xsl:choose>
			</td>
			<td style="text-align: center;">
				<xsl:choose>
					<xsl:when test="contains(@Convocatoria, 'Primer Cuatrimestre') and contains(@Convocatoria, 'Incidencias')">6</xsl:when>
					<xsl:when test="contains(@Convocatoria, 'Segundo Cuatrimestre') and contains(@Convocatoria, 'Incidencias')">7</xsl:when>
					<xsl:when test="contains(@Convocatoria, 'Segunda Convocatoria') and contains(@Convocatoria, 'Incidencias')">8</xsl:when>
					<xsl:when test="contains(@Convocatoria, 'Febrero')">1</xsl:when>
					<xsl:when test="contains(@Convocatoria, 'Junio')">2</xsl:when>
					<xsl:when test="contains(@Convocatoria, 'Segunda')">3</xsl:when>
					<xsl:when test="contains(@Convocatoria, 'Noviembre')">4</xsl:when>
					<xsl:when test="contains(@Convocatoria, 'Diciembre')">5</xsl:when>
					<xsl:otherwise>--</xsl:otherwise>
				</xsl:choose>
			</td>
			<td style="text-align: center;">
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]/@Mes &gt; 9">
						<xsl:value-of select="substring-before(//Curso/@Año, '-')" />
					</xsl:when>
					<xsl:otherwise>
						<xsl:value-of select="substring-after(//Curso/@Año, '-')" />
					</xsl:otherwise>
				</xsl:choose>
			</td>
			<td style="text-align: center;">
				<xsl:value-of select="//Asignatura[@cod_asig=current()/@cod_asig][contains(@Convocatoria, $convocatoria)]/@Aula" />
			</td>
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
