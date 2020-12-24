<?php

namespace app\models;

use app\config\DbConfig;
use app\exceptions\DatabaseLayerException;
use PDO;

require("../vendor/autoload.php");


class ProductManager
{
    /**
     * @return array|false|mixed
     * @throws DatabaseLayerException
     */
    public function SelectProducts()
    {
        $products = new DatabaseLayer(DbConfig::$credentials,DbConfig::$settings);
        $products->selectTables(["product"]);
        $products->setFetchMethod(2);
        $products->setFetchMode(PDO::FETCH_ASSOC);
        return $products->execute();
    }

    /**
     * @param $name
     *
     * @return array|false|mixed
     */
    public function SelectProductsByName($name)
    {
        $products = new DatabaseLayer;
        $products->selectTables(["product"])->where("name","%".$name."%","LIKE");
        return $products->execute();
    }

    /**
     * @return array|false|mixed
     */
    public function SelectProductsPrice()
    {
        $products = new DatabaseLayer;
        $products->selectColumns(["product"], ["price","name"]);
        return $products->execute();
    }

    /**
     * @param $limit
     *
     * @return array|false|mixed
     * @throws DatabaseLayerException
     */
    public function SelectProductsPriceFiltered($limit,$name)
    {
        $products = new DatabaseLayer;
        $products->selectColumns(["product"], ["price"])->distinct()->where("price",$limit,"<")->or("name","%".$name."%","LIKE");
        $products->setFetchMethod("fetchAll");
        return $products->execute();
    }

    /**
     * @param $productId
     *
     * @return array|false|mixed
     * @throws DatabaseLayerException
     */
    public function selectProductWithImages($productId){
        $product = new DatabaseLayer;
        $product->selectColumns(["product"], ["product.name AS ","i.name"])->join("image_has_product ihp","ihp.product_id","product.id")->join("image i","ihp.image_id","i.id")->where("product.id",$productId);
        $product->setFetchMethod("fetchAll");
        return $product->execute();
    }
}