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
    $lease_id='';
    $c_no='';
    $p_no='';
    $rental='';
    $duration='';
    $start_date='';
    $finish_date='';
    $e_no='';
    $payment_method='';
    if(isset($_GET['user_id'])){
        $lease_id=mysqli_real_escape_string($connection,$_GET['user_id']);
        
        $query="SELECT * FROM lease WHERE Lease_No={$lease_id} LIMIT 1";
        $result_set=mysqli_query($connection,$query);
        
        if($result_set){
            if(mysqli_num_rows($result_set)==1){
                //user found
                $result=mysqli_fetch_assoc($result_set);
                $c_no=$result['Client_No'];
                $p_no=$result['Property_No'];
                $rental=$result['Monthly_Rent'];
                $duration=$result['Duration'];
                $start_date=$result['St_Date'];
                $finish_date=$result['Finish_Date'];
                $e_no=$result['Emp_No'];
                $payment_method=$result['Payment_Method'];
                
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
        $lease_id=$_POST['user_id'];
        $c_no= $_POST['Client_No'];
        $p_no= $_POST['Property_No'];
        $rental= $_POST['Rental'];
        $duration= $_POST['Duration'];
        $start_date= $_POST['Start_Date'];
        $finish_date= $_POST['Finish_Date'];
        $e_no= $_POST['Employee_No'];
        $payment_method= $_POST['Payment_Method'];
        

         //checking required fields
         $req_fields=array('user_id','Client_No', 'Property_No', 'Rental', 'Duration','Start_Date','Finish_Date','Employee_No','Payment_Method');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array('Client_No'=>10, 'Property_No'=>10, 'Rental'=>10 , 'Duration'=>40,'Employee_No'=>10);
        foreach($max_len_fields as $field=>$max_len){
            if(strlen(trim($_POST[$field]))>$max_len){
                $errors[]= $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        if(empty($errors)){
            //No errors found,adding new records
            $C_no=mysqli_real_escape_string($connection,$_POST['Client_No']);
            $P_no=mysqli_real_escape_string($connection,$_POST['Property_No']);
            $Rental=mysqli_real_escape_string($connection,$_POST['Rental']);
            $Duration=mysqli_real_escape_string($connection,$_POST['Duration']);
            $Start_date=mysqli_real_escape_string($connection,$_POST['Start_Date']);
            $Finish_date=mysqli_real_escape_string($connection,$_POST['Finish_Date']);
            $Employee_No=mysqli_real_escape_string($connection,$_POST['Employee_No']);
            $Payment_method=mysqli_real_escape_string($connection,$_POST['Payment_Method']);
         
            $query= "UPDATE lease SET Client_No='{$C_no}',Property_No='{$P_no}',Monthly_Rent='{$Rental}',Duration='{$Duration}',St_Date='{$Start_date}',Finish_Date='{$Finish_date}',Emp_No='{$Employee_No}',Payment_Method='{$Payment_method}'";
            $query.="WHERE Lease_No={$lease_id} LIMIT 1";

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
    <div class="loggedin">Welcome <span class="name"><?php echo $_SESSION['First_Name']; ?>!</span>&emsp; <a href="../logout.php" class="logout">Log out</a> </div>
    </nav>
    <div class="container_1">
    <h2 >Edit Lease&ensp;&ensp;<span ><a href="data.php"><=Back to Lease List</a></span></h2>

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
        <input type="hidden" name="user_id" value="<?php echo $lease_id;?>">
        <div class="one_line"><div class="labels"><label for="E_name" name="Client_No">Client Number:</label></div>
        <div class="inputs"><input type="text" name="Client_No" id="input" placeholder="Client Number" <?php echo 'value="'. $c_no. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_salary" name="Property_No">Property Number:</label></div>
         <div class="inputs"><input type="text" name="Property_No" id="input" placeholder="Property_Number" <?php echo 'value="'. $p_no. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Rental">Monthly Rental:</label></div>
         <div class="inputs"><input type="text" name="Rental" id="input" placeholder="Monthly Rental" <?php echo 'value="'. $rental. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Duration">Duration:</label></div>
         <div class="inputs"><input type="text" name="Duration" id="input" placeholder="Duration" <?php echo 'value="'. $duration. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Start_Date">Rent Start Date:</label></div>
         <div class="inputs"><input type="text" name="Start_Date" id="input" placeholder="Rent Start Date" <?php echo 'value="'. $start_date. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Finish_Date">Rent Finish Date:</label></div>
         <div class="inputs"><input type="text" name="Finish_Date" id="input" placeholder="Rent Finish Date" <?php echo 'value="'. $finish_date. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Employee_No">Added By(Employee No):</label></div>
         <div class="inputs"><input type="text" name="Employee_No" id="input" placeholder="Added By(Employee Number)" <?php echo 'value="'. $e_no. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_salary" name="Payment_Method">Payment Method:</label></div>
         <div class="inputs">
             <select name="Payment_Method">
            <option>Select one</option>
            <option>Cash</option>
            <option>Cheque</option>
            <option>Credit card</option>
           
         </select>
         </div></div>
        <button type="submit" name="submit" onclick=" return confirm('Are You Sure?')">Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
