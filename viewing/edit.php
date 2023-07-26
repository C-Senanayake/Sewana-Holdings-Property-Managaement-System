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
    $view_id='';
    $c_no='';
    $p_no='';
    $date='';
    $comment='';
    if(isset($_GET['user_id'])){
        $view_id=mysqli_real_escape_string($connection,$_GET['user_id']);
        
        $query="SELECT * FROM view WHERE View_ID={$view_id} LIMIT 1";
        $result_set=mysqli_query($connection,$query);
        
        if($result_set){
            if(mysqli_num_rows($result_set)==1){
                //user found
                $result=mysqli_fetch_assoc($result_set);
                $c_no=$result['Client_No'];
                $p_no=$result['Property_No'];
                $date=$result['View_Date'];
                $comment=$result['Comment'];
                
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
        $view_id=$_POST['user_id'];
        $c_no= $_POST['Client_No'];
        $p_no= $_POST['Property_No'];
        $date= $_POST['Date'];
        $comment= $_POST['Comment'];
               

         //checking required fields
         $req_fields=array( 'Property_No', 'Date', 'Comment');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array( 'Property_No'=>10 ,'Comment'=>255 );
        foreach($max_len_fields as $field=>$max_len){
            if(strlen(trim($_POST[$field]))>$max_len){
                $errors[]= $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        if(empty($errors)){
            //No errors found,adding new records
            $C_no=mysqli_real_escape_string($connection,$_POST['Client_No']);
            $P_no=mysqli_real_escape_string($connection,$_POST['Property_No']);
            $Date=mysqli_real_escape_string($connection,$_POST['Date']);
            $Comment=mysqli_real_escape_string($connection,$_POST['Comment']);
             
            
            $query= "UPDATE view SET Property_No='{$P_no}',View_Date='{$Date}',Comment='{$Comment}'";
            $query.="WHERE View_ID={$view_id} LIMIT 1";

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
    <h2 >Add New Employee&ensp;&ensp;<span ><a href="data.php"><=Back to Employee List</a></span></h2>

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
    <input type="hidden" name="user_id" value="<?php echo $view_id;?>">
    
       
         <!-- <div class="one_line"><div class="labels"><label for="E_name" name="Client_No">Client Number:</label></div>
        <div class="inputs"><input type="text" name="Client_No" id="input" placeholder="Client Number" <?php echo 'value="'. $c_no. '"';?>></div></div> -->
         <div class="one_line"><div class="labels"><label for="E_salary" name="Property_No">Property Number:</label></div>
         <div class="inputs"><input type="text" name="Property_No" id="input" placeholder="Property Number" <?php echo 'value="'. $p_no. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Date">Date Viewed:</label></div>
         <div class="inputs"><input type="text" name="Date" id="input" placeholder="Date Viewed" <?php echo 'value="'. $date. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Comment">Comment:</label></div>
         <div class="inputs"><textarea name="Comment" cols="45" rows="15" id="input" placeholder="Comment about viewing!!" <?php echo 'value="'. $comment. '"';?>></textarea></div></div>
         <button type="submit" name="submit" >Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
