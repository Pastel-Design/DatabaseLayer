<?php

namespace app\models;

use app\exceptions\DatabaseLayerException;

require("../vendor/autoload.php");


class ProductManager
{
    /**
     * @return array|false|mixed
     */
    public function SelectProducts()
    {
        $products = new DatabaseLayer;
        $products->selectTables(["product"]);
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
     */
    public function SelectProductsPriceFiltered($limit)
    {
        $products = new DatabaseLayer;
        $products->selectColumns(["product"], ["price"])->where("price",$limit,">");
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