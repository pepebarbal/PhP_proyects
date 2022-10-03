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
			<xsl:for-each select="//Asignatura[not (@Convocatoria = preceding::Asignatura/@Convocatoria)]">
				<xsl:sort select="@Convocatoria" />
				<xsl:if test="@Convocatoria!=''">
					<p style="page-break-after: always;">
						<p style="text-align: center;">Convocatoria <xsl:value-of select="@Convocatoria" /></p>
						<xsl:choose>
							<xsl:when test="//Calendario">
								<xsl:call-template name="horario_aula">
									<xsl:with-param name="conv" select="@Convocatoria" />
								</xsl:call-template>
							</xsl:when>
							<xsl:otherwise>
								<xsl:call-template name="horario">
									<xsl:with-param name="conv" select="@Convocatoria" />
								</xsl:call-template>
							</xsl:otherwise>
						</xsl:choose>
					</p>
				</xsl:if>
			</xsl:for-each>
		</body>
	</html>
</xsl:template>
<!---->

<xsl:template name="horario_aula">
	<xsl:param name="conv" />
	
	<table border="2" style='font-size: 10pt; page-break-inside: avoid;' width="95%" align="center">
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
			<xsl:sort select="@id" />
			<xsl:if test="current()//Asignatura[@Convocatoria=$conv]">
				<tr><td>*</td></tr>
			</xsl:if>				
			<xsl:for-each select="current()//Asignatura[@Convocatoria=$conv]">
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
							<xsl:when test="contains(@Convocatoria, 'Febrero')">1</xsl:when>
							<xsl:when test="contains(@Convocatoria, 'Junio')">2</xsl:when>
							<xsl:when test="contains(@Convocatoria, 'Segunda')">3</xsl:when>
							<xsl:when test="contains(@Convocatoria, 'Noviembre')">4</xsl:when>
							<xsl:when test="contains(@Convocatoria, 'Diciembre')">5</xsl:when>
							<xsl:when test="contains(@Convocatoria, 'Primer Cuatrimestre') and contains(@Convocatoria, 'Incidencias')">6</xsl:when>
							<xsl:when test="contains(@Convocatoria, 'Segundo Cuatrimestre') and contains(@Convocatoria, 'Incidencias')">7</xsl:when>
							<xsl:when test="contains(@Convocatoria, 'Segunda Convocatoria') and contains(@Convocatoria, 'Incidencias')">8</xsl:when>
							<xsl:otherwise>--</xsl:otherwise>
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
		</xsl:for-each>
	</table>
</xsl:template>

