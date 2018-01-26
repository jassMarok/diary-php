<?php
  session_start();
  $link = mysqli_connect("shareddb1e.hosting.stackcp.net","usersdb-323327f5","ro2xBqt7vYpC","usersdb-323327f5");
    
    if(mysqli_connect_error())
    {
    die ("There was error connecting to database");
    echo "<p>Connection Error</p>";
    }

   if(array_key_exists('email',$_POST) OR array_key_exists('password',$_POST))
   {
     if($_POST['email']=='')
     {
      echo "<p>Email is required</p>";
     }
      else if($_POST['password']=='')
     {
      echo "<p>Password is required</p>";
     }
     else
     {
       $query="SELECT id FROM users WHERE email = '".mysqli_real_escape_string($link,$_POST['email'])."'";
       
       $result = mysqli_query($link,$query);
       
       if(mysqli_num_rows($result) > 0)
       {
         echo "<p>Email Address Already Registered</p>";   
       }
       else
       {
       $query ="INSERT INTO users(email,password) VALUES('".mysqli_real_escape_string($link,$_POST['email'])."','".mysqli_real_escape_string($link,$_POST['password'])."')";
        if(mysqli_query($link,$query))
        {
        $_SESSION['email']=$_POST['email'];
        header("Location:session.php");  
        }
         else
         {
           echo "<p>There was problem signing you up- Try again Later</p>";
         }
       }
     
   }
   
   }

 
?>
<form method = "POST">
  <input name="email" type="text" placeholder="Email Address">
  <input name="password" type="text" placeholder="Password">
  <input type="submit" value="sign up">
</form>