<?php 
    class Product_model extends Database{

        function __construct(){
            parent::__construct();
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

        public function checkProductExist($id){
            try{
                $sql = "SELECT id, approve, current_bird_price, bird_max_price, bird_minimum_price, end_at, user_id FROM product WHERE id=:id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":id", $id);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch(PDOException $err){
                die($err);
            }
        }

        public function getProductById($id){
            try{
                $sql = "SELECT p.id, p.approve, p.name, p.description, p.image, p.bird_max_price, p.bird_minimum_price,
                        p.hot_price, p.created_at, p.end_at, p.status, p.current_bird_price, 
                        pt.name AS product_thumbnail,   
                        c.name AS category_name, 
                        u.name AS own_product, u.username AS own_product_username
                        FROM product p
                        LEFT JOIN product_thumbnail pt ON pt.product_id = p.id
                        LEFT JOIN product_category pc ON pc.product_id = p.id
                        LEFT JOIN category c ON c.id = pc.category_id
                        LEFT JOIN product_bird pb on pb.id = p.bid_winner_id
                        LEFT JOIN user u ON u.id = p.user_id
                        WHERE p.id = :id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":id", $id);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!$result)
                    return false;
                return $this->mergeResultProd($result);
            }
            catch(PDOException $err){
                die($err);
            }
        }

        public function getBirdOfAuction($idAuction){
            try{
                $sql = "SELECT pb.price, pb.created_at, pb.isHot, 
                        u.username, u.name
                        FROM product_bird pb
                        INNER JOIN user u ON u.id = pb.user_id
                        WHERE pb.product_id =:idAuction
                        ORDER BY pb.price DESC";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":idAuction", $idAuction);
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

        public function mergeResultProd($arrProd){
            $newArr = '';
            foreach($arrProd as $product){
                if(empty($newArr)){
                    $newArr = $product;
                    continue;
                }
                $newArr = array_merge_recursive($newArr, $product);
                //  No overwrite 
                unset($product['product_thumbnail']);
                unset($product['category_name']);
                $newArr = array_replace($newArr, $product);
            }
            if(!empty($newArr)){
                //  Check if is array and the first value not null
                if(is_array($newArr['category_name']) && !(array_filter($newArr['category_name']) == [])){
                    $newArr['category_name'] = array_unique($newArr['category_name']);  //  Remove duplicate value
                    $newArr['category_name'] = implode(' , ', $newArr['category_name']);    //  Return a string
                }
                //  Check if not empty and is array but first value of array is null
                else if(!empty($newArr['category_name']) && is_array($newArr['category_name']) && array_filter($newArr['category_name']) == [])
                    $newArr['category_name'] = NULL;


                //  Check if is array and the first value not null
                if(is_array($newArr['product_thumbnail']) && !(array_filter($newArr['product_thumbnail']) == []))
                    $newArr['product_thumbnail'] = array_unique($newArr['product_thumbnail']);
                //  Check if not empty and is array but first value of array is null
                else if(!empty($newArr['product_thumbnail']) && is_array($newArr['product_thumbnail']) && array_filter($newArr['product_thumbnail']) == [])
                    $newArr['product_thumbnail'] = NULL;
                //  Check if not empty and is string
                else if(!empty($newArr['product_thumbnail']) && is_string($newArr['product_thumbnail']))
                    $newArr['product_thumbnail'] = [$newArr['product_thumbnail']];
                else
                    $newArr['product_thumbnail'] = NULL;


                if(empty($newArr['current_bird_price']))
                    $newArr['current_bird_price'] = 0.00;
            
                if(strtotime($newArr['end_at']) <= strtotime($newArr['created_at']))
                    $newArr['elapsed_time'] = FALSE;
                else
                $newArr['elapsed_time'] = calculateTime($newArr['created_at'], $newArr['end_at']);
                
            }
            return $newArr;
        }

        public function createProductBird($prodID, $price, $userID, $isHot = FALSE){
            try{
                $sql = "INSERT INTO product_bird(product_id, price, user_id, isHot)
                        VALUES(:prodID, :price, :userID, :isHot)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":prodID", $prodID);
                $stmt->bindValue(":price", $price);
                $stmt->bindValue(":userID", $userID);
                $stmt->bindValue(":isHot", $isHot);
                $stmt->execute();
            }
            catch(PDOException $err){
                die($err);
            }
        }

        public function updateBirdPriceProd($id, $amount){
            try{
                $amount = (float)$amount;
                $sql = "UPDATE product SET current_bird_price =:amount WHERE id =:id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":id", $id);
                $stmt->bindValue(":amount", $amount);
                $stmt->execute();
            }
            catch(PDOException $err){
                die($err);
            }
        }

        public function getUserByUsrName($username){
            try{
                $sql = "SELECT * from user WHERE username = :username";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":username", $username);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch(PDOException $err){
                die($err);
            }

        }

        public function createReview($prodID, $userID, $comment){
            try{
                $sql = "INSERT INTO product_review(product_id, user_id, comment) VALUES (:prodID, :userID, :comment)";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":prodID", $prodID);
                $stmt->bindValue(":userID", $userID);
                $stmt->bindValue(":comment", $comment);
                $stmt->execute();
            }
            catch(PDOException $err){
                die($err);
            }

        }

        public function getReview($id){
            try{
                $sql = "SELECT pv.user_id, pv.comment, pv.created_at,u.name
                FROM product_review as pv
                INNER JOIN user u ON u.id=pv.user_id
                 WHERE product_id=:id";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":id", $id);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            catch(PDOException $err){
                die($err);
            }
        }

    }
