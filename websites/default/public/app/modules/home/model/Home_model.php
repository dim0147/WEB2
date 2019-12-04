<?php 

    class Home_model extends Database{

        function __construct(){
            parent::__construct();
        }

        public function getCate(){
            try{
                $sql = "SELECT name FROM category";
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

        public function getProduct(){
            try{
                $sql = "SELECT u.name AS name_user, u.username, p.id, p.name, p.description, p.image, p.current_bird_price, GROUP_CONCAT(c.name) AS category_name
                       FROM product p
                       LEFT JOIN product_category pc ON pc.product_id = p.id
                       LEFT JOIN category c ON c.id = pc.category_id
                       LEFT JOIN user u ON u.id = p.user_id
                       WHERE p.end_at > NOW()
                       AND p.status = 'Open'
                       AND p.approve = 1
                       AND p.finish = 0
                       GROUP BY p.id
                       ORDER BY p.created_at DESC
                       LIMIT 10";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!$result)
                    return [];
                return  $this->priceConvert($result);
            }catch(PDOException $err){

            }
        }

        public function getProductByCategory($category){
            try{
                $sql = "SELECT p.id, p.name, p.description, p.image, p.current_bird_price, c.name AS category_name
                       FROM product p
                       INNER JOIN product_category pc ON pc.product_id = p.id
                       INNER JOIN category c ON c.id = pc.category_id
                       WHERE c.name=:category
                       AND p.end_at > NOW()
                       AND p.status = 'Open'
                       AND p.approve = 1
                       AND p.finish = 0
                       GROUP BY p.id
                       ORDER BY p.created_at DESC";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":category", $category);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!$result)
                    return NULL;
                return  $this->priceConvert($result);
            }catch(PDOException $err){
                die($err);
            }
        }

        public function getProductByString($string){
            try{
                $string = "%$string%";
                $sql = "SELECT p.id, p.name, p.description, p.image, p.current_bird_price, c.name AS category_name
                       FROM category c
                       INNER JOIN product_category pc ON pc.category_id = c.id
                       INNER JOIN product p ON p.id = pc.product_id
                       WHERE p.name
                       LIKE :string
                       AND p.end_at > NOW()
                       AND p.status = 'Open'
                       AND p.approve = 1
                       AND p.finish = 0
                       GROUP BY p.id
                       ORDER BY p.created_at DESC";
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":string", $string, PDO::PARAM_STR);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!$result)
                    return NULL;
                return  $this->priceConvert($result);
            }catch(PDOException $err){
                die($err);
            }
        }

        public function priceConvert($result){
            if(!is_array($result))
                return [];
            foreach($result as $key => $productTemp){
                if(empty($productTemp['current_bird_price']))
                    $result[$key]['current_bird_price'] = 0.00;
            }
            return $result;
        }

    }
