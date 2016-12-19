<?php
/*
Plugin Name: Ak company records
Description: Company records
version: 1.0
Author: Ak
*/
?>
<?php

$name=$points=$uname=$upass=$userInstitute=$useremail=$uregname=$uregpass=$uregconfirmpass='';

global $name, $points, $uname, $upass, $userInstitute, $useremail, $uregname, $uregpass, $uregconfirmpass;

global $jal_db_version;
$jal_db_version = '1.0';

function table_create() {

	global $wpdb;
	global $jal_db_version;

	$table_name = $wpdb->prefix . 'institute_details';
	
	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		institute_name tinytext NOT NULL,
		email varchar(70) NOT NULL,
                institute_username tinytext NOT NULL,
                institute_password tinytext NOT NULL,
                points int(9) NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	add_option( 'jal_db_version', $jal_db_version );
}



register_activation_hook( __FILE__, 'table_create' );


add_action("admin_menu", "addMenu");

function addMenu()
{
     add_menu_page("Institute Details", "Institute Details", 4, "form-page", "form_page");
}

function form_page(){
    $namerr=$pointerr='';
    
        global $wpdb;
        $table_name = $wpdb->prefix . 'institute_details';

           if($_POST['SUBMIT']){if(empty($_POST['instituteName'])){$namerr='Please fill in the name of the institute';}
                                else{$name=$_POST['instituteName'];}
                                if(empty($_POST['institutePoints'])){$pointerr='Please allot some points';}
                                else{$points=$_POST['institutePoints'];}
                                
                                if((!empty($_POST['instituteName'])) && (!empty($_POST['institutePoints'])))
              {
            
            
             $wpdb->update( 
		$table_name, 
		array( 
			'points' => $points
		),
                array('institute_name' => $name) 
	);
      }
    }



        echo "<center><form method='post' action='".$_SERVER['REQUEST_URI']."'>".
         "Name of Institute: <input type='text' name='instituteName' maxlength='70'><span>*".$namerr."</span><br/><br/>".
         "Number of points: <input type='text' name='institutePoints' maxlength='70'><span>*".$pointerr."</span><br/><br/>".
         "<input type='submit' name='SUBMIT'><br/><br/>".
         "</form>";
                     
  
    $results = $wpdb->get_results("SELECT * FROM $table_name ");

    echo "</p>List of institutes who have registered themselves:</p>";

    echo "<style>table, th, td{border:1px solid black; border-collapse:collapse;} td{text-align:center;}</style>";

    echo "<table style='width:50%'><tr><th>Id</th><th>Name</th><th>Points</th></tr>";

    foreach($results as $r) {
       echo "<tr><td>".$r->id."</td><td>".$r->institute_name."</td><td>".$r->points."</td></tr>";
    }
      
    echo "</table></center>";     

}

class Ak_company_records extends WP_Widget{



  function Ak_company_records(){
            $widget_options = array(
              'classname'=>'Ak_company_records',
              'description'=>__('Company details')
              );
   parent::WP_Widget('Ak_company_records', 'Company details', $widget_options);
      }


  function widget($args, $instance){

    $institutenamerr=$emailerr=$usernamerr=$passerr=$confirmpasserr='';
    global $wpdb;
    $table_name = $wpdb->prefix . 'institute_details';



           if($_POST['Submit']){if(empty($_POST['userInstitute'])){$institutenamerr='Please fill in the name of your institute';}
                                else{$userInstitute=$_POST['userInstitute'];}

                                if(empty($_POST['useremail'])){$emailerr='Please fill in your email id';}
                                else{$useremail=$_POST['useremail'];}

                                if(empty($_POST['uregname'])){$usernamerr='Please choose a username';}
                                else{$uregname=$_POST['uregname'];}

                                if(empty($_POST['uregpass'])){$passerr='Please choose a password';}
                                else{$uregpass=$_POST['uregpass'];}

                                if(empty($_POST['uregconfirmpass'])){$confirmpasserr='Please confirm your password';}
                                else{$uregconfirmpass=$_POST['uregconfirmpass'];}
                                   
                                if($uregpass===$uregconfirmpass){
                                if((!empty($_POST['userInstitute'])) && (!empty($_POST['useremail'])) && (!empty($_POST['uregname'])) && (!empty($_POST['uregpass'])) && (!empty($_POST['uregconfirmpass'])))
              {
            
            
             $wpdb->insert( 
		$table_name, 
		array( 
			'institute_name' => $userInstitute, 
			'email' => $useremail,
                        'institute_username' => $uregname,
                        'institute_password' => $uregpass,
                        'points' => 0,  
		) 
	    );
                echo "Your registration has been done<br/>";
          }
       }
               else{$confirmpasserr="Your Confirm password is different than your chosen password";}
      
      }
        

          if($_POST['submit']){
                               if(empty($_POST['uname'])){$usernamerr='Please choose a username';}
                                else{$uname=$_POST['uname'];}

                                if(empty($_POST['upass'])){$passerr='Please choose a password';}
                                else{$upass=$_POST['upass'];}

                               if((!empty($_POST['userInstitute'])) && (!empty($_POST['useremail']))){
                             
                             $point = $wpdb->get_results("SELECT * FROM $table_name WHERE institute_username = $uname AND institute_password = $upass ");
                                
             

    

                            foreach($point as $p) {
                              echo "Your number of points is $p->points <br/> ";
                             }    
      
     

                               
                               }
                           }                         


           echo "Are you a coaching institute and want to know your balance? If yes, login below";

           echo "<div id='userlogin'><form method='post' action='".$_SERVER['REQUEST_URI']."'>Username: <input type='text' name='uname' maxlength='70'>*<br/>
                                        <span class='error'> $usernamerr </span><br/>
                                    Password: <input type='password' name='upass' maxlength='70'>*<br/>
                                         <span class='error'> $passerr </span><br/>
                                              <input type='submit' name='submit'><br/></form>
            New to our website? <a onclick = 'changeInput1()'>click here to register</a></div>";

           echo "<div id='userreg' style='display:none;'><form method='post' action='".$_SERVER['REQUEST_URI']."'>
                         Name of institute: <input type='text' name='userInstitute' maxlength='70'>*<br/>
                                      <span class='error'> $institutenamerr </span><br/>
                                     email: <input type='text' name='useremail' maxlength='70'>*<br/>
                                       <span class='error'> $emailerr </span><br/>
                                  Username: <input type='text' name='uregname' maxlength='70'>*<br/>
                                       <span class='error'> $usernamerr </span><br/>
                                  Password: <input type='password' name='uregpass' maxlength='70'>*<br/>
                                       <span class='error'> $passerr </span><br/>
                          Confirm Password: <input type='password' name='uregconfirmpass' maxlength='70'>*<br/>
                                        <span class='error'> $confirmpasserr </span><br/>
                                            <input type='submit' name='Submit'><br/>
                                            </form>
            Already have an account? <a onclick = 'changeInput2()'>click here to login</a></div>";
              
        echo "<script>
         function changeInput1(){
           document.getElementById('userlogin').style.display='none';
           document.getElementById('userreg').style.display='block';
                }</script>";

      echo "<script>
         function changeInput2(){
           document.getElementById('userlogin').style.display='block';
           document.getElementById('userreg').style.display='none';
                }</script>";
}
  
  function form($instance){          
           echo "username: <input type='text' disabled><br/>
                password: <input type='password' disabled><br/><br/>
                          <input type='submit'><br/>
                New to our website? <a>click here to register</a>";
     }
}

function Ak_company_records_init(){
      register_widget('Ak_company_records');
}

add_action('widgets_init', 'Ak_company_records_init');
?>
