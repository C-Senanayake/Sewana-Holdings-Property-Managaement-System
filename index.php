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

                $query= "SELECT * FROM user WHERE (U_ID='{$U_Name}'  AND  passwor='{$hashed_password}') LIMIT 1";
                $result_set=mysqli_query($connection,$query);

                // $query1= "SELECT * FROM manager WHERE (Manager_No='{$U_Name}'  AND  Passwor='{$hashed_password}') LIMIT 1";
                // $result_set1=mysqli_query($connection,$query1);
               

                // $query2= "SELECT * FROM supervisor WHERE ((U_ID='{$U_Name}'  OR NIC='{$U_Name}') AND  passwor='{$hashed_password}') LIMIT 1";
                // $result_set2=mysqli_query($connection,$query2);
               

                // $query3= "SELECT * FROM assistant WHERE ((U_ID='{$U_Name}'  OR NIC='{$U_Name}') AND  passwor='{$hashed_password}') LIMIT 1";
                // $result_set3=mysqli_query($connection,$query3);
               

                $querya= "SELECT * FROM client WHERE (Client_No='{$U_Name}'   AND  Passwor='{$hashed_password}') LIMIT 1";
                $result_seta=mysqli_query($connection,$querya);
               

                $queryb= "SELECT * FROM company WHERE (Company_ID='{$U_Name}'  AND  Passwor='{$hashed_password}') LIMIT 1";
                $result_setb=mysqli_query($connection,$queryb);
               
                $queryc= "SELECT * FROM person WHERE (Person_NIC='{$U_Name}'  AND  Passwor='{$hashed_password}') LIMIT 1";
                $result_setc=mysqli_query($connection,$queryc);
               

                // if($result_set){
                //     if(mysqli_num_rows($result_set)==1){
                //         $user=mysqli_fetch_assoc($result_set);
                //         $_SESSION['user_ID']=$user['U_ID'];
                //         $_SESSION['First_Name']=$user['First_Name'];
                //         $_SESSION['NIC']=$user['NIC'];
                //         // $_SESSION['NIC']=$user['NIC'];

                //         //adding login time
                //         $Query="UPDATE user SET Last_Login_Date= NOW() WHERE U_ID={$_SESSION['user_ID']}  LIMIT 1";
                //         $Result_set=mysqli_query($connection,$Query);
                        
                        // if($result_set){
                            if(mysqli_num_rows($result_set)==1){
                                $user=mysqli_fetch_assoc($result_set);
                                $_SESSION['user_ID']=$user['U_ID'];
                                $_SESSION['First_Name']=$user['First_Name'];
                                    if( ($_SESSION['user_ID']<100) ){
                                        header('Location:main_page.php');
                                    }
                                    else if( ($_SESSION['user_ID']>99999) && ($_SESSION['user_ID']<200000) ){
                                        header('Location:manager/main_page.php');
                                    }else if( ($_SESSION['user_ID']>199999) && ($_SESSION['user_ID']<300000) ){
                                        header('Location:supervisor/main_page.php');
                                    }
                                    else if( ($_SESSION['user_ID']>299999) && ($_SESSION['user_ID']<400000) ){
                                        header('Location:assistant/main_page.php');
                                    }

                            }
                            // else{
                            //     $errors[]="1Invalid 1username/password";
                            // }
                        // }
                        // else if( $result_set1){
                        //     if(mysqli_num_rows($result_set1)==1){
                        //         $user=mysqli_fetch_assoc($result_set1);
                        //         $_SESSION['user_ID']=$user['U_ID'];
                        //         $_SESSION['First_Name']=$user['First_Name'];
                        //         header('Location:manager/main_page.php');

                        //     }else{
                        //         $errors[]="Invalid username/password";
                        //     }
                        // }  
                        // else if( $result_set2){
                        //     if(mysqli_num_rows($result_set2)==1){
                        //         $user=mysqli_fetch_assoc($result_set2);
                        //         $_SESSION['user_ID']=$user['U_ID'];
                        //         $_SESSION['First_Name']=$user['First_Name'];
                        //         header('Location:supervisor/main_page.php');

                        //     }else{
                        //         $errors[]="Invalid username/password";
                        //     }
                        // }  
                        // else if( $result_set3){
                        //     if(mysqli_num_rows($result_set3)==1){
                        //         $user=mysqli_fetch_assoc($result_set3);
                        //         $_SESSION['user_ID']=$user['U_ID'];
                        //         $_SESSION['First_Name']=$user['First_Name'];
                        //         header('Location:assistant/main_page.php');

                        //     }else{
                        //         $errors[]="Invalid username/password";
                        //     }
                        // }  
                        // else if( $result_seta){
                            else if(mysqli_num_rows($result_seta)==1){
                                $user=mysqli_fetch_assoc($result_seta);
                                $_SESSION['user_ID']=$user['Client_No'];
                                $_SESSION['First_Name']=$user['Client_Name'];
                                header('Location:client/main_page.php');

                            }
                            // else{
                            //     $errors[]="2Invalid username/password";
                            // }
                        // }  
                        // else if( $result_setb){
                            else if(mysqli_num_rows($result_setb)==1){
                                $user=mysqli_fetch_assoc($result_setb);
                                $_SESSION['user_ID']=$user['Company_ID'];
                                $_SESSION['First_Name']=$user['Company_Name'];
                                header('Location:company/main_page.php');

                            }
                            // else{
                            //     $errors[]="3Invalid username/password";
                            // }
                        // }  
                        // else if( $result_setc){
                            else if(mysqli_num_rows($result_setc)==1){
                                $user=mysqli_fetch_assoc($result_setc);
                                $_SESSION['user_ID']=$user['Person_ID'];
                                $_SESSION['First_Name']=$user['Person_Name'];
                                $_SESSION['NIC']=$user['Person_NIC'];
                                header('Location:person/main_page.php');
                            }
                            // else{
                            //         $errors[]="4Invalid username/password";
                            //     }
                            
                        //}
                        else{
                            $errors[]="Invalid username/password";
                        
                        }    
                        // else if( ($_SESSION['user_ID']>99999) && ($_SESSION['user_ID']<200000) ){
                        //     header('Location:manager/main_page.php');
                        // }
                        // else if( ($_SESSION['user_ID']>199999) && ($_SESSION['user_ID']<300000) ){
                        //     header('Location:supervisor/main_page.php');
                        // }
                        // else if( ($_SESSION['user_ID']>299999) && ($_SESSION['user_ID']<400000) ){
                        //     header('Location:assistant/main_page.php');
                        // }
                        // else if( ($_SESSION['user_ID']>399999) && ($_SESSION['user_ID']<50000) ){
                        //     header('Location:client/main_page.php');
                        // }
                        // else if( ($_SESSION['user_ID']>499999) && ($_SESSION['user_ID']<600000) ){
                        //     header('Location:company/main_page.php');
                        // }
                        // else{
                        //     $Query="UPDATE user SET Last_Login_Date= NOW() WHERE NIC={$_SESSION['NIC']}  LIMIT 1";
                        //     $Result_set=mysqli_query($connection,$Query);
                        
                        //     header('Location:person/main_page.php');
                        // }    
                        // else{
                        //     $Query="UPDATE user SET Last_Login_Date= NOW() WHERE NIC={$_SESSION['NIC']}  LIMIT 1";
                        //     $Result_set=mysqli_query($connection,$Query);
                            
                            
                        //     header('Location:person/main_page.php');
                        // }    


                //     }else{
                //         $errors[]="Invalid username/password";
                //     }
                // }
                // else{
                //     $errors[]="Database query failed";
                
                // }
        
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
    <link rel="stylesheet" href="css/index.css">
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
        <input type="text" name="Username" id="input" placeholder="User ID/ NIC(only for owner type person) "><br>
        <label for="password" name="pass">Password:</label>
        <input type="password" name="pass" id="input" placeholder="Password"><br>
        <button type="submit" name="submit" >Log</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>