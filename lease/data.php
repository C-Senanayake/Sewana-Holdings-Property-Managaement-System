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
        $query="SELECT * FROM lease WHERE (Lease_No LIKE '%$search%' OR Client_No LIKE '%$search%' OR Emp_No LIKE '%$search%' OR Property_No LIKE '%$search%' OR Payment_Method LIKE '%$search%') ORDER BY Lease_No";
    }else{
       
        $query="SELECT * FROM lease ORDER BY Lease_No";
    }
    
    $leases=mysqli_query($connection,$query);
    if($leases){
        while($lease=mysqli_fetch_assoc($leases)){
            $users_list.="<tr>";
            $users_list.="<td class=\"t_data\">{$lease['Lease_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$lease['Client_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$lease['Property_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$lease['Monthly_Rent']}</td> ";
            $users_list.="<td class=\"t_data\">{$lease['Duration']}</td> ";
            $users_list.="<td class=\"t_data\">{$lease['St_Date']}</td> ";
            $users_list.="<td class=\"t_data\">{$lease['Finish_Date']}</td> ";
            $users_list.="<td class=\"t_data\">{$lease['Payment_Method']}</td> ";
            $users_list.="<td class=\"t_data\">{$lease['Emp_No']}</td> ";
            $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$lease['Lease_No']}\" >Edit</a> </td> ";
            $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$lease['Lease_No']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
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
    <div class="loggedin">Welcome <span class="name"><?php echo $_SESSION['First_Name']; ?>!</span>&emsp; <a href="../logout.php" class="logout">Log out</a> </div>
    </nav>
    <div class="t_container">
    <h2 >Lease Data&ensp;&ensp;<span>
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
            <input type="text" name="search" placeholder="Type Leae_No/Client_No/Property_No/Payment_Method/Duration/Date/Rental & Press Enter" <?php echo $search; ?>autofocus>
        </form>
    </div>
    <div class="table_container">
    <table id="table">
        <tr id="t_row">
            <th id="t_header">Lease Number</th>
            <th id="t_header">Client Number</th>
            <th id="t_header">Property Number</th>
            <th id="t_header">Monthly Rent</th>
            <th id="t_header">Duration</th>
            <th id="t_header">Start Date</th>
            <th id="t_header">Finish Date</th>
            <th id="t_header">Payment Method</th>
            <th id="t_header">Added By(Employee_ID)</th>
            <th id="t_header">Edit</th>
            <th id="t_header">Delete</th></th>
        </tr>
        <?php echo $users_list; ?>
    </table>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
