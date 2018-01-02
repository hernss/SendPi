<?php

include ('passconfig.php') ;

function verifyPassword($password)

{
	
	

	global $encryptedpasswd ;

	// read encrypted password

	$encryptedpasswd = getpass() ;

 	if ((crypt($password,'$5$rounds=5000$saltgoeshere$') ==  $encryptedpasswd) )
	{ 
		return ('right') ;
	}
 	else
	{     	
		return ('false') ;
 	}
}

function getpass()
{
	global  $passwordlocation;

	if(file_exists($passwordlocation)){
		include ("$passwordlocation");
		return($passwd);
	}else{
		return false;
	}
}

function encryptPwd($password){

	return crypt($password,'$5$rounds=5000$saltgoeshere$');
}

function createPasswordFile($password){

	global  $passwordlocation;

	$file = fopen("$passwordlocation","w");
	fwrite($file,"<?php\n \$passwd='" . encryptPwd($password) .  "';\n ?>");
	fclose($file);

	if(file_exists($passwordlocation)){
		return true;
	}else{
		return false;
	}
}
?>
