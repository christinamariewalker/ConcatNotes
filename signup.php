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
$servername = include 'config/servername.php';
$dbname = include 'config/dbname.php';
$dbpass = include 'config/dbpassword.php';
$db = include 'config/db.php';

$username = $password = $email = $emailErr = $nameErr = $passErr = $roleErr = "";
$flag = 0;
// Create connection
$link = new mysqli($servername, $dbname, $dbpass, $db);

// check connection
if($link->connect_error){
	die("Connection failed: " . $link->connect_error);
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
  if(empty($_POST["schoolEmail"]))
    $queryErr = "Email address is required.";
  else{//email is not empty
    $email = test_input($_POST["schoolEmail"]);
    if(strpos($email,"@cougars.csusm.edu") === FALSE) 
    {
      $queryErr = "You must enter a CSUSM school email.";
    }
    else
      $flag = 1;
  }
	if(empty($_POST["uname"]))
		$nameErr = "Username is required.";
	else{// username is not empty
		$username = test_input($_POST["uname"]);
  }//end of username is not empty
  if(empty($_POST["psw"]))
      $passErr = "Password is required.";
    else{
       $password = test_input($_POST["psw"]);
    }// end of password is not empty 
  if($flag == 1){
    $query = "SELECT email from students";
    $result = $link->query($query);
    if($result->num_rows > 0){
      while($row = $result->fetch_assoc()){
        if($email == $row["email"]){
          $queryErr = "This email is already used.";
          $flag = 2;
        }
      }
    }
    if($flag == 1){
      $query = "INSERT INTO students (username, password, email, SoftwareEngineering, Cybersecurity, AssemblyLanguage, ComputerArchitecture, EmbeddedSystems, OperatingSystems, ProgrammingLanguages) 
      VALUES ('$username','$password','$email', 0, 0, 0, 0, 0, 0, 0)";
      if($link->query($query) === FALSE)
        $queryErr = "Username is already used.\n";
      else
      {
        $msg = "Thank you for registering for Concatnotes.\n\nYour username is: " . $username;
        $msg = wordwrap($msg,70);
        mail($email,"Concatnotes registration",$msg);
        header("Location: index.php");
      }
    }
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
<div class="signUp">
  <form method = "post" class="formBoxes" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="imgcontainer">
      <img src="images/forum.jpg" alt="Logo" class="avatar">
    </div>

    <div class="container">
      <div class="error"><?php echo $queryErr;?><br></div>
      <label class="formLabels">Academic Email</label>
      <input type="text" name="schoolEmail" required>

      <label class="formLabels">Username</label>
      <input type="text" name="uname" required>

      <label class="formLabels">Password</label>
      <input type="password" name="psw" required>
      <button type="submit" class="btnClassic">Sign Up</button>
      <a href="index.php" class="linkSignUp">Login</a>
    </div>
  </form>
</div>
</body>
</html>
