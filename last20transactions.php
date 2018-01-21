<?php
include("header.php");
include("pass.php");
$trans = $coin->listtransactions('*', 100);
$prearrayx = array_reverse($trans);

$x = array();

foreach ($prearrayx as $item) {
    if($x[$item['txid']]){
        if( $item['amount'] == 0){
            $x[$item['txid']]['amount'] += (float)$item['fee'];
        }else{
            $x[$item['txid']]['amount'] += (float)$item['amount'];
        }
        $x[$item['txid']]['category'] = "mined";

        if ($x[$item['txid']]['account'] == ""){
            $x[$item['txid']]['account'] = $item['account'];
        }
        if($item['fee']){
            $x[$item['txid']]['fee'] += $item['fee'];
        }
    }else{
        $x[$item['txid']] = array();
        if( $item['amount'] == 0){
            $x[$item['txid']]['amount'] = (float)$item['fee'];
        }else{
            $x[$item['txid']]['amount'] = (float)$item['amount'];
        }
        $x[$item['txid']]['txid'] = $item['txid'];
        $x[$item['txid']]['category'] = $item['category'];
        $x[$item['txid']]['confirmations'] = $item['confirmations'];
        $x[$item['txid']]['time'] = $item['time'];
        $x[$item['txid']]['account'] = $item['account'];
        $x[$item['txid']]['comment'] = $item['comment'];
        $x[$item['txid']]['address'] = $item['address'];
        if($item['fee']){
            $x[$item['txid']]['fee'] = $item['fee'];
        }else{
            $x[$item['txid']]['fee'] = 0;
        }


    }
}
$l = count($x);
if ($l > 20){
    $l = 20;
}else{
    $l -= 1;
}
$x = array_slice($x, 0, $l);
?>

<p><b>Last 20 Transactions:</b></p>
<div class="panel panel-default">
    <div class="table-responsive">
        <?php
        echo "<table class='table-hover table-condensed table-bordered table'>
        <thead><tr><th>Method</th><th>Address</th><th>Account</th><th>Amount</th><th>Confirmations</th><th>Time</th><th>Txid</th><th>Comment</th></tr></thead>";
        foreach ($x as $x) {
	  $txid = "{$x['txid']}";
	  $commentFile = $dataDir . $currentWallet.$txid."comment.php";
            if ($x['amount'] > 0) {
                $coloramount = "green";
            } 
            else {
                $coloramount = "red";
            }
            if ($x['confirmations'] >= 6) {
                $colorconfirms = "green";
            } 
            else {
                $colorconfirms = "red";
            }
	    
            $date = date('D M j y g:i a', $x['time']);
            if ($x['amount'] == 0) {
                $x['amount'] = $x['fee'];
                $x['category'] = "self Payment";
            }

            if ($x['confirmations'] == -1){
                $x['category'] = "orphan";
            }
            echo "<tr>";
            echo "<td>" . ucfirst($x['category']) . "</td>";

	  if ($x['comment'] != "") {
            echo "<td>{$x['address']}</td>
                <td><div style='width:100%;overflow:hidden'>{$x['account']}</div></td>
                <td><div style='width:100%;overflow:hidden'><font color='{$coloramount}'>" . exp_to_dec($x['amount']) . "</font></div></td>
		<td><div style='width:110px;overflow:hidden'><font color='{$colorconfirms}'>{$x['confirmations']}</font></div></td>
                <td>{$date}</td>
                <td><div style='width:120px;overflow:hidden'>{$x['txid']}</div></td>
		<td>{$x['comment']}</td>
                </tr>";
	  }
	  elseif(file_exists($commentFile)){
	   include("$commentFile");
            echo "<td>{$x['address']}</td>
                <td><div style='width:100%;overflow:hidden'>{$x['account']}</div></td>
                <td><div style='width:100%;overflow:hidden'><font color='{$coloramount}'>" . exp_to_dec($x['amount']) . "</font></div></td>
		<td><div style='width:110px;overflow:hidden'><font color='{$colorconfirms}'>{$x['confirmations']}</font></div></td>
                <td>{$date}</td>
                <td><div style='width:120px;overflow:hidden'>{$x['txid']}</div></td>
		<td><div style='width:140px;overflow:hidden'>$comment</div></td>
                </tr>";
	  }
	  else {
            echo "<td>{$x['address']}</td>
                <td><div style='width:100%;overflow:hidden'>{$x['account']}</div></td>
                <td><div style='width:100%;overflow:hidden'><font color='{$coloramount}'>" . exp_to_dec($x['amount']) . "</font></div></td>
		<td><div style='width:110px;overflow:hidden'><font color='{$colorconfirms}'>{$x['confirmations']}</font></div></td>
                <td>{$date}</td>
                <td><div style='width:120px;overflow:hidden'>{$x['txid']}</div></td>
		<td>
			<form action='comment' method='POST'><input type='hidden'>
				<button class='btn btn-default btn-block ' type='submit' name='txid' value={$x['txid']}>Add Comment</button>
			</form>
		</td>
                </tr>";
	  }
        }
        echo "</table>";
        ?>
    </div>
</div>
<b><a href="listtransactions.php">View All Transactions</a></b>
</div>
<?php include("footer.php"); ?>