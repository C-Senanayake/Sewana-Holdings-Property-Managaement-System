<?php session_start();?>
<?php
    require_once('../include/connection.php');
?>
<?php
    if(!isset($_SESSION['user_ID'])){
        header('Location:../index.php'); 
    }     

        if(isset($_GET['user_id'])){
            $user_id=mysqli_real_escape_string($connection,$_GET['user_id']);
         
            if($user_id==$_SESSION['user_ID']){
                //deleting current user
                header('Location:data.php?err=cannot_delete_current_user');
            }
            else{
                $query="DELETE FROM property WHERE Property_No={$user_id} LIMIT 1";
                $result=mysqli_query($connection,$query);
                if($result){
                    //user deleted
                    header('Location:data.php?user_deleted');
                }
                else{
                    echo 'Error deleting';
                }
            }
        }
        else{
            header('Location:data.php');
        }
?>
<?php mysqli_close($connection);?>
