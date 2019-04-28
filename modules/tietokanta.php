<?php
    class Tietokanta
    {
        
        public function __construct()
        {
            require_once("db_connection.php");
            $this->db_servername = $db_servername;
            $this->db_username = $db_username;
            $this->db_password = $db_password;
            $this->db_name = $db_name;
        }

        public function __deconstruct()
        {

        }

        public function KirjauduSisaan($tunnus, $salasana)
        {
            $connection = new mysqli($this->db_servername, $this->db_username, $this->db_password, $this->db_name);

            if ($connection->connect_error)
            {
                die("Ei saada yhteyttä tietokantaan.");
            }

            $user = mysqli_real_escape_string($connection, $tunnus);
            $pass = mysqli_real_escape_string($connection, $salasana);

            $query = "SELECT * FROM Kayttajatunnus WHERE tunnus = '$user' AND salasana = '$pass'";

            $result = $connection->query($query);
            
            
            if ($result->num_rows > 0) 
            {
                while($row = $result->fetch_assoc()) {
                    $luokka = $row["luokka"];
                    $connection->close();
                    return $luokka;
                }
            } 
        
            else 
            {
                return null;
            }
            $connection->close();
        }    

        // TIMON OSA  //
        
        

        /** 
         * Listaa asiakkaat tietokannasta
         */
        public function HaeAsiakkaat()
        {
            $connection = new mysqli($this->db_servername, $this->db_username, $this->db_password, $this->db_name);
        
            if ($connection->connect_error)
            {
                die("Ei saada yhteyttä tietokantaan.");
            }
        
            $query = "SELECT * FROM asiakas";
                    
            $listAsiakkaat = array();

            $result = $connection->query($query);
                    
            if ($result->num_rows > 0) 
            {
                while($row = $result->fetch_assoc()) {
                    $asiakas = new Asiakkaat($row["asiakas_id"], $row["etunimi"], $row["sukunimi"],
                    $row["lahiosoite"], $row["postitoimipaikka"], $row["postinro"], $row["email"], $row["puhelinnro"]);
                                
                    $listAsiakkaat[] = $asiakas;
                }
            } 
            else {
                $listAsiakkaat = null;
            }
            $connection->close();
                    
            return $listAsiakkaat;
        }
        // Hakee yksittäisen asiakkaan tiedot //
        // EI KÄYTÖSSÄ //
        public function HaeAsiakas($asiakkaanID) {

            $connection = new mysqli($this->db_servername, $this->db_username, $this->db_password, $this->db_name);
            
            if ($connection->connect_error)
            {
                die("Ei saada yhteyttä tietokantaan.");
            }

            $query = "SELECT * FROM asiakas WHERE asiakas_id = '$asiakkaanID'";

            $result = $connection->query($query);

            if ($result->num_rows > 0) 
            {
                $row = $result->fetch_assoc();
                $asiakas = new Asiakkaat($row["asiakas_id"], $row["etunimi"], $row["sukunimi"], $row["lahiosoite"], 
                    $row["postitoimipaikka"], $row["postinro"], $row["email"], $row["puhelinnro"]);
                
            } 
            else {
                $asiakas = null;
                // echo "Ei yhtään tulosta.";
            }

            $connection->close();

            return $asiakas;
        }
        // Hakee yksittäisen laskun tiedot
        public function HaeLaskut($asiakkaanID)
        {
            $connection = new mysqli($this->db_servername, $this->db_username, $this->db_password, $this->db_name);

            if ($connection->connect_error)
            {
                die("HaeLaskut ei saa yhteyttä tietokantaan.");
            }

            $query = "SELECT * FROM lasku WHERE asiakas_id ='$asiakkaanID'";

            $result = $connection->query($query);

            if ($result->num_rows > 0) 
            {
                while($row = $result->fetch_assoc()) {
                    $laskut = new Lasku($row["lasku_id"], $row["asiakas_id"], $row["varaus_id"], $row["sukunimi"],
                    $row["lahiosoite"], $row["postitoimipaikka"], $row["postinro"], $row["summa"], $row["alv"]);
                    $listLaskut[] = $laskut;     
                
                }
            } 
                
            else 
            {
                echo "Ei laskuja tietokannassa.";
            }
            $connection->close();

            return $listLaskut;
        }
        //Päivittää muokattavan laskun tiedot
        public function PaivitaLasku($lasku) {

            $connection = new mysqli($this->db_servername, $this->db_username, $this->db_password, $this->db_name);
            
            if ($connection->connect_error)
            {
                die("Ei saada yhteyttä tietokantaan.");
            }

            $lasku_id = $connection->real_escape_string($lasku->getLaskuId());
            $varaus_id = $connection->real_escape_string($lasku->getVarausId());
            $asiakas_id = $connection->real_escape_string($lasku->getAsiakasId());
            $sukunimi = $connection->real_escape_string($lasku->getSukunimi());
            $lahiosoite = $connection->real_escape_string($lasku->getLahiosoite());
            $postitoimipaikka = $connection->real_escape_string($lasku->getPostitoimipaikka());
            $postinro = $connection->real_escape_string($lasku->getPostinro());
            $summa = $connection->real_escape_string($lasku->getSumma());
            $alv = $connection->real_escape_string($lasku->getAlv());

            $query = "UPDATE lasku SET lasku_id='$lasku_id', varaus_id='$varaus_id', asiakas_id='$asiakas_id', sukunimi='$sukunimi', lahiosoite='$lahiosoite',
             postitoimipaikka='$postitoimipaikka', postinro='$postinro', summa='$summa', alv='$alv' WHERE lasku_id='$lasku_id'";

            $result = $connection->query($query);

            if ($result){
                $message = "Tietojen tallentaminen onnistui";
                
            } else {
                $message = "Sattui odottamaton virhe, yritä myöhemmin uudelleen";
            }

            $connection->close();
            return $message;
        }
        //Lisää uuden laskun tietokantaan
        public function LisaaLasku($lasku) {

            $connection = new mysqli($this->db_servername, $this->db_username, $this->db_password, $this->db_name);
            
            if ($connection->connect_error)
            {
                die("Ei saada yhteyttä tietokantaan.");
            }
            
            $lasku_id = $connection->real_escape_string($lasku->getLaskuId());
            $varaus_id = $connection->real_escape_string($lasku->getVarausId());
            $asiakas_id = $connection->real_escape_string($lasku->getAsiakasId());
            $sukunimi = $connection->real_escape_string($lasku->getSukunimi());
            $lahiosoite = $connection->real_escape_string($lasku->getLahiosoite());
            $postitoimipaikka = $connection->real_escape_string($lasku->getPostitoimipaikka());
            $postinro = $connection->real_escape_string($lasku->getPostinro());
            $summa = $connection->real_escape_string($lasku->getSumma());
            $alv = $connection->real_escape_string($lasku->getAlv()); 
            
            $query = "INSERT INTO lasku (lasku_id, varaus_id, asiakas_id, sukunimi, lahiosoite, postitoimipaikka, postinro, summa, alv) VALUES ('$lasku_id', '$varaus_id', '$asiakas_id', '$sukunimi' ,'$lahiosoite', '$postitoimipaikka', '$postinro' '$summa', '$alv')";

            if($connection->query($query) === TRUE)
            {
                $message = "Tietojen tallentaminen onnistui";
            }
            else
            {
                $message = "Sattui odottamaton virhe, yritä myöhemmin uudelleen";
            }

            $connection->close();
            return $message;
        }
        // Hakee yksittäisen laskun tiedot
        public function HaeLaskunTiedot($laskunID) {

            $connection = new mysqli($this->db_servername, $this->db_username, $this->db_password, $this->db_name);

            if ($connection->connect_error)
            {
                die("Ei saada yhteyttä tietokantaan.");
            }

            $query = "SELECT * FROM lasku WHERE lasku_id='$laskunID'";

            $result = $connection->query($query);

            if ($result->num_rows > 0) 
            {
                
                while($row = $result->fetch_assoc()) {
                    $laskuntiedot = new Lasku($row["lasku_id"], $row["asiakas_id"], $row["varaus_id"], $row["sukunimi"],
                    $row["lahiosoite"], $row["postitoimipaikka"], $row["postinro"], $row["summa"], $row["alv"]);
                        
                
                }
            } 
            else 
            {
                $laskuntiedot = null;
            }

            $connection->close();
            return $laskuntiedot;
        } 
        // Poistaa laskun tietokanasta
        public function PoistaLasku($laskunID) {

            $connection = new mysqli($this->db_servername, $this->db_username, $this->db_password, $this->db_name);

            if ($connection->connect_error)
            {
                die("Ei saada yhteyttä tietokantaan.");
            }

            $query = "DELETE FROM lasku WHERE lasku_id='$laskunID'";

            //$result = $connection->query($query);

            if($connection->query($query) === TRUE)
            {
                $message = "Tietojen poistaminen onnistui";
            }
            else
            {
                $message = "Sattui odottamaton virhe, yritä myöhemmin uudelleen";
            }

            $connection->close();
            return $message;
        } 
    }
?>