<?php


	#ini_set("display_errors", false);

	require("libs/config.php");
	require("jsonRPCClient.php");
	require("printarray.php");
	require($dataDir . "currency.php");
#	include("/home/stakebox/UI/email.php");
	include("diskusage.php");

	//hard set currency to usd

	$currency='usd';
	$longCurrency='US Dollar';
	$symbol='$';
	$currentWallet="SocialSend";
/*
	session_start();
	if (isset($_GET['currentWallet']) && !empty($_GET['currentWallet']))
		$_SESSION['currentWallet'] = $_GET['currentWallet'];

	if (isset($_SESSION['currentWallet']) && !empty($_SESSION['currentWallet']))
		$currentWallet = $_SESSION['currentWallet'];
	else {
		$keys = array_keys($wallets);
		$currentWallet = $keys[0];
		$_SESSION['currentWallet'] = $currentWallet;
	}*/

	$coinu = $wallets[$currentWallet];

	$coin = new jsonRPCClient("{$coinu['protocol']}://{$coinu['user']}:{$coinu['pass']}@{$coinu['host']}:{$coinu['port']}", true);
	
	$ticker = $coinu['ticker'];

	$pair = "$ticker"."_btc";

	// fetch price in BTC price of current coin
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://www.cryptopia.co.nz/api/GetMarket/SEND_BTC");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$rawData = curl_exec($curl);
	$data = json_decode($rawData);
	//ar_dump($data);
	$priceSend_btc = $data->Data->LastPrice;
	$priceBtc = exp_to_dec($data->Data->LastPrice);
	curl_close($curl);


	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://www.cryptopia.co.nz/api/GetMarket/BTC_USDT");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$rawData = curl_exec($curl);
	$data = json_decode($rawData);
	//ar_dump($data);
	$priceBtc_usdt = $data->Data->LastPrice;


	$priceUsd = $priceSend_btc * $priceBtc_usdt;//$data["Data"]->LastPrice;
	
	// echo "<pre>";
	// print_r($data);
	// echo "</pre>";
	//
	// echo "<pre>";
	// print_r($data[0]);
	// echo "</pre>";
	//
	// echo "<pre>";
	// print_r([$priceBtc, $priceUsd]);
	// echo "</pre>";
	/*
	// fetch fiat value of BTC
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "http://api.cryptocoincharts.info/tradingPair/btc_".$currency);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$rawData1 = curl_exec($curl);
	curl_close($curl);
	$data1 = json_decode($rawData1);
	$fiatBTC = $data1->price;

	$lastRunLog = '/home/stakebox/UI/lastrun';
	$versionLocation = '/home/stakebox/UI/version.php';

	if(!file_exists("$lastRunLog")){
		$file = fopen("$lastRunLog","w");
		fwrite($file,"");
		fclose($file);
	}

	if(!file_exists("$versionLocation")){
		$file = fopen("$versionLocation","w");
		fwrite($file,"");
		fclose($file);
	}

	if (file_exists($lastRunLog)) {
	    $lastRun = file_get_contents($lastRunLog);
	    if (time() - $lastRun >= 86400) {
        	// fetch github info
        	$curl = curl_init();
		curl_setopt($curl, CURLOPT_HTTPHEADER,array('User-Agent: StakeBox'));
        	curl_setopt($curl, CURLOPT_URL, "https://api.github.com/repos/stakebox/webui/tags");
        	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        	$rawData2 = curl_exec($curl);
        	curl_close($curl);
	        $data2 = json_decode($rawData2);
		$current = $data2[0]->name;
	        //update lastrun.log with current time
	        file_put_contents($lastRunLog, time());
		//update version.php with current version
		$fp = fopen($versionLocation, "w");
	  	fwrite($fp, "<?php\n\$newestVersion='$current';\n?>");
	  	fclose($fp);

	    }
	}
	*/

	function exp_to_dec($float_str)
	// formats a floating point number string in decimal notation, supports signed floats, also supports non-standard formatting e.g. 0.2e+2 for 20
	// e.g. '1.6E+6' to '1600000', '-4.566e-12' to '-0.000000000004566', '+34e+10' to '340000000000'
	// Author: Bob
	{
	    // make sure its a standard php float string (i.e. change 0.2e+2 to 20)
	    // php will automatically format floats decimally if they are within a certain range
	    $float_str = (string)((float)($float_str));

	    // if there is an E in the float string
	    if(($pos = strpos(strtolower($float_str), 'e')) !== false)
	    {
	        // get either side of the E, e.g. 1.6E+6 => exp E+6, num 1.6
	        $exp = substr($float_str, $pos+1);
	        $num = substr($float_str, 0, $pos);
	       
	        // strip off num sign, if there is one, and leave it off if its + (not required)
	        if((($num_sign = $num[0]) === '+') || ($num_sign === '-')) $num = substr($num, 1);
	        else $num_sign = '';
	        if($num_sign === '+') $num_sign = '';
	       
	        // strip off exponential sign ('+' or '-' as in 'E+6') if there is one, otherwise throw error, e.g. E+6 => '+'
	        if((($exp_sign = $exp[0]) === '+') || ($exp_sign === '-')) $exp = substr($exp, 1);
	        else trigger_error("Could not convert exponential notation to decimal notation: invalid float string '$float_str'", E_USER_ERROR);
	       
	        // get the number of decimal places to the right of the decimal point (or 0 if there is no dec point), e.g., 1.6 => 1
	        $right_dec_places = (($dec_pos = strpos($num, '.')) === false) ? 0 : strlen(substr($num, $dec_pos+1));
	        // get the number of decimal places to the left of the decimal point (or the length of the entire num if there is no dec point), e.g. 1.6 => 1
	        $left_dec_places = ($dec_pos === false) ? strlen($num) : strlen(substr($num, 0, $dec_pos));
	       
	        // work out number of zeros from exp, exp sign and dec places, e.g. exp 6, exp sign +, dec places 1 => num zeros 5
	        if($exp_sign === '+') $num_zeros = $exp - $right_dec_places;
	        else $num_zeros = $exp - $left_dec_places;
	       
	        // build a string with $num_zeros zeros, e.g. '0' 5 times => '00000'
	        $zeros = str_pad('', $num_zeros, '0');
	       
	        // strip decimal from num, e.g. 1.6 => 16
	        if($dec_pos !== false) $num = str_replace('.', '', $num);
	       
	        // if positive exponent, return like 1600000
	        if($exp_sign === '+') return $num_sign.$num.$zeros;
	        // if negative exponent, return like 0.0000016
	        else return $num_sign.'0.'.$zeros.$num;
	    }
	    // otherwise, assume already in decimal notation and return
	    else return $float_str;
	}
