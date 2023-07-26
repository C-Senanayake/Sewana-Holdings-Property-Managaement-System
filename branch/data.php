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
        $query="SELECT * FROM branch WHERE (Branch_No LIKE '%$search%' OR B_City LIKE '%$search%' OR B_District LIKE '%$search%' OR Manager_No LIKE '%$search%' ) ORDER BY Branch_No";
    }else{
       
        $query="SELECT * FROM branch ORDER BY Branch_No";
    }
    
    $branches=mysqli_query($connection,$query);
    if($branches){
        while($branch=mysqli_fetch_assoc($branches)){
            $users_list.="<tr>";
            $users_list.="<td class=\"t_data\">{$branch['Branch_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$branch['B_District']}</td> ";
            $users_list.="<td class=\"t_data\">{$branch['B_City']}</td> ";
            $users_list.="<td class=\"t_data\">{$branch['B_Contact_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$branch['B_Email']}</td> ";
            $users_list.="<td class=\"t_data\">{$branch['Manager_No']}</td> ";
            if(($_SESSION['user_ID']<100) ){
                $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$branch['Branch_No']}\" >Edit</a> </td> ";
                $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$branch['Branch_No']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
            }
            
            // $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$branch['Branch_No']}\" >Edit</a> </td> ";
            // $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$branch['Branch_No']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
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
    <h2 >Branches&ensp;&ensp;<span>
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
            <input type="text" name="search" placeholder="Type No/District/City/Email/Manager no/Contact_no & Press Enter" <?php echo $search; ?>autofocus>
        </form>
    </div>
    <div class="table_container">
    <table id="table">
        <tr id="t_row">
            <th id="t_header">Branch_No</th>
            <th id="t_header">Branch_District</th>
            <th id="t_header">Branch_City</th>
            <th id="t_header">Branch_Contact_No</th>
            <th id="t_header">Branch_Email</th>
            <th id="t_header">Manager_No</th>
            <?php
            if(($_SESSION['user_ID']<100) ){
               echo "<th id=\"t_header\">Edit</th>";
               echo " <th id=\"t_header\">Delete</th></th>";
            }
            ?></tr>
        <?php echo $users_list; ?>
    </table>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
