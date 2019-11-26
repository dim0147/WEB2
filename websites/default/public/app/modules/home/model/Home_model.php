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
                return $result;
            }
            catch(PDOException $err){
                die($err);
            }
        }

        public function getProduct(){
            try{
                $sql = "SELECT p.id, p.name, p.description, p.image, p.current_bird_price, GROUP_CONCAT(c.name) AS category_name
                       FROM product p
                       LEFT JOIN product_category pc ON pc.product_id = p.id
                       LEFT JOIN category c ON c.id = pc.category_id
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
