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
        $query="SELECT * FROM view WHERE (View_ID LIKE '%$search%' OR Client_No LIKE '%$search%' OR Property_No LIKE '%$search%' OR View_Date LIKE '%$search%') ORDER BY Client_No";
    }else{
       
        $query="SELECT * FROM view ORDER BY View_ID";
    }
    
    $views=mysqli_query($connection,$query);
    if($views){
        while($view=mysqli_fetch_assoc($views)){
            $users_list.="<tr>";
            $users_list.="<td class=\"t_data\">{$view['Client_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$view['Property_No']}</td> ";
            $users_list.="<td class=\"t_data\">{$view['View_Date']}</td> ";
            $users_list.="<td class=\"t_data\">{$view['Comment']}</td> ";
            if(($_SESSION['user_ID']<400000) ){
                // $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$view['View_ID']}\" >Edit</a> </td> ";
                $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$view['View_ID']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";
                    
            }
            // else{
                if($_SESSION['user_ID']==$view['Client_No']){
                    $users_list.="<td class=\"t_data\"><a href=\"edit.php?user_id={$view['View_ID']}\" >Edit</a> </td> ";
                    $users_list.="<td class=\"t_data\"><a href=\"delete.php?user_id={$view['View_ID']}\" onclick=\"return confirm('Are You Sure?');\">Delete</a> </td> ";          
                }
                
            // }
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
    <h2 >Property Viewings&ensp;&ensp;<span>
        
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
            <input type="text" name="search" placeholder="Type Client_No/Property_No/Date & Press Enter" <?php echo $search; ?>autofocus>
        </form>
    </div>
    <div class="table_container">
    <table id="table">
        <tr id="t_row">
            <th id="t_header">Client Number</th>
            <th id="t_header">Property Number</th>
            <th id="t_header">View Date</th>
            <th id="t_header">Comment</th>
            <?php 
            if(($_SESSION['user_ID']<500000) && ($_SESSION['user_ID']>399999)){
                echo "<th id=\"t_header\">Edit</th>";
                // echo "<th id=\"t_header\">Delete</th>";
            } 
            if(($_SESSION['user_ID']<500000) ){
                // echo "<th id=\"t_header\">Edit</th>";
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
