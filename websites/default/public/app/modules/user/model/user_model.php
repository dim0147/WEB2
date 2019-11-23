<?php 

class User_model extends Database{


    public function getUsrByUsrName($username){
        try{
            $sql = "SELECT * FROM user WHERE username = :username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function createNewUsr($username, $password, $name){
        try{
            $password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user(username, password, name, type) VALUES(:username, :password, :name, :type)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":username", $username);
            $stmt->bindValue(":password", $password);
            $stmt->bindValue(":name", $name);
            $stmt->bindValue(":type", "user");
            $stmt->execute();
            return true;
        }
        catch(PDOException $err){
            die($err);
        }

    }
}