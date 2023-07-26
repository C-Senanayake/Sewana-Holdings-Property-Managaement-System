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
        $query="SELECT * FROM company WHERE (Company_ID LIKE '%$search%' OR Company_Name LIKE '%$search%' OR Owner_ID LIKE '%$search%' OR Company_Address LIKE '%$search%' OR Company_Email LIKE '%$search%' OR Company_Contact_No LIKE '%$search%') ORDER BY Company_Name";
    }else{
       
        $query="SELECT * FROM company ORDER BY Company_Name";
    }
    
    $companies=mysqli_query($connection,$query);
    if($companies){
        while($company=mysqli_fetch_assoc($companies)){
            $users_list.="<tr>";
            $users_list.="<td class=\"t_data\">{$company['Company_ID']}</td> ";
            $users_list.="<td class=\"t_data\">{$company['Company_Name']}</td> ";
            $users_list.="<td class=\"t_data\">{$company['Company_Contact_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$company['Company_Email']}</td> ";
            $users_list.="<td class=\"t_data\">{$company['Company_Address']}</td> ";
            $users_list.="<td class=\"t_data\">{$company['Owner_ID']}</td> ";
            
            if(($_SESSION['user_ID']<500000) ){
                $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$company['Company_ID']}\" >Edit</a> </td> ";
                $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$company['Company_ID']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
            }
            else{
                if($_SESSION['user_ID']==$company['Company_ID']){
                    $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$company['Company_ID']}\" >Edit</a> </td> ";
                    $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$company['Company_ID']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
            
                }
            }
            // $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$company['Company_ID']}\" >Edit</a> </td> ";
            // $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$company['Company_ID']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
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
    <h2 >Person&ensp;&ensp;<span>
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
            <input type="text" name="search" placeholder="Type Company Name/Company ID/Owner ID/Company Address & Press Enter" <?php echo $search; ?>autofocus>
        </form>
    </div>
    <div class="table_container">
    <table id="table">
        <tr id="t_row">
            <th id="t_header">Comapany_ID</th>
            <th id="t_header">Comapany_Name</th>
            <th id="t_header">Comapany_Contact_No</th>
            <th id="t_header">Comapany_Email</th>
            <th id="t_header">Comapany_Address</th>
            <th id="t_header">Owner_ID</th>
            <?php 
            if(($_SESSION['user_ID']<600000) ){
                echo "<th id=\"t_header\">Edit</th>";
                echo "<th id=\"t_header\">Delete</th>";
            } 
            ?>
            
        </tr>
        <?php echo $users_list; ?>
    </table>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