<xsl:template name="horario">
	<xsl:param name="conv" />
	<xsl:for-each select="//Titulacion">
		<xsl:variable name="nodo" select="Comunes/Asignatura[@Convocatoria=$conv] | Itinerario/Asignatura[@Convocatoria=$conv]" />
		<xsl:variable name="color">
			<xsl:choose>
				<xsl:when test="position() mod 2 = 0">#FFFFFF</xsl:when>
				<xsl:otherwise>#FFFFCC</xsl:otherwise>
			</xsl:choose>
		</xsl:variable>
		<table border="1" style="background-color: {$color};">
			<tr  style="page-break-inside:avoid;">
				<td style="font-size: 8pt; width: 70px;">
					<xsl:value-of select="@nombre" />
				</td>
				<xsl:for-each select="//Asignatura[@Convocatoria=$conv][not (@Mes=preceding::Asignatura[@Convocatoria=$conv]/@Mes)]">
					<xsl:sort data-type="number" select="@Mes" />
					<xsl:variable name="mes" select="@Mes" />
					<xsl:for-each select="//Asignatura[@Convocatoria=$conv][@Mes=$mes][not (@Dia=preceding::Asignatura[@Convocatoria=$conv][@Mes=$mes]/@Dia)]">
						<xsl:sort data-type="number" select="@Dia" />
						<xsl:variable name="dia" select="@Dia" />
						
						<xsl:variable name="fecha_sig0">
							<xsl:call-template name="obtener_fecha_sig">
								<xsl:with-param name="dia" select="$dia" />
								<xsl:with-param name="mes" select="$mes" />
							</xsl:call-template>
						</xsl:variable>
						<xsl:variable name="dia_sig0" select="substring-before($fecha_sig0, '/')" />
						<xsl:variable name="mes_sig0" select="substring-after($fecha_sig0, '/')" />
						<xsl:variable name="fecha_sig1">
							<xsl:call-template name="obtener_fecha_sig">
								<xsl:with-param name="dia" select="$dia_sig0" />
								<xsl:with-param name="mes" select="$mes_sig0" />
							</xsl:call-template>
						</xsl:variable>
						<xsl:variable name="dia_sig1" select="substring-before($fecha_sig1, '/')" />
						<xsl:variable name="mes_sig1" select="substring-after($fecha_sig1, '/')" />
						
						<td style="vertical-align: top;">
							<table border="1" width="60">
								<tr>
									<td style="font-size: 8pt; background-color: #CCCCFF;">
										<xsl:value-of select="@Dia" />/
										<xsl:call-template name="obtener_mes">
											<xsl:with-param name="mes" select="@Mes" />
										</xsl:call-template>
									</td>
								</tr>
								<xsl:for-each select="$nodo[@Curso=1][@Dia=$dia and @Mes=$mes]">
									<tr><td style="font-size: 7pt; background-color: #FFFFFF; height: 25px;">
										<!--<xsl:value-of select="@nombre" />-->
										<xsl:call-template name="abreviatura">
											<xsl:with-param name="nombre" select="@nombre" />
										</xsl:call-template>
									</td></tr>
								</xsl:for-each>
								<xsl:if test="not ($nodo[@Curso=1][@Dia=$dia and @Mes=$mes])">
									<tr><td style="font-size: 7pt; background-color: #FFFFFF; height: 25px;">--</td></tr>
								</xsl:if>
								<xsl:for-each select="$nodo[@Curso=2][@Dia=$dia and @Mes=$mes]">
									<tr><td style="font-size: 7pt; background-color: #00FFFF; height: 25px;">
										<!--<xsl:value-of select="@nombre" />-->
										<xsl:call-template name="abreviatura">
											<xsl:with-param name="nombre" select="@nombre" />
										</xsl:call-template>
									</td></tr>
								</xsl:for-each>
								<xsl:if test="not ($nodo[@Curso=2][@Dia=$dia and @Mes=$mes])">
									<tr><td style="font-size: 7pt; background-color: #00FFFF; height: 25px;">--</td></tr>
								</xsl:if>
								<xsl:for-each select="$nodo[@Curso=3][@Dia=$dia and @Mes=$mes]">
									<tr><td style="font-size: 7pt; background-color: #FF00FF; height: 25px;">
										<!--<xsl:value-of select="@nombre" />-->
										<xsl:call-template name="abreviatura">
											<xsl:with-param name="nombre" select="@nombre" />
										</xsl:call-template>
									</td></tr>
								</xsl:for-each>
								<xsl:if test="not ($nodo[@Curso=3][@Dia=$dia and @Mes=$mes])">
									<tr><td style="font-size: 7pt; background-color: #FF00FF; height: 25px;">--</td></tr>
								</xsl:if>
								<xsl:for-each select="$nodo[@Curso=4][@Dia=$dia and @Mes=$mes]">
									<tr><td style="font-size: 7pt; background-color: #00FF00; height: 25px;">
										<!--<xsl:value-of select="@nombre" />-->
										<xsl:call-template name="abreviatura">
											<xsl:with-param name="nombre" select="@nombre" />
										</xsl:call-template>
									</td></tr>
								</xsl:for-each>
								<xsl:if test="not ($nodo[@Curso=4][@Dia=$dia and @Mes=$mes])">
									<tr><td style="font-size: 7pt; background-color: #00FF00; height: 25px;">--</td></tr>
								</xsl:if>
								<xsl:for-each select="$nodo[@Curso=5][@Dia=$dia and @Mes=$mes]">
									<tr><td style="font-size: 7pt; background-color: #FF0000; height: 25px;">
										<!--<xsl:value-of select="@nombre" />-->
										<xsl:call-template name="abreviatura">
											<xsl:with-param name="nombre" select="@nombre" />
										</xsl:call-template>
									</td></tr>
								</xsl:for-each>
								<xsl:for-each select="$nodo[@Dia=$dia and @Mes=$mes]">
									<xsl:choose>
										<xsl:when test="@Curso">
										</xsl:when>
										<xsl:otherwise>
											<tr><td style="font-size: 7pt;">
												<xsl:call-template name="abreviatura">
													<xsl:with-param name="nombre" select="@nombre" />
												</xsl:call-template>
											</td></tr>
										</xsl:otherwise>
									</xsl:choose>
								</xsl:for-each>
							</table>
						</td>

						<xsl:if test="not (//Asignatura[@Convocatoria=$conv][@Dia=$dia_sig0 and @Mes=$mes_sig0])">
							<td style="vertical-align: top;">
								<table border="1" width="45">
									<tr>
										<td style="font-size: 8pt; background-color: #CCCCFF;">
											<xsl:value-of select="$dia_sig0" />/
											<xsl:call-template name="obtener_mes">
												<xsl:with-param name="mes" select="$mes_sig0" />
											</xsl:call-template>
										</td>
									</tr>
								</table>
							</td>
							<xsl:if test="not (//Asignatura[@Convocatoria=$conv][@Dia=$dia_sig1 and @Mes=$mes_sig1])">
								<td style="vertical-align: top;">
									<table border="1" width="45">
										<tr>
											<td style="font-size: 8pt; background-color: #CCCCFF;">
												<xsl:value-of select="$dia_sig1" />/
												<xsl:call-template name="obtener_mes">
													<xsl:with-param name="mes" select="$mes_sig1" />
												</xsl:call-template>
											</td>
										</tr>
									</table>
								</td>
							</xsl:if>
						</xsl:if>

					</xsl:for-each>
				</xsl:for-each>
			</tr>
		</table>
	</xsl:for-each>
