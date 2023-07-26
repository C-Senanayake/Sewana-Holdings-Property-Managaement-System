<?php session_start();?>
<?php
    require_once('../include/connection.php');
?>
<?php
    $emp_id='';
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
                $quer="SELECT * FROM manager WHERE Manager_No={$user_id} LIMIT 1";
                $results=mysqli_query($connection,$quer);    
                $resu=mysqli_fetch_assoc($results);
                $emp_id=$resu['Emp_ID'];
                $hashed_empid=sha1($emp_id);

                $Query="UPDATE employee SET Employee_Sectioned='0' WHERE Emp_ID=$emp_id LIMIT 1";
                $Result=mysqli_query($connection,$Query);

                $qery="UPDATE user SET U_ID='$hashed_empid' WHERE Emp_ID=$emp_id LIMIT 1";
                $rsult=mysqli_query($connection,$qery);


                $query="DELETE FROM manager WHERE Manager_No={$user_id} LIMIT 1";
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
