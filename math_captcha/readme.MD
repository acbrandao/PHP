## Match Captcha in PHP 

PHP Math Captcha basics
Let's begin with the basics we'll create a simple arithmetic math problem like 5+3 then ask the user to supply and answer, then we'll capture the user's input and compare the result with the correct solution on the form before we do anything with the input. Since most bots may not (some of the more sophisticated ones do recognition, but most do not), they'll just fail and NOT send you the form/ or capture the user input.

#### Here's the really basic code:

Essentially it sets up a couple of arrays:

one of the operations like +, – , *  $ops
one of the digits 1-10 as both numbers and words $numbers
It then randomly chooses the first and second numbers from a range of 0..10
We then use those values as the indexes into the corresponding arrays $numbers  and $ops
We then put the regular $n1 $op $n2 into an eval function to get our answer as $computed
For encoding the actual answer we use and MD5 hash to  create a one-way hash , we'll compare this hash later with the user supplied value,
```php
$ops=["+","-","*"];
$numbers=["0","one","2","three","4","five","6","seven","8","nine","ten"];
$n1= rand(0,10);
$n2= rand(0,10);
$op=$ops[rand(0,2)];
echo $numbers[$n1]." ".$op." ".$numbers[$n2]." = ";
$computed= eval('return '.$n2.$op.$n1.';');
$val=md5($computed);
// echo "<input type='hidden' name='answer' value='$computed'>"; //debugging purposes
echo "<input type='hidden' name='captcha' value='$val'>";
?>
```

### Importance of usign **salt** value
For increased security  add a salt value to the $computed number to prevent md5 brute-force
```php
$salt_value=12345;
$val=md5($computed+$salt_value); //$salt_value can be any number
echo "<input type='hidden' name='answer' value='$computed'>"; //debugging purposes
```
Don't forget to use the **Same $salt_value** on the captcha check page

## Captcha Sample form 

you would then provide the proper input tag to capture the users input like, because this Captcha uses a mix of numbers and worked numbers, you may want to add clarity to the user that all the answers need to be as numbers.

`html
<input type="text" name="captchauser" id="captchauser" />
`

### Math/Logic Captcha Verification
Finally on the form where you are going to collect the data you would verify the Math captcha is correct.

```
<?php
try {
  // Do something here with your input
//Is the captcha correct ?
if ($_REQUEST["captcha"]!=MD5($_REQUEST["captchauser"]) )
  throw new Exception("Captcha answer is not correct. No message sent. <a href='index.php#contact' >Re-try</a> ");
// Continue.. with the normal flow of capturing data input
} catch (Exception $e) {
    echo "Message could not be sent. Error:".$e->getMessage() ;
}
?>
```
That's it very simple , and pretty effective against defeating most Spambots. You can of course increase the complexity of this captcha using things like more than just on $operation , or add more numbers, or mispell  the numbers or add more operations etc..

The point is it becomes exponentially harder for the bots to determine the answer, while still keeping the code clean, simple and straightforward without a lot external dependencies. My favorite type of code..