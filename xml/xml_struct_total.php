<?php

if($_GET["cuatr"]==4 || $_GET["cuatr"]==5){
$xmlstr = <<<XML
<?xml-stylesheet href="./plantilla_examen_3.xsl" type="text/xsl"?>
<Examenes>

</Examenes>
XML;
}/*else if($_GET["cuatr"]==6 || $_GET["cuatr"]==7 || $_GET["cuatr"]==8){
$xmlstr = <<<XML
<?xml-stylesheet href="./plantilla_examen_4.xsl" type="text/xsl"?>
<Examenes>

</Examenes>
XML;
}*/else if($_GET["cuatr"]==1 || $_GET["cuatr"]==2 || $_GET["cuatr"]==3){
$xmlstr = <<<XML
<?xml-stylesheet href="./plantilla_examen_2.xsl" type="text/xsl"?>
<Examenes>

</Examenes>
XML;
}
else {
$xmlstr = <<<XML
<?xml-stylesheet href="./plantilla_examen_4.xsl" type="text/xsl"?>
<Examenes>

</Examenes>
XML;
}

?>
