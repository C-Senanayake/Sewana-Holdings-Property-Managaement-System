<?php session_start();?>
<?php
    require_once('../include/connection.php');
?>
<?php
    if(!isset($_SESSION['user_ID'])){
        header('Location:../index.php');
    }
    $errors=array();
    $name='';
    $salary='';
    $gender='';
    $dOB='';
    $contact_No='';
    $nIC='';
    $start_Date='';
    $branch_No='';
    $password='';

    if(isset($_POST['submit'])){

        $name= $_POST['Name'];
        $salary= $_POST['Salary'];
        $gender= $_POST['Gender'];
        $dOB= $_POST['DOB'];
        $contact_No= $_POST['Contact_No'];
        $nIC= $_POST['NIC'];
        $start_Date= $_POST['Start_Date'];
        $branch_No= $_POST['Branch_No'];
        $password= $_POST['Password'];

         //checking required fields
         $req_fields=array('Name', 'Salary', 'Gender', 'DOB','Contact_No','NIC','Start_Date','Branch_No','Password');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array('Name'=> 100 , 'Salary'=> 10 , 'Gender'=> 8 , 'DOB'=> 10 , 'Contact_No'=> 30 ,'NIC'=> 15 ,'Start_Date'=> 10 , 'Branch_No'=> 11 , 'Password'=> 20);
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
            
            $query= "INSERT INTO employee(Emp_Name,Emp_Salary,Emp_Gender,Emp_DOB,Emp_Contact_Number,Emp_NIC,Emp_Start_Date,Emp_Branch_No,Passwor,Employee_SEctioned)";
            $query.="VALUES ('{$Name}','{$Salary}','{$Gender}','{$DOB}','{$Contact_No}','{$NIC}','{$Start_Date}','{$Branch_No}','{$hashed_password}','0')";

            $result=mysqli_query($connection,$query);
            if($result){
                //query successful
                $user_id=mysqli_insert_id($connection);
                $users_id=mysqli_insert_id($connection);
                $query= "INSERT INTO user(U_ID,First_Name,Emp_ID,passwor)";
                $query.="VALUES ('{$user_id}','{$Name}','{$users_id}','{$hashed_password}')";

                $result=mysqli_query($connection,$query);
                
                header('Location:data.php?user_added=successful');
            }
            else{
                $errors[]='Failed to add record';
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
    <h2 >Add New Employee&ensp;&ensp;<span >
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
       
         <div class="one_line"><div class="labels"><label for="E_name" name="Name">Employee Name:</label></div>
        <div class="inputs"><input type="text" name="Name" id="input" placeholder="Full Name" <?php echo 'value="'. $name. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_salary" name="Salary">Salary:</label></div>
         <div class="inputs"><input type="text" name="Salary" id="input" placeholder="Salary" <?php echo 'value="'. $salary. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Gender">Gender:</label></div>
         <div class="inputs"><input type="text" name="Gender" id="input" placeholder="Male/Female/Other" <?php echo 'value="'. $gender. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="DOB">Date Of Birth:</label></div>
         <div class="inputs"><input type="text" name="DOB" id="input" placeholder="yyyy-mm-dd" <?php echo 'value="'. $dOB. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Contact_No">Contact Number:</label></div>
         <div class="inputs"><input type="text" name="Contact_No" id="input" placeholder="Contact Number" <?php echo 'value="'. $contact_No. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="NIC">NIC Number:</label></div>
         <div class="inputs"><input type="text" name="NIC" id="input" placeholder="NIC Number" <?php echo 'value="'. $nIC. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Start_Date">Work Start Date:</label></div>
         <div class="inputs"><input type="text" name="Start_Date" id="input" placeholder="Working Start Date" <?php echo 'value="'. $start_Date. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Branch_No">Working Branch Number:</label></div>
         <div class="inputs"><input type="text" name="Branch_No" id="input" placeholder="Working Branch Number" <?php echo 'value="'. $branch_No. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="n_password" name="Password">New Password:</label></div>
         <div class="inputs"><input type="password" name="Password" id="input" placeholder="Password"></div></div>
        <button type="submit" name="submit" >Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
