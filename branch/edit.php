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
    $branch_No='';
    $district='';
    $city='';
    $contact_no='';
    $email='';
    $m_no='';
    if(isset($_GET['user_id'])){
        $branch_No=mysqli_real_escape_string($connection,$_GET['user_id']);
        
        $query="SELECT * FROM branch WHERE Branch_No={$branch_No} LIMIT 1";
        $result_set=mysqli_query($connection,$query);
        
        if($result_set){
            if(mysqli_num_rows($result_set)==1){
                //user found
                $result=mysqli_fetch_assoc($result_set);
                $district=$result['B_District'];
                $city=$result['B_City'];
                $contact_no=$result['B_Contact_No'];
                $email=$result['B_Email'];
                $m_no=$result['Manager_No'];
                
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
        $branch_No=$_POST['user_id'];
        $district= $_POST['District'];
        $city= $_POST['City'];
        $contact_no= $_POST['Contact_No'];
        $email= $_POST['Email'];     
        $m_no= $_POST['M_No'];     

         //checking required fields
         $req_fields=array('user_id', 'District', 'City', 'Contact_No','Email','M_No');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array( 'District'=>40, 'City'=>40, 'Contact_No'=>40,'Email'=>40,'M_No'=>10);
        foreach($max_len_fields as $field=>$max_len){
            if(strlen(trim($_POST[$field]))>$max_len){
                $errors[]= $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        if(empty($errors)){
            //No errors found,adding new records
            $District=mysqli_real_escape_string($connection,$_POST['District']);
            $City=mysqli_real_escape_string($connection,$_POST['City']);
            $Contact_no=mysqli_real_escape_string($connection,$_POST['Contact_No']);
            $Email=mysqli_real_escape_string($connection,$_POST['Email']);
            $M_No=mysqli_real_escape_string($connection,$_POST['M_No']);
            
            $query= "UPDATE branch SET B_District='{$District}',B_City='{$City}',B_Contact_No='{$Contact_no}',B_Email='{$Email}', Manager_No='{$M_No}'";
            $query.="WHERE Branch_No={$branch_No} LIMIT 1";

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
    <h2 >Edit Branch&ensp;&ensp;<span ><a href="data.php"><=Back to Branch List</a></span></h2>

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
        <input type="hidden" name="user_id" value="<?php echo $branch_No;?>">
         <div class="one_line"><div class="labels"><label for="E_name" name="District">Branch District:</label></div>
        <div class="inputs"><input type="text" name="District" id="input" placeholder="Branch District" <?php echo 'value="'. $district. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_salary" name="City">Branch City:</label></div>
         <div class="inputs"><input type="text" name="City" id="input" placeholder="Branch City" <?php echo 'value="'. $city. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Contact_No">Contact Number:</label></div>
         <div class="inputs"><input type="text" name="Contact_No" id="input" placeholder="Contact Number" <?php echo 'value="'. $contact_no. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Email">Email:</label></div>
         <div class="inputs"><input type="email" name="Email" id="input" placeholder="Email Address" <?php echo 'value="'. $email. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="M_No">Manager Number:</label></div>
         <div class="inputs"><input type="text" name="M_No" id="input" placeholder="Manager Number" <?php echo 'value="'. $m_no. '"';?>></div></div>
         
        <button type="submit" name="submit" onclick=" return confirm('Are You Sure?')">Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
