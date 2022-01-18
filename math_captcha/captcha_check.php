<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Captcha Check- abrandao.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
        
<div class="col-lg-8 mx-auto p-3 py-md-5">
     
    <main>
      <h1>Math Captcha Results</h1>
      <p class="fs-5 col-md-8">
         Below are the results inlcluding the hashed value </p>
 
      <hr class="col-3 col-md-2 mb-5">
        <div class="col-md-12">
          <h2>User submission</h2>    
          <p> Below are the results of the User entered hash </p>   
            <li> The User Entered : <?php echo $_REQUEST["captchauser"] ?>
            <li> The User Hash was:  <?php echo MD5($_REQUEST["captchauser"]) ?>
            <li> The Answer hash  : <?php echo  MD5($_REQUEST["captcha"]) ?>
            <!-- Math Captcha PHP code goes here   -->
                <?php
                try {
                // Now lets compare the has value Is the captcha correct with the user entered phrase

                //Note: if using a salt_value (from ast page), you must add apply the salt value to this comparison
                if ($_REQUEST["captcha"]!=MD5($_REQUEST["captchauser"]) )
                throw new Exception("Captcha answer is not correct. No message sent. <a href='index.php#contact' >Re-try</a> ");
                echo "<h1> Congrats! You passed the Captch </h1>";
                } catch (Exception $e) {
                echo "<h2>Sorry the Captcha was not passed </h2>. Error:".$e->getMessage() ;
                }
                ?>
        
      </div>
    </main>
    <footer class="pt-5 my-5 text-muted border-top">
      a bootstrap inspired template  &middot; &copy; abrandao.com 2021
    </footer>
  </div>
  
</body>
</html>