<?php
include ('verify.php');
$encryptedpasswd= getpass() ;
$firstTime = false;

if($encryptedpasswd == false){
		$firstTime = true;
}

$showlogin = true ;
// check cookie 
if (isset($_COOKIE['logincheck'])) 
{
	if (md5($encryptedpasswd) == $_COOKIE['logincheck'])
	{
		$showlogin = false ;
	}
}
?>



<?php
if (isset($_POST['pass']) )
{
	if (verifyPassword($_POST['pass'])!='right') 
	{ 
	echo ('<center><font color="red">Password incorrect. </font></center>');
	}
	else
	{
	$showlogin= false; // the pass is good
	setcookie("logincheck",md5($encryptedpasswd),time()+3600);// expire in 1 hour
	}
}

if (isset($_POST['newPass']) )
{
	if($_POST['newPass'] != $_POST['newPass2']){
		echo "<h2>Password doesn't match.</h2>";
		$showlogin = false;
		$firstTime = true;
	}else{

		include("createConfig.php");

		generateConfigFile();

		if(createPasswordFile($_POST['newPass'])){

			echo "<h2>Password file created succefully. Please login now.</h2>";
			$showlogin = true;
			$firstTime = false;
		}else{
			echo "<h2>Error creating password file.</h2>";
			$showlogin = false;
			$firstTime = true;
		}
	}
}

if($firstTime){

?>
<div class="row">
<div class="col-lg-6">
	<h2>Thanks for chossing SocialSend.</h2>
	<p>This is the first time you use your SocialSend StakeBox. First you need to configure a password and then the system will generate the necessary files.</p>
   <form name="sql-data" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
      <div class="form-group">
      		<input class="form-control" type="password" name="newPass2" value ="" placeholder="Enter your new password" maxlength="60" size="30"> 
      </div>
      <div class="input-group">
      	<input class="form-control" type="password" name="newPass" value ="" placeholder="Repeat your password" maxlength="60" size="30"> 
         <span class="input-group-btn">
	   		<button class='btn btn-default' type="submit" value="Submit">Submit</button>
         </span>
      </div><!-- /input-group -->
   </form>
</div><!-- /.col-lg-6 -->
</div>
</div>
<?php include ("footer.php");?>
<?php
	die() ;
}

if ($showlogin) {
?>
<div class="row">
<div class="col-lg-6">
   <form name="sql-data" method="post" action="<?php $_SERVER['PHP_SELF'] ?>">
      <div class="input-group">
         <span class="input-group-btn">
	    <button class='btn btn-default' type="submit" value="Submit">Submit</button>
         </span>
	    <input class="form-control" type="password" name="pass" value ="" placeholder="Enter your password to continue" maxlength="60" size="30"> 
      </div><!-- /input-group -->
   </form>
</div><!-- /.col-lg-6 -->
</div>
</div>
<?php include ("footer.php");?>
<?php
}
if ($showlogin)  die() ;
?>