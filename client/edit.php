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
    $number='';
    $name='';
    $NIC='';
    $email='';
    $password='';
    if(isset($_GET['user_id'])){
        $emp_id=mysqli_real_escape_string($connection,$_GET['user_id']);
        
        $query="SELECT * FROM client WHERE Client_No={$emp_id} LIMIT 1";
        $result_set=mysqli_query($connection,$query);
        
        if($result_set){
            if(mysqli_num_rows($result_set)==1){
                //user found
                $result=mysqli_fetch_assoc($result_set);
                $number=$result['Client_No'];
                $name=$result['Client_Name'];
                $NIC=$result['Client_NIC'];
                $email=$result['Client_Email'];
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
        $number=$_POST['user_id'];
        $name= $_POST['Name'];
        $NIC= $_POST['NIC'];
        $email= $_POST['Email'];
        if($user_id!=$_SESSION['user_ID']){ 
        }else{
            $password= $_POST['Password'];
        }        

         //checking required fields
         $req_fields=array('user_id','Name', 'NIC','Email');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array('Name'=> 100 , 'NIC'=> 20 ,'Email'=> 40  );
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
            
            $query= "UPDATE client SET Client_Name='{$Name}',Client_NIC='{$NIC}',Client_Email='{$Email}',Passwor='{$hashed_password}'";
            $query.="WHERE Client_No={$number} LIMIT 1";

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
        <input type="hidden" name="user_id" value="<?php echo $emp_id;?>">
         <div class="one_line"><div class="labels"><label for="C_name" name="Name">Client Name:</label></div>
         <div class="inputs"><input type="text" name="Name" id="input" placeholder="Full Name" <?php echo 'value="'. $name. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="NIC">NIC Number:</label></div>
         <div class="inputs"><input type="text" name="NIC" id="input" placeholder="NIC Number" <?php echo 'value="'. $NIC. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Email">Email:</label></div>
         <div class="inputs"><input type="email" name="Email" id="input" placeholder="Email Address" <?php echo 'value="'. $email. '"';?>></div></div>
         <?php
            if($user_id==$_SESSION['user_ID']){
               echo '<div class="one_line"><div class="labels"><label for="n_password" name="Password">Present/New Password:</label></div>';
               echo '<div class="inputs"><input type="password" name="Password" id="input" placeholder="Password" ></div></div>';
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
