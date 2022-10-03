<?php

class Saml{

    protected static $mail_list = array("sub.ordenacion@eps.uhu.es","sub.ordenacion@etsi.uhu.es","sub.innovacion@eps.uhu.es","sub.innovacion@etsi.uhu.es",
    "sub.estudios@eps.uhu.es","sub.estudios@etsi.uhu.es","sub.estudiantes@eps.uhu.es","sub.estudiantes@etsi.uhu.es",
    "sub.calidad@eps.uhu.es","sub.calidad@etsi.uhu.es","sub.orientacion@eps.uhu.es","sub.orientacion@etsi.uhu.es",
    "direccion@eps.uhu.es","direccion@etsi.uhu.es","secretario.centro@eps.uhu.es",
    "secretario.centro@etsi.uhu.es","secretaria.direccion@eps.uhu.es","secretaria.direccion@etsi.uhu.es","webmaster@eps.uhu.es","webmaster@etsi.uhu.es","consuelo.gonzalez@sc.uhu.es",
    "josecarlos.mora@alu.uhu.es","amaranto.delbarrio@alu.uhu.es","secretaria.centro@eps.uhu.es","secretaria.centro@etsi.uhu.es");

    private function __construct(){}

    public static function Check_for_samldata($session){
        //Comprobamos si esta el correo electronico en el saml. El nombre, apellido y dni es posible que no estén, como en el caso del webmaster
        //y del resto de los datos no tenemos necesidad (asignaturas matriculadas, etc).

        if(isset($session)){
            if(in_array($session,self::$mail_list)){
                return 1;
            }else{
                return 0;
            }
            
        }else{
            return 0;
        }

    }    

    public static function is_admin($correo){
        if( !in_array($correo, self::$mail_list)){
             return 0;   
        }else{
            return 1;
        }
    }

    public static function Check_mails($session){
        //Seleccionamos los correos de la cadena de conexion devuelta
		$mystr = implode($session);
		$pos = strpos($mystr, "saml:logout");
		//Filtramos con Preg_match	
		$mystr = substr($mystr, 0, $pos);
		preg_match_all('/([aA-zZ]||\.)+\@([aA-zZ]||\.)*uhu.es/', $mystr, $correos, PREG_SET_ORDER);
		$Correo = array();
		//metemos cada correo en un lugar del array $conjunto_correos	
		foreach($correos as &$correo) {
			if (!in_array($correo[0], $Correo)) {
				$Correo[] = $correo[0];
			}
        }
        
        return $Correo;
    }

    public static function get_user_type($attributes){
        
        $id_user='1';
        $metadata = explode("@", $attributes['eduPersonPrincipalName'][0])[0];

		$tipo = explode(".", $metadata);
            
        if (end($tipo) == "alu") {
			$id_user='1';
		} else if (end($tipo) == "eps") {
			$id_user='2';
		} else {
			$id_user='3';
        }
        
        unset($tipo);
        unset($metadata);
        
        return $id_user;

    }            
}

?>