</xsl:template>

<xsl:template name="obtener_fechas">
	<xsl:param name="nodo" />
	<xsl:param name="tipo" />
	<xsl:param name="dia" />
	<xsl:param name="mes" />

	<xsl:choose>
		<xsl:when test="$tipo='final'">
			<xsl:choose>
			<xsl:when test="$nodo[@Mes &gt; $mes]">
				<xsl:call-template name="obtener_fechas">
					<xsl:with-param name="nodo" select="$nodo" />
					<xsl:with-param name="tipo" select="$tipo" />
					<xsl:with-param name="mes" select="$nodo[@Mes &gt; $mes]/@Mes" />
					<xsl:with-param name="dia" select="$dia" />
				</xsl:call-template>
			</xsl:when>
			<xsl:when test="$nodo[@Mes=$mes][@Dia &gt; $dia]">
				<xsl:call-template name="obtener_fechas">
					<xsl:with-param name="nodo" select="$nodo" />
					<xsl:with-param name="tipo" select="$tipo" />
					<xsl:with-param name="mes" select="$mes" />
					<xsl:with-param name="dia" select="$nodo[@Mes=$mes][@Dia &gt; $dia]/@Dia" />
				</xsl:call-template>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="$dia"/>/<xsl:value-of select="$mes" />
			</xsl:otherwise>
		</xsl:choose>
		</xsl:when>
		<xsl:otherwise>
			<xsl:choose>
			<xsl:when test="$nodo[@Mes &lt; $mes]">
				<xsl:call-template name="obtener_fechas">
					<xsl:with-param name="nodo" select="$nodo" />
					<xsl:with-param name="tipo" select="$tipo" />
					<xsl:with-param name="mes" select="$nodo[@Mes &lt; $mes]/@Mes" />
					<xsl:with-param name="dia" select="$dia" />
				</xsl:call-template>
			</xsl:when>
			<xsl:when test="$nodo[@Mes=$mes][@Dia &lt; $dia]">
				<xsl:call-template name="obtener_fechas">
					<xsl:with-param name="nodo" select="$nodo" />
					<xsl:with-param name="tipo" select="$tipo" />
					<xsl:with-param name="mes" select="$mes" />
					<xsl:with-param name="dia" select="$nodo[@Mes=$mes][@Dia &lt; $dia]/@Dia" />
				</xsl:call-template>
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="$dia"/>/<xsl:value-of select="$mes" />
			</xsl:otherwise>
		</xsl:choose>
		</xsl:otherwise>
	</xsl:choose>
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

