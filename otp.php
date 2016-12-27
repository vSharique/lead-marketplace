<?php
function edugorilla_otp()
{
    $otp_form = $_POST['otp_form'];
    if ($otp_form == "self") {
        $edugorilla_mno = $_POST['edugorilla_mno'];
        if (!preg_match("/([0-9]{10}+)/", $edugorilla_mno)) $error = "INVALID";

        if (empty($error)) {
        	include_once plugin_dir_path(__FILE__) . "api/gupshup.api.php";
        	$otp = rand(1000,9999);
        	$msg = "Your OTP is".$otp.".";
        	$response = send_sms("2000163336","PI65cXYoE",$edugorilla_mno,$msg);
      
        	list($response, $response_code,$response_msg) = explode("|",$response);
        	$response = trim($response);
        	
        	if($response != "error") $success = "<div class='notice notice-success is-dismissible'><p>OTP $otp has been sent successfully. </p></div>";
        	else $success = "<div class='notice notice-error is-dismissible'><p>Something went wrong</p></div>";
        }
    }
    ?>
    <div class="wrap">
        <h1>EduGorilla OTP</h1>
        <?php
        if ($success) {
             echo $success; 
    	?>
           
            <?php
        }
        ?>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th>Mobile No.<sup><font color="red">*</font></sup></th>
                    <td>
                        <input name="edugorilla_mno" value="<?php echo $edugorilla_mno; ?>"
                               placeholder="Type mobile no. here...">
                        <font color="red"><?php echo $error; ?></font>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="hidden" name="otp_form" value="self">
                        <input type="submit" class="button button-primary" value="Send OTP">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php
}

?>
