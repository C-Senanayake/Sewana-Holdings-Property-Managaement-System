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
    $requirement_no='';
    $client_no='';
    $required_Type='';
    $max_Rental='';
    $area='';
    $w_date='';

    if(isset($_GET['user_id'])){
        $requirement_no=mysqli_real_escape_string($connection,$_GET['user_id']);
        
        $query="SELECT * FROM property_requirement WHERE Requirement_No={$requirement_no} LIMIT 1";
        $result_set=mysqli_query($connection,$query);
        
        if($result_set){
            if(mysqli_num_rows($result_set)==1){
                //user found
                $result=mysqli_fetch_assoc($result_set);
                $client_no=$result['Client_No'];
                $required_Type=$result['Required_Type'];
                $max_Rental=$result['Max_Rent'];
                $area=$result['Area'];
                $w_date=$result['Date_Willing_To_Rent'];
                
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
        $requirement_no=$_POST['user_id'];
        $client_no= $_POST['Client_no'];
        $required_Type= $_POST['Required_Type'];
        $max_Rental= $_POST['Max_Rental'];
        $area= $_POST['Area'];
        $w_date= $_POST['W_date'];
       
         //checking required fields
         $req_fields=array('Client_no', 'Required_Type', 'Max_Rental', 'Area','W_date');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array('Client_no'=>10, 'Max_Rental'=>10, 'Area'=>100);
        foreach($max_len_fields as $field=>$max_len){
            if(strlen(trim($_POST[$field]))>$max_len){
                $errors[]= $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        if(empty($errors)){
            //No errors found,adding new records
            $Client_no=mysqli_real_escape_string($connection,$_POST['Client_no']);
            $Required_Type=mysqli_real_escape_string($connection,$_POST['Required_Type']);
            $Max_Rental=mysqli_real_escape_string($connection,$_POST['Max_Rental']);
            $Area=mysqli_real_escape_string($connection,$_POST['Area']);
            $W_date=mysqli_real_escape_string($connection,$_POST['W_date']);
             
            $query= "UPDATE property_requirement SET Client_No='{$Client_no}',Required_Type='{$Required_Type}',Area='{$Area}',Max_Rent='{$Max_Rental}',Date_Willing_To_Rent='{$W_date}'";
            $query.="WHERE Requirement_No={$requirement_no} LIMIT 1";

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
    <h2 >Edit requirements&ensp;&ensp;<span ><a href="data.php"><=Back to Property requirement List</a></span></h2>

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
        <input type="hidden" name="user_id" value="<?php echo $requirement_no;?>">
        <div class="one_line"><div class="labels"><label for="E_name" name="Client_no">Client Number:</label></div>
        <div class="inputs"><input type="text" name="Client_no" id="input" placeholder="Client Number" <?php echo 'value="'. $client_no. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_salary" name="Required_Type">Required Type:</label></div>
         <div class="inputs">
             <select name="Required_Type">
            <option>Select one</option>
            <option>Annex</option>
            <option>Bunglow</option>
            <option>Commercial</option>
            <option>Flat</option>
         </select>
         </div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Area">Area:</label></div>
         <div class="inputs"><input type="text" name="Area" id="input" placeholder="Area" <?php echo 'value="'. $area. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Max_Rental">Max Rental:</label></div>
         <div class="inputs"><input type="text" name="Max_Rental" id="input" placeholder="Max Rental" <?php echo 'value="'. $max_Rental. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="W_date">Date willing to rent:</label></div>
         <div class="inputs"><input type="text" name="W_date" id="input" placeholder="Date willing to rent" <?php echo 'value="'. $w_date. '"';?>></div></div>
         <button type="submit" name="submit" onclick=" return confirm('Are You Sure?')">Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
