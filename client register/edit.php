<?php session_start();?>
<?php
    require_once('../include/connection.php');
?>
<?php
$user_id='';
    if(!isset($_SESSION['user_ID'])){
        header('Location:../index.php');
    }
    if(isset($_SESSION['user_ID'])){
              

        if(isset($_GET['user_id'])){
            $user_id=mysqli_real_escape_string($connection,$_GET['user_id']);
        }  
        
        
    }
    $errors=array();
    $client_id='';
    $branch_no='';
    $staff_name='';
    $r_date='';
    if(isset($_GET['user_id'])){
        $client_id=mysqli_real_escape_string($connection,$_GET['user_id']);
        
        $query="SELECT * FROM client_register WHERE Client_ID={$client_id} LIMIT 1";
        $result_set=mysqli_query($connection,$query);
        
        if($result_set){
            if(mysqli_num_rows($result_set)==1){
                //user found
                $result=mysqli_fetch_assoc($result_set);
                $branch_no=$result['Branch_No'];
                $staff_name=$result['Staff_Name'];
                $r_date=$result['R_Date'];
                
            }
            else{
                //user not found
                header('Location:data.php?err=user_not_found');
            }
        }
        else{
            //query failed
            header('Location:data.php?err=query_failed');
        }
    }

    if(isset($_POST['submit'])){
        $client_id=$_POST['user_id'];
        $branch_no= $_POST['Branch_No'];
        $staff_name= $_POST['Staff_Name'];
        $r_date= $_POST['R_Date'];
             

         //checking required fields
         $req_fields=array('user_id','Branch_No', 'Staff_Name','R_Date');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array('Branch_No'=>10, 'Staff_Name'=>100 );
        foreach($max_len_fields as $field=>$max_len){
            if(strlen(trim($_POST[$field]))>$max_len){
                $errors[]= $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        if(empty($errors)){
            //No errors found,adding new records
            $Branch_no=mysqli_real_escape_string($connection,$_POST['Branch_No']);
            $Staff_name=mysqli_real_escape_string($connection,$_POST['Staff_Name']);
            $R_date=mysqli_real_escape_string($connection,$_POST['R_Date']);
           
            
            $query= "UPDATE client_register SET Branch_No='{$Branch_no}',Staff_Name='{$Staff_name}',R_Date='{$R_date}'";
            $query.="WHERE Client_ID={$client_id} LIMIT 1";

            $result=mysqli_query($connection,$query);
            if($result){
                //query successful
                header('Location:data.php?user_modified=successful');
            }
            else{
                $errors[]='Failed to modify the record';
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
    <title>Add Client</title>
    <link rel="stylesheet" href="../css/newuser.css">
</head>
<body>
    <nav id="navigation">
    <div class="main_name">Sewana Holdings</div>
    <div class="loggedin">Welcome <span class="name"><?php echo $_SESSION['First_Name']; ?>!</span>&emsp; <a href="../logout.php" class="logout">Log out</a> </div>
    </nav>
    <div class="container_1">
    <h2 >Edit Client&ensp;&ensp;<span ><a href="data.php"><=Back to Client List</a></span></h2>

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

    <form action="edit.php" method="POST">
        <input type="hidden" name="user_id" value="<?php echo $client_id;?>">
         <div class="one_line"><div class="labels"><label for="C_name" name="Branch_No">Branch Number:</label></div>
         <div class="inputs"><input type="text" name="Branch_No" id="input" placeholder="Branch Number" <?php echo 'value="'. $branch_no. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Staff_Name">Registered By:</label></div>
         <div class="inputs"><input type="text" name="Staff_Name" id="input" placeholder="Registered By" <?php echo 'value="'. $staff_name. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="R_Date">Registered Date:</label></div>
         <div class="inputs"><input type="text" name="R_Date" id="input" placeholder="Registered Date" <?php echo 'value="'. $r_date. '"';?>></div></div>
         <?php
            if($user_id==$_SESSION['user_ID']){
               echo '<div class="one_line"><div class="labels"><label for="n_password" name="Password">Present/New Password:</label></div>';
               echo '<div class="inputs"><input type="password" name="Password" id="input" placeholder="Password"></div></div>';
            }
            else{    
            }   
        ?>
        <button type="submit" name="submit" onclick=" return confirm('Are You Sure?')">Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
