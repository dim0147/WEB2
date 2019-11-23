<?php 

    class Database {

        protected $host = DB_HOST;
        protected $dbName = DB_DB;
        protected $username = DB_USERNAME;
        protected $password = DB_PASSWORD; 

        protected $pdo;
        
        function __construct(){
            try{
                $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->username, $this->password);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $ex){
                die($ex);
            }
        }

    }
