
$message = '';
$db = new MySQLi('localhost', 'fujiconn_user', 'Qc^4)ZtZ87^y', 'fujiconn_website');
mysqli_set_charset( $db, "utf8");

if ($db->connect_error) {
    $message = $db->connect_error;
}

$sql = "SELECT language FROM users where language='administrator'";
$result = $db->query($sql);

  $user_details=array();
 $user_stolen_serials=array();
if (mysqli_num_rows($result) > 0)
{
    while($userData = $result->fetch_assoc())  
    {
        print_r($userData['email']);
    }
}
?>