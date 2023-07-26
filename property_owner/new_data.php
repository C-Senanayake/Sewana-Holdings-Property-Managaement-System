<?php session_start();?>
<?php
    require_once('../include/connection.php');
?>
<?php
    if(!isset($_SESSION['user_ID'])){
        header('Location:../index.php');
    }
    $errors=array();
    $type='';
    $client_id='';
    $branch_no='';

    if(isset($_POST['submit'])){

        $type= $_POST['Type'];
        $branch_no= $_POST['Branch_No'];

        if(empty($errors)){
            //No errors found,adding new records
            $Type=mysqli_real_escape_string($connection,$_POST['Type']);
            $Branch_No=mysqli_real_escape_string($connection,$_POST['Branch_No']);
            
            $query= "INSERT INTO property_owner(Owner_Type)";
            $query.="VALUES ('{$Type}')";

            $results=mysqli_query($connection,$query);

            if($results){
                         
            $users_list=mysqli_insert_id($connection);

            $query= "INSERT INTO property_owner_register(Owner_ID,Branch_No)";
            $query.="VALUES ('{$users_list}','{$Branch_No}')";

            $results=mysqli_query($connection,$query);
                
                if($Type=='Person'){
                    header("Location:../person/new_data.php?user_id={$users_list} ");
                }else if($Type=='Company'){
                    header("Location:../company/new_data.php?user_id={$users_list}");
                }
            }else{
                echo "Failed to add record";
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
    <title>Poperty owner</title>
    <link rel="stylesheet" href="../css/newuser.css">
</head>
<body>
    <nav id="navigation">
    <div class="main_name">Sewana Holdings</div>
    <div class="loggedin">Welcome <?php echo $_SESSION['First_Name']; ?>!&emsp; <a href="../logout.php" class="logout">Log out</a> </div>
    </nav>
    <div class="container_1">
    <h2 >Add New Owner&ensp;&ensp;<span >
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
       
        <div class="one_line"><div class="labels"><label for="E_name" name="Type">Owner Type:</label></div>
         <div class="inputs">
             <select name="Type">
            <option>Select one</option>
            <option>Person</option>
            <option>Company</option>
         </select>
         </div></div>
         <div class="one_line"><div class="labels"><label for="C_name" name="Branch_No">Branch Number:</label></div>
         <div class="inputs"><input type="text" name="Branch_No" id="input" placeholder="Branch Number>199" <?php echo 'value="'. $branch_no. '"';?>></div></div>

        <button type="submit" name="submit" >Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
