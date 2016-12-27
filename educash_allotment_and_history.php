<?php
function educash_deals_form_page()
{

    global $wpdb;
    $table_name3 = $wpdb->prefix . 'educash_deals';
    $users_table = $wpdb->prefix.users;

    if ($_POST['submit']) {

        if (empty($_POST['clientName'])) {
            $clientnamerr = "<span  style='color:red;'>This field cannot be blank</span>";
        } else {
            $clientName = $_POST['clientName'];
            $check_client = $wpdb->get_var("SELECT COUNT(ID) from $users_table WHERE ID = '$clientName' ");
            if($check_client == 0){
                $invalid_client = "<span style='color:red'>This client does not exist in our database</span>";
            }
        }

        if (empty($_POST['educash'])) {
            $educasherr = "<span  style='color:red;'>This field cannot be blank</span>";
        } else {
            $educash = $_POST['educash'];
        }

        if ((!empty($_POST['clientName'])) && (!empty($_POST['educash'])) && (!($check_client == 0))) {
            $adminName = wp_get_current_user();
            $adminComment = $_POST['adminComment'];
            $time = current_time('mysql');
            $wpdb->insert($table_name3, array(
                'time' => $time,
                'admin_name' => $adminName->ID,
                'client_name' => $clientName,
                'educash_added' => $educash,
                'comments' => $adminComment
            ));
        }
    }

    if ($_POST['Submit']) {
        if (empty($_POST['admin_Name']) && empty($_POST['client_Name']) && empty($_POST['date'])) {

            $all_three_error = "<span style='color:red;'> *All three fields cannot be blank</span>";
        }
    }
    echo "<style>table, th, td{border:1px solid black; border-collapse:collapse;} td{text-align:center;}</style>";
?>
    <div style='display:inline-block; width:48%;'><h2>Use this form to allocate educash to a client</h2><br/>
    <form method='post' onsubmit="return confirm('Do you really want to submit this entry?');" action="<?php echo $_SERVER['REQUEST_URI'];?>">
             Client ID (Type the ID of the client whom you want to allot educash):<br/><input type='number' name='clientName' min='1' max='1000000000000'><span>* <?php echo $clientnamerr; echo $invalid_client;?> </span><br/><br/>
             Type the educash to be added in the client's account:<br/><input type='number' name='educash' min='-100000000' max='100000000'><span>* <?php echo $educasherr;?> </span><br/><br/>
             Type your comments here (optional):<br/><textarea rows='4' cols='60' name='adminComment' maxlength='500'></textarea><br/><br/>
             <input type='submit' name='submit'><br/>
             </form></div>
<?php
    echo "<div style='display:inline-block; width:48%;'><h2>Use this form to know the history of educash transactions</h2>";
    echo "<p>Fill atleast one field<p>";
    echo "<form method='post' action='" . $_SERVER['REQUEST_URI'] . "'>
             Admin ID (Type the ID of the admin whose history you want to see):<br/><input type='number' name='admin_Name' min='1' max='1000000000000'><br/><br/>
             Client ID (Type the ID of the client whose history you want to see):<br/><input type='number' name='client_Name' min='1' max='1000000000000'><br/><br/>
             Date (Select the date whose transaction details you want to see):<br/><input type='date' name='date' min='1990-12-31' max='2050-12-31'><br/><br/>
             <input type='submit' name='Submit'>" . $all_three_error . "<br/><br/><br/>
             </form></div>";

    if ($_POST['submit'] && (!empty($_POST['clientName'])) && (!empty($_POST['educash'])) && (!($check_client == 0))) {
        $r = $wpdb->get_row("SELECT * FROM $table_name3 WHERE time = '$time' ");
        echo "<center></p>You have made the following entry just now:</p>";
        echo "<table style='width:70%'><tr><th>Id</th><th>Admin ID</th><th>Client ID</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
        echo "<tr><td>" . $r->id . "</td><td>" . $r->admin_name . "</td><td>" . $r->client_name . "</td><td>" . $r->educash_added . "</td><td>" . $r->time . "</td><td>" . $r->comments . "</td></tr>";
        echo "</table></center><br/><br/>";

    }

    if ($_POST['Submit']) {
        if (!empty($_POST['admin_Name']) && empty($_POST['client_Name']) && empty($_POST['date'])) {
            $admin_Name = $_POST['admin_Name'];
            $results = $wpdb->get_results("SELECT * FROM $table_name3 WHERE admin_name = '$admin_Name' ");

            echo "<center><p>The history of transactions made by admin " . $_POST['admin_Name'] . " is:</p>";
            echo "<table style='width:70%'><tr><th>Id</th><th>Admin ID</th><th>Client ID</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
            foreach ($results as $r) {
                echo "<tr><td>" . $r->id . "</td><td>" . $r->admin_name . "</td><td>" . $r->client_name . "</td><td>" . $r->educash_added . "</td><td>" . $r->time . "</td><td>" . $r->comments . "</td></tr>";
            }
            echo "</table><br/>";
            $total = $wpdb->get_var("SELECT sum(educash_added) FROM $table_name3 WHERE admin_name = '$admin_Name' ");
            echo "<span style='color:green;'>Total educash transactions made by admin " . $_POST['admin_Name'] . " is <b>" . $total . "</b></span></center>";
        }
        if (empty($_POST['admin_Name']) && !empty($_POST['client_Name']) && empty($_POST['date'])) {
            $client_Name = $_POST['client_Name'];
            $results = $wpdb->get_results("SELECT * FROM $table_name3 WHERE client_name = '$client_Name' ");

            echo "<center><p>The history of transactions made by client " . $_POST['client_Name'] . " is:</p>";
            echo "<table style='width:70%'><tr><th>Id</th><th>Admin ID</th><th>Client ID</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
            foreach ($results as $r) {
                echo "<tr><td>" . $r->id . "</td><td>" . $r->admin_name . "</td><td>" . $r->client_name . "</td><td>" . $r->educash_added . "</td><td>" . $r->time . "</td><td>" . $r->comments . "</td></tr>";
            }
            echo "</table><br/>";
            $total = $wpdb->get_var("SELECT sum(educash_added) FROM $table_name3 WHERE client_name = '$client_Name' ");
            echo "<span style='color:green;'>Total educash transactions made by client " . $_POST['client_Name'] . " is <b>" . $total . "</b></span></center>";

        }
        if (!empty($_POST['admin_Name']) && !empty($_POST['client_Name']) && empty($_POST['date'])) {
            $admin_Name = $_POST['admin_Name'];
            $client_Name = $_POST['client_Name'];
            $results = $wpdb->get_results("SELECT * FROM $table_name3 WHERE admin_name = '$admin_Name' AND client_name = '$client_Name' ");

            echo "<center><p>The history of transactions made by admin " . $_POST['admin_Name'] . " with client " . $_POST['client_Name'] . " is:</p>";
            echo "<table style='width:70%'><tr><th>Id</th><th>Admin ID</th><th>Client ID</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
            foreach ($results as $r) {
                echo "<tr><td>" . $r->id . "</td><td>" . $r->admin_name . "</td><td>" . $r->client_name . "</td><td>" . $r->educash_added . "</td><td>" . $r->time . "</td><td>" . $r->comments . "</td></tr>";
            }
            echo "</table><br/>";
            $total = $wpdb->get_var("SELECT sum(educash_added) FROM $table_name3 WHERE admin_name = '$admin_Name' AND client_name = '$client_Name' ");
            echo "<span style='color:green;'>Total educash transactions made by admin " . $_POST['admin_Name'] . " with client " . $_POST['client_Name'] . " is <b>" . $total . "</b></span></center>";
        }
        if (empty($_POST['admin_Name']) && empty($_POST['client_Name']) && !empty($_POST['date'])) {
            $date = $_POST['date'];
            $results = $wpdb->get_results("SELECT * FROM $table_name3 WHERE DATE(time)='$date' ");

            echo "<center><p>The history of transactions made on " . $date . " is:</p>";
            echo "<table style='width:70%'><tr><th>Id</th><th>Admin ID</th><th>Client ID</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
            foreach ($results as $r) {
                echo "<tr><td>" . $r->id . "</td><td>" . $r->admin_name . "</td><td>" . $r->client_name . "</td><td>" . $r->educash_added . "</td><td>" . $r->time . "</td><td>" . $r->comments . "</td></tr>";
            }
            echo "</table><br/>";
            $total = $wpdb->get_var("SELECT sum(educash_added) FROM $table_name3 WHERE DATE(time)='$date' ");
            echo "<span style='color:green;'>Total educash transactions made on " . $date . " is <b>" . $total . "</b></span></center>";
        }
        if (!empty($_POST['admin_Name']) && empty($_POST['client_Name']) && !empty($_POST['date'])) {
            $admin_Name = $_POST['admin_Name'];
            $date = $_POST['date'];
            $results = $wpdb->get_results("SELECT * FROM $table_name3 WHERE admin_name = '$admin_Name' AND DATE(time)='$date' ");

            echo "<center><p>The history of transactions made by admin " . $_POST['admin_Name'] . " on " . $date . " is:</p>";
            echo "<table style='width:70%'><tr><th>Id</th><th>Admin ID</th><th>Client ID</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
            foreach ($results as $r) {
                echo "<tr><td>" . $r->id . "</td><td>" . $r->admin_name . "</td><td>" . $r->client_name . "</td><td>" . $r->educash_added . "</td><td>" . $r->time . "</td><td>" . $r->comments . "</td></tr>";
            }
            echo "</table><br/>";
            $total = $wpdb->get_var("SELECT sum(educash_added) FROM $table_name3 WHERE admin_name = '$admin_Name' AND DATE(time)='$date' ");
            echo "<span style='color:green;'>Total educash transactions made by admin " . $_POST['admin_Name'] . " on " . $date . " is <b>" . $total . "</b></span></center>";
        }
        if (empty($_POST['admin_Name']) && !empty($_POST['client_Name']) && !empty($_POST['date'])) {
            $date = $_POST['date'];
            $client_Name = $_POST['client_Name'];
            $results = $wpdb->get_results("SELECT * FROM $table_name3 WHERE client_name = '$client_Name' AND DATE(time)='$date' ");

            echo "<center><p>The history of transactions made by client " . $_POST['client_Name'] . " on " . $date . " is:</p>";
            echo "<table style='width:70%'><tr><th>Id</th><th>Admin ID</th><th>Client ID</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
            foreach ($results as $r) {
                echo "<tr><td>" . $r->id . "</td><td>" . $r->admin_name . "</td><td>" . $r->client_name . "</td><td>" . $r->educash_added . "</td><td>" . $r->time . "</td><td>" . $r->comments . "</td></tr>";
            }
            echo "</table><br/>";
            $total = $wpdb->get_var("SELECT sum(educash_added) FROM $table_name3 WHERE client_name = '$client_Name' AND DATE(time)='$date' ");
            echo "<span style='color:green;'>Total educash transactions made by client " . $_POST['client_Name'] . " on " . $date . " is <b>" . $total . "</b></span></center>";
        }
        if (!empty($_POST['admin_Name']) && !empty($_POST['client_Name']) && !empty($_POST['date'])) {
            $admin_Name = $_POST['admin_Name'];
            $client_Name = $_POST['client_Name'];
            $date = $_POST['date'];
            $results = $wpdb->get_results("SELECT * FROM $table_name3 WHERE admin_name = '$admin_Name' AND client_name = '$client_Name' AND DATE(time)='$date' ");

            echo "<center><p>The history of transactions made by admin " . $_POST['admin_Name'] . " with client " . $_POST['client_Name'] . " on " . $date . " is:</p>";
            echo "<table style='width:70%'><tr><th>Id</th><th>Admin ID</th><th>Client ID</th><th>Educash added</th><th>Time</th><th>Comments</th></tr>";
            foreach ($results as $r) {
                echo "<tr><td>" . $r->id . "</td><td>" . $r->admin_name . "</td><td>" . $r->client_name . "</td><td>" . $r->educash_added . "</td><td>" . $r->time . "</td><td>" . $r->comments . "</td></tr>";
            }
            echo "</table><br/>";
            $total = $wpdb->get_var("SELECT sum(educash_added) FROM $table_name3 WHERE admin_name = '$admin_Name' AND client_name = '$client_Name' AND DATE(time)='$date' ");
            echo "<span style='color:green;'>Total educash transactions made by admin " . $_POST['admin_Name'] . " with client " . $_POST['client_Name'] . " on " . $date . " is <b>" . $total . "</b></span></center>";
        }
    }
}
?>
