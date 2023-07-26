<?php session_start();?>
<?php
    require_once('../include/connection.php');
?>
<?php
    if(!isset($_SESSION['user_ID'])){
        header('Location:../index.php');
    }
    $errors=array();
    
    $emp_id='';
    $appointed_date='';

    if(isset($_POST['submit'])){

        $emp_id= $_POST['Emp_id'];
        $appointed_date= $_POST['Appointed_date'];

        //checking required fields
        $req_fields=array('Emp_id', 'Appointed_date');
        foreach($req_fields as $field){
            if(empty(trim($_POST[$field]))){
                $errors[]= $field . ' is required';
            }
        }
        //checking nax lengths
       $max_len_fields=array('Emp_id'=> 10  );
       foreach($max_len_fields as $field=>$max_len){
           if(strlen(trim($_POST[$field]))>$max_len){
               $errors[]= $field . ' must be less than ' . $max_len . ' characters';
           }
       }

        if(empty($errors)){
            //No errors found,adding new records
            $Emp_id=mysqli_real_escape_string($connection,$_POST['Emp_id']);
            $Appointed_date=mysqli_real_escape_string($connection,$_POST['Appointed_date']);
            
            $Query="SELECT * FROM employee WHERE Emp_ID=$Emp_id ";
            $Results=mysqli_query($connection,$Query);
            
                    $Result=mysqli_fetch_assoc($Results);
            
                    $flag= $Result['Employee_Sectioned'];
            


            if($flag=='0'){
                    
                $query= "INSERT INTO manager(Emp_ID,M_Appointed_Date)";
                $query.="VALUES ('{$Emp_id}','{$Appointed_date}')";

                $results=mysqli_query($connection,$query);
                $user_id=mysqli_insert_id($connection);

                if($results){

                    $Query="SELECT * FROM user WHERE U_ID=$Emp_id";
                
                    $users=mysqli_query($connection,$Query);
                    if($users){
                        $user=mysqli_fetch_assoc($users);

                        $Password=$user['passwor'];
                        $Name=$user['First_Name'];


                        // $query= "INSERT INTO user(U_ID,First_Name,passwor)";
                        // $query.="VALUES ('{$user_id}','{$Name}','{$Password}')";
                    
                        // $result=mysqli_query($connection,$query);

                        // $qery="DELETE FROM user WHERE U_ID={$Emp_id} LIMIT 1";
                        // $rsult=mysqli_query($connection,$qery);

                        $qery="UPDATE user SET U_ID={$user_id} WHERE Emp_ID=$Emp_id LIMIT 1";
                            $rsult=mysqli_query($connection,$qery);
                        
                        $quey="UPDATE employee SET Employee_Sectioned='1' WHERE Emp_ID=$Emp_id LIMIT 1";
                        $reslt=mysqli_query($connection,$quey);

                    
                    }

                header('Location:data.php?user_added=successful');

                }else{
                    echo "Failed to add record";
                }

            }else{
                echo "Have same employee ID";
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
    <title>Users</title>
    <link rel="stylesheet" href="../css/newuser.css">
</head>
<body>
    <nav id="navigation">
    <div class="main_name">Sewana Holdings</div>
    <div class="loggedin">Welcome <span class="name"><?php echo $_SESSION['First_Name']; ?>!</span>&emsp; <a href="../logout.php" class="logout">Log out</a> </div>
    </nav>
    <div class="container_1">
    <h2 >Add New Manager&ensp;&ensp;<span >
    <?php
                        if( ($_SESSION['user_ID']>0) && ($_SESSION['user_ID']<100)){
                            echo "<a href=\"../main_page.php\"><=Back to Home</a></span></h2>";
                        }  
                        else if( ($_SESSION['user_ID']>99999) && ($_SESSION['user_ID']<200000) ){
                            echo "<a href=\"../manager/main_page.php\"><=Back to Home</a></span></h2>";
                        }
                        else if( ($_SESSION['user_ID']>199999) && ($_SESSION['user_ID']<300000) ){
                            echo "<a href=\"../supervisor/main_page.php\"><=Back to Home</a></span></h2>";
                        }
                        else if( ($_SESSION['user_ID']>299999) && ($_SESSION['user_ID']<400000) ){
                            echo "<a href=\"../assistant/main_page.php\"><=Back to Home</a></span></h2>";
                        }
                        else if( ($_SESSION['user_ID']>399999) && ($_SESSION['user_ID']<500000) ){
                            echo "<a href=\"../client/main_page.php\"><=Back to Home</a></span></h2>";
                        }
                        else if( ($_SESSION['user_ID']>499999) && ($_SESSION['user_ID']<600000) ){
                            echo "<a href=\"../company/main_page.php\"><=Back to Home</a></span></h2>";
                        }    
                        else if( ($_SESSION['user_ID']>599999) && ($_SESSION['user_ID']<700000) ){
                            echo "<a href=\"../person/main_page.php\"><=Back to Home</a></span></h2>";
                        }  
    ?>     

    <?php 
    if(!empty($errors)){
        echo '<div class="err_msg"';
            echo '<b>There are error(s) in your form.</b><br>';
            foreach($errors as $error){
                echo '-'.$error . '<br>';
            }
        echo '</div>';
     }
     ?>

    <form action="new_data.php" method="POST">
       
        <div class="one_line"><div class="labels"><label for="E_name" name="Emp_id">Employee ID:</label></div>
        <div class="inputs"><input type="text" name="Emp_id" id="input" placeholder="Employee ID" <?php echo 'value="'. $emp_id. '"';?>></div></div>
         
         <div class="one_line"><div class="labels"><label for="C_name" name="Appointed_date">Manager appointed date:</label></div>
         <div class="inputs"><input type="text" name="Appointed_date" id="input" placeholder="yyyy-mm-dd" <?php echo 'value="'. $appointed_date. '"';?>></div></div>

        <button type="submit" name="submit" >Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
