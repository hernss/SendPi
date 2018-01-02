<?php
//require("jsonRPCClient.php");
//$coin = new jsonRPCClient("{$coinu['protocol']}://{$coinu['user']}:{$coinu['pass']}@{$coinu['host']}:{$coinu['port']}", true);
include ("header.php");
include("pass.php");
//$coin = new jsonRPCClient("http://sendrpc:EvVX3YFJBbU9XMMPNXDzLCTWCAUoE3WqTENWvQk6ka7A@localhost:50051/", true);

?>

<p><b>Server Information:</b></p>
<div class="panel panel-default">
    <table class="table-hover table-condensed table-bordered table" >
    	<thead>
    		<tr>
    			<th>Category</th>
    			<th>Value</th>
    		</tr>
    	</thead>
    	<tbody>
    		 <?php $info = $coin->getinfo();       
			foreach ($info as $key => $val){
				if ($val != "")
					echo "<tr><td>".$key."</td><td>".$val."</td></tr>";
			}
            
		?>
    	</tbody>
    </table>

</div>
</div>
