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
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="../styles.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", FALSE);
header("Pragma: no-cache");
session_start();

$query = "Select "

?>
<meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
<meta http-equiv="pragma" content="no-cache" />
<div class="body">
<div class="menubar">
	<ul class="topnav">
		<li><a class="fa fa-home" href="studentHome.php" title="Home" id="home"></a></li>
		<li><a class="post" href="/yourPost.php" title="Your Post">Your Posts</a></li>
		<li><a class="note" href="/notebook.php" title="Notebook">Notebook</a></li>
		<li style="float:right" class="dropdown">
			<a href="javascript:void(0)" class="fa fa-user" id="dropdown-icon"></a>
			<div class="dropdown-content">
				<a href="../index.php">Log out</a>
			</div>
		</li>
	</ul>
</div>
<div class="vertical-data">
	<div class="header"><h4>Your Classes:</h4><hr></div>
	<div class="classes">
		<div class="event">CS 441<hr></div>
		<div class="event">CS 433</div>
	</div>
</div>
<div class="notification"><h3>Notification:</h3><hr>
	<div class="note-class">Today, I learned about Class Diagram<hr></div>
	<div class="note-class">Know the difference between fork and thread</div>
	<br>
</div>	
</body>
</html>