$lockStateLocation = $dataDir . $currentWallet."lockstate.php";

function changeLockState(){

	global $lockStateLocation;
	global $newLockState;
	if(!file_exists("$lockStateLocation")){
		$file = fopen("$lockStateLocation","w");
		fwrite($file,"");
		fclose($file);
	}
	if (is_readable($lockStateLocation) == FALSE)
		die ("The lock state file must be writable.") ;

	// Open the file and erase the contents if any
	$fp = fopen($lockStateLocation, "w");
	// Write the data to the file
	// CODE INJECTION WARNING!
  	fwrite($fp, "<?php\n\$lockState='$newLockState';\n?>");
  	// Close the file
  	fclose($fp);
}

include($dataDir . "version.php");
include($dataDir . "primary".$currentWallet."address.php");

try {
	$coinGetInfo = $coin->getinfo();
} catch(Exception $e) {
	$newLockState = "Locked";
	changeLockState();
	$coinGetInfo = false;
}

if($coinGetInfo) {
	if (!isset($coinGetInfo['unlocked_until'])) {
		$lockState = "Not Encrypted";
		$newLockState = "Not Encrypted";
		changeLockState();
	}

	if (isset($coinGetInfo['unlocked_until']) && (int) $coinGetInfo['unlocked_until'] > 0) {
		$address = $coin->getaddressesbyaccount("")[0];
		try {
			$signed = $coin->signmessage($address, "test message");
			$newLockState = "Unlocked For Sending";
			changeLockState();
		} catch (Exception $e) {
			$newLockState = "Unlocked For Staking";
			changeLockState();
		}
	}

	if (isset($coinGetInfo['unlocked_until']) && (int) $coinGetInfo['unlocked_until'] === 0) {
		$newLockState = "Locked";
		changeLockState();
	}

}

