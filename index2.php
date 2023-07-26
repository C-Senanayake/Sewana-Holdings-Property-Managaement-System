<?php session_start();?>
<?php
    require_once('include/connection.php');
?>
<?php
    if(isset($_POST['submit'])){ //checking whether user pressed submit button
        $errors=array();
        if(!isset($_POST['Username']) || strlen(trim($_POST['Username']))<1){
            $errors[]="Username invalid";
        }
        if(!isset($_POST['pass']) || strlen(trim($_POST['pass']))<1){
            $errors[]="password Invalid";
        }
        if(empty($errors)){
            $U_Name=mysqli_real_escape_string($connection,$_POST['Username']);
            $Pass=mysqli_real_escape_string($connection,$_POST['pass']);
            $query= "SELECT * FROM user 
            WHERE Email='{$U_Name}' 
            AND  passwor='{$Pass}' 
            LIMIT 1";
            $result_set=mysqli_query($connection,$query);
            if($result_set){
                if(mysqli_num_rows($result_set)==1){
                    $user=mysqli_fetch_assoc($result_set);
                    $_SESSION['user_ID']=$user['U_ID'];
                    $_SESSION['First_Name']=$user['First_Name'];
                    //adding login time
                //     $Query="UPDATE user SET Last_Login_Date= NOW() WHERE User_ID={$_SESSION['user_ID']} LIMIT 1";
                //     $Result_set=mysqli_query($connection,$Query);
                //     if(!$Result_set){
                //         die("Database connection error");
                //     }
                     header('Location:main_page.php');
                }else{
                    $errors[]="Invalid username/password";
                }
            }else{
                $errors[]="Database query failed";
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="css/inde.css">
</head>
<body>
    <nav id="navigation">
    <div class="main_name">Sewana Holdings</div>
    </nav>
    <div class="container_1">
    <form action="index.php" method="POST">
        <h3 id="login">Log In</h3>
        <?php 
            if(isset($errors) && !empty($errors)){
                echo '<p id="errors" >Invalid username/password</p>';
            }
        ?>
        <?php
            if(isset($_GET['logout'])){
                echo '<p id="log" >Successfully loged out</p>';
            }
        ?>
        <label for="username" name="Username">Username:</label>
        <input type="text" name="Username" id="input" placeholder="Username"><br>
        <label for="password" name="pass">Password:</label>
        <input type="password" name="pass" id="input" placeholder="Password"><br>
        <button type="submit" name="submit" >Log</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>