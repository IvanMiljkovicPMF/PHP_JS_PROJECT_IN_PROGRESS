<?php

    class SefiProdavac
    {
        public $id;
        public $korisnicko_ime;
        
        function __construct($id,$korisnicko_ime) {
            $this->id=$id;
            $this->korisnicko_ime=$korisnicko_ime;
        }
    }
    class Proizvodi
    {
        public $id;
        public $naziv;
        public $tip;
        public $opis;
        public $kolicina;
        function __construct($id,$naziv,$tip,$opis,$kolicina) {
            $this->id=$id;
            $this->naziv=$naziv;
            $this->tip=$tip;
            $this->opis=$opis;
            $this->kolicina=$kolicina;
        }
        
    }
    class Db{
        const host = 'localhost';
        const user = 'root';
        const pass = ''; 
        const dbname = "magacin"; 
        private $dbh;
            function __construct() 
            { 
                try 
                { 
                    $string="mysql:host=".self::host.";dbname=".self::dbname; 
                    $this->dbh = new PDO($string, self::user, self::pass); 

                } 
                catch(PDOException $e) 
                { 
                    echo "GRESKA: "; 
                    echo $e->getMessage(); 
                }
            } 
            function __destruct() 
            { 
                $this->dbh = null; 
            
            }
            public function checkUser($user,$pass)
            {
                try { 
                    $sql = "SELECT * FROM korisnik WHERE korisnicko_ime='$user' and sifra='$pass'"; 
                    $pdo_izraz = $this->dbh->query($sql);
                    $obj = $pdo_izraz->fetch(PDO::FETCH_ASSOC); 
                    return $obj; 
                } 
                catch(PDOException $e) 
                { 
                    echo "GRESKA: "; 
                    echo $e->getMessage(); 
                }
            }
            public function getUloga($id)
            {
                try { 
                    $sql = "SELECT * FROM tip_korisnika WHERE id=$id"; 
                    $pdo_izraz = $this->dbh->query($sql);
                    $obj = $pdo_izraz->fetch(PDO::FETCH_ASSOC); 
                    return $obj; 
                    } 
                catch(PDOException $e) 
                { 
                    echo "GRESKA: "; 
                    echo $e->getMessage(); 
                }
            }
            public function getProizvodeID($id)
            {
                try { $sql = "SELECT * FROM proizvod WHERE id=$id"; 
                $pdo_izraz = $this->dbh->query($sql);
                $obj = $pdo_izraz->fetch(PDO::FETCH_ASSOC); 
                return $obj; } 
                catch(PDOException $e) 
                { echo "GRESKA: "; 
                echo $e->getMessage(); }
            }
            public function getKorisnika($id)
            {
                try { $sql = "SELECT * FROM korisnik WHERE id=$id"; 
                $pdo_izraz = $this->dbh->query($sql);
                $obj = $pdo_izraz->fetch(PDO::FETCH_ASSOC); 
                return $obj; } 
                catch(PDOException $e) 
                { echo "GRESKA: "; 
                echo $e->getMessage(); }
            }
            public function getAllProizvode()
            {
                try { $sql = "SELECT * FROM proizvod"; 
                $pdo_izraz = $this->dbh->query($sql);
                $proizvodi = $pdo_izraz->fetchAll(PDO::FETCH_ASSOC); 
                 
                $proizvod=[];
                foreach ($proizvodi as $p)
                {
                    $id=$p["id"];
                    $naziv=$p["naziv"];
                    $tip=$p["tip"];
                    $opis=$p["opis"];
                    $kolicina=$p["kolicina"];
                    $proizvod[]=new Proizvodi($id, $naziv, $tip, $opis, $kolicina);
                }
                echo json_encode($proizvod);
                } 
                catch(PDOException $e) 
                { 
                    echo "GRESKA: "; 
                    echo $e->getMessage(); 
                }
            }
            public function brisiProizvode($id) 
            { 
                try 
                { 
                    $sql = "DELETE FROM proizvod WHERE id=$id";
                    $pdo_izraz = $this->dbh->exec($sql);
                    return true; 
                } 
                catch(PDOException $e) 
                {   
                    echo "GRESKA: ";
                    echo $e->getMessage(); 
                    return false; 
                } 
            }
            
            public function dodajProizvod($n, $t,$o,$k) 
            { 
                try 
                {
                    $sql = "SELECT * FROM proizvod WHERE naziv='$n' and opis='$o'"; 
                    $pdo_izraz = $this->dbh->query($sql);
                    $obj = $pdo_izraz->fetch(PDO::FETCH_ASSOC); 
                
                    if($obj)
                    {
                        $id=$obj["id"];
                        $sql = "UPDATE proizvod SET kolicina=:kolicina WHERE id=:id"; 
                        $pdo_izraz = $this->dbh->prepare($sql); 
                        $kolicina1= $obj["kolicina"]+$k;
                        $pdo_izraz->bindParam(':id', $id); 
                        $pdo_izraz->bindParam(':kolicina', $kolicina1); 
                        $pdo_izraz->execute(); 
                        return true;
                    }
                    else
                    {
                        $sql = "INSERT INTO proizvod(naziv,tip,opis,kolicina) VALUES ('$n', '$t', '$o',$k)"; 
                        $pdo_izraz = $this->dbh->exec($sql);
                        return true;  
                    }
                 } 
                catch(PDOException $e) 
                { 
                    echo "GRESKA: "; 
                    echo $e->getMessage(); 
                    return false; 
                } 
            }
            public function dodajProdavca($ki, $s) 
            {   try { 
                        $sql = "SELECT * FROM korisnik WHERE korisnicko_ime='$ki' and sifra='$s' "; 
                        $pdo_izraz = $this->dbh->query($sql);
                        $obj = $pdo_izraz->fetch(PDO::FETCH_ASSOC); 
                        if($obj)
                        {
                            return false;
                        }
                        else{
                            $sql = "INSERT INTO korisnik(korisnicko_ime,sifra,uloga) VALUES ('$ki', '$s',1)"; 
                            $pdo_izraz = $this->dbh->exec($sql); return true;
                            }
                    } 
                catch(PDOException $e) 
                { 
                    echo "GRESKA: "; 
                    echo $e->getMessage(); 
                    return false; 
                } 
            }
        
            public function izmeniKolicinu($id) 
            {    
                try { 
                        $sql = "UPDATE proizvod SET kolicina=:kolicina WHERE id=:id"; 
                        $pdo_izraz = $this->dbh->prepare($sql); 
                        $kolicina= $this->getProizvodeID($id);
                        $pdo_izraz->bindParam(':id', $id); 
                        $smanjenaKolicina=$kolicina["kolicina"]-1;
                        $pdo_izraz->bindParam(':kolicina', $smanjenaKolicina); 
                        $pdo_izraz->execute(); 
                        return true; 
                    }
                    catch(PDOException $e) 
                    { 
                        echo "GRESKA: "; 
                        echo $e->getMessage(); 
                        return false; 
                    } 
            }
    }
?>
