<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math Captha - abrandao.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>

<div class="jumbotron text-center">
  <h1>PHP Math Captcha Example</h1>
  <p>a simple logic captcha, such as a  math (arithmetic) captcha </p>
</div>

<div class="container">
<div class="row">
    <div class="col-md-12">
    <h1 class="h3 mb-3 fw-normal">Math captcha Sample</h1>
    <p>a simple logic captcha, such as a  math (arithmetic) captcha (Add, subtract , multiply??) is enough to defeat most bots, so below 
            is a simple code example of how a simple text based math captcha can solve the issue..</p>
    </div>
  
  </div>
  <div class="row">
    <div class="col-md-12">
    <form action="captcha_check.php" method="POST">
           <h2> Solve the Captcha Below </h2>
      <div class="field">
        <label for="message">Captcha</label>
 
        <small> Solve  math problem: 
         <!-- PHP Math Captcha Code goes here --->   
            <?php 
            $ops=["+","-","*"];
            $numbers=["0","won","2","three","4","five","6","seven","8","nine","ten"];
            $n1= rand(0,10);
            $n2= rand(0,10);
            $op=$ops[rand(0,2)];
            echo $numbers[$n1]." ".$op." ".$numbers[$n2]." = ";
            $computed= eval('return '.$n2.$op.$n1.';');
            $val=md5($computed);  
            // more secure way is to add a salt value to the $computed number to prevent md5 brute-force
            // $salt_value=12345;
            // $val=md5($computed+$salt_value); //$salt_value can be any number
            // echo "<input type='hidden' name='answer' value='$computed'>"; //debugging purposes
            echo "<input type='hidden' name='captcha' value='$val'>";
            ?>
        

        </small>
        <!-- this is the User Input we Use -->
        <input type="text" name="captchauser" id="captchauser" required />
     </div>

      <button class="btn-primary" type="submit">Verify Captcha </button><br>

      <button class="btn-secondary" type="button" onClick="location.reload()">Reset Captcha</button>
     
    </form>
  </div>

  <p class="mt-5 mb-3 text-muted">&copy; abrandao.com 2022</p>
</div>
 

</body>
</html>