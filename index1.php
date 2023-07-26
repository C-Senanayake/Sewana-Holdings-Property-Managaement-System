<?php session_start();?>
<?php
    require_once('include/connection.php');
?>
<?php
    $flag='';
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
            $hashed_password=sha1($Pass);

                $query= "SELECT * FROM user WHERE ((U_ID='{$U_Name}'  OR NIC='{$U_Name}') AND  passwor='{$hashed_password}') LIMIT 1";
                $result_set=mysqli_query($connection,$query);
                if($result_set){
                    if(mysqli_num_rows($result_set)==1){
                        $user=mysqli_fetch_assoc($result_set);
                        $_SESSION['user_ID']=$user['U_ID'];
                        $_SESSION['First_Name']=$user['First_Name'];
                        $_SESSION['NIC']=$user['NIC'];
                        // $_SESSION['NIC']=$user['NIC'];

                        //adding login time
                        $Query="UPDATE user SET Last_Login_Date= NOW() WHERE U_ID={$_SESSION['user_ID']}  LIMIT 1";
                        $Result_set=mysqli_query($connection,$Query);
                        
                        if( ($_SESSION['user_ID']>0) && ($_SESSION['user_ID']<100)){
                            header('Location:main_page.php');
                        }  
                        else if( ($_SESSION['user_ID']>99999) && ($_SESSION['user_ID']<200000) ){
                            header('Location:manager/main_page.php');
                        }
                        else if( ($_SESSION['user_ID']>199999) && ($_SESSION['user_ID']<300000) ){
                            header('Location:supervisor/main_page.php');
                        }
                        else if( ($_SESSION['user_ID']>299999) && ($_SESSION['user_ID']<400000) ){
                            header('Location:assistant/main_page.php');
                        }
                        else if( ($_SESSION['user_ID']>399999) && ($_SESSION['user_ID']<50000) ){
                            header('Location:client/main_page.php');
                        }
                        else if( ($_SESSION['user_ID']>499999) && ($_SESSION['user_ID']<600000) ){
                            header('Location:company/main_page.php');
                        }
                        else{
                            $Query="UPDATE user SET Last_Login_Date= NOW() WHERE NIC={$_SESSION['NIC']}  LIMIT 1";
                            $Result_set=mysqli_query($connection,$Query);
                        
                            header('Location:person/main_page.php');
                        }    
                        // else{
                        //     $Query="UPDATE user SET Last_Login_Date= NOW() WHERE NIC={$_SESSION['NIC']}  LIMIT 1";
                        //     $Result_set=mysqli_query($connection,$Query);
                            
                            
                        //     header('Location:person/main_page.php');
                        // }    


                    }else{
                        $errors[]="Invalid username/password";
                    }
                }
                else{
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