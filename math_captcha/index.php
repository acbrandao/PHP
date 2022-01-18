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

<hr>
<main class="form-signin" action="captcha_check.php" method="POST">
    <form>
        <h1 class="h3 mb-3 fw-normal">>Math captcha Sample</h1>
        <p>a simple logic captcha, such as a  math (arithmetic) captcha (Add, subtract , multiply??) is enough to defeat most bots, so below 
            is a simple code example of how a simple text based math captcha can solve the issue..</p>

      <div class="form-floating">
        <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Email address</label>
      </div>
      <div class="form-floating">
        <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Password</label>
      </div>
  
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

      <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
      <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p>
    </form>
  </main>
  

</body>
</html>