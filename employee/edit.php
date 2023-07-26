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
    $emp_id='';
    $users_id='';
    $name='';
    $salary='';
    $gender='';
    $dOB='';
    $contact_No='';
    $nIC='';
    $start_Date='';
    $branch_No='';
    $password='';
    if(isset($_GET['user_id'])){
        $emp_id=mysqli_real_escape_string($connection,$_GET['user_id']);
        
        $query="SELECT * FROM employee WHERE Emp_ID={$emp_id} LIMIT 1";
        $result_set=mysqli_query($connection,$query);

        $Query="SELECT * FROM user WHERE Emp_ID={$emp_id} LIMIT 1";
        $Result_set=mysqli_query($connection,$Query);
        if($Result_set){
            $result=mysqli_fetch_assoc($Result_set);
            $users_id=$result['U_ID'];
        }
        
        if($result_set){
            if(mysqli_num_rows($result_set)==1){
                //user found
                $result=mysqli_fetch_assoc($result_set);
                $name=$result['Emp_Name'];
                $salary=$result['Emp_Salary'];
                $gender=$result['Emp_Gender'];
                $dOB=$result['Emp_DOB'];
                $contact_No=$result['Emp_Contact_Number'];
                $nIC=$result['Emp_NIC'];
                $start_Date=$result['Emp_Start_Date'];
                $branch_No=$result['Emp_Branch_No'];
                $password=$result['Passwor'];
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
        $emp_id=$_POST['user_id'];
        $name= $_POST['Name'];
        $salary= $_POST['Salary'];
        $gender= $_POST['Gender'];
        $dOB= $_POST['DOB'];
        $contact_No= $_POST['Contact_No'];
        $nIC= $_POST['NIC'];
        $start_Date= $_POST['Start_Date'];
        $branch_No= $_POST['Branch_No'];
        if($user_id!=$_SESSION['user_ID']){ 
        }else{
            $password= $_POST['Password'];
        }        

         //checking required fields
         $req_fields=array('user_id','Name', 'Salary', 'Gender', 'DOB','Contact_No','NIC','Start_Date','Branch_No');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array('Name'=> 100 , 'Salary'=> 10 , 'Gender'=> 8 , 'DOB'=> 10 , 'Contact_No'=> 30 ,'NIC'=> 15 ,'Start_Date'=> 10 , 'Branch_No'=> 11 );
        foreach($max_len_fields as $field=>$max_len){
            if(strlen(trim($_POST[$field]))>$max_len){
                $errors[]= $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        if(empty($errors)){
            //No errors found,adding new records
            $Name=mysqli_real_escape_string($connection,$_POST['Name']);
            $Salary=mysqli_real_escape_string($connection,$_POST['Salary']);
            $Gender=mysqli_real_escape_string($connection,$_POST['Gender']);
            $DOB=mysqli_real_escape_string($connection,$_POST['DOB']);
            $Contact_No=mysqli_real_escape_string($connection,$_POST['Contact_No']);
            $NIC=mysqli_real_escape_string($connection,$_POST['NIC']);
            $Start_Date=mysqli_real_escape_string($connection,$_POST['Start_Date']);
            $Branch_No=mysqli_real_escape_string($connection,$_POST['Branch_No']);
            $Password=mysqli_real_escape_string($connection,$_POST['Password']);
            $hashed_password=sha1($Password);
            
            
            $query= "UPDATE employee SET Emp_Name='{$Name}',Emp_Salary='{$Salary}',Emp_Gender='{$Gender}',Emp_DOB='{$DOB}',Emp_Contact_Number='{$Contact_No}',Emp_NIC='{$NIC}',Emp_Start_Date='{$Start_Date}',Emp_Branch_No='{$Branch_No}',Passwor='{$hashed_password}'";
            $query.="WHERE Emp_ID={$emp_id} LIMIT 1";

            $result=mysqli_query($connection,$query);

            $Query= "UPDATE user SET First_Name='{$Name}',passwor='{$hashed_password}'";
            $Query.="WHERE Emp_ID={$emp_id} LIMIT 1";

            $Result=mysqli_query($connection,$Query);
            
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
        <input type="hidden" name="user_id" value="<?php echo $emp_id;?>">
         <div class="one_line"><div class="labels"><label for="E_name" name="Name">Employee Name:</label></div>
        <div class="inputs"><input type="text" name="Name" id="input" placeholder="Full Name" <?php echo 'value="'. $name. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_salary" name="Salary">Salary:</label></div>
         <div class="inputs"><input type="text" name="Salary" id="input" placeholder="Salary" <?php echo 'value="'. $salary. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Gender">Gender:</label></div>
         <div class="inputs"><input type="text" name="Gender" id="input" placeholder="Male/Female/Other" <?php echo 'value="'. $gender. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="DOB">Date Of Birth:</label></div>
         <div class="inputs"><input type="text" name="DOB" id="input" placeholder="Date Of Birth" <?php echo 'value="'. $dOB. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Contact_No">Contact Number:</label></div>
         <div class="inputs"><input type="text" name="Contact_No" id="input" placeholder="Contact Number" <?php echo 'value="'. $contact_No. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="NIC">NIC Number:</label></div>
         <div class="inputs"><input type="text" name="NIC" id="input" placeholder="NIC Number" <?php echo 'value="'. $nIC. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Start_Date">Work Start Date:</label></div>
         <div class="inputs"><input type="text" name="Start_Date" id="input" placeholder="Working Start Date" <?php echo 'value="'. $start_Date. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Branch_No">Working Branch Number:</label></div>
         <div class="inputs"><input type="text" name="Branch_No" id="input" placeholder="Working Branch Number" <?php echo 'value="'. $branch_No. '"';?>></div></div>
         <?php
            if($users_id==$_SESSION['user_ID']){
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
