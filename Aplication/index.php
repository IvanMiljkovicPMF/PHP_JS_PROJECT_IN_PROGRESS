<?php
    include_once './db.php';
    session_start();
    $db=new Db();
    if(!isset($_SESSION["auth"]))
    {
        header("Location: login.php");
    }
    if(isset($_GET["logout"]))
    {
        session_destroy();
        header("Location: login.php");
    }
    if(isset($_GET["dodaj"]))
    {
        $n=$_GET["naziv"];
        $t=$_GET["tip"];
        $o=$_GET["opis"];
        $k=$_GET["kolicina"];
        $db->dodajProizvod($n, $t, $o, $k);
    }
    if(isset($_GET["dodajp"]))
    {
        $ki=$_GET["ime"];
         $s=$_GET["sifra"];
         $db->dodajProdavca($ki, $s);
    }
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body onload="proizvodi()">
        <?php
        $korisnik=$db->getKorisnika($_SESSION["auth"]);
        if($_SESSION["admin"]==1)
        {
            echo "Sef magacina: ".$korisnik["korisnicko_ime"];
            
        ?>
        <br>
        <div onchange="proizvodi()">
        <table id="tabela1" border="1"></table>
        </div><br>
        <form id="forma1">
        Naziv:<input type="text" name="naziv"><br>
        Tip:<input type="text" name="tip"><br>
        Opis:<input type="text" name="opis"><br>
        Kolicina:<input type="text" name="kolicina"><br>
        <input type="submit" name="dodaj" value="Dodaj PRoizvod"><br>
        </form>
        <br>
        
        <?php
        }
        else {
            echo "Prodavac: ".$korisnik["korisnicko_ime"];
        
          ?>
        <br>
        <div onchange="proizvodi()">
        <table id="tabela1" border="1"></table>
        </div><br>
        <form id="forma2">
        Korisnicko ime:<input type="text" name="ime"><br>
       Sifra: <input type="text" name="sifra"><br>
        
       <input type="submit"  name="dodajp" value="dodajProdavca"><br>
        </form>
        <br>
        <br>
        
        <?php
        }
        ?>
        
        <br><br>
        <a href="login.php?logout">Logout</a>
        <script>
            
        function proizvodi()
        {
            
        admin=<?php echo $_SESSION["admin"];?>;
        http = new XMLHttpRequest();
        http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
        proizvodi = JSON.parse(http.responseText);
        tabela = "<tr><th>Naziv</th><th>Tip</th><th>Opis</th><th>Kolicina</th></tr>";
        for(i = 0; i < proizvodi.length; i++){
            
            
            if(admin==1){
             tabela+="<tr><td>"+proizvodi[i].naziv+"</td><td>"+proizvodi[i].tip+"</td><td>"+proizvodi[i].opis+"</td><td>"+proizvodi[i].kolicina+"</td>";   
             tabela+="<td><input type='button' onclick='obrisiProizvod("+proizvodi[i].id+")' value='Obrisi'></td>";
            }
            else
            {
                if(proizvodi[i].kolicina!=0)
                {
                    tabela+="<tr><td>"+proizvodi[i].naziv+"</td><td>"+proizvodi[i].tip+"</td><td>"+proizvodi[i].opis+"</td><td>"+proizvodi[i].kolicina+"</td>"
                     tabela+="<td><input type='button' onclick='naruciProizvod("+proizvodi[i].id+")' value='Naruci'></td>";
                }
                
            }
            
            tabela+="</tr>";
        }
        
        document.getElementById("tabela1").innerHTML=tabela;
    }
    };
        http.open("GET", "service.php?showTable" ,true);
        http.send();
    }
    
    
      function obrisiProizvod(id)
      {
          http = new XMLHttpRequest();
        http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            window.location="index.php";
        }
    };
     http.open("GET", "service.php?obrisi&id="+id ,true);
        http.send();
      }
      
      
      function naruciProizvod(id)
      {
          http = new XMLHttpRequest();
        http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            window.location="index.php";
        }
    };
     http.open("GET", "service.php?update&id1="+id ,true);
        http.send();
      }
      
      
      function forma1()
      {
          http = new XMLHttpRequest();
        http.onreadystatechange = function() {
        if (http.readyState == 4 && http.status == 200) {
            window.location="index.php";
        }
    };
     http.open("GET", "service.php?dodajproizvod&id1="+id ,true);
        http.send();
      }
      
      
        </script>
    </body>
</html>
