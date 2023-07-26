<?php session_start();?>
<?php
    require_once('../include/connection.php');
?>
<?php
    $user_id='';
    if(!isset($_SESSION['user_ID'])){
        header('Location:../index.php');
    }
    $errors=array();
    $client_no='';
    $required_Type='';
    $max_Rental='';
    $area='';
    $w_date='';
    
    $user_id=mysqli_real_escape_string($connection,$_GET['user_id']);
    
    if(isset($_POST['submit'])){
        $user_id=$_POST['user_id'];
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
             
            $query= "INSERT INTO property_requirement(Client_no, Required_Type, Area,Max_Rent, Date_Willing_To_Rent)";
            $query.="VALUES ('{$Client_no}','{$Required_Type}','{$Area}','{$Max_Rental}','{$W_date}')";

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
    <div class="loggedin">Welcome <?php echo $_SESSION['First_Name']; ?>!&emsp; <a href="../logout.php" class="logout">Log out</a> </div>
    </nav>
    <div class="container_1">
    <h2 >Add New Property Requirement&ensp;&ensp;<span >
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
    <input type="hidden" name="user_id" value="<?php echo $user_id;?>">
    <?php 
        if(($_SESSION['user_ID']<400000) || ($_SESSION['user_ID']>499999)){
            echo "<div class=\"one_line\"><div class=\"labels\"><label for=\"E_name\" name=\"Client_no\">Client Number:</label></div>";
            echo "<div class=\"inputs\"><input type=\"text\" name=\"Client_no\" id=\"input\" placeholder=\"Client Number\" value=\"$client_no \"></div></div>";
             
        }else{
            echo "<div class=\"one_line\"><div class=\"labels\"><label for=\"E_name\" name=\"Client_no\">Client Number:</label></div>";
            echo "<div class=\"inputs\"><input type=\"text\" name=\"Client_no\" id=\"input\" placeholder=\"Client Number\"  value=\"$user_id \" ></div></div>";
            
        }

    ?>
         <div class="one_line"><div class="labels"><label for="E_name" name="Client_no">Client Number:</label></div>
        <!-- <div class="inputs"><input type="text" name="Client_no" id="input" placeholder="Client_no" <?php echo 'value="'. $client_no. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_salary" name="Required_Type">Required Type:</label></div> -->
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
         <button type="submit" name="submit" >Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
