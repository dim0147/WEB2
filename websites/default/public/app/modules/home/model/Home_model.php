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
                $sql = "SELECT p.id, p.name, p.description, p.image, p.current_bird_price, c.name AS category_name
                       FROM product p
                       LEFT JOIN product_category pc ON pc.product_id = p.id
                       LEFT JOIN category c ON c.id = pc.category_id
                       group by p.id
                       ORDER BY p.created_at DESC
                       LIMIT 10";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if(!$result)
                    return [];
                return  $this->mergeProd($result);
            }catch(PDOException $err){

            }
        }

        public function mergeProd($arrProd){
            //  Create empty array
            $newArray = [];
            //  Loop through list product
            foreach($arrProd as $product){
                //  Get product id
                $idProduct = $product['id'];
                //  If id not found in array, assign this id with value is $product['id']
                if(!array_key_exists($idProduct, $newArray)){
                    $newArray[$idProduct] = $product;
                    continue;
                }
                //  Merge current id product with product get from DB
                $newArray[$idProduct] = array_merge_recursive($newArray[$idProduct], $product);
                //  Unset the category name so don't overwrite the category
                unset($product['category_name']);
                // Replace array but don't replace category name
                $newArray[$idProduct] = array_replace($newArray[$idProduct], $product);
            }
            //  Convert category_name and current_bird for view
            if(!empty($newArray)){
                foreach($newArray as $idProduct => $product){
                    //  convert category and bird for view
                if(is_array($product['category_name']))
                    $newArray[$idProduct]['category_name'] = implode(' , ',$product['category_name']);
                if(empty($product['current_bird_price']))
                    $newArray[$idProduct]['current_bird_price'] = 0.00;
                }
            }
            return $newArray;
        }

    }
