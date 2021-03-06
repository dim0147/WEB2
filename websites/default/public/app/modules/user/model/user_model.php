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

    public function updateProfileUser($name, $username){
        try{
            $sql = "UPDATE user SET name=:name WHERE username=:username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":name", $name);
            $stmt->bindValue(":username", $username);
            $stmt->execute();
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function updateUserPassword($password, $username){
        try{
            $password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE user SET password=:password WHERE username=:username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":password", $password);
            $stmt->bindValue(":username", $username);
            $stmt->execute();
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function getBirdUserById($id){
        try{
            $sql = "SELECT p.current_bird_price, p.finish, p.image, p.name, p.bird_max_price, p.bird_minimum_price, p.hot_price, p.created_at, p.end_at, p.status,
                    pt.product_id, MAX(pt.price) AS user_price, pt.created_at AS time_bird,
                    GROUP_CONCAT(DISTINCT c.name SEPARATOR ', ') AS product_category
                    FROM product_bird pt
                    INNER JOIN product p ON p.id = pt.product_id
                    LEFT JOIN product_category pc ON pc.product_id = p.id
                    LEFT JOIN category c ON c.id = pc.category_id
                    WHERE pt.user_id =:id
                    GROUP BY p.id
                    ORDER BY pt.created_at DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result)
                return NULL;
            $result = $this->editPriceTimeAuction($result);
            if(!$result)
                return NULL;
            return $result;
        }
        catch(PDOException $err){
            die($err);
        }
    }
    
    public function getAuctionUserById($id, $approve = FALSE){
        try{
            if($approve)
                $approve = "AND p.approve = 1";
            else
                $approve = NULL;
            $sql = "SELECT p.id, p.bid_winner_id, p.finish, p.approve, p.current_bird_price, p.image, p.name, p.bird_max_price, p.bird_minimum_price, p.hot_price, p.created_at, p.end_at, p.status,
                    GROUP_CONCAT(c.name SEPARATOR ', ') AS product_category
                    FROM product p
                    LEFT JOIN product_category pc ON pc.product_id = p.id
                    LEFT JOIN category c ON c.id = pc.category_id
                    WHERE p.user_id =:id $approve
                    GROUP BY p.id
                    ORDER BY p.approve DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result)
                return NULL;
            $result = $this->editPriceTimeAuction($result);
            if(!$result)
                return NULL;
            return $result;
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function getReviewUserById($id){
        try{
            $sql = "SELECT pv.*, p.name, p.image FROM product_review pv 
                    INNER JOIN product p ON pv.product_id = p.id
                    WHERE pv.user_id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
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

    public function createNewAuction($name, $description, $image, $maxPrice = NULL, $minPrice = NULL, $hotPrice = NULL, $endDate, $status, $userID){
        try{
            $sql = "INSERT INTO
                    product(name, description, image, bird_max_price, bird_minimum_price, hot_price, end_at, status, user_id)
                    VALUES(:name, :description, :image, :maxPrice, :minPrice, :hotPrice, :endDate, :status, :userID)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":name", $name);
            $stmt->bindValue(":description", $description);
            $stmt->bindValue(":image", $image);
            $stmt->bindValue(":maxPrice", $maxPrice);
            $stmt->bindValue(":minPrice", $minPrice);
            $stmt->bindValue(":hotPrice", $hotPrice);
            $stmt->bindValue(":endDate", $endDate);
            $stmt->bindValue(":status", $status);
            $stmt->bindValue(":userID", $userID);
            $stmt->execute();
            return $this->pdo->lastInsertId();
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function editAuction($updateData, $id){
        try{
            $sql = '';
            if($updateData['image'] === FALSE)
                $sql = "UPDATE product SET name=:name, description=:description, bird_minimum_price=:minimum_price,
                        bird_max_price=:maximum_price, hot_price=:hot_price, end_at=:end_at, status=:status
                        WHERE id=:id";
            else
                $sql = "UPDATE product SET name=:name, description=:description, bird_minimum_price=:minimum_price,
                        bird_max_price=:maximum_price, hot_price=:hot_price, end_at=:end_at, status=:status, image=:image
                        WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":name", $updateData['name']);
            $stmt->bindValue(":description", $updateData['description']);
            $stmt->bindValue(":minimum_price", $updateData['minimum_price']);
            $stmt->bindValue(":maximum_price", $updateData['maximum_price']);
            $stmt->bindValue(":hot_price", $updateData['hot_price']);
            $stmt->bindValue(":end_at", $updateData['end_at']);
            $stmt->bindValue(":status", $updateData['status']);
            $stmt->bindValue(":id", $id);
            if($updateData['image'])
                $stmt->bindValue(":image", $updateData['image']);
            $stmt->execute();
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function getThumbnailAuction($listThumbnail, $prodID){
        try{
            //  Holder array
            $valPlaceHolder = [];
            //  Value to insert
            $valToInsert = [];
            //  Loop through list Image
            foreach($listThumbnail as $name){
                //  Place holder
                $placeHold = "?";
                //  Push place holder to array of placeholder
                array_push($valPlaceHolder, $placeHold);
                //  Push value for ?
                array_push($valToInsert, $name);
            }
            //  Add ',' for each element, return ?,?,?
            $valPlaceHolder = implode(',' ,$valPlaceHolder);
            //  Add product id for last "?""
            array_push($valToInsert, $prodID);
            $sql = "SELECT pt.name FROM product_thumbnail pt WHERE id IN ($valPlaceHolder) AND product_id = ?";
            $stmt = $this->pdo->prepare($sql);
            //  Execute query with value insert array
            $stmt->execute($valToInsert);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result)
                return FALSE;
            $newArr = [];
            foreach($result as $val){
                $newArr[] = $val['name'];
            }
            return $newArr;
        }catch(PDOException $err){
            die($err);
        }
    }

    public function uploadThumbnailAuction($listThumbnail, $prodID){
        try{
            //  Holder array
            $valPlaceHolder = [];
            //  Value to insert
            $valToInsert = [];
            //  Loop through list Image
            foreach($listThumbnail as $name){
                //  Place holder
                $placeHold = "(?,?)";
                //  Push place holder to array of placeholder
                array_push($valPlaceHolder, $placeHold);
                //  Push value for (?,?)
                array_push($valToInsert, $prodID);
                array_push($valToInsert, $name);
            }
            //  Add ',' for each element, return (?,?), (?,?)
            $valPlaceHolder = implode(',' ,$valPlaceHolder);
            $sql = "INSERT INTO product_thumbnail(product_id,name) VALUES $valPlaceHolder";
            $stmt = $this->pdo->prepare($sql);
            //  Execute query with value insert array
            $stmt->execute($valToInsert);
            return true;

        }catch(PDOException $err){
            die($err);
        }
    }

    public function deleteThumbnailAuction($listThumbnail, $prodID){
        try{
            //  Holder array
            $valPlaceHolder = [];
            //  Value to insert
            $valToInsert = [];
            //  Loop through list Image
            foreach($listThumbnail as $name){
                //  Place holder
                $placeHold = "?";
                //  Push place holder to array of placeholder
                array_push($valPlaceHolder, $placeHold);
                //  Push value for ?
                array_push($valToInsert, $name);
            }
            //  Add ',' for each element, return ?,?,?
            $valPlaceHolder = implode(',' ,$valPlaceHolder);
            //  Add product id for last "?""
            array_push($valToInsert, $prodID);
            $sql = "DELETE FROM product_thumbnail WHERE id IN ($valPlaceHolder) AND product_id = ?";
            $stmt = $this->pdo->prepare($sql);
            //  Execute query with value insert array
            $stmt->execute($valToInsert);
            return true;
        }catch(PDOException $err){
            die($err);
        }
    }

    public function createCategoryProduct($listCategory, $prodID){
        try{
            $placeHolder = [];  
            $valToInsert = [];  
            foreach($listCategory as $category){    //  Loop through list category
                $tempPlaceHold = "(?,?)";   //  PlaceHold for one row
                array_push($placeHolder, $tempPlaceHold);   //  Push to array placeHold
                array_push($valToInsert, $prodID);  //  Push value t (?,?) equal to product id and category
                array_push($valToInsert, $category);
            }
            $placeHolder = implode(',' ,$placeHolder);  //  Convert to text, add "," eg: (?,?),(?,?)
            $sql = "INSERT INTO product_category VALUES $placeHolder";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($valToInsert);
            return true;

        }catch(PDOException $err){
            die($err);
        }
    }

    public function removeCateProduct($listCategory, $prodID){
        try{
            $placeHolder = [];  
            $valToInsert = [];  
            foreach($listCategory as $category){    //  Loop through list category
                $tempPlaceHold = "?";   //  PlaceHold for one row
                array_push($placeHolder, $tempPlaceHold);   //  Push to array placeHold
                array_push($valToInsert, $category); //  Push value to "?""
            }
            array_push($valToInsert, $prodID);  // Push last ID
            $placeHolder = implode(',' ,$placeHolder);  //  Convert to text, add "," eg: (?,?),(?,?)
            $sql = "DELETE FROM product_category WHERE category_id IN($placeHolder) AND product_id =?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($valToInsert);
            return true;

        }catch(PDOException $err){
            die($err);
        }
    }

    public function getCate(){
        try{
            $sql = "SELECT * FROM category";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function getCateArr(){
        try{
            $sql = "SELECT * FROM category";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result)
                return false;
            $newArr = [];
            foreach($result as $cate){
                $newArr[$cate['id']] = $cate['name'];
            }
            return $newArr;
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function getAuctionById($id){
        try{
            $sql = "SELECT DISTINCT u.username, p.*, pt.name AS product_thumbnail, pt.id AS thumbnail_id, c.name AS category_name, c.id AS category_id
                    FROM product p 
                    LEFT JOIN user u ON u.id = p.user_id
                    LEFT JOIN product_thumbnail pt ON pt.product_id = p.id
                    LEFT JOIN product_category pc ON pc.product_id = p.id
                    LEFT JOIN category c ON c.id = pc.category_id
                    WHERE p.id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result)
                return false;
            $result = $this->mergeResultProd($result);
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
            unset($product['product_thumbnail']);
            unset($product['category_name']);
            unset($product['thumbnail_id']);
            unset($product['category_id']);
            $newArr = array_replace($newArr, $product);
        }

        if(!empty($newArr)){
            if(is_array($newArr['product_thumbnail']))
                $newArr['product_thumbnail'] = array_unique($newArr['product_thumbnail']);
            if(is_array($newArr['category_name']))
                $newArr['category_name'] = array_unique($newArr['category_name']);
            if(is_array($newArr['thumbnail_id']))
                $newArr['thumbnail_id'] = array_unique($newArr['thumbnail_id']);
            if(is_array($newArr['category_id']))
                $newArr['category_id'] = array_unique($newArr['category_id']);
            // Check thumbnail if have one thumbnail is a string
            if(!is_array($newArr['product_thumbnail']) && !empty($newArr['product_thumbnail'])){
                $idThumbnail = $newArr['thumbnail_id'];
                $nameThumbnail = $newArr['product_thumbnail'];
                $newArr['product_thumbnail'] = [$idThumbnail => $nameThumbnail];
            }
            // Check if is array and first element of product thumbnail not equal NULL
            else if(is_array($newArr['product_thumbnail']) && !empty(array_values($newArr['product_thumbnail'])[0])){ 
                //  Combine ID, "ID" is key and "name" is value, eg: [16 => pant.jpg]
                $newArr['product_thumbnail'] = array_combine($newArr['thumbnail_id'], $newArr['product_thumbnail']);
            }
            else{   //  Equal NULL
                $newArr['product_thumbnail'] = NULL;
            }

            //  Check category If have one category, only string
            if(!is_array($newArr['category_name']) && !empty($newArr['category_name'])){
                $idCate = $newArr['category_id'];
                $nameCate = $newArr['category_name'];
                $newArr['category_name'] = [$idCate => $nameCate];
            }
            // Check if is array and first element of category_name not equal NULL
            else if(is_array($newArr['category_name']) && !empty(array_values($newArr['category_name'])[0]) ){    //  Have more than 1 category
                //  Combine ID, "ID" is key and "name" is value, eg: [16 => Electronic]
                $newArr['category_name'] = array_combine($newArr['category_id'], $newArr['category_name']);
            }
            else{   //  Equal NULL
                $newArr['category_name'] = [];
            }
            //  If nobody bird yet
            if(empty($newArr['current_bird_price']))
                $newArr['current_bird_price'] = 0.00;
            //  Remove field when finish assign
            unset($newArr['thumbnail_id']);
            unset($newArr['category_id']);
            
            if(strtotime($newArr['end_at']) <= time())
                $newArr['finish'] = TRUE;
            $newArr['elapsed_time'] = calculateTime($newArr['created_at'], $newArr['end_at']);
        }
        return $newArr;
    }

    public function getProductWithUsr($id){
        try{
            $sql = "SELECT p.name, p.finish, p.end_at, p.image, p.current_bird_price, c.id as category_id, c.name as category_name, u.username
                    FROM product p
                    LEFT JOIN user u ON u.id=p.user_id
                    LEFT JOIN product_category pc ON pc.product_id = p.id
                    LEFT JOIN category c ON pc.category_id = c.id
                    WHERE p.id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(!$result)
                return false;
            return $this->mergeResultSample($result);
        }
        catch(PDOException $err){
            die($err);
        }
    }

    public function mergeResultSample($arrProd){
        $newArr = '';
        foreach($arrProd as $product){
            if(empty($newArr)){
                $newArr = $product;
                continue;
            }
            $newArr = array_merge_recursive($newArr, $product);;
            unset($product['category_name']);
            unset($product['category_id']);
            $newArr = array_replace($newArr, $product);
            $newArr['category_name'] = array_unique($newArr['category_name']);
            $newArr['category_id'] = array_unique($newArr['category_id']);
        }
        if(!empty($newArr)){

            //  Check category If have one category
            if(!is_array($newArr['category_name']) && !empty($newArr['category_name'])){
                $idCate = $newArr['category_id'];
                $nameCate = $newArr['category_name'];
                unset($newArr['category_name']);
                $newArr['category_name'] = [$idCate => $nameCate];
            }
            else if(is_array($newArr['category_name'])){    //  Have more than 1 category
                $newArr['category_name'] = array_combine($newArr['category_id'], $newArr['category_name']);
            }
            else{   //  Don't have
                $newArr['category_name'] = [];
            }
            unset($newArr['category_id']);
            if(strtotime($newArr['end_at']) <= time())
                $newArr['finish'] = true;
        }
        return $newArr;
    }

    public function removeAuction($product){
        try{
            $this->pdo->beginTransaction();
            //  Check if valid field
            if(!empty($product['product_thumbnail']) && is_array($product['product_thumbnail']))
                $this->deleteThumbnailAuction(array_keys($product['product_thumbnail']), $product['id']);
            //  Check if valid field
            if(!empty($product['category_name']) && is_array($product['category_name']))
                $this->removeCateProduct(array_keys($product['category_name']), $product['id']);
            $this->removeAllBirdProd($product['id']);
            $this->removeAllReviewProd($product['id']);
            $sql = "DELETE FROM product WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $product['id']);
            $stmt->execute();
            $this->pdo->commit();
            //  Delete on local storage
            if(is_array($product['product_thumbnail'])){
                foreach($product['product_thumbnail'] as $name){
                    deleteImage($name);
                }
            }
            deleteImage($product['image']);
        }
        catch(PDOException $err){
            $this->pdo->rollBack();
            die($err);
        }
    }

    public function removeAllBirdProd($id){
        try{
            $sql = "DELETE FROM product_bird WHERE product_id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            return;
        }
        catch (PDOException $err){
            die($err);
        }
    }

    public function removeAllReviewProd($id){
        try{
            $sql = "DELETE FROM product_review WHERE product_id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            return;
        }
        catch (PDOException $err){
            die($err);
        }
    }

    public function getLastInderId(){
        return $this->pdo->lastInsertId();
    }

    public function editPriceTimeAuction($arrBird){
        if(!is_array($arrBird))
            return FALSE;
        foreach($arrBird as $key => $bird){
            if(strtotime($bird['end_at']) <= time())
                    $arrBird[$key]['finish'] = TRUE;
            $arrBird[$key]['elapsed_time'] = calculateTime($bird['created_at'], $bird['end_at']);
            if((float)$bird['bird_max_price'] == 0.00)
                $arrBird[$key]['bird_max_price'] = 'No';
            if((float)$bird['bird_minimum_price'] == 0.00)
                $arrBird[$key]['bird_minimum_price'] = 'No';
            if((float)$bird['hot_price'] == 0.00)
                $arrBird[$key]['hot_price'] = 'No';
            if(empty($bird['current_bird_price']))
                $arrBird[$key]['current_bird_price'] = 0;
        }
        return $arrBird;
    }

}