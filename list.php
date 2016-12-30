<?php
function form_list()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'edugorilla_lead ';
    $count_query = $wpdb->get_results("SELECT * FROM $table_name");
    $num_rows = count($count_query); //PHP count()
    $cpage = $_REQUEST['cpage'];
    $list_caller = $_REQUEST['list_caller'];
    $current_page = 1;
    $page_size = 10;
    if ($num_rows % $page_size == 0)
        $total_pages = $num_rows / $page_size;
    else
        $total_pages = intval($num_rows / $page_size) + 1;
    $index = ($current_page - 1) * $page_size;
    global $wpdb;
    $search_from_date_form = $_POST['search_from_date_form'];
    if ($search_from_date_form == "self") {
        $edugorilla_list_date_from = $_POST['edugorilla_list_date_from'];
        $edugorilla_list_date_to = $_POST['edugorilla_list_date_to'];
        $q = "select * from {$wpdb->prefix}edugorilla_lead WHERE (date_time BETWEEN '$edugorilla_list_date_from%' AND '$edugorilla_list_date_to%') order by id";
    } else {
        $q = "select * from {$wpdb->prefix}edugorilla_lead order by id";
    }
    $leads_datas = $wpdb->get_results($q, 'ARRAY_A');

    $p = '';
    for ($i = 1; $i <= $total_pages; $i++) {
        if ($i == $current_page)
            $p .= "<option value='$i' selected> $i </option>";
        else
            $p .= "<option value='$i'>$i</option>";
    }
    ?>
    <div class="wrap">
        <h1>Promotional Leads List <a href="admin.php?page=edugorilla" class="button button-primary">Add</a></h1>
        
        <div id="list-tabs">
          <ul>
            <li><a href="#promotion-sent">Promotion Sent</a></li>
            <li><a href="#leads">Leads</a></li>
          </ul>
          <div id="promotion-sent">
            <center><h4><?php echo $_REQUEST['success']; ?></h4></center>
                <table class="widefat fixed" cellspacing="0">
                    <thead>
                   
                    <form method="post">
                        <label>Date From</label><input name="edugorilla_list_date_from" id="edugorilla_list_date_from">
                        <label>Date To</label><input name="edugorilla_list_date_to" id="edugorilla_list_date_to">
                        <input type="hidden" name="search_from_date_form" value="self">
                        <input type="submit" class="button action" value="OK">
                    </form>
                    <div class="alignright actions bulkactions">
        
                        <form name="f10" action="admin.php?page=Listing&current_page=$cpage&page_size=$page_size">
                            <label>Page No. </label>
                            <select name="cpage" onchange='this.form.submit();'>
                                <?php echo $p; ?>
                            </select>
                        </form>
                    </div>
                    <tr>
                        <th id="cb" class="manage-column column-cb check-column" scope="col">
                        	<input id="cb-select-all-1" style="margin-top:16px;" type="checkbox">
                    	</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Institute Name</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Flag</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Email/Status</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Email Count(s)</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Date Time</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th id="cb" class="manage-column column-cb check-column" scope="col">
                        	<input id="cb-select-all-1" style="margin-top:16px;" type="checkbox">
                    	</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Institute Name</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Flag</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Email/Status</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Email Count(s)</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Date Time</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
                    $lead_names = array();
                    foreach ($leads_datas as $leads_data) {
                        $lead_names[] = $leads_data['institute_name'];
                    }
        
                    foreach ($leads_datas as $leads_data) {
                        $category = "";
                        $term_ids = explode(",", $leads_data['category_id']);
                        $total_terms = count($term_ids);
                        if (!empty($term_ids)) {
                            foreach ($term_ids as $index => $term_id) {
                                $category_data = get_term_by('id', $term_id, 'listing_categories');
                                if ($index == $total_terms - 1)
                                    $category .= $category_data->name;
                                else
                                    $category .= $category_data->name . ",";
                            }
                        }
                        ?>
                        <tr class="alternate" valign="top">
                            <th class="check-column" scope="row"><input id="cb-select-all-1" type="checkbox" name="check_list[]"
                                                                        value="<?php echo $leads_data['id']; ?>"></th>
                            <td class="column-columnname"><?php echo $leads_data['institute_name']; ?>
                                <div class="row-actions">
                                    <span><a href="post.php?post=<?php echo $leads_data['post_id']; ?>&action=edit">
                                            Edit</a> | </span>
                                    <span><a href="<?php echo get_permalink($leads_data['post_id']); ?>">
                                            View</a> | </span>
                                </div>
                            </td>
                            <td class="column-columnname"><?php echo $leads_data['flag']; ?></td>
                            <td class="column-columnname">
                                <?php
                                $emails_confrms = json_decode($leads_data['email_status'], true);
        
                                $email_count = array_count_values($lead_names);
                                if (!empty($emails_confrms)) {
                                    foreach ($emails_confrms as $email => $status) {
                                        if ($status == true) $staus = "Success";
                                        else  $staus = "Unsuccess";
                                        echo $email . "/" . $staus . ",<br>";
                                    }
                                }
                                ?>
                            </td>
                            <td class="column-columnname"><?php echo $email_count[$leads_data['institute_name']]; ?></td>
                            <td class="column-columnname"><?php echo $leads_data['date_time']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
          </div>
          <div id="leads">
          
          		<table class="widefat fixed" cellspacing="0">
                    <thead>
                    <tr>
                        <th id="cb" class="manage-column column-cb check-column" scope="col">
                        	<input id="cb-select-all-1" style="margin-top:16px;" type="checkbox">
                    	</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Lead's Name</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Lead's Contact#</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Lead's Email</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Lead's Query</th>
                     	<th id="columnname" class="manage-column column-columnname" scope="col">Lead's Category</th>
                    	<th id="columnname" class="manage-column column-columnname" scope="col">Lead's Location</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Date Time</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th id="cb" class="manage-column column-cb check-column" scope="col">
                        	<input id="cb-select-all-1" style="margin-top:16px;" type="checkbox">
                    	</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Lead's Name</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Lead's Contact#</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Lead's Email</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Lead's Query</th>
                    	<th id="columnname" class="manage-column column-columnname" scope="col">Lead's Category</th>
                    	<th id="columnname" class="manage-column column-columnname" scope="col">Lead's Location</th>
                        <th id="columnname" class="manage-column column-columnname" scope="col">Date Time</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    <?php
						$q1 = "select * from {$wpdb->prefix}edugorilla_lead_contact_log order by id";
                    	$leads_details = $wpdb->get_results($q1, 'ARRAY_A');
						foreach($leads_details as $leads_detail)
                        {
 							if(!empty($leads_detail['category_id']))
        					{
        						$category_names = array();
            					$term_ids = explode(",", $leads_detail['category_id']);
            					
            					if (!empty($term_ids)) {
                					foreach ($term_ids as $index => $term_id) {
                  						$category_data = get_term_by('id', $term_id, 'listing_categories');
                   						$category_names[] = $category_data->name;
                					}
                                
            	 					$leads_category = implode(",",$category_names);
             					}else $leads_category = "N/A";
        	
        					}else $leads_category = "N/A";  
        
        					if (!empty($leads_detail['location_id'])) {
        							$location_data = get_term_by('id', $leads_detail['location_id'], 'locations');
        							$leads_location = $location_data->name;
           					 }else
            				{
            					$leads_location = "N/A";
           					 }
                    ?>
                        <tr class="alternate" valign="top">
                            <th class="check-column" scope="row"><input id="cb-select-all-1" type="checkbox" name="check_list[]"
                                                                        value="<?php echo $leads_detail['id']; ?>"></th>
                            <td class="column-columnname"><?php echo  $leads_detail['name']; ?>
                            </td>
                            <td class="column-columnname"><?php echo $leads_detail['contact_no']; ?></td>
                            <td class="column-columnname"><?php echo $leads_detail['email']; ?></td>
                        	<td class="column-columnname"><?php echo $leads_detail['query']; ?></td>
                        	<th class="manage-column column-columnname"><?php echo $leads_category; ?></th>
                    		<th class="manage-column column-columnname"><?php echo $leads_location; ?></th>
                            <td class="column-columnname"><?php echo $leads_detail['date_time']; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
          
          </div>
        </div>
        
       
        <?php
        if ($list_caller == "self") {
            $option = isset($_POST['choice']) ? $_POST['choice'] : false;
            if ($option) {
                $checkbox = $_POST['check_list'];
                for ($i = 0; $i < count($checkbox); $i++) {
                    $del_id = $checkbox[$i];
                    global $wpdb;
                    $result = $wpdb->delete(
                        wp_edugorilla_lead,
                        array('id' => $del_id),
                        array('%d'));
                }
            }
        }
        ?>
    </div>
    <?php
}

?>
