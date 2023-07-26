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
        $query="SELECT * FROM employee WHERE (Emp_Name LIKE '%$search%' OR Emp_ID LIKE '%$search%' OR Emp_NIC LIKE '%$search%') ORDER BY Emp_Name";
    }else{
       
        $query="SELECT * FROM employee ORDER BY Emp_Name";
    }
    
    $employees=mysqli_query($connection,$query);
    if($employees){
        while($employee=mysqli_fetch_assoc($employees)){
            $users_list.="<tr>";
            $users_list.="<td class=\"t_data\">{$employee['Emp_ID']}</td> ";
            $users_list.="<td class=\"t_data\">{$employee['Emp_Name']}</td> ";
            $users_list.="<td class=\"t_data\">{$employee['Emp_Salary']}</td> ";
            $users_list.="<td class=\"t_data\">{$employee['Emp_Gender']}</td> ";
            $users_list.="<td class=\"t_data\">{$employee['Emp_DOB']}</td> ";
            $users_list.="<td class=\"t_data\">{$employee['Emp_Contact_Number']}</td> ";
            $users_list.="<td class=\"t_data\">{$employee['Emp_NIC']}</td> ";
            $users_list.="<td class=\"t_data\">{$employee['Emp_Start_Date']}</td> ";
            $users_list.="<td class=\"t_data\">{$employee['Emp_Branch_No']}</td> ";
            
            if(($_SESSION['user_ID']<300000) ){
                $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$employee['Emp_ID']}\" >Edit</a> </td> ";
                $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$employee['Emp_ID']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
            }
            
            // $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$employee['Emp_ID']}\" >Edit</a> </td> ";
            // $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$employee['Emp_ID']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
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
    <h2 >Employees&ensp;&ensp;<span>
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
            <input type="text" name="search" placeholder="Type Name/ID//Gender/DOB/Branch_no/Start_Date/NIC & Press Enter" <?php echo $search; ?>autofocus>
        </form>
    </div>
    <div class="table_container">
    <table id="table">
        <tr id="t_row">
            <th id="t_header">Emp_ID</th>
            <th id="t_header">Emp_Name</th>
            <th id="t_header">Emp_Salary</th>
            <th id="t_header">Emp_Gender</th>
            <th id="t_header">Emp_DOB</th>
            <th id="t_header">Emp_Contact_Number</th>
            <th id="t_header">Emp_NIC</th>
            <th id="t_header">Emp_Start_Date</th>
            <th id="t_header">Emp_Branch_No</th>
            <?php
            if(($_SESSION['user_ID']<300000) ){
               echo "<th id=\"t_header\">Edit</th>";
               echo " <th id=\"t_header\">Delete</th></th>";
            }
            ?>
        </tr>
        <?php echo $users_list; ?>
    </table>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