<xsl:template name="obtener_fecha_sig">
	<xsl:param name="dia" />
	<xsl:param name="mes" />
	<xsl:choose>
		<xsl:when test="$dia = 28 and $mes = 2">
			1/<xsl:value-of select="$mes+1"/>
		</xsl:when>
		<xsl:when test="$dia = 31 and ($mes mod 2) = 1">
			1/<xsl:value-of select="$mes+1"/>
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="$dia+1"/>/<xsl:value-of select="$mes"/>
		</xsl:otherwise>
	</xsl:choose>
</xsl:template>

<xsl:template name="abreviatura">
	<xsl:param name="nombre"/>
	<xsl:variable name="parte" select="substring-before($nombre,' ')"/>
	<xsl:choose>
		<xsl:when test="not ($nombre='')">
			<xsl:choose>
				<xsl:when test="$parte='la'"/>
				<xsl:when test="$parte='las'"/>
				<xsl:when test="$parte='el'"/>
				<xsl:when test="$parte='los'"/>
				<xsl:when test="$parte='de'"/>
				<xsl:when test="$parte='del'"/>
				<xsl:when test="$parte='un'"/>
				<xsl:when test="$parte='una'"/>
				<xsl:when test="$parte='unos'"/>
				<xsl:when test="$parte='unas'"/>
				<xsl:when test="$parte='a'"/>
				<xsl:when test="$parte='al'"/>
				<xsl:when test="$parte='o'"/>
				<xsl:when test="$parte='y'"/>
				<xsl:when test="$parte='e'"/>
				<xsl:when test="$parte='u'"/>
				<xsl:when test="$parte='en'"/>
				<xsl:when test="$parte='por'"/>
				<xsl:when test="$parte='para'"/>
				<xsl:when test="$parte='hasta'"/>
				<xsl:when test="$parte='hacia'"/>
				<xsl:when test="$parte='sin'"/>
				<xsl:when test="$parte='' and contains($nombre, 'II')">
					<xsl:value-of select="$nombre"/>
				</xsl:when>
				<xsl:when test="$parte='' and contains($nombre, 'VI')">
					<xsl:value-of select="$nombre"/>
				</xsl:when>
				<xsl:when test="$parte='' and contains($nombre, 'XI')">
					<xsl:value-of select="$nombre"/>
				</xsl:when>
				<xsl:when test="$parte='' and contains($nombre, 'IV')">
					<xsl:value-of select="$nombre"/>
				</xsl:when>
				<xsl:when test="$parte='' and contains($nombre, 'IX')">
					<xsl:value-of select="$nombre"/>
				</xsl:when>
				<xsl:when test="$parte='' and contains($nombre, 'XV')">
					<xsl:value-of select="$nombre"/>
				</xsl:when>
				<xsl:otherwise>
					<xsl:value-of select="substring($nombre,1,4)"/><xsl:text> </xsl:text>
				</xsl:otherwise>
			</xsl:choose>
			<xsl:call-template name="abreviatura">
				<xsl:with-param name="nombre" select="substring-after($nombre, ' ')"/>
			</xsl:call-template>
		</xsl:when>
	</xsl:choose>
</xsl:template>

</xsl:stylesheet>
