<?php
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $redirect);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title> ConcatNote </title>
<link rel="stylesheet" type="text/css" href="../styles.css">
</head>
<body>
<?php
session_start();
$servername = include '../config/servername.php';
$dbname = include '../config/dbname.php';
$dbpass = include '../config/dbpassword.php';
$db = include '../config/db.php';

$newPassword = $confNewpassword = "";
$username = $_SESSION['username'];
$password = $_SESSION['password'];

// Create connection
$link = new mysqli($servername, $dbname, $dbpass, $db);

// check connection
if($link->connect_error){
	die("Connection failed: " . $link->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $oldPassword = test_input($_POST["oldPassword"]);
		$newPassword = test_input($_POST["newPassword"]);
    $confNewpassword = test_input($_POST["confNewpassword"]);

    if($password == $oldPassword){
      if($newPassword == $confNewpassword) {
        echo "PASSWORDS MATCH";
        $query = "UPDATE students SET password = '$newPassword' WHERE username = '$username'";
        if($link->query($query) === FALSE){
          $queryErr = "Error changing password.";
        }
        else {
          $success = "You signed up successfully.";
            header('Location: home.php');
        } 
      } 
      else {
        $queryErr = "Passwords do not match.";
      } 
    }
    else {
        $queryErr = "Old password is incorrect.";
    }
}//end of POST

function test_input($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
$link->close();
?>
<div class="changePassword">
  <form method = "post" class="formBoxes" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="imgcontainer">
      <img src="../images/forum.jpg" alt="Logo" class="avatar">
    </div>

    <div class="container">
      <div class="error"><?php echo $queryErr;?><br></div>
      <div class="success"><?php echo $success;?><br></div>
      <label class="formLabels">Old Password</label>
      <input type="password" placeholder="Enter old password" name="oldPassword" required>

      <label class="formLabels">New Password</label>
      <input type="password" placeholder="Enter new password" name="newPassword" required>

      <label class="formLabels">Confirm Password</label>
      <input type="password" placeholder="Confirm new password" name="confNewpassword" required>
      <button type="submit" class="btnClassic">Submit</button>
    </div>
  </form>
</div>
</body>
</html>
