<?php
session_start();
$error="";
if(array_key_exists("logout",$_GET))
{
    session_unset();
    setcookie("id","",time() - 60*60);
    $_COOKIE["id"]="";
}
else if((array_key_exists("id",$_SESSION) AND $_SESSION['id']) OR (array_key_exists("id",$_COOKIE) AND $_COOKIE['id']))
{
    header("Location:loggedInPage.php");
}
if(array_key_exists("submit",$_POST))
{
   include("connection.php");
    
    if(!$_POST['email'])
    {
        $error .= "An email is required </br>";
    }
    if(!$_POST['password'])
    {
        $error .="A password is required </br>";
    }
    if($error != "")
    {
        $error ="<p>There were errors in form:</p>".$error;
    }
    else
    {
     if($_POST['signUp']==1)
     {
     $query ="SELECT id FROM users WHERE email='".mysqli_real_escape_string($link,$_POST['email'])."'LIMIT 1 ";
     
     $result = mysqli_query($link,$query);
     
     if(mysqli_num_rows($result) > 0)
     {
         $error = "<p>Email Address is taken</p>";
     }
     else 
     {
         $query="INSERT INTO users (email,password) VALUES('".mysqli_real_escape_string($link,$_POST['email'])."',
         '".mysqli_real_escape_string($link,$_POST['password'])."')";
         
         if(!mysqli_query($link,$query))
         {
             $error="<p>Sign Up failed try again later</p> ";
         }
         else 
         {
             $query= "UPDATE users SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."'WHERE id = '".mysqli_insert_id($link)."'LIMIT 1";
             mysqli_query($link,$query);
             $_SESSION['id']=mysqli_insert_id($link);
             if($_POST['stayloggedin'] == '1')
             {
                 setcookie("id",mysqli_insert_id($link),time()+60*60*24*365);
                
             }
             header("Location:loggedInPage.php");
         }
     }
    }
    else 
    {
        $query="SELECT * FROM users WHERE email='".mysqli_real_escape_string($link,$_POST['email'])."'";
        $result=mysqli_query($link,$query);
        $row=mysqli_fetch_array($result);
        if(array_key_exists("id",$row))
        {
            $hashedpassword=md5(md5($row['id']).$_POST['password']);
            if($hashedpassword==$row['password'])
            {
                $_SESSION['id']= $row['id'];
                if(isset($_POST['stayloggedin']) AND $_POST['stayloggedin'] == '1')
                {
                    setcookie("id",$row['id'],time()+60*60*24*365);
                    
                }
                
                header("Location:loggedInPage.php");
                
            }
            else
            {
                $error="<p>Email Password Combination not Found</p>";
            }
        }
    }
    }
 
}
?>
<?php include("header.php");?>
 <div class="container" id="homepage">

    <h1>Secret Diary</h1>
    <p><strong>Store your thoughts securely</strong></p>
   
<div id="error"><?php if($error!="")
{
    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
}
    ?>
</div>
<form method=post id="sign-up-form">
<div class="form-group">
<p>Interested? Sign up now<p>
 <input class="form-control" name="email" type="email" placeholder="Email">
</div>
<div class="form-group">
 <input class="form-control" name="password" type="text" placeholder="Password">
</div>
<div class="form-group">
<div class="form-check">
<label class="form-check-label">
 <input type="checkbox" name="stayloggedin" value=1  >
 Stay Logged In
 </label>
</div>
</div>
<div class="form-group">
 <input type="hidden" name="signUp" value="1">
 <input  class="btn btn-success" type="submit" name="submit" value="Sign Up">
</div>
<p><a href="#" class="toggleForm">Log In</a></p>
</form>
<form method=post id="log-in-form" >
<p>Log in your username and password</p>
<div class="form-group">
 <input  class="form-control" name="email" type="email" placeholder="Email">
</div>
<div class="form-group">
 <input  class="form-control" name="password" type="text" placeholder="Password">
</div>
<div class="form-group">
<div class="form-check">
<label class="form-check-label">
 <input type="checkbox" name="stayloggedin" value=1  >
  Stay Logged In
  </label>
</div>
</div>
<div class="form-group">
 <input type="hidden" name="signUp" value="0">
 <input class="btn btn-success" type="submit" name="submit" value="Log In">
</div>
<p><a href="#" class="toggleForm">Sign Up</a></p>
</form>
<?php include("footer.php");?>