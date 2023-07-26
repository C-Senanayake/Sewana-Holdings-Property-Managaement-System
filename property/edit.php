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
    $property_no='';
    $property_Address='';
    $property_Type='';
    $property_Rental='';
    $no_Of_Rooms='';
    $branch_No='';
    $owner_ID='';

    if(isset($_GET['user_id'])){
        $property_no=mysqli_real_escape_string($connection,$_GET['user_id']);
        
        $query="SELECT * FROM property WHERE Property_No={$property_no} LIMIT 1";
        $result_set=mysqli_query($connection,$query);
        
        if($result_set){
            if(mysqli_num_rows($result_set)==1){
                //user found
                $result=mysqli_fetch_assoc($result_set);
                $property_Address=$result['Property_Address'];
                $property_Type=$result['Property_Type'];
                $property_Rental=$result['Property_Rental'];
                $no_Of_Rooms=$result['No_Of_Rooms'];
                $branch_No=$result['Branch_No'];
                $owner_ID=$result['Owner_ID'];
                
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
        $property_no=$_POST['user_id'];
        $property_Address= $_POST['Property_Address'];
        $property_Type= $_POST['Property_Type'];
        $property_Rental= $_POST['Property_Rental'];
        $no_Of_Rooms= $_POST['No_Of_Rooms'];
        $branch_No= $_POST['Branch_No'];
        $owner_ID= $_POST['Owner_ID'];
        
         //checking required fields
         $req_fields=array('user_id','Property_Address', 'Property_Type', 'Property_Rental', 'No_Of_Rooms','Branch_No','Owner_ID');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array('Property_Address'=>100,'Property_Rental'=>10,2, 'No_Of_Rooms'=>10,'Branch_No'=>10,'Owner_ID'=>10);
        foreach($max_len_fields as $field=>$max_len){
            if(strlen(trim($_POST[$field]))>$max_len){
                $errors[]= $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        if(empty($errors)){
            //No errors found,adding new records
            $Property_Address=mysqli_real_escape_string($connection,$_POST['Property_Address']);
            $Property_Type=mysqli_real_escape_string($connection,$_POST['Property_Type']);
            $Property_Rental=mysqli_real_escape_string($connection,$_POST['Property_Rental']);
            $No_Of_Rooms=mysqli_real_escape_string($connection,$_POST['No_Of_Rooms']);
            $Branch_No=mysqli_real_escape_string($connection,$_POST['Branch_No']);
            $Owner_ID=mysqli_real_escape_string($connection,$_POST['Owner_ID']);
            
            $query= "UPDATE property SET Property_Address='{$Property_Address}',Property_Type='{$Property_Type}',Property_Rental='{$Property_Rental}',No_Of_Rooms='{$No_Of_Rooms}',Branch_No='{$Branch_No}',Owner_ID='{$Owner_ID}'";
            $query.="WHERE Property_No={$property_no} LIMIT 1";

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
        <input type="hidden" name="user_id" value="<?php echo $property_no;?>">
        <div class="one_line"><div class="labels"><label for="E_name" name="Property_Address">Property Address:</label></div>
        <div class="inputs"><input type="text" name="Property_Address" id="input" placeholder="Property Address" <?php echo 'value="'. $property_Address. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_salary" name="Property_Type">Property Type:</label></div>
         <div class="inputs">
             <select name="Property_Type">
            <option>Select one</option>
            <option>Annex</option>
            <option>Bunglow</option>
            <option>Commercial</option>
            <option>Flat</option>
         </select>
         </div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Property_Rental">Property Rental:</label></div>
         <div class="inputs"><input type="text" name="Property_Rental" id="input" placeholder="Property Rental" <?php echo 'value="'. $property_Rental. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="No_Of_Rooms">No Of Rooms:</label></div>
         <div class="inputs"><input type="text" name="No_Of_Rooms" id="input" placeholder="No Of Rooms" <?php echo 'value="'. $no_Of_Rooms. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Branch_No">Working Branch Number:</label></div>
         <div class="inputs"><input type="text" name="Branch_No" id="input" placeholder="Working Branch Number" <?php echo 'value="'. $branch_No. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Owner_ID">Owner ID:</label></div>
         <div class="inputs"><input type="text" name="Owner_ID" id="input" placeholder="Owner_ID" <?php echo 'value="'. $owner_ID. '"';?>></div></div>
         <button type="submit" name="submit" onclick=" return confirm('Are You Sure?')">Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
