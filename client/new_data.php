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
    $nIC='';
    $email='';
    $password='';
    $branch_no='';
    $staff_name='';
    $r_date='';

    if(isset($_POST['submit'])){

        $name= $_POST['Name'];
        $nIC= $_POST['NIC'];
        $email= $_POST['Email'];
        $password= $_POST['Password'];
        $branch_no= $_POST['Branch_no'];
        $staff_name= $_POST['Staff_name'];
        $r_date= $_POST['R_date'];

         //checking required fields
         $req_fields=array('Name', 'NIC','Email','Password','Branch_no','Staff_name','R_date');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array('Name'=> 100 ,'NIC'=> 20 ,'Email'=> 40 , 'Password'=> 20 ,'Branch_no'=>10,'Staff_name'=>100);
        foreach($max_len_fields as $field=>$max_len){
            if(strlen(trim($_POST[$field]))>$max_len){
                $errors[]= $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        if(empty($errors)){
            //No errors found,adding new records
            $Name=mysqli_real_escape_string($connection,$_POST['Name']);
            $NIC=mysqli_real_escape_string($connection,$_POST['NIC']);
            $Email=mysqli_real_escape_string($connection,$_POST['Email']);
            $Password=mysqli_real_escape_string($connection,$_POST['Password']);
            $hashed_password=sha1($Password);
            $Branch_no=mysqli_real_escape_string($connection,$_POST['Branch_no']);
            $Staff_name=mysqli_real_escape_string($connection,$_POST['Staff_name']);
            $R_date=mysqli_real_escape_string($connection,$_POST['R_date']);
            
            
            $query= "INSERT INTO client(Client_Name,Client_NIC,Client_Email,Passwor)";
            $query.="VALUES ('{$Name}','{$NIC}','{$Email}','{$hashed_password}')";

            $result=mysqli_query($connection,$query);


            if($result){
                //query successful
                $user_id=mysqli_insert_id($connection);
                // $query= "INSERT INTO user(U_ID,First_Name,passwor)";
                // $query.="VALUES ('{$user_id}','{$Name}','{$hashed_password}')";

                // $result=mysqli_query($connection,$query);

                $Query= "INSERT INTO client_register(Client_ID,Client_Name,Branch_No,Staff_Name,R_Date)";
                $Query.="VALUES ('{$user_id}','{$Name}','{$Branch_no}','{$Staff_name}','{$R_date}')";
                $Result=mysqli_query($connection,$Query);
                if($result && $Result){
                    header('Location:data.php?user_added=successful');

                }else{
                    $errors[]='Failed to add record to user to client_register';

                }

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
    <h2 >Add New Client&ensp;&ensp;<span >
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
       
         <div class="one_line"><div class="labels"><label for="E_name" name="Name">Client Name:</label></div>
        <div class="inputs"><input type="text" name="Name" id="input" placeholder="Full Name" <?php echo 'value="'. $name. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="NIC">NIC Number:</label></div>
         <div class="inputs"><input type="text" name="NIC" id="input" placeholder="NIC Number" <?php echo 'value="'. $nIC. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Email">Email:</label></div>
         <div class="inputs"><input type="email" name="Email" id="input" placeholder="Email Address" <?php echo 'value="'. $email. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Branch_no">Branch Number:</label></div>
        <div class="inputs"><input type="text" name="Branch_no" id="input" placeholder="Branch Number>199" <?php echo 'value="'. $branch_no. '"';?>></div></div>
        <div class="one_line"><div class="labels"><label for="E_name" name="Staff_name">Registered By:</label></div>
        <div class="inputs"><input type="text" name="Staff_name" id="input" placeholder="Staff Member Name" <?php echo 'value="'. $staff_name. '"';?>></div></div>
        <div class="one_line"><div class="labels"><label for="E_name" name="R_date">Registered_Date:</label></div>
        <div class="inputs"><input type="text" name="R_date" id="input" placeholder="yyyy-mm-dd" <?php echo 'value="'. $r_date. '"';?>></div></div>
         
         <div class="one_line"><div class="labels"><label for="n_password" name="Password">New Password:</label></div>
         <div class="inputs"><input type="password" name="Password" id="input" placeholder="Password"></div></div>
        <button type="submit" name="submit" >Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
