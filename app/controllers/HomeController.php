<?php

namespace app\controllers;

use app\models\ProductManager;

/**
 * Controller HomeController
 *
 * @package app\controllers
 */
class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Sets default homepage
     *
     * @param array      $params
     * @param array|null $gets
     *
     * @return void
     */
    public function process(array $params, array $gets = null)
    {
        $product = new ProductManager();
        //var_dump($product->SelectProducts());
        echo("<br>");
        var_dump($product->SelectProductsByName("Omen"));
        echo("<br>");
        //var_dump($product->SelectProductsPrice());
        echo("<br>");
        //var_dump($product->SelectProductsPriceFiltered(100));


        $this->head['page_title'] = "";
        $this->head['page_keywords'] = "";
        $this->head['page_description'] = "";
        $this->setView('default');
    }
}
