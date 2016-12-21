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

add_action("admin_menu", "leadmarketplace_add_menu_1");

function leadmarketplace_add_menu_1()
{
     add_menu_page("Educash Allotment", "Educash Allotment", 4, "form-page_1", "form_page_1");
}

add_action("admin_menu", "leadmarketplace_add_menu_2");

function leadmarketplace_add_menu_2()
{
     add_menu_page("Educash History", "Educash History", 4, "form-page_2", "form_page_2");
}
               
function form_page_1() {
                
             global $wpdb;
             $table_name = $wpdb->prefix . 'educash_allotment';
                 
               
             if($_POST['submit']){
             if(empty($_POST['adminName'])){$adminamerr='This field cannot be blank';}
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
              
             echo "<h2>Use this form to allocate educash to a client</h2><br/>";
             echo "<form method='post' action='".$_SERVER['REQUEST_URI']."'>
             Admin Name (Type your name here):<br/><input type='text' name='adminName' maxlength='70'><span>* $adminamerr </span><br/><br/>
             Client Name (Type the name of the client whom you want to allot educash):<br/><input type='text' name='clientName' maxlength='70'><span>* $clientnamerr </span><br/><br/>        
             Type the educash to be added in the client's account:<br/><input type='number' name='educash' min='-100000000' max='100000000'><span>* $educasherr </span><br/><br/>
             Type your comments here (optional):<br/><input type='text' name='adminComment' maxlength='500'><br/><br/>
             <input type='submit' name='submit'><br/><br/>
             </form>";  
                
             if($_POST['submit'] && (!empty($_POST['adminName'])) && (!empty($_POST['clientName'])) && (!empty($_POST['educash']))){
             $r = $wpdb->get_row("SELECT * FROM $table_name WHERE time = '$time' ");
             echo "<center></p>You have made the following entry just now:</p>";       
             echo "<style>table, th, td{border:1px solid black; border-collapse:collapse;} td{text-align:center;}</style>";
             echo "<table style='width:70%'><tr><th>Id</th><th>Admin Name</th><th>Client Name</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
             echo "<tr><td>".$r->id."</td><td>".$r->admin_name."</td><td>".$r->client_name."</td><td>".$r->educash_added."</td><td>".$r->time."</td><td>".$r->comments."</td></tr>";  
             echo "</table></center><br/><br/>"; 
                  
   }              
               
}              
                  
function form_page_2() {
             
             global $wpdb;
             $table_name = $wpdb->prefix . 'educash_allotment';
                
             echo "<h2>Use this page to know the history of educash transactions</h2><br/>";
             echo "<p>Fill atleast one field</p><br/>";
             echo "<form method='post' action='".$_SERVER['REQUEST_URI']."'>
             Admin Name (Type the name of the admin whose history you want to see):<br/><input type='text' name='admin_Name' maxlength='70'><br/><br/>
             Client Name (Type the name of the client whose history you want to see):<br/><input type='text' name='client_Name' maxlength='70'><br/><br/>
             Date (Select the date whose transaction details you want to see):<br/><input type='date' name='date' min='1990-12-31' max='2050-12-31'><br/><br/>
             <input type='submit' name='Submit'><br/><br/>
             </form>";
              
          
             if($_POST['Submit']){
             if(empty($_POST['admin_Name']) && empty($_POST['client_Name']) && empty($_POST['date'])){
           
             echo "All three fields cannot be blank";
   }    
     
             if(!empty($_POST['admin_Name']) && empty($_POST['client_Name']) && empty($_POST['date'])){
             $admin_Name = $_POST['admin_Name'];   
             $results = $wpdb->get_results("SELECT * FROM $table_name WHERE admin_name = '$admin_Name' ");
             
             echo "<center>The history of transactions made by admin ".$_POST['admin_Name']." is:<br/><br/>";
             echo "<style>table, th, td{border:1px solid black; border-collapse:collapse;} td{text-align:center;}</style>";
             echo "<table style='width:70%'><tr><th>Id</th><th>Admin Name</th><th>Client Name</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
             foreach($results as $r){
                echo "<tr><td>".$r->id."</td><td>".$r->admin_name."</td><td>".$r->client_name."</td><td>".$r->educash_added."</td><td>".$r->time."</td><td>".$r->comments."</td></tr>";
                 }
             echo "</table></center><br/><br/>";
   }
             if(empty($_POST['admin_Name']) && !empty($_POST['client_Name']) && empty($_POST['date'])){
             $client_Name = $_POST['client_Name'];        
             $results = $wpdb->get_results("SELECT * FROM $table_name WHERE client_name = '$client_Name' ");
                                                                
             echo "<center>The history of transactions made by client ".$_POST['client_Name']." is:<br/><br/>";
             echo "<style>table, th, td{border:1px solid black; border-collapse:collapse;} td{text-align:center;}</style>";
             echo "<table style='width:70%'><tr><th>Id</th><th>Admin Name</th><th>Client Name</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
             foreach($results as $r){
                echo "<tr><td>".$r->id."</td><td>".$r->admin_name."</td><td>".$r->client_name."</td><td>".$r->educash_added."</td><td>".$r->time."</td><td>".$r->comments."</td></tr>";
                 }
             echo "</table></center><br/><br/>";
   }
             if(!empty($_POST['admin_Name']) && !empty($_POST['client_Name']) && empty($_POST['date'])){  
             $admin_Name = $_POST['admin_Name'];
             $client_Name = $_POST['client_Name'];    
             $results = $wpdb->get_results("SELECT * FROM $table_name WHERE admin_name = '$admin_Name' AND client_name = '$client_Name' ");
                                                                
             echo "<center>The history of transactions made by admin ".$_POST['admin_Name']." with client ".$_POST['client_Name']." is:<br/><br/>";
             echo "<style>table, th, td{border:1px solid black; border-collapse:collapse;} td{text-align:center;}</style>";
             echo "<table style='width:70%'><tr><th>Id</th><th>Admin Name</th><th>Client Name</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
             foreach($results as $r){
                echo "<tr><td>".$r->id."</td><td>".$r->admin_name."</td><td>".$r->client_name."</td><td>".$r->educash_added."</td><td>".$r->time."</td><td>".$r->comments."</td></tr>";
                 }
             echo "</table></center><br/><br/>";
   }     
             if(empty($_POST['admin_Name']) && empty($_POST['client_Name']) && !empty($_POST['date'])){
             $date=$_POST['date'];
             $results = $wpdb->get_results("SELECT * FROM $table_name WHERE DATE(time)='$date' ");
                                                                
             echo "<center>The history of transactions made on ".$date." is:<br/><br/>";
             echo "<style>table, th, td{border:1px solid black; border-collapse:collapse;} td{text-align:center;}</style>";
             echo "<table style='width:70%'><tr><th>Id</th><th>Admin Name</th><th>Client Name</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
             foreach($results as $r){
                echo "<tr><td>".$r->id."</td><td>".$r->admin_name."</td><td>".$r->client_name."</td><td>".$r->educash_added."</td><td>".$r->time."</td><td>".$r->comments."</td></tr>";
                 }
             echo "</table></center><br/><br/>"; 
   }      
             if(!empty($_POST['admin_Name']) && empty($_POST['client_Name']) && !empty($_POST['date'])){
             $admin_Name = $_POST['admin_Name'];
             $date=$_POST['date'];   
             $results = $wpdb->get_results("SELECT * FROM $table_name WHERE admin_name = '$admin_Name' AND DATE(time)='$date' ");
                                                                
             echo "<center>The history of transactions made by admin ".$_POST['admin_Name']." on ".$date." is:<br/><br/>";
             echo "<style>table, th, td{border:1px solid black; border-collapse:collapse;} td{text-align:center;}</style>";
             echo "<table style='width:70%'><tr><th>Id</th><th>Admin Name</th><th>Client Name</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
             foreach($results as $r){
                echo "<tr><td>".$r->id."</td><td>".$r->admin_name."</td><td>".$r->client_name."</td><td>".$r->educash_added."</td><td>".$r->time."</td><td>".$r->comments."</td></tr>";
                 }
             echo "</table></center><br/><br/>";
   }        
             if(empty($_POST['admin_Name']) && !empty($_POST['client_Name']) && !empty($_POST['date'])){
             $date=$_POST['date'];
             $client_Name = $_POST['client_Name'];    
             $results = $wpdb->get_results("SELECT * FROM $table_name WHERE client_name = '$client_Name' AND DATE(time)='$date' ");
                                                                
             echo "<center>The history of transactions made by client ".$_POST['client_Name']." on ".$date." is:<br/><br/>";
             echo "<style>table, th, td{border:1px solid black; border-collapse:collapse;} td{text-align:center;}</style>";
             echo "<table style='width:70%'><tr><th>Id</th><th>Admin Name</th><th>Client Name</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
             foreach($results as $r){
                echo "<tr><td>".$r->id."</td><td>".$r->admin_name."</td><td>".$r->client_name."</td><td>".$r->educash_added."</td><td>".$r->time."</td><td>".$r->comments."</td></tr>";
                 }
             echo "</table></center><br/><br/>";
   }      
             if(!empty($_POST['admin_Name']) && !empty($_POST['client_Name']) && !empty($_POST['date'])){
             $admin_Name = $_POST['admin_Name'];
             $client_Name = $_POST['client_Name']; 
             $date=$_POST['date'];   
             $results = $wpdb->get_results("SELECT * FROM $table_name WHERE admin_name = '$admin_Name' AND client_name = '$client_Name' AND DATE(time)='$date' ");
                                                                
             echo "<center>The history of transactions made by admin ".$_POST['admin_Name']." with client ".$_POST['client_Name']." on ".$date." is:<br/><br/>";
             echo "<style>table, th, td{border:1px solid black; border-collapse:collapse;} td{text-align:center;}</style>";
             echo "<table style='width:70%'><tr><th>Id</th><th>Admin Name</th><th>Client Name</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
             foreach($results as $r){
                echo "<tr><td>".$r->id."</td><td>".$r->admin_name."</td><td>".$r->client_name."</td><td>".$r->educash_added."</td><td>".$r->time."</td><td>".$r->comments."</td></tr>";
                 }
             echo "</table></center><br/><br/>";
   }        
   } 
}      
?>
