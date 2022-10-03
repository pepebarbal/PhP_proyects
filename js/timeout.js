
function timeout(){
	$("#recarga").load("recarga.php");
}

function its_session_ok(){
    timeout();
    setInterval(timeout, 1000);
}
