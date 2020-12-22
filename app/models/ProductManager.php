<?php

namespace app\models;

require("../vendor/autoload.php");


class ProductManager
{

    public function SelectProducts()
    {
        $products = new DatabaseLayer;
        $products->selectTables(["product"]);
        return $products->execute();
    }

    public function SelectProductsByName($name)
    {
        $products = new DatabaseLayer;
        $products->selectTables(["product"])->where("name","%".$name."%","LIKE");
        return $products->execute();
    }

    public function SelectProductsPrice()
    {
        $products = new DatabaseLayer;
        $products->selectColumns(["product"], ["price","name"]);
        return $products->execute();
    }

    public function SelectProductsPriceFiltered($limit)
    {
        $products = new DatabaseLayer;
        $products->selectColumns(["product"], ["price"])->where("price",$limit,">");
        return $products->execute();
    }
    public function selectProductWithImages($productId){
        $product = new DatabaseLayer;
        $product->selectColumns(["product"], [""]);
    }
}