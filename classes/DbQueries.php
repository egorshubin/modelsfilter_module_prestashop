<?php

/**
 * Created by PhpStorm.
 * User: egors
 * Date: 19.04.2018
 * Time: 10:53
 */
class DbQueries
{
    //Tables for installation

    public static function createMfFeatures() {
        $query = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."mf_features (
            id int(10) NOT NULL,
            id_feature int(10) NOT NULL,
            PRIMARY KEY (id)
        )  ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8";
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function createMfCategories() {
        $query = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."mf_categories (
            id_category int(10) NOT NULL,
            title_1 TINYTEXT NOT NULL,
            title_2 TINYTEXT NOT NULL,
            description_1 TINYTEXT NOT NULL,
            description_2 TINYTEXT NOT NULL,
            active TINYINT NOT NULL,
            PRIMARY KEY (id_category)
        )  ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8";
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function insertMfFeatures($id) {
        $query = "INSERT INTO "._DB_PREFIX_."mf_features (id) VALUES ({$id})";
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function dropMfFeatures() {
        $query = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'mf_features';
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function dropMfCategories() {
        $query = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'mf_categories';
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function createMfMain() {
        $query = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."mf_main (
            id int(10) NOT NULL,
            id_category int(10) NOT NULL,
            PRIMARY KEY (id)
        )  ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8";
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function insertMfMain() {
        $query = "INSERT INTO "._DB_PREFIX_."mf_main (id) VALUES ('1')";
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function dropMfMain() {
        $query = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'mf_main';
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function createDbActive() {
        $query = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."mf_db_active(
            id int(10) NOT NULL,
            db TINYTEXT NOT NULL, 
            PRIMARY KEY (id)) ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8";
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function insertDbActive() {
        $query = "INSERT INTO "._DB_PREFIX_."mf_db_active (id, db) VALUES ('1', 'mf_active_features')";
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function dropDbActive() {
        $query = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'mf_db_active';
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function createMfActiveFeatures() {
        $query = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."mf_active_features (
            id_marka int(10) NOT NULL,
            marka TINYTEXT NOT NULL,
            id_model int(10) NOT NULL,
            model TINYTEXT NOT NULL,
            id_modif int(10) NOT NULL,
            modif TINYTEXT NOT NULL,
            PRIMARY KEY (id_modif)
        )  ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8";
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function createMfReserveFeatures() {
        $query = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."mf_reserve_features (
            id_marka int(10) NOT NULL,
            marka TINYTEXT NOT NULL,
            id_model int(10) NOT NULL,
            model TINYTEXT NOT NULL,
            id_modif int(10) NOT NULL,
            modif TINYTEXT NOT NULL,
            PRIMARY KEY (id_modif)
        )  ENGINE="._MYSQL_ENGINE_." DEFAULT CHARSET=utf8";
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function dropMfActiveFeatures() {
        $query = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'mf_active_features';
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function dropMfReserveFeatures() {
        $query = 'DROP TABLE IF EXISTS '._DB_PREFIX_.'mf_reserve_features';
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }


    //choosing category to show filter
    public static function showMfFilter($id_category) {
        $query = "SELECT id_category FROM "._DB_PREFIX_."mf_categories WHERE id_category = {$id_category} AND active = 1";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    //frontend queries

    public static function getFeaturesByCategory($id, $fmarka, $fmodel, $fmodif) {
        $query = "SELECT p.id_product AS id,
f".$fmarka.".id_feature_value AS id_marka, f".$fmarka.".feature_value AS marka,
f".$fmodel.".id_feature_value AS id_model, f".$fmodel.".feature_value AS model,
f".$fmodif.".id_feature_value AS id_modif, f".$fmodif.".feature_value AS modif
FROM "._DB_PREFIX_."product AS p 
INNER JOIN 
(SELECT fp.id_product AS id_product, fp.id_feature_value AS id_feature_value, fvl.value AS feature_value FROM  "._DB_PREFIX_."feature_product fp INNER JOIN "._DB_PREFIX_."feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value WHERE fp.id_feature = ".$fmarka.") AS f".$fmarka." ON p.id_product = f".$fmarka.".id_product
INNER JOIN 
(SELECT fp.id_product AS id_product, fp.id_feature_value AS id_feature_value, fvl.value AS feature_value FROM  "._DB_PREFIX_."feature_product fp INNER JOIN "._DB_PREFIX_."feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value WHERE fp.id_feature = ".$fmodel.") AS f".$fmodel." ON p.id_product = f".$fmodel.".id_product
INNER JOIN 
(SELECT fp.id_product AS id_product, fp.id_feature_value AS id_feature_value, fvl.value AS feature_value FROM  "._DB_PREFIX_."feature_product fp INNER JOIN "._DB_PREFIX_."feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value WHERE fp.id_feature = ".$fmodif.") AS f".$fmodif." ON p.id_product = f".$fmodif.".id_product
 WHERE p.id_category_default = {$id} AND p.active = 1 AND p.visibility <> 'none'
 ORDER BY marka, model, modif";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function getFeaturesByCategoryAndFeature($id_category, $column, $id_feature, $fmarka, $fmodel, $fmodif) {
        $query = "SELECT p.id_product AS id,
f".$fmarka.".id_feature_value AS id_marka, f".$fmarka.".feature_value AS marka,
f".$fmodel.".id_feature_value AS id_model, f".$fmodel.".feature_value AS model,
f".$fmodif.".id_feature_value AS id_modif, f".$fmodif.".feature_value AS modif
FROM "._DB_PREFIX_."product AS p 
INNER JOIN 
(SELECT fp.id_product AS id_product, fp.id_feature_value AS id_feature_value, fvl.value AS feature_value FROM  "._DB_PREFIX_."feature_product fp INNER JOIN "._DB_PREFIX_."feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value WHERE fp.id_feature = ".$fmarka.") AS f".$fmarka." ON p.id_product = f".$fmarka.".id_product
INNER JOIN 
(SELECT fp.id_product AS id_product, fp.id_feature_value AS id_feature_value, fvl.value AS feature_value FROM  "._DB_PREFIX_."feature_product fp INNER JOIN "._DB_PREFIX_."feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value WHERE fp.id_feature = ".$fmodel.") AS f".$fmodel." ON p.id_product = f".$fmodel.".id_product
INNER JOIN 
(SELECT fp.id_product AS id_product, fp.id_feature_value AS id_feature_value, fvl.value AS feature_value FROM  "._DB_PREFIX_."feature_product fp INNER JOIN "._DB_PREFIX_."feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value WHERE fp.id_feature = ".$fmodif.") AS f".$fmodif." ON p.id_product = f".$fmodif.".id_product
 WHERE p.id_category_default = {$id_category} AND {$column} = {$id_feature} AND p.active = 1 AND p.visibility <> 'none'
 ORDER BY marka, model, modif";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function getUnivByCategory($id_category, $fUniv) {
        $query = "SELECT fl.id_feature_value AS uid, fl.value AS uvalue FROM "._DB_PREFIX_."product AS p  
INNER JOIN "._DB_PREFIX_."feature_product AS fp USING (id_product) INNER JOIN "._DB_PREFIX_."feature_value_lang AS fl USING (id_feature_value) WHERE p.id_category_default = {$id_category} AND fp.id_feature = {$fUniv} AND p.active = 1 AND p.visibility <> 'none' LIMIT 1";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }
    public static function getUnivByAllCategories($fUniv) {
        $query = "SELECT fl.id_feature_value AS uid, fl.value AS uvalue FROM "._DB_PREFIX_."product AS p  
INNER JOIN "._DB_PREFIX_."feature_product AS fp USING (id_product) INNER JOIN "._DB_PREFIX_."feature_value_lang AS fl USING (id_feature_value) WHERE fp.id_feature = {$fUniv} AND p.active = 1 AND p.visibility <> 'none' LIMIT 1";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }


    public static function getFeatureById($id) {
        $query = "SELECT value FROM  "._DB_PREFIX_."feature_value_lang WHERE id_feature_value = {$id}";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function getMainCategory() {
        $query = "SELECT id_category FROM "._DB_PREFIX_."mf_main WHERE id = '1'";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function getMainCategorySlug() {
        $query = "SELECT cl.link_rewrite FROM "._DB_PREFIX_."category_lang AS cl INNER JOIN "._DB_PREFIX_."mf_main AS mm USING (id_category) WHERE mm.id = '1'";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    // Generating titles
    public static function getNameBySlug($slug) {
        $query = "SELECT feature_value FROM "._DB_PREFIX_."features_direct WHERE feature_value_transformed = '{$slug}'";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function getTitleDescs($id_category) {
        $query = "SELECT title_1, title_2, description_1, description_2 FROM "._DB_PREFIX_."mf_categories WHERE id_category = {$id_category} AND active = 1";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    // Backoffice DB requests

    public static function allCategories() {
        $query = "SELECT cl.id_category AS c_id, cl.name AS c_name, mc.title_1 AS c_title_1, mc.title_2 AS c_title_2, mc.description_1 AS c_desc_1, mc.description_2 AS c_desc_2, mc.active AS c_active FROM "._DB_PREFIX_."category_lang AS cl INNER JOIN "._DB_PREFIX_."category AS c ON cl.id_category = c.id_category LEFT OUTER JOIN "._DB_PREFIX_."mf_categories AS mc ON cl.id_category = mc.id_category WHERE c.level_depth <> 0 AND c.level_depth <> 1 AND c.active = 1 ORDER BY cl.name ";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function readyCategories($id) {
        $query = "SELECT id_category FROM "._DB_PREFIX_."mf_categories WHERE id_category = {$id}";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function addCategory($id, $title_1, $title_2, $desc_1, $desc_2, $active) {
        $query = "INSERT INTO "._DB_PREFIX_."mf_categories(id_category, title_1, title_2, description_1, description_2, active) VALUES ('{$id}', '{$title_1}', '{$title_2}', '{$desc_1}', '{$desc_2}', '{$active}')";
        $f = Db::getInstance()->execute($query);
        return $f;
    }

    public static function updateActive($id, $active) {
        $query = "UPDATE "._DB_PREFIX_."mf_categories SET active = '{$active}' WHERE id_category = '{$id}'";
        $f = Db::getInstance()->execute($query);
        return $f;
    }

    public static function updateMetas($id, $title_1, $title_2, $desc_1, $desc_2, $active) {
        $query = "UPDATE "._DB_PREFIX_."mf_categories SET title_1 = '{$title_1}', title_2 = '{$title_2}', description_1 = '{$desc_1}', description_2 = '{$desc_2}', active = '{$active}' WHERE id_category = '{$id}'";
        $f = Db::getInstance()->execute($query);
        return $f;
    }

    public static function mfAllFeatures() {
        $query = "SELECT mf.id AS id, fl.id_feature AS id_feature, fl.name AS name FROM "._DB_PREFIX_."mf_features AS mf RIGHT OUTER JOIN "._DB_PREFIX_."feature_lang AS fl USING (id_feature) ORDER BY name";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function mfFeature($id) {
        $query = "SELECT mf.id AS id, fl.id_feature AS id_feature, fl.name AS name FROM "._DB_PREFIX_."mf_features AS mf RIGHT OUTER JOIN "._DB_PREFIX_."feature_lang AS fl USING (id_feature) WHERE mf.id = {$id}";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function updateMfFeatures($id, $id_feature) {
        $query = "UPDATE "._DB_PREFIX_."mf_features SET id_feature = '{$id_feature}' WHERE id = '{$id}'";
        $f = Db::getInstance()->execute($query);
        return $f;
    }

    public static function mfAllCategoriesMain() {
        $query = "SELECT mm.id AS id, cl.id_category AS id_category, cl.name AS name FROM "._DB_PREFIX_."mf_main AS mm RIGHT OUTER JOIN "._DB_PREFIX_."category_lang AS cl USING (id_category) ORDER BY name";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function mfCategoryMain() {
        $query = "SELECT mm.id AS id, cl.id_category AS id_category, cl.name AS name FROM "._DB_PREFIX_."mf_main AS mm RIGHT OUTER JOIN "._DB_PREFIX_."category_lang AS cl USING (id_category) WHERE mm.id = '1'";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function updateMfMain($id_category) {
        $query = "UPDATE "._DB_PREFIX_."mf_main SET id_category = '{$id_category}' WHERE id = '1'";
        $f = Db::getInstance()->execute($query);
        return $f;
    }

    //Generating table with ready active features

    public static function updateDbActive($table) {
        $query = "UPDATE "._DB_PREFIX_."mf_db_active SET db = '{$table}' WHERE id = '1'";
        if (Db::getInstance()->execute($query)) {
            return true;
        }
        return false;
    }

    public static function getDbActive() {
        $query = "SELECT db FROM "._DB_PREFIX_."mf_db_active WHERE id = '1'";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function getFeaturesByAllCategories($table) {
        $query = "SELECT id_marka, marka, id_model, model, id_modif, modif FROM "._DB_PREFIX_. $table . " ORDER BY marka, model, modif";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function getFeaturesByAllCategoriesAndFeature($table, $column, $id_feature) {
        $query = "SELECT id_marka, marka, id_model, model, id_modif, modif FROM "._DB_PREFIX_. $table . " WHERE {$column} = {$id_feature} ORDER BY marka, model, modif";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function getFeaturesAllActive($fmarka, $fmodel, $fmodif) {
        $query = "SELECT p.id_product AS id,
f".$fmarka.".id_feature_value AS id_marka, f".$fmarka.".feature_value AS marka,
f".$fmodel.".id_feature_value AS id_model, f".$fmodel.".feature_value AS model,
f".$fmodif.".id_feature_value AS id_modif, f".$fmodif.".feature_value AS modif
FROM "._DB_PREFIX_."product AS p 
INNER JOIN 
(SELECT fp.id_product AS id_product, fp.id_feature_value AS id_feature_value, fvl.value AS feature_value FROM  "._DB_PREFIX_."feature_product fp INNER JOIN "._DB_PREFIX_."feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value WHERE fp.id_feature = ".$fmarka.") AS f".$fmarka." ON p.id_product = f".$fmarka.".id_product
INNER JOIN 
(SELECT fp.id_product AS id_product, fp.id_feature_value AS id_feature_value, fvl.value AS feature_value FROM  "._DB_PREFIX_."feature_product fp INNER JOIN "._DB_PREFIX_."feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value WHERE fp.id_feature = ".$fmodel.") AS f".$fmodel." ON p.id_product = f".$fmodel.".id_product
INNER JOIN 
(SELECT fp.id_product AS id_product, fp.id_feature_value AS id_feature_value, fvl.value AS feature_value FROM  "._DB_PREFIX_."feature_product fp INNER JOIN "._DB_PREFIX_."feature_value_lang fvl ON fp.id_feature_value = fvl.id_feature_value WHERE fp.id_feature = ".$fmodif.") AS f".$fmodif." ON p.id_product = f".$fmodif.".id_product
 WHERE p.active = 1 AND p.visibility <> 'none'
 ORDER BY marka, model, modif";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function insertFeaturesBothTables($table, $id_marka, $marka, $id_model, $model, $id_modif, $modif) {
        $query = "INSERT INTO "._DB_PREFIX_.$table . " (id_marka, marka, id_model, model, id_modif, modif) VALUES ('{$id_marka}', '{$marka}', '{$id_model}', '{$model}', '{$id_modif}', '{$modif}')";
        $f = Db::getInstance()->execute($query);
        return $f;
    }

    public static function deleteFeaturesBothTables($table) {
        $query ="DELETE FROM  "._DB_PREFIX_. $table;
        $f = Db::getInstance()->execute($query);
        return $f;
    }

    public static function countingFeaturesActive() {
        $query = "SELECT COUNT(*) FROM "._DB_PREFIX_. "mf_active_features";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    //Generating table with direct url decoding
    public static function createFDirect() {
        $query = "CREATE TABLE IF NOT EXISTS "._DB_PREFIX_."features_direct (id_feature_value int(10) NOT NULL, feature_value TEXT NOT NULL, feature_value_transformed TEXT NOT NULL, PRIMARY KEY (id_feature_value)) DEFAULT CHARSET=utf8";
        $f = Db::getInstance()->execute($query);
        return $f;
    }

    public static function selectingForAdding($fmarka, $fmodel, $fmodif) {
        $query = "SELECT i.idi AS id_final, i.valu AS value_lang FROM "._DB_PREFIX_."features_direct AS r RIGHT JOIN
        (SELECT pl.id_feature_value AS idi, pl.value AS valu FROM "._DB_PREFIX_."feature_value_lang AS pl INNER JOIN "._DB_PREFIX_."feature_value AS p ON pl.id_feature_value = p.id_feature_value WHERE p.id_feature = ".$fmarka." OR p.id_feature = ".$fmodel." OR p.id_feature = ".$fmodif.") AS i ON r.id_feature_value = i.idi WHERE r.id_feature_value IS NULL";
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function addingNew($id, $value, $trans) {
        $query = "INSERT INTO "._DB_PREFIX_."features_direct (id_feature_value, feature_value, feature_value_transformed) VALUES ('{$id}', '{$value}', '{$trans}')";
        $f = Db::getInstance()->execute($query);
        return $f;
    }

    public static function selectingForUpdating($fmarka, $fmodel, $fmodif) {
        $query = "SELECT pl.id_feature_value AS idi, pl.value AS valu FROM "._DB_PREFIX_."feature_value_lang AS pl INNER JOIN "._DB_PREFIX_."feature_value AS p ON pl.id_feature_value = p.id_feature_value WHERE p.id_feature = ".$fmarka." OR p.id_feature = ".$fmodel." OR p.id_feature = ".$fmodif;
        $f = Db::getInstance()->executeS($query);
        return $f;
    }

    public static function updatingDirectFeatures($id, $value, $trans) {
        $query = "UPDATE "._DB_PREFIX_."features_direct SET feature_value = '{$value}', feature_value_transformed = '{$trans}' WHERE id_feature_value = '{$id}'";
        $f = Db::getInstance()->execute($query);
        return $f;
    }
}