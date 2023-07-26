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
    $supervisor_id='';
    $appointed_date='';

    if(isset($_GET['user_id'])){
        $supervisor_id=mysqli_real_escape_string($connection,$_GET['user_id']);
        
        $query="SELECT * FROM supervisor WHERE Supervisor_ID={$supervisor_id} LIMIT 1";
        $result_set=mysqli_query($connection,$query);
        
        if($result_set){
            if(mysqli_num_rows($result_set)==1){
                //user found
                $result=mysqli_fetch_assoc($result_set);
                $appointed_date=$result['S_Appointed_Date'];
                
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
        $supervisor_id=$_POST['user_id'];
        $appointed_date= $_POST['Appointed_date'];
               

         //checking required fields
         $req_fields=array( 'Appointed_date');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
        
        if(empty($errors)){
            //No errors found,adding new records
            $Appointed_date=mysqli_real_escape_string($connection,$_POST['Appointed_date']);
           
            $query= "UPDATE supervisor SET S_Appointed_Date='{$Appointed_date}'";
            $query.="WHERE Supervisor_ID={$supervisor_id} LIMIT 1";

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
    <title>Modify User</title>
    <link rel="stylesheet" href="../css/newuser.css">
</head>
<body>
    <nav id="navigation">
    <div class="main_name">Sewana Holdings</div>
    <div class="loggedin">Welcome <?php echo $_SESSION['First_Name']; ?>!&emsp; <a href="../logout.php" class="logout">Log out</a> </div>
    </nav>
    <div class="container_1">
    <h2 >Edit Manager&ensp;&ensp;<span ><a href="data.php"><=Back to Manager List</a></span></h2>

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
        <input type="hidden" name="user_id" value="<?php echo $supervisor_id;?>"> 
        <div class="one_line"><div class="labels"><label for="C_name" name="Appointed_date">Supervisor appoint. date:</label></div>
         <div class="inputs"><input type="text" name="Appointed_date" id="input" placeholder="yyyy-mm-dd" <?php echo 'value="'. $appointed_date. '"';?>></div></div>

        <button type="submit" name="submit" onclick=" return confirm('Are You Sure?')">Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
