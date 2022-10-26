<?php
    include_once './db.php';
    session_start();
    $db=new Db();
    if(isset($_GET["send"]))
    {
        $user=$_GET["user"];
        $pass=$_GET["pass"];
        $korisnik=$db->checkUser($user, $pass);
        if($korisnik)
        {
            $_SESSION["auth"]=$korisnik["id"];
            setcookie("user",$user, time()+86400*2);
            if(isset($_GET["zapamti"]))
            {
                setcookie("pass",$pass, time()+86400*2);
            }
            $uloga=$db->getUloga($korisnik["uloga"]);
            if($uloga["uloga"]=="sef magacina")
            {
                $_SESSION["admin"]=1;
            }
            else {
                $_SESSION["admin"]=0;
            }
            header("Location: index.php");   
        }
        else
        {
            echo 'Nije dobro Korisnicko ime ili lozinka';
        }
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Prijava</h1>
        <form>
            <input type="text" placeholder="Korisnicko ime" value="<?php if(isset($_COOKIE["user"])) echo $_COOKIE["user"];?>" name="user"><br>
            <input type="text" placeholder="Lozinka" value="<?php if(isset($_COOKIE["pass"])) echo $_COOKIE["pass"];?>" name="pass"><br>
            <input type="checkbox" name="zapamti" > Zapamti me<br>
            <input type="submit" value="Login" name="send">
        </form>
    </body>
</html>
