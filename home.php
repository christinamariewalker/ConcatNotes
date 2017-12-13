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
       <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- upvote and downvote icon-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Home</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Bootstrap CSS CDN -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- Our Custom CSS -->
        <link rel="stylesheet" href="../styles.css">
        <!-- Scrollbar Custom CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.min.css">
    </head>
    <body>
        <?php
            session_start();
            if($_SESSION['logon'] == false) {
                header("Location: ../index.php");
                die();
            }
            header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
            header("Cache-Control: post-check=0, pre-check=0", FALSE);
            header("Pragma: no-cache");
            $count = 0;
            $username = $_SESSION['username'];
            
            $servername = include '../config/servername.php';
            $dbname = include '../config/dbname.php';
            $dbpass = include '../config/dbpassword.php';
            $db = include '../config/db.php';

            // variables
            $className = "";
            // create connection
            $link = new mysqli($servername, $dbname, $dbpass, $db);
            // check connection
            if($link->connect_error){
                die("Connection failed: " . $link->connect_error);
            }

            // Delete comment after 2 mins
            $query = "SELECT * from validClasses";
            $result = $link->query($query);
            if($result === FALSE){
                echo "Could not get all classes.";
            }
            else{
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $classes[] = $row['className'];
                    }
                    foreach($classes as $class){
                        $query = "DELETE from $class where ts < (NOW() - INTERVAL 2 MINUTE)";
                        if($link->query($query) === FALSE){
                            echo "Could not delete comment";
                        }
                    }
                }
            }

            if($_SERVER["REQUEST_METHOD"] == "POST"){ 
                if(isset($_POST['addClass'])){ //add class button
                    if(isset($_POST['classname'])){
                        if(empty($_POST["classname"]))
                            $nameErr = "Class name is required.";
                        else {
                            $className = test_input($_POST["classname"]);
                            //$className = preg_replace('/\s+/', '', $className);
                            $query = "UPDATE students SET $className = 1 WHERE username = '$username'";
                            $link->query($query);
                        }
                    }
                }//end add class 

                if(isset($_POST['btnRemoveClass'])) {
                    if(isset($_POST['ddlRemoveClass'])){
                        if(empty($_POST["ddlRemoveClass"]))
                            $nameErr = "Class name required for removal.";
                        else {
                            $removeName = test_input($_POST["ddlRemoveClass"]);
                            $removeName = preg_replace('/\s+/', '', $removeName);
                            $query = "UPDATE students SET $removeName = 0 WHERE username = '$username'";
                            $link->query($query);
                        }
                    }
                } //end remove class

                if(isset($_POST['btnSubmitComment'])) {
                    if(isset($_POST['ddlClassList'])){
                        if(empty($_POST["ddlClassList"]))
                            $nameErr = "Class name required for removal.";
                        else
                            $className = test_input($_POST["ddlClassList"]);
                            $className = preg_replace('/\s+/', '', $className);
                        if(empty($_POST["txtArea"]))    
                            $commentErr = "Comment is required.";
                        else {
                            $comment = test_input($_POST["txtArea"]);
                            $query = "INSERT INTO $className (username,comment) VALUES ('$username','$comment')";
                            $link->query($query);
                        }
                    }
                }//end add comment
                header("Location: home.php");
            }//end post

            function test_input($data){
              $data = trim($data);
              $data = stripslashes($data);
              $data = htmlspecialchars($data);
              return $data;
            }

        ?>
        <div class="wrapper">
            <!-- Sidebar -->
            <nav id="sidebar">
                <div class="sidebar-header">
                    <div class="usrDropdown">
                        <img src="../images/User_icon.png" alt="User icon" class=imgUsr>
                        <h4>Hello, <?php echo $username ?></h4>
                        <div class="usrDropdown-content">
                            <a href="changePassword.php" class=usrLinks>Change Password</a>
                            <a href="../index.php" class=usrLinks>Log Out</a>
                        </div>
                    </div>
                </div>
                <ul class="list-unstyled components">
                <p>Your Classes</p>
                <?php
                    $query = "SELECT * from students where username = '$username'";
                    $result = $link->query($query);
                    if($result->num_rows > 0){
                        while($row = $result->fetch_assoc()){
                            if($row["SoftwareEngineering"] == 1)
                                echo "<li><a href=#SoftwareEngineering>Software Engineering</a></li>";                            
                            if($row["Cybersecurity"] == 1)
                                echo "<li><a href=#Cybersecurity>Cybersecurity</a></li>";                            
                            if($row["AssemblyLanguage"] == 1)
                                 echo "<li><a href=#AssemblyLanguage>Assembly Language</a></li>";                            
                            if($row["ComputerArchitecture"] == 1)
                                echo "<li><a href=#ComputerArchitecture>Computer Architecture</a></li>";                            
                            if($row["EmbeddedSystems"] == 1)
                                echo "<li><a href=#EmbeddedSystems>Embedded Systems</a></li>";                            
                            if($row["OperatingSystems"] == 1)
                                echo "<li><a href=#OperatingSystems>Operating Systems</a></li>";                            
                            if($row["ProgrammingLanguages"] == 1)
                                echo "<li><a href=#ProgrammingLanguages>Programming Languages</a></li>";                            
                        }
                    }
                ?>
                </ul>
            </nav>

            <!-- Page Content -->
            <div id="content">
                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                    <a data-toggle="collapse" href="#collapse1" class="arrow-down" onclick="this.classList.toggle('active')"><h3>Add Class</h3></a>
                    </div>
                    <div class="forumSection">
                        <div id="collapse1" class="panel-collapse collapse">
                            <form method = "post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                                <div class="ddl"
                                    <?php echo $queryErr;?><br/>
                                    <?php 
                                    $query = "SELECT* FROM students WHERE username = '$username'";
                                    $result = $link->query($query); 
                                    echo "<select name = \"classname\" class=\"ddlAddClass\">";?>
                                    <option value="" selected>Select a class</option>
                                    <?php  
                                    if($result->num_rows > 0){
                                     while($row = $result->fetch_assoc()){
                                        if($row["SoftwareEngineering"] == 0)
                                            echo "<option value='SoftwareEngineering'>Software Engineering</option>";
                                        if($row['Cybersecurity'] == 0)
                                            echo "<option value='Cybersecurity'>Cybersecurity</option>";
                                        if($row['AssemblyLanguage'] == 0)
                                            echo "<option value='AssemblyLanguage'>Assembly Language</option>";
                                        if($row['ComputerArchitecture'] == 0)
                                            echo "<option value='ComputerArchitecture'>Computer Architecture</option>";
                                        if($row['EmbeddedSystems'] == 0)
                                            echo "<option value='EmbeddedSystems'>Embedded Systems</option>";
                                        if($row['OperatingSystems'] == 0)
                                            echo "<option value='OperatingSystems'>Operating Systems</option>";
                                        if($row['ProgrammingLanguages'] == 0)
                                            echo "<option value='ProgrammingLanguages'>Programming Languages</option>";
                                 }//end while loop
                                    } 
                                    echo "</select>"
                                    ?>
                                    <button type="submit" class="btnSmall" name="addClass">Add Class</button>
                                    <br><br><br>
                                </div>
                            </form>
                        </div>
                    </div>
                </nav>
                <br><br>

                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                      <a data-toggle="collapse" href="#collapse3" class="arrow-down" onclick="this.classList.toggle('active')"><h3>Remove Class</h3></a>
                    </div>
                    <div class="forumSection">
                        <div id="collapse3" class="panel-collapse collapse">
                            <form method = "post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="ddl"
                                <?php echo $queryErr;?><br/>
                                <?php 
                                $query = "SELECT* FROM students WHERE username = '$username'";
                                $result = $link->query($query); 
                                echo "<select name = \"ddlRemoveClass\" class=\"ddlAddClass\">";?>
                                <option value="" selected>Your classes</option>
                                <?php  
                                if($result->num_rows > 0){
                                 while($row = $result->fetch_assoc()){
                                        if($row["SoftwareEngineering"] == 1)
                                            echo "<option value='SoftwareEngineering'>Software Engineering</option>";
                                        if($row['Cybersecurity'] == 1)
                                            echo "<option value='Cybersecurity'>Cybersecurity</option>";
                                        if($row['AssemblyLanguage'] == 1)
                                            echo "<option value='AssemblyLanguage'>Assembly Language</option>";
                                        if($row['ComputerArchitecture'] == 1)
                                            echo "<option value='ComputerArchitecture'>Computer Architecture</option>";
                                        if($row['EmbeddedSystems'] == 1)
                                            echo "<option value='EmbeddedSystems'>Embedded Systems</option>";
                                        if($row['OperatingSystems'] == 1)
                                            echo "<option value='OperatingSystems'>Operating Systems</option>";
                                        if($row['ProgrammingLanguages'] == 1)
                                            echo "<option value='ProgrammingLanguages'>Programming Languages</option>";
                                 }//end while loop
                                } 
                                echo "</select>"
                                ?>
                                <button type="submit" class="btnSmall" name="btnRemoveClass">Remove Class</button>
                                <br><br><br>
                            </form>
                        </div>
                    </div>
                </nav>
                <br><br>

                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                      <a data-toggle="collapse" href="#collapse2" class="arrow-down" onclick="this.classList.toggle('active')"><h3>Insert Comment</h3></a>
                    </div>
                    <div class="forumSection">
                     <div id="collapse2" class="panel-collapse collapse">
                        <form method = "post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                            <div class="ddl"
                            <?php echo $queryErr;?><br/>
                            <?php 
                            $query = "SELECT* FROM students WHERE username = '$username'";
                                $result = $link->query($query); 
                                echo "<select name = \"ddlClassList\" class=\"ddlAddClass\">";?>
                                <option value="" selected>Your classes</option>
                                <?php  
                                if($result->num_rows > 0){
                                 while($row = $result->fetch_assoc()){
                                       if($row["SoftwareEngineering"] == 1)
                                            echo "<option value='SoftwareEngineering'>Software Engineering</option>";
                                        if($row['Cybersecurity'] == 1)
                                            echo "<option value='Cybersecurity'>Cybersecurity</option>";
                                        if($row['AssemblyLanguage'] == 1)
                                            echo "<option value='AssemblyLanguage'>Assembly Language</option>";
                                        if($row['ComputerArchitecture'] == 1)
                                            echo "<option value='ComputerArchitecture'>Computer Architecture</option>";
                                        if($row['EmbeddedSystems'] == 1)
                                            echo "<option value='EmbeddedSystems'>Embedded Systems</option>";
                                        if($row['OperatingSystems'] == 1)
                                            echo "<option value='OperatingSystems'>Operating Systems</option>";
                                        if($row['ProgrammingLanguages'] == 1)
                                            echo "<option value='ProgrammingLanguages'>Programming Languages</option>";
                                 }//end while loop
                                } 
                                echo "</select></br></br>"
                            ?>
                            </div>
                            <textarea rows="4" cols="130" name="txtArea" placeholder="Enter comment here" class="txtArea" required></textarea></br>
                            <button type="submit" class="btnSmall" name="btnSubmitComment">Submit</button> 
                            <br><br><br>
                        </form>
                    </div>
                 </div>
                </nav>
                <br><br>

                <nav class="navbar navbar-default">
                    <div class="container-fluid">
                        <h3>Comments</h3>
                    </div>
                    <div class="forumSection">
                    <?php 
                        $query = "SELECT * from students where username = '$username'";
                        $result = $link->query($query);
                        if($result->num_rows > 0){
                            while($row = $result->fetch_assoc()){
                                if($row["SoftwareEngineering"] == 1){
                                    $commentedClass[] = "SoftwareEngineering";
                                }
                                if($row["Cybersecurity"] == 1){
                                    $commentedClass[] = "Cybersecurity";
                                }
                                if($row["AssemblyLanguage"] == 1){
                                    $commentedClass[] = "AssemblyLanguage";
                                }
                                if($row["ComputerArchitecture"] == 1){
                                    $commentedClass[] = "ComputerArchitecture";
                                }
                                if($row["EmbeddedSystems"] == 1){
                                    $commentedClass[] = "EmbeddedSystems";
                                }
                                if($row["OperatingSystems"] == 1){
                                    $commentedClass[] = "OperatingSystems";
                                }
                                if($row["ProgrammingLanguages"] == 1){
                                    $commentedClass[] = "ProgrammingLanguages";
                                }
                            }
                        }

                        $classFlag = 0;
                        $classCount = 1;
                        $colorFlag = 1;
                        foreach ($commentedClass as $class) {
                            $i = 1;
                            $newClass = preg_replace('/([a-z])([A-Z])/s','$1 $2', $class);
                            $query = "SELECT comment, ts FROM $class";
                            $result = $link->query($query);
                            if($result->num_rows > 0){
                                $classFlag = 1;
                                if($classCount%2 == 0) 
                                    echo "<div class='evenCommentStyle'><h4 id=" . $class . "><center><u>" . $newClass . "</u></center></h4>";
                                else
                                    echo "<div class='oddCommentStyle'><h4 id=" . $class . "><center><u>" . $newClass . "</u></center></h4>";
                                while($row = $result->fetch_assoc()){
                                    $date = date_create($row["ts"]);
                                    $newDate = date_format($date, 'g:i A');
                                    echo "<div class='timestamp'>" . $newDate . "</div>";
                                    echo "<div class='commentWord'>" . $row["comment"] . "</div></br>";
                                    if($i != $result->num_rows){
                                        echo "<div class='oddCommentLine'></div>";
                                    }
                                    $i++;
                                }//end while
                                echo "<div class='classLine'></div></div>";
                                $classCount++;
                            } 
                        }   
                        if($classFlag == 0)
                            echo "No comments to display";
                    ?>
            </div>
            </nav>
        </div>
        </nav>                


        <?php $link->close();?>

        <!-- jQuery CDN -->
        <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        <!-- Bootstrap Js CDN -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- jQuery Custom Scroller CDN -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                $("#sidebar").mCustomScrollbar({
                    theme: "minimal"
                });

                $('#content').on('click', function () {
                    $('.arrows[aria-expanded=true]').attr('aria-expanded', 'false');
                });
            });
        </script>
    </body>
</html>
