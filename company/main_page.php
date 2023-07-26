<?php session_start()?>
<?php
    require_once('../include/connection.php');
    if(!isset($_SESSION['user_ID'])){
      header('Location:../index.php');
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Page</title>
    <link rel="stylesheet" href="../css/main_p.css">
  </head>
  <body>
    <nav id="navigation">
      <div class="main_name">Sewana Holdings</div>
      <div class="loggedin">Welcome <span class="name"><?php echo $_SESSION['First_Name']; ?>!</span>&emsp; <a href="../logout.php" class="logout">Log out</a> </div>
    </nav>
    <div class="container">
    <div class="container_1">
      <div class="dropdown">
        <button class="dropbtn">Branch</button>
        <div class="dropdown-content">
          <!-- <a href="../branch/new_data.php">&gtAdd</a> -->
          <a href="../branch/data.php">&gtView</a>
        </div>
      </div><br>
      <!-- <div class="dropdown">
        <button class="dropbtn">Client</button>
        <div class="dropdown-content">
          <a href="new_data.php">&gtAdd</a>
          <a href="data.php">&gtView Clients</a>
          <a href="../client register/data.php">&gtClient Registration Details</a>
        </div>
      </div> -->
      <!-- <div class="dropdown">
        <button class="dropbtn">Employee</button>
        <div class="dropdown-content">
          <a href="../employee/new_data.php">&gtAdd Employee</a>
          <a href="../employee/data.php">&gtView/Update/Delete-Employee</a>
          <a href="../manager/new_data.php">&gtAdd-Manager</a>
          <a href="../manager/data.php">&gtView/Update/Delete</a>
          <a href="../supervisor/new_data.php">&gtAdd-Supervisor</a>
          <a href="../supervisor/data.php">&gtView/Update/Delete-Supervisor</a>
          <a href="../assistant/new_data.php">&gtAdd-Assistant</a>
          <a href="../assistant/data.php">&gtView/Update/Delete-Assistant</a>
          
        </div>
      </div> -->
      <!-- <div class="dropdown">
        <button class="dropbtn">Lease</button>
        <div class="../dropdown-content">
          <a href="../lease/new_data.php">&gtAdd</a>
          <a href="../lease/data.php">&gtView/Update/Delete</a>
        </div>
      </div><br> -->
      <div class="dropdown">
        <button class="dropbtn">News Paper</button>
        <div class="dropdown-content">
        <!-- <a href="news_paper/new_data.php">&gtAdd News Paper</a>
        <a href="../news_paper/data.php">&gtView/Update/Delete-NP</a>
        <a href="../advertise/new_data.php">&gtAdd Advertisement</a> -->
          <a href="../advertise/data.php">&gtView Advertisement</a>
          <a href="../daily/data.php">&gtView-Daily</a>
          <a href="../weakly/data.php">&gtView-Weakly</a>
        </div>
      </div><br>
      <div class="dropdown">
        <button class="dropbtn">Property</button>
        <div class="dropdown-content">
          <!-- <a href="../property/new_data.php">&gtAdd</a> -->
          <a href="../property/data.php">&gtView</a>
        </div>
      </div><br>
      <div class="dropdown">
        <button class="dropbtn">Property Owner</button>
        <div class="dropdown-content">
          <!-- <a href="../property_owner/new_data.php">&gtAdd-Owner</a> -->
          <a href="../p_owner_register/data.php">&gtOwner Registration Details</a>
          <a href="../company/data.php">&gtView/Update/Delete-Company</a>
        <!-- <a href="../person/data.php">&gtView/Update/Delete-Person</a> -->
      </div>
    </div>
    <div class="dropdown">
        <button class="dropbtn">Property Requirements</button>
        <div class="dropdown-content">
          <!-- <?php echo "<a href=\"../property_requirements/new_data.php?user_id={$_SESSION['user_ID']}\">&gtAdd</a> ";?> -->
          <a href="../property_requirements/data.php">&gtView</a>
        </div>
      </div><br>
    <div class="dropdown">
      <button class="dropbtn">Property Viewing</button> 
      <div class="dropdown-content">
      <!-- <?php echo "<a href=\"../viewing/new_data.php?user_id={$_SESSION['user_ID']}\">&gtAdd</a> ";?> -->
        <a href="../viewing/data.php">&gtView</a>
      </div>
    </div><br>
</div>
<div class="paragraph"><span class="welcome"> Welcome to Sewana Property Renters!! </span><br>The number 1 property renting Company in Sri Lanka with 42 years of experience.
    We help you rent any kind of property available in Sri Lanka for a very reasonable price. We assure that you will get a value for your
    payment. We have thousands of properties for rent, from Luxury Apartments, Bungalows to Annexes.<br> 
    With all the experience we gathered through these years, we provide you the best service in Sri Lanka. A service that you have never 
    experienced before. We will provide you the most accurate, efficient and reliable service for your requirements. 
</div>

    </div>
</body>
</html>