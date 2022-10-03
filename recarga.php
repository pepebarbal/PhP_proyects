<?php  session_start();  ?>
  <?php
        echo "Hora y Fecha actual: ".date("h:i:s A  j/n/Y")."<br>";
        echo "Estado de la sesión:";

        if((isset($_SESSION["year"]))){
              //Comprobar que el año sea valido
              echo"<script>document.getElementById('div_recarga').style.backgroundColor='#66ff66';</script>";
              echo"¡Correcta!";
          }else{
              echo"<script>document.getElementById('div_recarga').style.backgroundColor='#ff3333';</script>";
              echo " Incorrecta.";
        };
  ?>
