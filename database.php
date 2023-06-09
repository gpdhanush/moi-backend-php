<?php
class Database{
    private $db_host = 'prakashdelight.in';
    
    // LOCAL DEVELOPMENT & TESTING
    // private $db_name = 'hellonew_gp_expense';
    // private $db_username = 'root';
    // private $db_password = '';

    // LIVE 
    private $db_name = 'prakashd_gp_db';
    private $db_username = 'prakashd_gp_db';
    private $db_password = 'GP@kt@1995';

    public function dbConnection() {
        try{
            $conn = new PDO('mysql:host='.$this->db_host.';dbname='.$this->db_name,$this->db_username,$this->db_password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }
        catch(PDOException $e){
            echo "Connection error ".$e->getMessage();
            exit;
        }
    }

    public function serverUrl() {
        // return $server_url = 'http://localhost/gp/expense';
        return $server_url = 'https://thaaimozhikalvi.com/api/expense'; 
    }
}
?>
