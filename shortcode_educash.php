<html>
<head>
<style>
.boxed{
   height: auto ;
   width: 45%;
   padding: 22px;
   text-align: center;
   border: 4px solid gray;
   margin-left: 30%;

}
</style>
</head>
<body>

<?php
  add_shortcode('educash','educash');
  function educash($atts,$content = null){
   global $wpdb;
   $cash = 0;
   if(is_user_logged_in()){
     $current_user = wp_get_current_user();
     $name = $current_user->user_firstname.' '.$current_user->user_lastname;
     $sql = "SELECT educash_added FROM wp_educash_deals WHERE client_name = '$name'";
     $myrow = $wpdb->get_results($sql);
     ?><br><br><div class="boxed"><?php
     if(count($myrow)>0){

     echo '<h3><b>Hello '.$current_user->user_login.',</b></h3>';
     //echo '<h3><b>Hello mayank chandra bhuban singh joshi ji on this way,</b></h3>';
     echo 'Username: ' . $current_user->user_login . '<br />';
     echo 'User email: ' . $current_user->user_email . '<br />';
     echo 'User first name: ' . $current_user->user_firstname . '<br />';
     echo 'User last name: ' . $current_user->user_lastname . '<br />';
     foreach ($myrow as $row)
     { $cash = $row->educash_added;}
     echo '<h4><b>Your educash is : '.$cash."</b></h4>";
     }

     else echo '<h4><b>Your educash is : '.$cash."</b></h4>";
     ?></div><br><?php
  }
 }
 ?>
</body>
</html>


      <?php /* to include file include this file in edugorilla page.
        (*** include_once plugin_dir_path(__FILE__) . "shortcode.php"; ***) ?>
        and use shorcode *** [educash] ***
      */ ?>
