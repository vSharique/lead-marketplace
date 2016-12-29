<?php

function edugorilla_promotion_sent_edit(){
    	$promotion_sent_edit_form = $_POST['promotion_sent_edit_form'];

    if ($promotion_sent_edit_form == "self") {
        /** Get Data From Form **/
        $contact_person_name = $_POST['contact_person_name'];
        $email_address= $_POST['email_address'];
        $iid = $_POST['iid'];

        /** Error Checking **/
        $errors = array();
        if (empty($contact_person_name)) $errors['contact_person_name'] = "Empty";
        elseif (!preg_match("/([A-Za-z]+)/", $contact_person_name)) $errors['contact_person_name'] = "Invalid";
    
        if (empty($email_address)) $errors['email_address'] = "Empty";
        else
        {
        	$emails = explode(",",$email_address);
        	if(count($emails) > 1)
            {
            	foreach($emails as $email)
                {
                	$email = trim($email);
        			if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) $errors['email_address'] = "Invalid";
                }
            }
        	elseif (filter_var($email_address, FILTER_VALIDATE_EMAIL) === false) $errors['email_address'] = "Invalid";
        }

        if (empty($errors)) {
            $institute_emails_status = array();

            $edugorilla_email = get_option('edugorilla_email_setting1');

        	$edugorilla_email_body = stripslashes($edugorilla_email['body']);
        
        	global $wpdb;
        	$q = "select * from {$wpdb->prefix}edugorilla_lead where id=$iid";
            $leads_details = $wpdb->get_results($q, 'ARRAY_A');
			foreach($leads_details as $leads_detail);
        	$contact_log_id = $leads_detail['contact_log_id'];
        	$email_listing_url = $leads_detail['listing_url'];
        
        	$q1 = "select * from {$wpdb->prefix}edugorilla_lead_contact_log where id=$contact_log_id";
            $email_details = $wpdb->get_results($q1, 'ARRAY_A');
			foreach($email_details as $email_detail);
        	$email_name = $email_detail['name'];
        	$email_contact_no = $email_detail['contact_no'];
        	$email_lead_email = $email_detail['email'];
        	$email_query = $email_detail['query'];

        if(!empty($email_detail['category_id']))
        {
        	$category_names = array();
            $term_ids = explode(",", $email_detail['category_id']);
            echo count($term_ids);
            if (!empty($term_ids)) {
                foreach ($term_ids as $index => $term_id) {
                   $category_data = get_term_by('id', $term_id, 'listing_categories');
                   $category_names[] = $category_data->name;
                }
            	 $email_category = implode(",",$category_names);
             }else $email_category = "N/A";
        	
        }else $email_category = "N/A";  
        
        	if (!empty($email_detail['location_id'])) {
        		$location_data = get_term_by('id', $email_detail['location_id'], 'locations');
        		$email_location = $location_data->name;
            }else
            {
            	$email_location = "N/A";
            }

            $edugorilla_email_subject = str_replace("{category}",$email_category , $edugorilla_email['subject']);
            $email_template_datas = array(
            								"{Contact_Person}"=> ucwords($contact_person_name),
            								"{category}" => $email_category,
            								"{location}"=> $email_location,
            								"{listing_URL}"=>$email_listing_url,
            								"{name}"=>$email_name,
            								"{contact no}"=>$email_contact_no,
            								"{email address}"=>$email_lead_email,
            								"{query}" => $email_query
            							);
        	
            		foreach($email_template_datas as $var=>$email_template_data)
           			{
                		$edugorilla_email_body = str_replace($var, $email_template_data, $edugorilla_email_body);
            		}
                
                
					$institute_emails = explode(",", $email_address);
					foreach ($institute_emails as $institute_email) {
						add_filter('wp_mail_content_type', 'edugorilla_html_mail_content_type');

						if (!empty($institute_email))
							$institute_emails_status[$institute_email] = wp_mail($institute_email, $edugorilla_email_subject, $edugorilla_email_body);

						remove_filter('wp_mail_content_type', 'edugorilla_html_mail_content_type');
					}
				
            	global $wpdb;
                $result = $wpdb->update(
                    $wpdb->prefix . 'edugorilla_lead',
                    array(
                        'contact_person' => $contact_person_name,
                        'email_status' => json_encode($institute_emails_status),
                    ),
                	array( 'id' =>  $iid)
                );

			}

            if ($result)
            {
                $success = "Mail Resended Successfully";
            }
            else $success = $result;

            //	foreach($_REQUEST as $var=>$val)$$var="";
        }
    	else
        {
        	global $wpdb;
        	$iid = $_REQUEST['iid'];
        	$q = "select * from {$wpdb->prefix}edugorilla_lead where id=$iid";
            $leads_details = $wpdb->get_results($q, 'ARRAY_A');
			foreach($leads_details as $leads_detail);
        	$contact_person_name = $leads_detail['contact_person'];
            $emails = json_decode($leads_detail['email_status'],1);
        	$email_address = implode(",",array_keys($emails));
        	
        }
    ?>
   
    <div class="wrap">
        <h1>EduGorilla Leads</h1>
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
                    <th>Name<sup><font color="red">*</font></sup></th>
                    <td>
                        <input name="contact_person_name" value="<?php echo $contact_person_name; ?>" placeholder="Type name here...">
                        <font color="red"><?php echo $errors['contact_person_name']; ?></font>
                    </td>
                </tr>
                <tr>
                    <th>Email<sup><font color="red">*</font></sup></th>
                    <td>
                        <input id="edu_email" name="email_address" value="<?php echo $email_address; ?>" placeholder="Type multiple email(abc@exp.com,cde@abc.co,......)">
                        <font color="red"><?php echo $errors['email_address']; ?></font>
                    </td>
                </tr>
                <tr>
                    <th>
                    	<input type="hidden" name="iid" value="<?php echo $_REQUEST['iid']; ?>" >
                        <input type="hidden" name="promotion_sent_edit_form" value="self">
                    </th>
                    <td>
                    	<input type="submit" value="Resend Details" class="button button-primary">
                        <!--<a id="update_details_button" disabled href="#confirmation"  class="button button-primary">Resend Details</a>-->
                    </td>
                </tr>
            </table>
        </form>
    </div>
<?php
}
?>