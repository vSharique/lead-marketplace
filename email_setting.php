<?php
function edugorilla_email_setting()
{
    $email_setting_form = $_POST['email_setting_form'];
    if ($email_setting_form == "self") {
        $errors = array();
        $edugorilla_email_subject = $_POST['edugorilla_subject'];
        $edugorilla_email_body = $_POST['edugorilla_body'];
        if (empty($edugorilla_email_subject)) $errors['edugorilla_subject'] = "Empty";

        if (empty($edugorilla_email_body)) $errors['edugorilla_body'] = "Empty";

        if (empty($errors)) {
            $edugorilla_email_setting = array('subject' => $edugorilla_email_subject, 'body' => $edugorilla_email_body);

            update_option("edugorilla_email_setting", $edugorilla_email_setting);
            $success = "Email Settings Saved Successfully.";
        }
    } else {
        $email_setting_options = get_option('edugorilla_email_setting');

        $edugorilla_email_subject = $email_setting_options['subject'];

        $edugorilla_email_body = $email_setting_options['body'];

    }
    ?>
    <div class="wrap">
        <h1>Promotional Email Template</h1>
        <?php
        if ($success) {
            ?>
            <div class="updated notice">
                <p><?php echo $success; ?></p>
            </div>
            <?php
        }
        ?>
        <form method="post">
            <table class="form-table">
                <tr>
                    <th>Subject <sup><font color="red">*</font></sup></th>
                    <td>
                        <input name="edugorilla_subject" value="<?php echo $edugorilla_email_subject; ?>"
                               placeholder="Type Email Subject here...">
                        <font color="red"><?php echo $errors['edugorilla_subject']; ?></font>
                    </td>
                </tr>
                <tr>
                    <th>Body template<sup><font color="red">*</font></sup></th>
                    <td>
                        <textarea name="edugorilla_body" rows="4" cols="65"
                                  placeholder="Type Email Body here..."><?php echo $edugorilla_email_body; ?></textarea>
                        <font color="red"><?php echo $errors['edugorilla_body']; ?></font>
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <input type="hidden" name="email_setting_form" value="self">
                        <input type="submit" class="button button-primary" value="Save">
                    </td>
                </tr>
            </table>
        </form>
    </div>
    <?php
}

?>
