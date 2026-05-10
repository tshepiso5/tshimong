<?php
include('admin/config/dbcon.php');
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary mb-4">
  <div class="container">
    <div class="navbar-header float-start d-inline-block">
      <a href="index.php" class="float-start">
        <div id="logo-img">

        </div>

      </a>
          
      </div>
    <a class="navbar-brand d-none d-md-block d-lg-block" href="index.php">
        <div class="col-md-6 tshimong-logo">
          <h1>Tshimong: the Dirty Farm</h1>
        </div>
      
    </a>
    
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav ms-auto">
       
       
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Dropdown link
          </a>
          <ul class="dropdown-menu">
            
            <?php
              if(isset($_SESSION['auth'])){
                ?>
                 <li><form action="logout.php" method="post">
                        <button type="submit" name="logout-btn" class="dropdown-item">Logout</button>
                    </form></li>
                 <?php
              }else{
                ?>
                <li><a class="dropdown-item" href="login.php">Login</a></li>
                <li><a class="dropdown-item" href="register.php">Register</a></li>
                <?php
              }
            ?>
           
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>