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
    $contact_No='';
    $email='';
    $address='';
    $type='';

    if(isset($_POST['submit'])){

        $name= $_POST['Name'];
        $contact_No= $_POST['Contact_No'];
        $email=$_POST['Email'];
        $address=$_POST['Address'];
        $type=$_POST['NP_Type'];

         //checking required fields
         $req_fields=array('Name', 'Contact_No' , 'NP_Type');
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
            $Type=mysqli_real_escape_string($connection,$_POST['NP_Type']);
            

            $Daily='Daily';
            $Weekly='Weekly';
            if($Type == $Daily ){

                $query= "INSERT INTO nws_paper(NP_Name,NP_Contact_No,NP_Email,NP_Type)";
                $query.="VALUES ('{$Name}','{$Contact_No}','{$Email}','{$Type}')";
                $result=mysqli_query($connection,$query);

                $Query= "INSERT INTO np_daily(NP_Name,NP_Email)";
                $Query.="VALUES ('{$Name}','{$Email}')";
                $Result=mysqli_query($connection,$Query);

            }else if($Type== $Weekly ){

                $query= "INSERT INTO nws_paper(NP_Name,NP_Contact_No,NP_Address,NP_Type)";
                $query.="VALUES ('{$Name}','{$Contact_No}','{$Address}','{$Type}')";
                $result=mysqli_query($connection,$query);

                $Query= "INSERT INTO np_weekly(NP_Name,NP_Address)";
                $Query.="VALUES ('{$Name}','{$Address}')";
                $Result=mysqli_query($connection,$Query);
            }


            
            if($result && $Result){
                //query successful
                header('Location:data.php?user_added=successful');
            }
            else{
                $errors[]='Failed to add record ';
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
    <h2 >Add New News Paper&ensp;&ensp;<span >
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

         <div class="one_line"><div class="labels"><label for="E_name" name="Name">News Paper Name:</label></div>
         <div class="inputs"><input type="text" name="Name" id="input" placeholder="Full Name" <?php echo 'value="'. $name. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Contact_No">Contact Number:</label></div>
         <div class="inputs"><input type="text" name="Contact_No" id="input" placeholder="Contact Number" <?php echo 'value="'. $contact_No. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Email">Email:</label></div>
         <div class="inputs"><input type="email" name="Email" id="input" placeholder="If NP_Type Daily,then only fill this" <?php echo 'value="'. $email. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="Address">Address:</label></div>
         <div class="inputs"><input type="text" name="Address" id="input" placeholder="If NP_Type is Weakly,then only fill this" <?php echo 'value="'. $address. '"';?>></div></div>
         <div class="one_line"><div class="labels"><label for="E_name" name="NP_Type">News Paper Type:</label></div>
         <div class="inputs">
             <select name="NP_Type">
            <option>Select one</option>
            <option>Daily</option>
            <option>Weekly</option>
         </select>
         </div></div>
         <button type="submit" name="submit" >Save</button>
        </form>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
