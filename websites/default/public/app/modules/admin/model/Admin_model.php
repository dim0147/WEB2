<?php 

class Admin_model extends Database{

    function __construct($table = 'user'){
        parent::__construct($table);
    }

    public function checkAdminExist($username){
        try{
            $sql = "SELECT * FROM user WHERE username = :username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function createAdmin($username, $password, $name){
        try{
            $password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO user VALUES (DEFAULT, :username, :password, :name, DEFAULT, :type)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':type', 'admin');
            $stmt->execute();
            return true;
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function checkCateExist($name){
        try{
            $sql = "SELECT * FROM category WHERE name = :name";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function addCate($name){
        try{
            $sql = "INSERT INTO category VALUES(DEFAULT, :name)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            return true;
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function editCate($oldName, $newName){
        try{
            $sql = "UPDATE category SET name = :newName WHERE name = :oldName";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':newName', $newName, PDO::PARAM_STR);
            $stmt->bindParam(':oldName', $oldName, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function removeCate($name){
        try{
            $sql = "DELETE FROM category WHERE name = :name";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        }
        catch(PDOException $err){
            die($err);
        }
    }
}
