<?php

/**
 * Created by PhpStorm.
 * User: egors
 * Date: 20.04.2018
 * Time: 12:47
 */

class Meta extends MetaCore
{
    public static function getCategoryMetas($idCategory, $idLang, $pageName, $title = '')
    {
        require_once(_PS_MODULE_DIR_ . 'modelsfilter/classes/DbQueries.php');
        require_once(_PS_MODULE_DIR_ . 'modelsfilter/classes/FeaturesVars.php');

        if (!empty($title)) {
            $title = ' - '.$title;
        }
        $pageNumber = (int)Tools::getValue('p');
        $sql = 'SELECT `name`, `meta_title`, `meta_description`, `meta_keywords`, `description`
				FROM `'._DB_PREFIX_.'category_lang` cl
				WHERE cl.`id_lang` = '.(int) $idLang.'
					AND cl.`id_category` = '.(int) $idCategory.Shop::addSqlRestrictionOnLang('cl');

        $cacheId = 'Meta::getCategoryMetas'.(int) $idCategory.'-'.(int) $idLang;
        if (!Cache::isStored($cacheId)) {

            if ($row = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql)) {


                $fMarkaName = FeaturesVars::getInstance()->getFMarkaName();
                $fModelName = FeaturesVars::getInstance()->getFModelName();
                $fModifName = FeaturesVars::getInstance()->getFModifName();

                if (!empty($_GET[$fModifName])){
                    $name = DbQueries::getNameBySlug($_GET[$fModifName])[0]['feature_value'];
                    $tArray = DbQueries::getTitleDescs($idCategory);
                    $new_row = [
                        'name' => $row['name'],
                        'meta_title' => $tArray[0]['title_1'] . ' ' . $name . ' ' . $tArray[0]['title_2'],
                        'meta_description' => $tArray[0]['description_1'] . ' ' . $name  . ' ' . $tArray[0]['description_2'],
                    ];
                    $result = Meta::completeMetaTags($new_row, $new_row['name']);
                }
                else if (!empty($_GET[$fModelName])) {
                    $name = DbQueries::getNameBySlug($_GET[$fModelName])[0]['feature_value'];
                    $tArray = DbQueries::getTitleDescs($idCategory);
                    $new_row = [
                        'name' => $row['name'],
                        'meta_title' => $tArray[0]['title_1'] . ' ' . $name . ' ' . $tArray[0]['title_2'],
                        'meta_description' => $tArray[0]['description_1'] . ' ' . $name  . ' ' . $tArray[0]['description_2'],
                    ];
                    $result = Meta::completeMetaTags($new_row, $new_row['name']);
                }
                else if (!empty($_GET[$fMarkaName])) {
                    $name = DbQueries::getNameBySlug($_GET[$fMarkaName])[0]['feature_value'];
                    $tArray = DbQueries::getTitleDescs($idCategory);
                    $new_row = [
                        'name' => $row['name'],
                        'meta_title' => $tArray[0]['title_1'] . ' ' . $name . ' ' . $tArray[0]['title_2'],
                        'meta_description' => $tArray[0]['description_1'] . ' ' . $name  . ' ' . $tArray[0]['description_2'],
                    ];
                    $result = Meta::completeMetaTags($new_row, $new_row['name']);
                }
                else {
                    if (empty($row['meta_description'])) {
                        $row['meta_description'] = strip_tags($row['description']);
                    }

                    // Paginate title
                    if (!empty($row['meta_title'])) {
                        $row['meta_title'] = $title.$row['meta_title'].(!empty($pageNumber) ? ' ('.$pageNumber.')' : '');
                    } else {
                        $row['meta_title'] = $row['name'].(!empty($pageNumber) ? ' ('.$pageNumber.')' : '');
                    }

                    if (!empty($title)) {
                        $row['meta_title'] = $title.(!empty($pageNumber) ? ' ('.$pageNumber.')' : '');
                    }

                    $result = Meta::completeMetaTags($row, $row['name']);
                }

            } else {
                $result = Meta::getHomeMetas($idLang, $pageName);
            }
            Cache::store($cacheId, $result);

            return $result;
        }

        return Cache::retrieve($cacheId);
    }

}