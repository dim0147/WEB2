<?php 

    class Home_model extends Database{

        function __construct($table = 'category'){
            parent::__construct($table);
        }

        public function getCate(){
            try{
                $sql = "SELECT name FROM category";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch(PDOException $err){
                die($err);
            }
        }

    }