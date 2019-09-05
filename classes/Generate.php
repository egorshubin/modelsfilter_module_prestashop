<?php

/**
 * Created by PhpStorm.
 * User: egors
 * Date: 23.04.2018
 * Time: 12:44
 */
class Generate
{
    public static function updateNames() {
        $fMarkaId = FeaturesVars::getInstance()->getFMarkaId();
        $fModelId = FeaturesVars::getInstance()->getFModelId();
        $fModifId = FeaturesVars::getInstance()->getFModifId();

        DbQueries::createFDirect();
        $array = DbQueries::selectingForAdding($fMarkaId, $fModelId, $fModifId);
        if ((!empty($array)) && $array != false) {
            foreach ($array as $row) {
                $trans = Tools::str2url($row['value_lang']);
                if (!DbQueries::addingNew($row['id_final'], $row['value_lang'], $trans)) {
                    return 'Adding Direct Failed! Report to the developer';
                }
            }
        }

        $array2 = DbQueries::selectingForUpdating($fMarkaId, $fModelId, $fModifId);
        if ((!empty($array2)) && $array2 != false) {
            foreach ($array2 as $row) {
                $trans = Tools::str2url($row['valu']);
                if (!DbQueries::updatingDirectFeatures($row['idi'], $row['valu'], $trans)) {
                    return 'Updating Direct Failed! Report to the developer';
                }
            }
        }

        $array3 = DbQueries::getFeaturesAllActive($fMarkaId, $fModelId, $fModifId);
        if ((!empty($array3)) && $array3 != false) {
            if (!DbQueries::updateDbActive('mf_reserve_features')) {
                return 'Updating Active Database step 1 failed! Report to the developer';
            }
            if (!DbQueries::deleteFeaturesBothTables('mf_active_features')) {
                return 'Cleaning Active Features Database failed! Report to the developer';
            }
            $array4 = [];
            $array5 = [];
            foreach ($array3 as $row) {
                if (!in_array($row['id_modif'],$array5)) {
                    $row_array = ['id_marka' => $row['id_marka'], 'marka' => $row['marka'], 'id_model' => $row['id_model'], 'model' => $row['model'], 'id_modif' => $row['id_modif'], 'modif' => $row['modif']];
                    array_push($array4, $row_array);
                    array_push($array5, $row['id_modif']);
                }
            }


            foreach ($array4 as $row) {
                if (!DbQueries::insertFeaturesBothTables('mf_active_features', $row['id_marka'], $row['marka'], $row['id_model'], $row['model'], $row['id_modif'], $row['modif'])) {
                    return 'Inserting into Active Features Failed! Report to the developer';
                }
            }
            if (!DbQueries::updateDbActive('mf_active_features')) {
                return 'Updating Active Database step 2 failed! Report to the developer';
            }

            if (!DbQueries::deleteFeaturesBothTables('mf_reserve_features')) {
                return 'Cleaning Active Features Database failed! Report to the developer';
            }


            foreach ($array4 as $row) {
                if (!DbQueries::insertFeaturesBothTables('mf_reserve_features', $row['id_marka'], $row['marka'], $row['id_model'], $row['model'], $row['id_modif'], $row['modif'])) {
                    return 'Inserting into Active Features Failed! Report to the developer';
                }
            }
        }
        else {
            return 'Getting all features failed! Report to the developer';
        }

        return 'Обновление прошло успешно';
    }
}