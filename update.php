<?php
include ("header.php");
include ("pass.php");
?>

<div style="padding: 5px 30px 0px;">
    <div class="row">
        <div class="col-lg-12">
            <b>SocialSend User Interface</b><br /><br />
            <form action="update" method="POST"><input type="hidden">
                <div class="input-group">
     	            <button class='btn btn-default' type="submit" name="status" value="webui">Update WebUI</button>
                </div><!-- /input-group -->
            </form><br />
            <?php
            $status = $_POST["status"];
            if ($status == "webui"){
                //echo exec("cd /home/stakebox/UI && /usr/bin/git pull 2>&1");
                echo "<h2>This function it's not implemented yet.</h2>";
            }
            ?>
        </div><!-- /.col-lg-2 -->
    </div><!-- /.row -->
    
</div>
</div>

<?php include ("footer.php"); ?>
