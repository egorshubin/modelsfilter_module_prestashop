<?php

/**
 * Created by PhpStorm.
 * User: egors
 * Date: 20.04.2018
 * Time: 12:55
 */
require_once(_PS_MODULE_DIR_ . 'modelsfilter/classes/DbQueries.php');
require_once(_PS_MODULE_DIR_ . 'modelsfilter/classes/FeaturesVars.php');

class CategoryController extends CategoryControllerCore
{

    /*
    * module: modelsfilter
    * date: 2018-04-20 14:02:38
    * version: 1.0.0
    */
    public function getCanonicalURL()
    {
        $fMarkaName = FeaturesVars::getInstance()->getFMarkaName();
        $fModelName = FeaturesVars::getInstance()->getFModelName();
        $fModifName = FeaturesVars::getInstance()->getFModifName();

        if (!empty($_GET[$fModifName])){
            return $this->context->link->getCategoryLink($this->category) . '?' . $fModifName . '=' . $_GET[$fModifName];
        }
        else if (!empty($_GET[$fModelName])) {
            return $this->context->link->getCategoryLink($this->category) . '?' . $fModelName . '=' . $_GET[$fModelName];
        }
        else if (!empty($_GET[$fMarkaName])) {
            return $this->context->link->getCategoryLink($this->category) . '?' . $fMarkaName . '=' . $_GET[$fMarkaName];
        }
        else {
            return $this->context->link->getCategoryLink($this->category);
        }

    }

    /*
    * module: modelsfilter
    * date: 2018-04-20 14:02:38
    * version: 1.0.0
    */
    protected function getTemplateVarCategory()
    {
        $fMarkaName = FeaturesVars::getInstance()->getFMarkaName();
        $fModelName = FeaturesVars::getInstance()->getFModelName();
        $fModifName = FeaturesVars::getInstance()->getFModifName();

        $category = $this->objectPresenter->present($this->category);
        $category['image'] = $this->getImage(
            $this->category,
            $this->category->id_image
        );

        if (!empty($_GET[$fModifName])){
            $category['description'] = '';
            $name = DbQueries::getNameBySlug($_GET[$fModifName])[0]['feature_value'];
            $category['name'] = $category['name'] . ' для ' . $name;
        }
        else if (!empty($_GET[$fModelName])) {
            $category['description'] = '';
            $name = DbQueries::getNameBySlug($_GET[$fModelName])[0]['feature_value'];
            $category['name'] = $category['name'] . ' для ' . $name;
        }
        else if (!empty($_GET[$fMarkaName])) {
            $category['description'] = '';
            $name = DbQueries::getNameBySlug($_GET[$fMarkaName])[0]['feature_value'];
            $category['name'] = $category['name'] . ' для ' . $name;
        }

        return $category;
    }
}