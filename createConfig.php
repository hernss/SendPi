<?php


	function generateConfigFile(){

		
		if(!file_exists("/home/pi/.send/send.conf")){
			return false;
		}

		if(($file = fopen("/home/pi/.send/send.conf", "r")) == false){
			
			return false;
		}

		$config = array();
		while (($line = fgets($file)) !== false) {
        	
        	$splited = explode("=", $line);

        	$config[$splited[0]] = substr($splited[1], 0, -1);

	    }

	    fclose($file);

	    

	    if(!isset($config["rpcuser"])){
	    	
	    	return false;
	    }

	    if(!isset($config["rpcpassword"])){
	    	
	    	return false;
	    }

	    $config["staking"] = false;

	    if(($file = fopen("/var/www/html/libs/config.php", "w")) == false){
	    	
			return false;
		}

	    fwrite($file, "<?php\n\$wallets = array();\n");





		fwrite($file, "\$wallets['SocialSend'] = array(\n");

		fwrite($file,  "	\"user\" => \"" . $config["rpcuser"] . "\",\n");

		fwrite($file,  "	\"pass\" => \"" . $config["rpcpassword"] . "\",\n");
		fwrite($file,  "	\"host\" => \"localhost\",");   
		fwrite($file,  "	\"port\" => 50051,\n");  
		fwrite($file,  "	\"protocol\" => \"http\",\n");  
		fwrite($file,  "	\"ticker\" => \"SEND\",\n");    
			

		if( $config["staking"] ){	
			fwrite($file, "		\"staking\" => " . $config["staking"] . "\n);\n");
		}else{
			fwrite($file, "		\"staking\" => false\n);\n");
		}
		
		
		fwrite($file,  "\$baseDir = \"/var/www/html/\";\n");  
		fwrite($file,  "\$dataDir = \$baseDir . \"dataUI/\";\n?>");    

		fclose($file);

	    return true;
	}



	
?>