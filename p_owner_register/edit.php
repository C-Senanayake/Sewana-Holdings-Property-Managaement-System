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
    $owner_id='';
    $branch_no='';
     if(isset($_GET['user_id'])){
        $owner_id=mysqli_real_escape_string($connection,$_GET['user_id']);
        
        $query="SELECT * FROM property_owner_register WHERE Owner_ID={$owner_id} LIMIT 1";
        $result_set=mysqli_query($connection,$query);
        
        if($result_set){
            if(mysqli_num_rows($result_set)==1){
                //user found
                $result=mysqli_fetch_assoc($result_set);
                $branch_no=$result['Branch_No'];
                
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
        $owner_id=$_POST['user_id'];
        $branch_no= $_POST['Branch_No'];
             

         //checking required fields
         $req_fields=array('user_id','Branch_No');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array('Branch_No'=>10 );
        foreach($max_len_fields as $field=>$max_len){
            if(strlen(trim($_POST[$field]))>$max_len){
                $errors[]= $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        if(empty($errors)){
            //No errors found,adding new records
            $Branch_no=mysqli_real_escape_string($connection,$_POST['Branch_No']);
            
            
            $query= "UPDATE property_owner_register SET Branch_No='{$Branch_no}'";
            $query.="WHERE Owner_ID={$owner_id} LIMIT 1";

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
        <input type="hidden" name="user_id" value="<?php echo $owner_id;?>">
         <div class="one_line"><div class="labels"><label for="C_name" name="Branch_No">Branch Number:</label></div>
         <div class="inputs"><input type="text" name="Branch_No" id="input" placeholder="Branch Number" <?php echo 'value="'. $branch_no. '"';?>></div></div>
         
        <button type="submit" name="submit" onclick=" return confirm('Are You Sure?')">Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
