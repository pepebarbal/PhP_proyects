<?php

class Year{

	private function __construct(){}

	public static	function bisiesto($year) //esta en C_panelfunctions
	{
		return ((($year%4 == 0) && ($year%100)) || $year%400 == 0)? true: false;
	}

	public static	function saber_dia($nombredia)
	{
	    $dias = array('', 'L', 'M', 'X', 'J', 'V', 'S', 'D',);
	    $fecha = $dias[date('N', strtotime($nombredia))];
	    return $fecha;
	}

}

?>
