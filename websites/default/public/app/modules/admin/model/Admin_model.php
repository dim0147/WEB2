<?php 

class Admin_model extends Database{

    function __construct(){
        parent::__construct();
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

    public function getCate(){
        try{
            $sql = "SELECT * FROM category";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result)
                return NULL;
            return $result;
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

    public function getAccount(){
        try{
            $sql = "SELECT * FROM user ORDER BY Username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result)
                return NULL;
            return $result;
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function removeAccount($id){
        try{
            $this->pdo->beginTransaction();
            $products = $this->getProductOfUser($id);
            $listThumbnail = NULL;
            $listImage = NULL;
            if(!empty($products['id']) && is_array($products['id'])){
                $listThumbnail = $this->getThumbnailOfProduct($products['id']);
                $this->removeThumbnailProduct($products['id']);
                $this->removeCategoryProduct($products['id']);
                $this->removeReviewProduct($products['id']);
                $this->removeBirdProduct($products['id']);
                $listImage = $products['name'];
            }
            $this->removeProductOfUser($id);
            $this->removeBirdOfUser($id);
            $this->removeReviewOfUser($id);
            $sql = "DELETE FROM user WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            $this->pdo->commit();
            if(!empty($listThumbnail) && is_array($listThumbnail)){
                foreach($listThumbnail as $name){
                    deleteImage($name);
                }
            }
            if(!empty($listImage) && is_array($listImage)){
                foreach($listImage as $name){
                    deleteImage($name);
                }
            }
        }
        catch(PDOException $err){
            $this->pdo->rollBack();
            die($err);
        }
    }

    public function removeBirdOfUser($id){
        $sql = "DELETE FROM product_bird WHERE user_id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
    }

    public function removeReviewOfUser($id){
        $sql = "DELETE FROM product_review WHERE user_id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
    }

    public function removeProductOfUser($id){
        $sql = "DELETE FROM product WHERE user_id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();
    }

    public function removeThumbnailProduct($listId){
        $listId = implode(',' , $listId);
        $sql = "DELETE FROM product_thumbnail WHERE product_id IN($listId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return;
    }

    public function removeCategoryProduct($listId){
        $listId = implode(',' , $listId);
        $sql = "DELETE FROM product_category WHERE product_id IN($listId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return;
    }

    public function removeReviewProduct($listId){
        $listId = implode(',' , $listId);
        $sql = "DELETE FROM product_review WHERE product_id IN($listId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return;
    }

    public function removeBirdProduct($listId){
        $listId = implode(',' , $listId);
        $sql = "DELETE FROM product_bird WHERE product_id IN($listId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return;
    }

    public function getProductOfUser($userID){
        $sql = "SELECT id, image FROM product WHERE user_id=:userID";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":userID", $userID);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$result || !is_array($result))
            return NULL;
        $listID = [
            'id' => [],
            'name' => []
        ];
        foreach($result as $val){
            array_push($listID['id'], $val['id']);
            array_push($listID['name'], $val['image']);
        }
        return $listID;
    }

    public function getThumbnailOfProduct($listId){
        $listId = implode(',' , $listId);
        $sql = "SELECT name FROM product_thumbnail WHERE product_id IN($listId)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(!$result)
            return NULL;
        $listName = [];
        foreach($result as $val){
            array_push($listName, $val['name']);
        }
        return $listName;
    }

    public function getAuction(){
        try{
            $sql = "SELECT p.id, p.approve, p.name, p.image, p.bird_max_price, p.bird_minimum_price, p.hot_price, p.created_at, p.end_at, p.current_bird_price, p.status,u.username
                    FROM product p
                    INNER JOIN user u ON p.user_id = u.id
                    GROUP BY p.id
                    ORDER BY p.approve ASC, p.created_at ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result) 
                return NULL;
            foreach($result as $key => $product){
                if((float)$product['bird_max_price'] == 0.00 || empty($product['bird_max_price']))
                    $result[$key]['bird_max_price'] = 'No';
                if((float)$product['bird_minimum_price'] == 0.00 || empty($product['bird_minimum_price']))
                    $result[$key]['bird_minimum_price'] = 'No';
                if((float)$product['hot_price'] == 0.00 || empty($product['hot_price']))
                    $result[$key]['hot_price'] = 'No';
                if(empty($product['current_bird_price']))
                    $result[$key]['current_bird_price'] = "No";
                if(strtotime($product['end_at']) <= strtotime($product['created_at']))
                    $result[$key]['elapsed_time'] = 'End Bird!';
                else
                    $result[$key]['elapsed_time'] = calculateTime($product['created_at'], $product['end_at']);
            }
            return $result;
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function getAuctionNeedApprove(){
        try{
            $sql = "SELECT COUNT(id) as total FROM product WHERE approve = 0";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result || !is_array($result) || !isset($result[0]['total']))
                return NULL;
            if((int)$result[0]['total'] === 0)
                return NULL;
            return (int)$result[0]['total'];
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function setApproveAuction($id, $approve){
        try{
            $sql = "UPDATE product SET approve=:approve WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":approve", $approve, PDO::PARAM_INT);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        catch(PDOException $err){
            die($err);
        }
    }
}
