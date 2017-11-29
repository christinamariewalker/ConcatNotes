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
<link rel="stylesheet" type="text/css" href="../profHome.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
<?php
session_start();
?>

<meta http-equiv="expires" content="Sun, 01 Jan 2014 00:00:00 GMT"/>
<meta http-equiv="pragma" content="no-cache" />

<div class="menubar">
	<ul class="topnav">
		<li><a class="fa fa-home" href="" title="Home" id="home"></a></li>
		<li><a class="post" href="/yourPost.php" title="Your Post">Your Posts</a></li>
		<li><a class="note" href="/notebook.php" title="Notebook">Notebook</a></li>
		<li><a class="note" href="/.php" title="Notebook">Notebook</a></li>
		<li style="float:right" class="dropdown">
			<a href="javascript:void(0)" class="fa fa-user" id="dropdown-icon"></a>
			<div class="dropdown-content">
				<a href="../index.php">Log out</a>
			</div>
		</li>
	</ul>
</div>

<div class="navbar navbar-inverse navbar-fixed-left">
  <ul class="nav navbar-nav">
   <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Your Classes <span class="caret"></span></a>
   <li><a href="#">Class1</a></li>
   <li><a href="#">Class2</a></li>
   <li><a href="#">Class3</a></li>
   <li><a href="#">Class4</a></li>
  </ul>
</div>

<div class="centerNotification">
	<div align="right">
	<a href="#">Class1</a>
	<a href="#">Comments</a>
	<a href="#">Rating</a>
</div>
	<p><b>Note: </b> This is your notification text.</p>
</div>
<div class="centerNotification">
	<div align="right">
	<a href="#">Class2</a>
	<a href="#">Comments</a>
	<a href="#">Rating</a>
</div>
	<p><b>Note: </b> This is your notification text.</p>
</div>

<div class="centerNotification">
	<div align="right">
	<a href="#">Class3</a>
	<a href="#">Comments</a>
	<a href="#">Rating</a>
</div>
	<p><b>Note: </b> This is your notification text.</p>
</div>
<div class="centerNotification">
	<div align="right">
	<a href="#">Class4</a>
	<a href="#">Comments</a>
	<a href="#">Rating</a>
</div>
	<p><b>Note: </b> This is your notification text.</p>
</div>

</body>
</html>