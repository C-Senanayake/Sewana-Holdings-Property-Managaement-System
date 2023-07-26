<?php session_start();?>
<?php
    require_once('../include/connection.php');
?>
<?php
    if(!isset($_SESSION['user_ID'])){
        header('Location:../index.php');
    }
    $errors=array();
    $property_Address='';
    $property_Type='';
    $property_Rental='';
    $no_Of_Rooms='';
    $branch_No='';
    $owner_ID='';
    $cp_ID='';


    if(isset($_POST['submit'])){

        $property_Address= $_POST['Property_Address'];
        $property_Type= $_POST['Property_Type'];
        $property_Rental= $_POST['Property_Rental'];
        $no_Of_Rooms= $_POST['No_Of_Rooms'];
        $branch_No= $_POST['Branch_No'];
        $owner_ID= $_POST['Owner_ID'];
        $cp_ID= $_POST['CP_ID'];
        

         //checking required fields
         $req_fields=array('Property_Address', 'Property_Type', 'Property_Rental', 'No_Of_Rooms','Branch_No','Owner_ID','CP_ID');
         foreach($req_fields as $field){
             if(empty(trim($_POST[$field]))){
                 $errors[]= $field . ' is required';
             }
         }
         //checking nax lengths
        $max_len_fields=array('Property_Address'=>100,'Property_Rental'=>10, 'No_Of_Rooms'=>10,'Branch_No'=>10,'Owner_ID'=>10,'CP_ID'=>10);
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
            $CP_ID=mysqli_real_escape_string($connection,$_POST['CP_ID']);
            
            $query= "INSERT INTO property(Property_Address,Property_Type,Property_Rental,No_Of_Rooms,Branch_No,Owner_ID,C_P_ID)";
            $query.="VALUES ('{$Property_Address}','{$Property_Type}','{$Property_Rental}','{$No_Of_Rooms}','{$Branch_No}','{$Owner_ID}','{$CP_ID}')";

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
    <h2 >Add New Property&ensp;&ensp;<span >
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
         <div class="one_line"><div class="labels"><label for="E_name" name="CP_ID">Company/Person ID:</label></div>
         <div class="inputs"><input type="text" name="CP_ID" id="input" placeholder="Company/Person ID" <?php echo 'value="'. $cp_ID. '"';?>></div></div>
        
         <button type="submit" name="submit" >Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
