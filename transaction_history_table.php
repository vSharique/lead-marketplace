<?php
  global $wpdb;
  $name = $wpdb->prefix.'transaction_history';
  $sql = "CREATE TABLE IF NOT EXISTS $name (
                    id int(15) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    client_id int(15) NOT NULL,
                    lead_id int(15) NOT NULL,
                    date_time varchar(200) NOT NULL
                  );";
  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  dbDelta($sql);
  ?>
<?php //transaction_history is a table that store all the details of lead id allocated to user and the date
      // and the date on which the leads are allocated to user. The client_id is user id of particular user ?>
