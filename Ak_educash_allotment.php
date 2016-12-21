<?php
/*
Plugin Name: Ak educash records
Description: Educash records
version: 1.0
Author: Ak
*/
?>
<?php

function educash_table() {

	global $wpdb;

	$table_name = $wpdb->prefix . 'educash_allotment';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		admin_name tinytext NOT NULL,
                client_name tinytext NOT NULL,
                educash_added int(9) DEFAULT 0 NOT NULL,
                time datetime NOT NULL,
                comments varchar(500) DEFAULT 'No comment' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	
}


register_activation_hook( __FILE__, 'educash_table' );

add_action("admin_menu", "addMenu");

function addMenu()
{
     add_menu_page("Educash Details", "Educash Details", 4, "form-page", "form_page");
}

function form_page(){

             global $wpdb;
             $table_name = $wpdb->prefix . 'educash_allotment';
                 
               
           if($_POST['submit']){if(empty($_POST['adminName'])){$adminamerr='This field cannot be blank';}
                                                          else{$adminName=$_POST['adminName'];}

                                if(empty($_POST['clientName'])){$clientnamerr='This field cannot be blank';}
                                                           else{$clientName=$_POST['clientName'];}

                                if(empty($_POST['educash'])){$educasherr='This field cannot be blank';}
                                                        else{$educash=$_POST['educash'];}
                                
                                if((!empty($_POST['adminName'])) && (!empty($_POST['clientName'])) && (!empty($_POST['educash']))){ 
                     
                 $educash=$_POST['educash'];
                 $adminComment=$_POST['adminComment'];
                 $time=current_time( 'mysql' );
                 $wpdb->insert( 
	             $table_name, 
	             array( 
		             'time' => $time, 
		             'admin_name' => $adminName, 
		             'client_name' => $clientName,
                             'educash_added' => $educash,
                             'comments' => $adminComment,
                              
	           ) 
                );
              }
             }


         echo "<form method='post' action='".$_SERVER['REQUEST_URI']."'>
         Admin Name (Type your name here):<br/><input type='text' name='adminName' maxlength='70'><span>* $adminamerr </span><br/><br/>
         Client Name (Type the name of the client whom you want to allot educash):<br/><input type='text' name='clientName' maxlength='70'><span>* $clientnamerr </span><br/><br/>        
         Type the educash to be added in the client's account:<br/><input type='text' name='educash' maxlength='9'><span>* $educasherr </span><br/><br/>
         Type your comments here (optional):<br/><input type='text' name='adminComment' maxlength='500'><br/><br/>
         <input type='submit' name='submit'><br/><br/>
         </form>";
     
                 
        
     if($_POST['submit'] && (!empty($_POST['adminName'])) && (!empty($_POST['clientName'])) && (!empty($_POST['educash']))){

        
            $r = $wpdb->get_row("SELECT * FROM $table_name WHERE time = '$time' ");

               echo "<center></p>You have made the following entry just now:</p>";

               echo "<style>table, th, td{border:1px solid black; border-collapse:collapse;} td{text-align:center;}</style>";

               echo "<table style='width:50%'><tr><th>Id</th><th>Admin Name</th><th>Client Name</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";

         
               echo "<tr><td>".$r->id."</td><td>".$r->admin_name."</td><td>".$r->client_name."</td><td>".$r->educash_added."</td><td>".$r->time."</td><td>".$r->comments."</td></tr>";
                       
      
               echo "</table></center><br/><br/>"; 

   }      

   }
?>
