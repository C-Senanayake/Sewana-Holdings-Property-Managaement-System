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
        $query="SELECT * FROM client WHERE (Client_No LIKE '%$search%' OR Client_Name LIKE '%$search%' OR Client_NIC LIKE '%$search%') ORDER BY Client_Name";
    }else{
       
        $query="SELECT * FROM client ORDER BY Client_Name";
    }
    
    $clients=mysqli_query($connection,$query);
    if($clients){
        while($client=mysqli_fetch_assoc($clients)){
            $users_list.="<tr>";
            $users_list.="<td class=\"t_data\">{$client['Client_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$client['Client_Name']}</td> ";
            $users_list.="<td class=\"t_data\">{$client['Client_NIC']}</td> ";
            $users_list.="<td class=\"t_data\">{$client['Client_Email']}</td> ";
            if(($_SESSION['user_ID']<400000) || ($_SESSION['user_ID']>499999)){
                $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$client['Client_No']}\" >Edit</a> </td> ";
                $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$client['Client_No']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
            }
            else{
                if($_SESSION['user_ID']==$client['Client_No']){
                    $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$client['Client_No']}\" >Edit</a> </td> ";
                    $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$client['Client_No']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
            
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
    <div class="loggedin">Welcome <span class="name"><?php echo $_SESSION['First_Name']; ?>!</span>&emsp; <a href="../logout.php" class="logout">Log out</a> </div>
    </nav>
    <div class="t_container">
    <h2 >Clients&ensp;&ensp;<span>
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
            <input type="text" name="search" placeholder="Type Number/Name/NIC & Press Enter" <?php echo $search; ?>autofocus>
        </form>
    </div>
    <div class="table_container">
    <table id="table">
        <tr id="t_row">
            <th id="t_header">Client Number</th>
            <th id="t_header">Client_Name</th>
            <th id="t_header">Client_NIC</th>
            <th id="t_header">Client_Email</th>
            <?php 
            // if(($_SESSION['user_ID']<400000) || ($_SESSION['user_ID']>499999)){
                echo "<th id=\"t_header\">Edit</th>";
                echo "<th id=\"t_header\">Delete</th></th>";
            // }
            ?>
        </tr>
        <?php echo $users_list; ?>
    </table>
    </div>
</body>
</html>
<?php mysqli_close($connection);?>
