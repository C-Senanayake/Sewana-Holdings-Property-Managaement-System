<?php session_start();?>
<?php
    require_once('../include/connection.php');
?>
<?php
    if(!isset($_SESSION['user_ID'] )){
        if(!isset($_SESSION['NIC'])){
            header('Location:../index.php');
        }
    }

    $users_list='';
    $search='';
     //getting the user list
    if(isset($_GET['search'])){
        $search=mysqli_real_escape_string($connection,$_GET['search']);
        $query="SELECT * FROM property WHERE (C_P_ID LIKE '%$search%' ORProperty_No LIKE '%$search%' OR Property_Type LIKE '%$search%' OR No_Of_Rooms LIKE '%$search%' OR Property_Rental LIKE '%$search%' OR Property_Address LIKE '%$search%' OR Branch_No LIKE '%$search%') ORDER BY Property_No";
    }else{
       
        $query="SELECT * FROM property ORDER BY Property_No";
    }
    
    $properties=mysqli_query($connection,$query);
    if($properties){
        while($property=mysqli_fetch_assoc($properties)){
            $users_list.="<tr>";
            $users_list.="<td class=\"t_data\">{$property['Property_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$property['Property_Address']}</td> ";
            $users_list.="<td class=\"t_data\">{$property['Property_Type']}</td> ";
            $users_list.="<td class=\"t_data\">{$property['Property_Rental']}</td> ";
            $users_list.="<td class=\"t_data\">{$property['No_Of_Rooms']}</td> ";
            $users_list.="<td class=\"t_data\">{$property['Branch_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$property['Owner_ID']}</td> ";
            $users_list.="<td class=\"t_data\">{$property['C_P_ID']}</td> ";
            if(($_SESSION['user_ID']<400000) ){
                $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$property['Property_No']}\" >Edit</a> </td> ";
                $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$property['Property_No']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
                }
            else{
                if($_SESSION['user_ID']==$property['C_P_ID']){
                    $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$property['Property_No']}\" >Edit</a> </td> ";
                    $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$property['Property_No']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
            }
            }
            
            // $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$property['Property_No']}\" >Edit</a> </td> ";
            // $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$property['Property_No']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
            $users_list.="</tr>";
        }
    }else{
        echo "Database query failed";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="stylesheet" href="../css/user.css">
</head>
<body>
    <nav id="navigation">
    <div class="main_name">Sewana Holdings</div>
    <div class="loggedin">Welcome <?php echo $_SESSION['First_Name']; ?>!&emsp; <a href="../logout.php" class="logout">Log out</a> </div>
    </nav>
    <div class="t_container">
    <h2 >Properties&ensp;&ensp;<span>
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
    <div class="search">
        <form action="data.php" method="GET">
            <input type="text" name="search" placeholder="Type No/NO_Of_Rooms/Rental?Type & Press Enter" <?php echo $search; ?>autofocus>
        </form>
    </div>
    <div class="table_container">
    <table id="table">
        <tr id="t_row">
            <th id="t_header">Property No</th>
            <th id="t_header">Property Address</th>
            <th id="t_header">Property Type</th>
            <th id="t_header">Property Rental</th>
            <th id="t_header">No Of Rooms</th>
            <th id="t_header">Branch No</th>
            <th id="t_header">Owner ID</th>
            <th id="t_header">Company/Person ID</th>
            <?php 
            //  if(($_SESSION['user_ID']<500000) || ($_SESSION['user_ID']>1500000)){
                echo "<th id=\"t_header\">Edit</th>";
                echo "<th id=\"t_header\">Delete</th></th>";
            //  }
            ?></tr>
        <?php echo $users_list; ?>
    </table>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
