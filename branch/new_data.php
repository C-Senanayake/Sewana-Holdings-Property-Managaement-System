<?php session_start();?>
<?php
    require_once('../include/connection.php');
?>
<?php
    if(!isset($_SESSION['user_ID'])){
        header('Location:../index.php');
    }
    $errors=array();
    $district='';
    $city='';
    $contact_No='';
    $email='';
    $m_no='';
    if(isset($_POST['submit'])){

        $district= $_POST['District'];
        $city= $_POST['City'];
        $contact_No= $_POST['Contact_No'];
        $email= $_POST['Email'];
        $m_no= $_POST['M_No'];

         //checking required fields
         $req_fields=array( 'District', 'City' ,'Contact_No','Email','M_No');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array( 'District'=>40, 'City'=>40 ,'Contact_No'=>30,'Email'=>40, 'M_No'=>10);
        foreach($max_len_fields as $field=>$max_len){
            if(strlen(trim($_POST[$field]))>$max_len){
                $errors[]= $field . ' must be less than ' . $max_len . ' characters';
            }
        }
        if(empty($errors)){
            //No errors found,adding new records
            $District=mysqli_real_escape_string($connection,$_POST['District']);
            $City=mysqli_real_escape_string($connection,$_POST['City']);
            $Contact_No=mysqli_real_escape_string($connection,$_POST['Contact_No']);
            $Email=mysqli_real_escape_string($connection,$_POST['Email']);
            $M_No=mysqli_real_escape_string($connection,$_POST['M_No']);
            
            $query= "INSERT INTO branch(B_District,B_City,B_Contact_No,B_Email,Manager_No)";
            $query.="VALUES ('{$District}','{$City}','{$Contact_No}','{$Email}','{$M_No}')";

            $result=mysqli_query($connection,$query);
            if($result){
                //query successful
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
    <h2 >Add New Branch&ensp;&ensp;<span >
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
       
         <div class="one_line"><div class="labels"><label for="E_salary" name="District">District:</label></div>
         <div class="inputs"><input type="text" name="District" id="input" placeholder="District" <?php echo 'value="'. $district. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="City">City:</label></div>
         <div class="inputs"><input type="text" name="City" id="input" placeholder="City" <?php echo 'value="'. $city. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Contact_No">Contact Number:</label></div>
         <div class="inputs"><input type="text" name="Contact_No" id="input" placeholder="Contact Number" <?php echo 'value="'. $contact_No. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Email">Email:</label></div>
         <div class="inputs"><input type="email" name="Email" id="input" placeholder="Email Address" <?php echo 'value="'. $email. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="M_No">Manager Number:</label></div>
         <div class="inputs"><input type="text" name="M_No" id="input" placeholder="Manager Number" <?php echo 'value="'. $m_no. '"';?>></div></div>
         <button type="submit" name="submit" >Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
