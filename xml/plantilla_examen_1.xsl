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

<xsl:template match="/">
	<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
		</head>
		<body>
			<xsl:choose>
				<xsl:when test="//Calendario/@Id_cal">
					<p style="page-break-after: always;">
						<xsl:call-template name="horario1">
							<xsl:with-param name="curso">1</xsl:with-param>
						</xsl:call-template>
						<!--
						<p>(* Campus de La Rábida)</p>
						<p>(** Campus de La Merced)</p>
						-->
					</p>
					<xsl:if test="//Asignatura[@Curso=2]">
						<p style="page-break-after: always;">
							<xsl:call-template name="horario1">
								<xsl:with-param name="curso">2</xsl:with-param>
							</xsl:call-template>
							<!--
							<p>(* Campus de La Rábida)</p>
							<p>(** Campus de La Merced)</p>
							-->
						</p>
					</xsl:if>
					<xsl:if test="//Asignatura[@Curso=3]">
						<p style="page-break-after: always;">
							<xsl:call-template name="horario1">
								<xsl:with-param name="curso">3</xsl:with-param>
							</xsl:call-template>
							<!--
							<p>(* Campus de La Rábida)</p>
							<p>(** Campus de La Merced)</p>
							-->
						</p>
					</xsl:if>
					<xsl:if test="//Asignatura[@Curso=4]">
						<p style="page-break-after: always;">
							<xsl:call-template name="horario1">
								<xsl:with-param name="curso">4</xsl:with-param>
							</xsl:call-template>
							<!--
							<p>(* Campus de La Rábida)</p>
							<p>(** Campus de La Merced)</p>
							-->
						</p>
					</xsl:if>
					<xsl:if test="//Asignatura[@Curso=5]">
						<p style="page-break-after: always;">
							<xsl:call-template name="horario1">
								<xsl:with-param name="curso">5</xsl:with-param>
							</xsl:call-template>
							<!--
							<p>(* Campus de La Rábida)</p>
							<p>(** Campus de La Merced)</p>
							-->
						</p>
					</xsl:if>
				</xsl:when>
				<xsl:otherwise>
					<p style="page-break-after: always;">
						<xsl:call-template name="horario">
							<xsl:with-param name="curso">1</xsl:with-param>
						</xsl:call-template>
						<!--
						<p>(* Campus de La Rábida)</p>
						<p>(** Campus de La Merced)</p>
						-->
					</p>
					<xsl:if test="//Asignatura[@Curso=2]">
						<p style="page-break-after: always;">
							<xsl:call-template name="horario">
								<xsl:with-param name="curso">2</xsl:with-param>
							</xsl:call-template>
							<!--
							<p>(* Campus de La Rábida)</p>
							<p>(** Campus de La Merced)</p>
							-->
						</p>
					</xsl:if>
					<xsl:if test="//Asignatura[@Curso=3]">
						<p style="page-break-after: always;">
							<xsl:call-template name="horario">
								<xsl:with-param name="curso">3</xsl:with-param>
							</xsl:call-template>
							<!--
							<p>(* Campus de La Rábida)</p>
							<p>(** Campus de La Merced)</p>
							-->
						</p>
					</xsl:if>
					<xsl:if test="//Asignatura[@Curso=4]">
						<p style="page-break-after: always;">
							<xsl:call-template name="horario">
								<xsl:with-param name="curso">4</xsl:with-param>
							</xsl:call-template>
							<!--
							<p>(* Campus de La Rábida)</p>
							<p>(** Campus de La Merced)</p>
							-->
						</p>
					</xsl:if>
					<xsl:if test="//Asignatura[@Curso=5]">
						<p style="page-break-after: always;">
							<xsl:call-template name="horario">
								<xsl:with-param name="curso">5</xsl:with-param>
							</xsl:call-template>
							<!--
							<p>(* Campus de La Rábida)</p>
							<p>(** Campus de La Merced)</p>
							-->
						</p>
					</xsl:if>
				</xsl:otherwise>
			</xsl:choose>
		</body>
	</html>
