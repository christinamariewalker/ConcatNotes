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
<link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>

<?php
session_start();
$_SESSION['logon'] = false;
$servername = include 'config/servername.php';
$dbname = include 'config/dbname.php';
$dbpass = include 'config/dbpassword.php';
$db = include 'config/db.php';

$username = $password = $nameErr = $passErr = "";

// Create connection
$link = new mysqli($servername, $dbname, $dbpass, $db);

// check connection
if($link->connect_error){
	die("Connection failed: " . $link->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
	if(empty($_POST["uname"]))
    $nameErr = "Username is required.";
  else{// username is not empty
		$username = test_input($_POST["uname"]);
	  if(empty($_POST["psw"]))
      $passErr = "Password is required.";
    else{
      $password = test_input($_POST["psw"]);
      $query = "SELECT username, password FROM students WHERE username = BINARY '$username' and password = BINARY '$password'";
  
      if($link->query($query) === FALSE)
        $queryErr = "Error: query " . $link->error;
      else{
        $result = $link->query($query);
        if($result->num_rows > 0){
          $_SESSION['logon'] = true;
          $_SESSION['username'] = $username;
          $_SESSION['password'] = $password;
          header("Location: pages/home.php");
          exit();
        } else{
          $queryErr = "Invalid credentials. Try again.";
        }
      }//end of successful query
    }// end of password is not empty       
  }// end of username is not empty 
}//end of POST
function test_input($data){
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
$link->close();
?>
<div class="logIn">
  <form method = "post" class= "formBoxes" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="imgcontainer">
      <img src="images/forum.jpg" alt="Logo" class="avatar">
    </div>
    <div class="container">
      <div class="error"><?php echo $queryErr;?><br></div>
      <label class="formLabels">Username</label>
      <input type="text" name="uname" required>

      <label class="formLabels">Password</label>
      <input type="password" name="psw" required>
     
      <button type="submit" class="btnClassic">Login</button>
      <a href="signup.php" class="linkSignUp">Sign Up</a>
    </div>
  </form>
</div>
</body>
</html>
