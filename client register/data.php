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
        $query="SELECT * FROM client_register WHERE (Client_ID LIKE '%$search%' OR Staff_Name LIKE '%$search%' OR Client_Name LIKE '%$search%' OR Branch_No LIKE '%$search%' OR R_Date LIKE '%$search%') ORDER BY Client_Name";
    }else{
       
        $query="SELECT * FROM client_register ORDER BY Client_Name";
    }
    
    $clients=mysqli_query($connection,$query);
    if($clients){
        while($client=mysqli_fetch_assoc($clients)){
            $users_list.="<tr>";
            $users_list.="<td class=\"t_data\">{$client['Client_ID']}</td> ";
            $users_list.="<td class=\"t_data\">{$client['Client_Name']}</td> ";
            $users_list.="<td class=\"t_data\">{$client['Branch_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$client['Staff_Name']}</td> ";
            $users_list.="<td class=\"t_data\">{$client['R_Date']}</td> ";
            if(($_SESSION['user_ID']<400000) ){
                $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$client['Client_ID']}\" >Edit</a> </td> ";
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
    <div class="loggedin">Welcome <span class="name"><?php echo $_SESSION['First_Name']; ?>!</span>&emsp; <a href="../logout.php" class="logout">Log out</a> </div>
    </nav>
    <div class="t_container">
    <h2 >Clients Registration Details&ensp;&ensp;<span>

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
            <input type="text" name="search" placeholder="Type Name/Number/Branch_No/Registered_Date & Press Enter" <?php echo $search; ?>autofocus>
        </form>
    </div>
    <div class="table_container">
    <table id="table">
        <tr id="t_row">
            <th id="t_header">Client Number</th>
            <th id="t_header">Client Name</th>
            <th id="t_header">Branch Number</th>
            <th id="t_header">Registered By</th>
            <th id="t_header">Registered Date</th>
            <?php 
            if(($_SESSION['user_ID']<400000) ){
                echo "<th id=\"t_header\">Edit</th>";
            } 
            ?>
            </tr>
        <?php echo $users_list; ?>
    </table>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
