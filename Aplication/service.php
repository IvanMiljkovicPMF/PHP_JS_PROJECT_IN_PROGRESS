<?php

 include_once './db.php';
    session_start();
    $db=new Db();
    if(isset($_GET["showTable"]))
    {
        $db->getAllProizvode();
        return;
    }
    if(isset($_GET["obrisi"]))
    {
        $id=$_GET["id"];
        $db->brisiProizvode($id);
        return;
    }
    if(isset($_GET["update"]))
    {
        $id=$_GET["id1"];
        $db->izmeniKolicinu($id);
        return;
    }
    ?>