include($dataDir . $currentWallet."lockstate.php");

?>

<html><head><title><?php echo $priceBtc; echo " BTC/"; echo $ticker;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href='css/slate.css' rel='stylesheet' >
<link href="css/main.css" rel="stylesheet" >
<link rel="icon" type="image/png" href="favicon.png">
<script src='libs/jquery.js'></script>
<script src='libs/bootstrap.js'></script>
	<script>
		$(function(){
			$('#selectwallet a').click(function(){
				window.location.href="./?currentWallet="+$(this).text();
				return false;
			});
		});
		var pair = <?php echo json_encode($pair); ?>;
		var ticker = <?php echo json_encode($ticker); ?>;
		var data_from_ajax;
		var refreshRate=setInterval(function(){fetchPrice()},60000);
		var priceSend;
		function fetchPrice() {
			$.get('price.php?pair='+pair, function(data) {
				data_from_ajax = data;
				document.title = data_from_ajax+" BTC/"+ticker;
				document.getElementById("price").innerHTML = data_from_ajax;
				priceSend = data_from_ajax;
			});
			$.get('price.php?pair=BTC_USDT', function(data) {
				data_from_ajax = data;
				document.getElementById("priceUSD").innerHTML = (data_from_ajax * priceSend).toFixed(3);
			});
		}
	</script>
</head>
<body>

	<nav class="navbar navbar-default navbar-fixed-top navbar-custom" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="./"><img src="SocialSendLogo.png" height="100%" style="margin-left: 10px"></a>

			</div>
			<div class="collapse navbar-collapse" id="navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="last20transactions">Transactions</a></li>
					<li><a href="sendcoins">Send Coins</a></li>
					<li><a href="control">Control</a></li>
					<li><a href="help">Help</a></li>
				</ul>
				<div class="navbar-right">
					<p class="navbar-text"><?php 	echo "Current price is <b id='price'>{$priceBtc}</b> BTC / <b id='priceUSD'>" . round($priceUsd, 3) . "</b> USD"; ?></p>
					<!--
					<p class="navbar-text">Select Wallet:</p>
					<ul class="nav navbar-nav">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $currentWallet;?> <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu" id="selectwallet">
								<?php
									foreach ($wallets as $walletName => $walletData)
										echo '<li><a href="#">'.$walletName.'</a></li>';
								?>
							</ul>
						</li>
					</ul>
					-->
				</div>
			</div>
		</div>
	</nav>
<div class="container-fluid">
<div class='content'>
<div class="well">
<?php
	try {
		$coininfo = $coin->getinfo();
	} catch(exception $e) {
		echo "<!-- $e -->";
		echo "<br><p class='bg-danger'><b>Error: Your wallet server is not running. Please restart your StakeBox via the power option in the server section on the control page. If you have just restarted it, or powered it on, please allow it up to several minutes before attempting to restart it again.</b></p>";
	}
	if($dp>97){
		echo "<br><p class='bg-danger'><b>NOTICE: Your disk is nearing capacity, it is currently ".$dp."% full, with ".$df." free space remaining!</b></p>";
	}
?>
