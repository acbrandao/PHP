<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
define('DOMAIN_FQDN', 'YOUR_DOMAIN.local'); //Replace with REAL DOMAIN FQDN
define('LDAP_SERVER', '10.0.1.3');  //Replace with REAL LDAP SERVER Address

//Basic Login verification
if (isset($_POST['submit']))
{
    $user = strip_tags($_POST['username']) .'@'. DOMAIN_FQDN;
    $pass = stripslashes($_POST['password']);

    $conn = ldap_connect("ldap://". LDAP_SERVER ."/");

    if (!$conn)
        $err = 'Could not connect to LDAP server';

    else
    {
//        define('LDAP_OPT_DIAGNOSTIC_MESSAGE', 0x0032);  //Already defined in PHP 5.x  versions
        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

        $bind = @ldap_bind($conn, $user, $pass);

        ldap_get_option($conn, LDAP_OPT_DIAGNOSTIC_MESSAGE, $extended_error);

        if (!empty($extended_error))
        {
            $errno = explode(',', $extended_error);
            $errno = $errno[2];
            $errno = explode(' ', $errno);
            $errno = $errno[2];
            $errno = intval($errno);

            if ($errno == 532)
                $err = 'Unable to login: Password expired';
        }

        elseif ($bind)
        {
      //determine the LDAP Path from Active Directory details
            $base_dn = array("CN=Users,DC=". join(',DC=', explode('.', DOMAIN_FQDN)), 
                "OU=Users,OU=People,DC=". join(',DC=', explode('.', DOMAIN_FQDN)));

            $result = ldap_search(array($conn,$conn), $base_dn, "(cn=*)");

            if (!count($result))
                $err = 'Result: '. ldap_error($conn);

            else
            {
                echo "Success";
        /* Do your post login code here */
            }
        }
    }

    // session OK, redirect to home page
    if (isset($_SESSION['redir']))
    {
        header('Location: /');
        exit();
    }

    elseif (!isset($err)) $err = 'Result: '. ldap_error($conn);

    ldap_close($conn);
}
?>
<!DOCTYPE html>
<head>
<title>PHP LDAP LOGIN</title>
</head>
<body>
<div align="center">
<h3>Login</h3>

<div style="margin:10px 0;"></div>
<div title="Login"  id="loginbox">
    <div style="padding:10px 0 10px 60px">
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" id="login" method="post">
        <table><?php if (isset($err)) echo '<tr><td colspan="2" class="errmsg">'. $err .'</td></tr>'; ?>
            <tr>
                <td>Login:</td>
                <td><input type="text" name="username" autocomplete="off"/></td>
            </tr>
            <tr
                <td>Password:</td>
                <td><input type="password" name="password"  autocomplete="off"/></td>
            </tr>
        </table>
        <input class="button" type="submit" name="submit" value="Login" />
    </form>
    </div>
</div>
</div>
</body>
</html>
