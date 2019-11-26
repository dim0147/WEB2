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
            $newArr['product_thumbnail'] = array_unique($newArr['product_thumbnail']);
            $newArr['category_name'] = array_unique($newArr['category_name']);
            $newArr['thumbnail_id'] = array_unique($newArr['thumbnail_id']);
            $newArr['category_id'] = array_unique($newArr['category_id']);
        }

        if(!empty($newArr)){
            // Check thumbnail If have one thumbnail
            if(!is_array($newArr['product_thumbnail']) && !empty($newArr['product_thumbnail'])){
                $idThumbnail = $newArr['thumbnail_id'];
                $nameThumbnail = $newArr['product_thumbnail'];
                unset($newArr['product_thumbnail']);
                $newArr['product_thumbnail'] = [$idThumbnail => $nameThumbnail];
            }
            else if(is_array($newArr['product_thumbnail']) && !empty(array_values($newArr['product_thumbnail'])[0])){    //  Have more than 1 category
               
                $newArr['product_thumbnail'] = array_combine($newArr['thumbnail_id'], $newArr['product_thumbnail']);
            }
            else{   //  Don't have
                echo "ok";
                $newArr['product_thumbnail'] = NULL;
            }

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

            if(empty($newArr['current_bird_price']))
                $newArr['current_bird_price'] = 0.00;
            unset($newArr['thumbnail_id']);
            unset($newArr['category_id']);
            $newArr['elapsed_time'] = calculateTime($newArr['created_at'], $newArr['end_at']);
        }
        return $newArr;
    }

    public function getProductWithUsr($id){
        try{
            $sql = "SELECT p.name, p.image, c.id as category_id, c.name as category_name, u.username
                    FROM product p
                    LEFT JOIN user u ON u.id=p.user_id
                    LEFT JOIN product_category pc ON pc.product_id = p.id
                    INNER JOIN category c ON pc.category_id = c.id
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


}