</xsl:template>
<!---->
<xsl:template name="horario">
	<xsl:param name="curso" />
	<xsl:variable name="spant">
		<xsl:choose>
			<xsl:when test="//Asignatura[@Tipo='Parcial']">
				2
			</xsl:when>
			<xsl:otherwise>1</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<table border="2" style='font-size: 10pt;' width="95%" align="center">
		<tr>
			<td width="30%" style="text-align: center;">
				<img src="membrete_pdf.png" />
			</td>
			<td width="40%" style="text-align: center;font-size: 15pt;"><xsl:value-of select="//Titulacion/@nombre" /></td>
			<td width="20%" style="text-align: center;font-size: 15pt;"><xsl:value-of select="//Curso/@Año" /></td>
		</tr>
	</table>
	<table border="1" style='font-size: 10pt;' width="95%" align="center">
		<tr>
			<th rowspan='3' style='background-color: #cccccc;'>Código</th>
			<th rowspan='3' style='background-color: #cccccc;'>Asignatura</th>
			<th rowspan='3' style='background-color: #cccccc;'>Curso</th>
			<th rowspan='3' style='background-color: #cccccc;'>A/C</th>
			<th rowspan='3' style='background-color: #cccccc;'>Carácter</th>
			<th rowspan='3' style='background-color: #cccccc;'>ECTS</th>
			<th colspan='{$spant+8}' style='background-color: #cccccc;'>Convocatoria</th>
		</tr>
		<tr>
			<th colspan='3' style='background-color: #cccccc;'>Febrero</th>
			<th colspan='{$spant+2}' style='background-color: #cccccc;'>Junio</th>
			<th colspan='3' style='background-color: #cccccc;'>Segunda (Septiembre)</th>
		</tr>
		<tr>
			<th style='background-color: #cccccc;'>Día</th>
			<th style='background-color: #cccccc;'>Hora</th>
			<th style='background-color: #cccccc;'>Aula</th>
			<th style='background-color: #cccccc;' colspan="{$spant}">Día</th>
			<th style='background-color: #cccccc;'>Hora</th>
			<th style='background-color: #cccccc;'>Aula</th>
			<th style='background-color: #cccccc;'>Día</th>
			<th style='background-color: #cccccc;'>Hora</th>
			<th style='background-color: #cccccc;'>Aula</th>
		</tr>
		<xsl:for-each select="//Asignatura[@Curso=$curso][not (@cod_asig = preceding::Asignatura/@cod_asig)]">
			<xsl:sort select="@Convocatoria" />
			<xsl:variable name="id_asig" select="@cod_asig" />
			<xsl:variable name="color">
				<xsl:choose>
					<xsl:when test="../@id and count(../preceding-sibling::Itinerario) = 0">
						<xsl:choose>
							<xsl:when test="position() mod 2 = 1">#ccffcc</xsl:when>
							<xsl:otherwise>#eeffee</xsl:otherwise>
						</xsl:choose>
					</xsl:when>
					<xsl:when test="../@id and count(../preceding-sibling::Itinerario) = 1">
						<xsl:choose>
							<xsl:when test="position() mod 2 = 1">#ffccff</xsl:when>
							<xsl:otherwise>#ffeeff</xsl:otherwise>
						</xsl:choose>
					</xsl:when>
					<xsl:when test="../@id and count(../preceding-sibling::Itinerario) = 2">
						<xsl:choose>
							<xsl:when test="position() mod 2 = 1">#ccccff</xsl:when>
							<xsl:otherwise>#eeeeff</xsl:otherwise>
						</xsl:choose>
					</xsl:when>
					<xsl:otherwise>
						<xsl:choose>
							<xsl:when test="position() mod 2 = 1">#ffffaa</xsl:when>
							<xsl:otherwise>#ffffee</xsl:otherwise>
						</xsl:choose>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="span">
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=$id_asig and @Tipo='Parcial']">
						2
					</xsl:when>
					<xsl:otherwise>1</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="tipo_ex">
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=$id_asig and @Tipo='Parcial']">
						Parcial
					</xsl:when>
					<xsl:otherwise>Final</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<tr style="background-color: {$color}">
				<td rowspan="{$span}" style="text-align: center;"><xsl:value-of select="@cod_asig" /></td>
				<td rowspan="{$span}"><xsl:value-of select="@nombre" /></td>
				<td rowspan="{$span}" style="text-align: center;"><xsl:value-of select="@Curso" /></td>
				<td rowspan="{$span}" style="text-align: center;">
					<xsl:choose>
						<xsl:when test="@duracion='Anual'">A</xsl:when>
						<xsl:when test="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]">1C</xsl:when>
						<xsl:otherwise>2C</xsl:otherwise>
					</xsl:choose>
				</td>
				<td rowspan="{$span}" style="text-align: center;"><xsl:value-of select="@Caracter" /></td>
				<td rowspan="{$span}" style="text-align: center;"><xsl:value-of select="@Creditos" /></td>
				<!-- Febrero -->
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]">
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]/@Dia" />/
							<xsl:call-template name="obtener_mes">
								<xsl:with-param name="mes" select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]/@Mes" />
							</xsl:call-template>
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]/@Hora" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]/@Aula" />
						</td>
					</xsl:when>
					<xsl:otherwise>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
					</xsl:otherwise>
				</xsl:choose>
				<!-- Junio -->
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')]">
						<xsl:choose>
							<xsl:when test="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Parcial']">
								<td style="text-align: center;">2P</td>
								<td style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Parcial']/@Dia" />/
									<xsl:call-template name="obtener_mes">
										<xsl:with-param name="mes" select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Parcial']/@Mes" />
									</xsl:call-template>
								</td>
								<td style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Parcial']/@Hora" />
								</td>
								<td style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Parcial']/@Aula" />
								</td>
							</xsl:when>
							<xsl:otherwise>
								<td colspan='{$spant - $span +1}' style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')]/@Dia" />/
									<xsl:call-template name="obtener_mes">
										<xsl:with-param name="mes" select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')]/@Mes" />
									</xsl:call-template>
								</td>
								<td style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')]/@Hora" />
								</td>
								<td>
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')]/@Aula" />
								</td>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:when>
					<xsl:otherwise>
						<td colspan="{$spant - $span +1}" style="text-align:center;">--</td>
						<td style="text-align:center;">--</td>
						<td style="text-align:center;">--</td>
					</xsl:otherwise>
				</xsl:choose>
				<!-- Segunda convocatoria -->
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Segunda')]">
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Segunda')]/@Dia" />/
							<xsl:call-template name="obtener_mes">
								<xsl:with-param name="mes" select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Segunda')]/@Mes" />
							</xsl:call-template>
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Segunda')]/@Hora" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Segunda')]/@Aula" />
						</td>
					</xsl:when>
					<xsl:otherwise>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
					</xsl:otherwise>
				</xsl:choose>
			</tr>
			<xsl:if test="$span=2">
				<tr style="background-color: {$color}">
					<td style="text-align: center;">F</td>
					<td style="text-align: center;">
						<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Final']/@Dia" />/
						<xsl:call-template name="obtener_mes">
							<xsl:with-param name="mes" select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Final']/@Mes" />
						</xsl:call-template>
					</td>
					<td style="text-align: center;">
						<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Final']/@Hora" />
					</td>
					<td style="text-align: center;">
						<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Final']/@Aula" />
					</td>
				</tr>
			</xsl:if>
		</xsl:for-each>
	</table>
