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
    $np_name='';
    $name='';
    $contact_No='';
    $email='';
    $address='';
    $type='';
    if(isset($_GET['user_id'])){
        $np_name=mysqli_real_escape_string($connection,$_GET['user_id']);
        
        $query="SELECT * FROM nws_paper WHERE NP_Name='{$np_name}' LIMIT 1";
        $result_set=mysqli_query($connection,$query);
        
        if($result_set){
            if(mysqli_num_rows($result_set)==1){
                //user found
                $result=mysqli_fetch_assoc($result_set);
                $name=$result['NP_Name'];
                $contact_No=$result['NP_Contact_No'];
                $email=$result['NP_Email'];
                $address=$result['NP_Address'];
                $type=$result['NP_Type'];
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
        $np_name=$_POST['user_id'];
        $name= $_POST['Name'];
        $contact_No= $_POST['Contact_No'];  
        $email=$_POST['Email'];
        $address=$_POST['Address'];
        

         //checking required fields
         $req_fields=array('user_id','Name', 'Contact_No' );
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array('Name'=> 100 ,  'Contact_No'=> 40 ,'Email'=>40 ,'Address'=>100);
        foreach($max_len_fields as $field=>$max_len){
            if(strlen(trim($_POST[$field]))>$max_len){
                $errors[]= $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        if(empty($errors)){
            //No errors found,adding new records
            $Name=mysqli_real_escape_string($connection,$_POST['Name']);
            $Contact_No=mysqli_real_escape_string($connection,$_POST['Contact_No']);
            $Email=mysqli_real_escape_string($connection,$_POST['Email']);
            $Address=mysqli_real_escape_string($connection,$_POST['Address']);
           
            
            $query= "UPDATE nws_paper SET NP_Name='{$Name}',NP_Contact_No='{$Contact_No}',NP_Email='{$Email}',NP_Address='{$Address}'";
            $query.="WHERE NP_Name='{$np_name}' LIMIT 1";

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
    <h2 >Edit News Paper&ensp;&ensp;<span ><a href="data.php"><=Back to News Paper List</a></span></h2>

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
        <input type="hidden" name="user_id" value="<?php echo $np_name;?>">
        <div class="one_line"><div class="labels"><label for="E_name" name="Name">News Paper Name:</label></div>
         <div class="inputs"><input type="text" name="Name" id="input" placeholder="Full Name" <?php echo 'value="'. $name. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Contact_No">Contact Number:</label></div>
         <div class="inputs"><input type="text" name="Contact_No" id="input" placeholder="Contact Number" <?php echo 'value="'. $contact_No. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Email">Email:</label></div>
         <div class="inputs"><input type="email" name="Email" id="input" placeholder="If NP_Type Daily,then only fill this" <?php echo 'value="'. $email. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Address">Address:</label></div>
         <div class="inputs"><input type="text" name="Address" id="input" placeholder="If NP_Type is Weakly,then only fill this" <?php echo 'value="'. $address. '"';?>></div></div>
         <button type="submit" name="submit" onclick=" return confirm('Are You Sure?')">Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
