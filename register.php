<?php
include 'config.php';

$message = '';

if (isset($_POST['submit'])) {
   $name = mysqli_real_escape_string($conn, $_POST['name']);
   $email = mysqli_real_escape_string($conn, $_POST['email']);
   $password = $_POST['password'];
   $cpassword = $_POST['cpassword'];
   $phone = mysqli_real_escape_string($conn, $_POST['phone']);

   // Check if passwords match
   if ($password != $cpassword) {
      $message = 'Confirm password does not match!';
   } else {
      // Hash the password using password_hash() function
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

      // Check if the email already exists in the database
      $select = mysqli_query($conn, "SELECT * FROM `user_form` WHERE email = '$email'");

      if (mysqli_num_rows($select) > 0) {
         $message = 'User already exists';
      } else {
         // Insert the user's information into the database
         $insert = mysqli_query($conn, "INSERT INTO `user_form` (name, email, password, phone) VALUES ('$name', '$email', '$hashedPassword', '$phone')");

         if ($insert) {
            $message = 'Registration successful. <a href="login.php">Login now</a>';
         } else {
            $message = 'Registration failed.';
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>
   <link rel="stylesheet" href="style.css">
</head>
<body>
   
<div class="form-container">
   <form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
      <h3>register now</h3>
      <div id="error-messages">
         <?php echo $message; ?>
      </div>
      <input type="text" name="name" id="name" placeholder="enter username" class="box" required>
      <input type="email" name="email" id="email" placeholder="enter email" class="box" required>
      <input type="phone" name="phone" id="phone" placeholder="enter phone number" class="box" required>
      <input type="password" name="password" id="password" placeholder="enter password" class="box" required>
      <input type="password" name="cpassword" id="cpassword" placeholder="confirm password" class="box" required>
      <input type="submit" name="submit" value="register now" class="btn">
      <p>already have an account? <a href="login.php">Login now</a></p>
   </form>
</div>


<script>
   function validateForm() {
      const name = document.getElementById('name').value;
      const email = document.getElementById('email').value;
      const password = document.getElementById('password').value;
      const cpassword = document.getElementById('cpassword').value;
      const errorMessages = document.getElementById('error-messages');
      errorMessages.innerHTML = '';

      let isValid = true;

      if (name.trim() === '') {
         errorMessages.innerHTML += '<p>Please enter a username.</p>';
         isValid = false;
      }

      if (email.trim() === '') {
         errorMessages.innerHTML += '<p>Please enter an email.</p>';
         isValid = false;
      } else if (!isValidEmail(email)) {
         errorMessages.innerHTML += '<p>Please enter a valid email.</p>';
         isValid = false;
      }

      if (password.trim() === '') {
         errorMessages.innerHTML += '<p>Please enter a password.</p>';
         isValid = false;
      }

      if (cpassword.trim() === '') {
         errorMessages.innerHTML += '<p>Please confirm your password.</p>';
         isValid = false;
      } else if (password !== cpassword) {
         errorMessages.innerHTML += '<p>Passwords do not match.</p>';
         isValid = false;
      }

      return isValid;
   }

   function isValidEmail(email) {
      // A basic email validation regex
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
   }
</script>
</body>
</html>