</xsl:template>

<xsl:template name="horario1">
	<xsl:param name="curso" />
	<xsl:variable name="spant">
		<xsl:choose>
			<xsl:when test="//Asignatura[@Tipo='Parcial']">
				2
			</xsl:when>
			<xsl:otherwise>1</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>
	<table border="2" style='font-size: 10pt;' width="95%" align="center">
		<tr>
			<td width="30%" style="text-align: center;">
				<img src="membrete_pdf.png" />
			</td>
			<td width="40%" style="text-align: center;font-size: 15pt;"><xsl:value-of select="//Titulacion/@nombre" /></td>
			<td width="20%" style="text-align: center;font-size: 15pt;"><xsl:value-of select="//Curso/@Año" /></td>
		</tr>
	</table>
	<table border="1" style='font-size: 10pt;' width="95%" align="center">
		<tr>
			<th rowspan='3' style='background-color: #cccccc;'>ID calendario</th>
			<th rowspan='3' style='background-color: #cccccc;'>Código</th>
			<th rowspan='3' style='background-color: #cccccc;'>Asignatura</th>
			<th rowspan='3' style='background-color: #cccccc;'>Curso</th>
			<th rowspan='3' style='background-color: #cccccc;'>A/C</th>
			<th rowspan='3' style='background-color: #cccccc;'>Carácter</th>
			<th rowspan='3' style='background-color: #cccccc;'>ECTS</th>
			<th colspan='24' style='background-color: #cccccc;'>Convocatoria</th>
		</tr>
		<tr>
			<th colspan='8' style='background-color: #cccccc;'>Febrero</th>
			<th colspan='8' style='background-color: #cccccc;'>Junio</th>
			<th colspan='8' style='background-color: #cccccc;'>Segunda (Septiembre)</th>
		</tr>
		<tr>
			<th style='background-color: #cccccc;'>Día</th>
			<th style='background-color: #cccccc;'>Mes</th>
			<th style='background-color: #cccccc;'>Hora</th>
			<th style='background-color: #cccccc;'>Convocatoria</th>
			<th style='background-color: #cccccc;'>Año</th>
			<th style='background-color: #cccccc;'>Aula</th>
			<th style='background-color: #cccccc;'>Tipo</th>
			<th style='background-color: #cccccc;'>Tipo</th>
			<th style='background-color: #cccccc;'>Día</th>
			<th style='background-color: #cccccc;'>Mes</th>
			<th style='background-color: #cccccc;'>Hora</th>
			<th style='background-color: #cccccc;'>Convocatoria</th>
			<th style='background-color: #cccccc;'>Año</th>
			<th style='background-color: #cccccc;'>Aula</th>
			<th style='background-color: #cccccc;'>Tipo</th>
			<th style='background-color: #cccccc;'>Tipo</th>
			<th style='background-color: #cccccc;'>Día</th>
			<th style='background-color: #cccccc;'>Mes</th>
			<th style='background-color: #cccccc;'>Hora</th>
			<th style='background-color: #cccccc;'>Convocatoria</th>
			<th style='background-color: #cccccc;'>Año</th>
			<th style='background-color: #cccccc;'>Aula</th>
			<th style='background-color: #cccccc;'>Tipo</th>
			<th style='background-color: #cccccc;'>Tipo</th>
		</tr>
		<xsl:for-each select="//Asignatura[@Curso=$curso][not (@cod_asig = preceding::Asignatura/@cod_asig)]">
			<xsl:sort select="@Convocatoria" />
			<xsl:variable name="id_asig" select="@cod_asig" />
			<xsl:variable name="color">
				<xsl:choose>
					<xsl:when test="../@id and count(../preceding-sibling::Itinerario) = 0">
						<xsl:choose>
							<xsl:when test="position() mod 2 = 1">#ccffcc</xsl:when>
							<xsl:otherwise>#eeffee</xsl:otherwise>
						</xsl:choose>
					</xsl:when>
					<xsl:when test="../@id and count(../preceding-sibling::Itinerario) = 1">
						<xsl:choose>
							<xsl:when test="position() mod 2 = 1">#ffccff</xsl:when>
							<xsl:otherwise>#ffeeff</xsl:otherwise>
						</xsl:choose>
					</xsl:when>
					<xsl:when test="../@id and count(../preceding-sibling::Itinerario) = 2">
						<xsl:choose>
							<xsl:when test="position() mod 2 = 1">#ccccff</xsl:when>
							<xsl:otherwise>#eeeeff</xsl:otherwise>
						</xsl:choose>
					</xsl:when>
					<xsl:otherwise>
						<xsl:choose>
							<xsl:when test="position() mod 2 = 1">#ffffaa</xsl:when>
							<xsl:otherwise>#ffffee</xsl:otherwise>
						</xsl:choose>
					</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="span">
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=$id_asig and @Tipo='Parcial']">
						2
					</xsl:when>
					<xsl:otherwise>1</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<xsl:variable name="tipo_ex">
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=$id_asig and @Tipo='Parcial']">
						Parcial
					</xsl:when>
					<xsl:otherwise>Final</xsl:otherwise>
				</xsl:choose>
			</xsl:variable>
			<tr style="background-color: {$color}">
				<td rowspan="{$span}" style="text-align: center;"><xsl:value-of select="//Calendario/@Id_cal" /></td>
				<td rowspan="{$span}" style="text-align: center;"><xsl:value-of select="@cod_asig" /></td>
				<td rowspan="{$span}"><xsl:value-of select="@nombre" /></td>
				<td rowspan="{$span}" style="text-align: center;"><xsl:value-of select="@Curso" /></td>
				<td rowspan="{$span}" style="text-align: center;">
					<xsl:choose>
						<xsl:when test="@duracion='Anual'">A</xsl:when>
						<xsl:when test="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]">1C</xsl:when>
						<xsl:otherwise>2C</xsl:otherwise>
					</xsl:choose>
				</td>
				<td rowspan="{$span}" style="text-align: center;"><xsl:value-of select="@Caracter" /></td>
				<td rowspan="{$span}" style="text-align: center;"><xsl:value-of select="@Creditos" /></td>
				<!-- Febrero -->
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]">
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]/@Dia" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]/@Mes" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]/@Hora" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">1</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="substring-after(//Curso/@Año, '-')" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Febrero')]/@Aula" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:choose>
								<xsl:when test="//Asignatura[@cod_asig=$id_asig][@Tipo='Parcial']">1</xsl:when>
								<xsl:otherwise>0</xsl:otherwise>
							</xsl:choose>
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:choose>
								<xsl:when test="//Asignatura[@cod_asig=$id_asig][@Tipo='Parcial']">1P</xsl:when>
								<xsl:otherwise>F</xsl:otherwise>
							</xsl:choose>
						</td>
					</xsl:when>
					<xsl:otherwise>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
					</xsl:otherwise>
				</xsl:choose>
				<!-- Junio -->
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')]">
						<xsl:choose>
							<xsl:when test="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Parcial']">
								<td style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Parcial']/@Dia" />
								</td>
								<td style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Parcial']/@Mes" />
								</td>
								<td style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Parcial']/@Hora" />
								</td>
								<td style="text-align: center;">2</td>
								<td style="text-align: center;">
									<xsl:value-of select="substring-after(//Curso/@Año, '-')" />
								</td>
								<td style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Parcial']/@Aula" />
								</td>
								<td style="text-align: center;">1</td>
								<td style="text-align: center;">2P</td>
							</xsl:when>
							<xsl:otherwise>
								<td style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')]/@Dia" />
								</td>
								<td style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')]/@Mes" />
								</td>
								<td style="text-align: center;">
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')]/@Hora" />
								</td>
								<td style="text-align: center;">2</td>
								<td style="text-align: center;">
									<xsl:value-of select="substring-after(//Curso/@Año, '-')" />
								</td>
								<td>
									<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')]/@Aula" />
								</td>
								<td style="text-align: center;">0</td>
								<td style="text-align: center;">F</td>
							</xsl:otherwise>
						</xsl:choose>
					</xsl:when>
					<xsl:otherwise>
						<td style="text-align:center;">--</td>
						<td style="text-align:center;">--</td>
						<td style="text-align:center;">--</td>
						<td style="text-align:center;">--</td>
						<td style="text-align:center;">--</td>
						<td style="text-align:center;">--</td>
						<td style="text-align:center;">--</td>
						<td style="text-align:center;">--</td>
					</xsl:otherwise>
				</xsl:choose>
				<!-- Segunda convocatoria -->
				<xsl:choose>
					<xsl:when test="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Segunda')]">
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Segunda')]/@Dia" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Segunda')]/@Mes" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Segunda')]/@Hora" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">3</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="substring-after(//Curso/@Año, '-')" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">
							<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Segunda')]/@Aula" />
						</td>
						<td rowspan="{$span}" style="text-align: center;">0</td>
						<td rowspan="{$span}" style="text-align: center;">F</td>
					</xsl:when>
					<xsl:otherwise>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
						<td rowspan="{$span}" style="text-align:center;">--</td>
					</xsl:otherwise>
				</xsl:choose>
			</tr>
			<xsl:if test="$span=2">
				<tr style="background-color: {$color}">
					<td style="text-align: center;">
						<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Final']/@Dia" />
					</td>
					<td style="text-align: center;">
						<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Final']/@Mes" />
					</td>
					<td style="text-align: center;">
						<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Final']/@Hora" />
					</td>
						<td style="text-align: center;">2</td>
					<td style="text-align: center;">
						<xsl:value-of select="substring-after(//Curso/@Año, '-')" />
					</td>
				<td style="text-align: center;">
						<xsl:value-of select="//Asignatura[@cod_asig=$id_asig][contains(@Convocatoria, 'Junio')][@Tipo='Final']/@Aula" />
					</td>
					<td style="text-align: center;">0</td>
					<td style="text-align: center;">F</td>
				</tr>
			</xsl:if>
		</xsl:for-each>
	</table>
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
