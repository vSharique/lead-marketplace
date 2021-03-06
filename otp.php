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
        	$credentials = get_option("ghupshup_credentials");
        	$response = send_sms($credentials['user_id'],$credentials['password'],$edugorilla_mno,$msg);
      
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

function edugorilla_settings()
{
    $ghupshup_credentials_form = $_POST['ghupshup_credentials_form'];
    if($ghupshup_credentials_form == "self")
    {
        $ghupshup_user_id = $_POST['ghupshup_user_id'];
        $ghupshup_pwd = $_POST['ghupshup_pwd'];
        
        $errors = array();
        
        if(empty($ghupshup_user_id)) $errors['ghupshup_user_id'] = "Empty";
        if(empty($ghupshup_pwd)) $errors['ghupshup_pwd'] = "Empty";
        
        if(empty($errors))
        {
            $credentials = array("user_id"=>$ghupshup_user_id, "password" => $ghupshup_pwd);
            update_option("ghupshup_credentials",$credentials);
            $success = "Saved Successfully";
        }
    }else
    {
        $credentials = get_option("ghupshup_credentials");
        $ghupshup_user_id = $credentials['user_id'];
        $ghupshup_pwd = $credentials['password'];
    }
?>
    <div class="wrap">
        <h1>Ghupshup Credentials</h1>
        <form method="post">
            <table>
                <tr>
                    <th>User ID</th>
                    <td>
                        <input name="ghupshup_user_id" value="<?php echo $ghupshup_user_id; ?>">
                        <font color="red"><?php echo $errors['ghupshup_user_id']; ?></font>
                    </td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td>
                        <input name="ghupshup_pwd" value="<?php echo $ghupshup_pwd; ?>">
                        <font color="red"><?php echo $errors['ghupshup_pwd']; ?></font>
                    </td>
                </tr>
                <tr>
                    <td><input type="hidden" name="ghupshup_credentials_form" value="self"></td>
                    <td><input type="submit" class="button button-primary" value="Save"></td>
                </tr>
            </table>
        </form>
    </div>
<?php
}
?>