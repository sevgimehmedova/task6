<?php
include 'config.php';
session_start();

if(isset($_POST['submit'])){
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = $_POST['password']; // Do not hash the input password here

   $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email'");

   if(mysqli_num_rows($select) > 0){
      $row = mysqli_fetch_assoc($select);
      $hashedPassword = $row['password']; // Retrieve the hashed password from the database

      // Use password_verify() to compare the input password with the stored hashed password
      if (password_verify($password, $hashedPassword)) {
         $_SESSION['user_id'] = $row['id'];
         header('location: home.php');
      } else {
         $message[] = 'Incorrect email or password!';
      }
   } else {
      $message[] = 'Incorrect email or password!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">
</head>
<body>
   
<div class="form-container">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>login now</h3>
      <?php
      if(isset($message)){
         foreach($message as $message){
            echo '<div class="message">'.$message.'</div>';
         }
      }
      ?>
      <input type="email" name="email" placeholder="enter email" class="box" required>
      <input type="password" name="password" placeholder="enter password" class="box" required>
      <input type="submit" name="submit" value="login now" class="btn">
      <p>don't have an account? <a href="register.php">Register now</a></p>
   </form>
</div>
</body>
</html>
