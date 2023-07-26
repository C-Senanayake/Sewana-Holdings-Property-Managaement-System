<?php session_start();?>
<?php
    require_once('../include/connection.php');
?>
<?php
    if(!isset($_SESSION['user_ID'])){
        header('Location:../index.php');
    }
    $users_list='';
    $search='';
     //getting the user list
    if(isset($_GET['search'])){
        $search=mysqli_real_escape_string($connection,$_GET['search']);
        $query="SELECT * FROM property_requirement WHERE (Requirement_No LIKE '%$search%' OR Client_No LIKE '%$search%' OR Required_Type LIKE '%$search%' OR Max_Rent LIKE '%$search%' OR Area LIKE '%$search%' OR Date_Willing_To_Rent LIKE '%$search%') ORDER BY Requirement_No";
    }else{
       
        $query="SELECT * FROM property_requirement ORDER BY Requirement_No";
    }
    
    $requirements=mysqli_query($connection,$query);
    if($requirements){
        while($requirement=mysqli_fetch_assoc($requirements)){
            $users_list.="<tr>";
            $users_list.="<td class=\"t_data\">{$requirement['Requirement_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$requirement['Client_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$requirement['Required_Type']}</td> ";
            $users_list.="<td class=\"t_data\">{$requirement['Area']}</td> ";
            $users_list.="<td class=\"t_data\">{$requirement['Max_Rent']}</td> ";
            $users_list.="<td class=\"t_data\">{$requirement['Date_Willing_To_Rent']}</td> ";
            if(($_SESSION['user_ID']<400000) ){
                $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$requirement['Requirement_No']}\" >Edit</a> </td> ";
                $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$requirement['Requirement_No']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
            }
            else{
                if($_SESSION['user_ID']==$requirement['Client_No']){
                    $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$requirement['Requirement_No']}\" >Edit</a> </td> ";
                    $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$requirement['Requirement_No']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
            
                }
                
            }
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
    <h2 >Property Requirements&ensp;&ensp;<span>
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
            <input type="text" name="search" placeholder="Type No/NO_Of_Rooms/Rental/Type & Press Enter" <?php echo $search; ?>autofocus>
        </form>
    </div>
    <div class="table_container">
    <table id="table">
        <tr id="t_row">
            <th id="t_header">Requirement Number</th>
            <th id="t_header">Client Number</th>
            <th id="t_header">Required Type</th>
            <th id="t_header">Area</th>
            <th id="t_header">Max Rental</th>
            <th id="t_header">Date Willing To Rent</th>
            <?php 
            if(($_SESSION['user_ID']<500000) ){
                echo "<th id=\"t_header\">Edit</th>";
                echo "<th id=\"t_header\">Delete</th>";
            } 
            ?>
            <!-- <th id="t_header">Edit</th>
            <th id="t_header">Delete</th></th> -->
        </tr>
        <?php echo $users_list; ?>
    </table>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
