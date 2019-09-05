<?php
if (!defined('_PS_VERSION_'))
{
    exit;
}

require_once(_PS_MODULE_DIR_ . 'modelsfilter/classes/DbQueries.php');
require_once(_PS_MODULE_DIR_ . 'modelsfilter/classes/FeaturesVars.php');

class ModelsFilter extends Module
{
    public $csv_dir;
    public $db;
    public $currentCategoryId;
    public $getArr;
    public $fv;
    public $mainCategoryId;
    public $activeFeaturesTable;

    public $fMarkaId;
    public $fModelId;
    public $fModifId;
    public $fUnivId;

    public $fMarkaName;
    public $fModelName;
    public $fModifName;
    public $fUnivName;

    public function __construct()
    {
        $this->name = 'modelsfilter';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Egor Shubin';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Car Models Filter');
        $this->description = $this->l('Our own extention for Amazzing filter. Filter by car brands, models and modifications. ');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

        if (!Configuration::get('MYMODULE_NAME'))
            $this->warning = $this->l('No name provided');

        $this->definePublicVariables();
    }

    public function definePublicVariables()
    {
        $this->csv_dir = $this->local_path . '../amazzingfilter/indexes/index_1_1_1.csv';
        $this->db = Db::getInstance();
        $this->fv = FeaturesVars::getInstance();
        $this->getArr = $this->parseGetRequest();
        $this->fMarkaId = $this->fv->getFMarkaId();
        $this->fModelId = $this->fv->getFModelId();
        $this->fModifId = $this->fv->getFModifId();
        $this->fUnivId = $this->fv->getFUnivId();
        $this->fMarkaName = $this->fv->getFMarkaName();
        $this->fModelName = $this->fv->getFModelName();
        $this->fModifName = $this->fv->getFModifName();
        $this->fUnivName = $this->fv->getFUnivName();
        $this->mainCategoryId = DbQueries::getMainCategory()[0]['id_category'];
        $this->activeFeaturesTable = DbQueries::getDbActive()[0]['db'];
    }

    // Install uninistall

    public function install()
    {
        if (Shop::isFeatureActive())
            Shop::setContext(Shop::CONTEXT_ALL);

        if (!parent::install() ||
            !$this->registerHook('displayModelsFilterHook') ||
            !$this->registerHook('displayMainModelsFilterHook') ||
            !$this->registerHook('header') ||
            !$this->registerHook('hookDisplayBackOfficeHeader') ||
            !$this->prepareDatabaseTables()) {
            $this->uninstall();
            return false;
        }

        Configuration::updateValue('MYMODULE_NAME', 'my friend');
//        $this->processAvailableOverrides('add');
//        // In some cases overrides are not reset automatically
        unlink(_PS_CACHE_DIR_.'class_index.php');
        return true;
    }

    public function prepareDatabaseTables()
    {
        if (!DbQueries::createMfCategories() || !DbQueries::createMfFeatures() || !DbQueries::createMfMain() || !DbQueries::insertMfMain() || !DbQueries::createDbActive() || !DbQueries::insertDbActive() || !DbQueries::createMfActiveFeatures() || !DbQueries::createMfReserveFeatures()) {
            return false;
        }

        $array = ['1', '2', '3', '4'];
        foreach ($array as $item) {
            if (!DbQueries::insertMfFeatures($item)) {
                return false;
            }
        }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() || !$this->dropDatabaseTables()) {
            return false;
        }
        return true;
    }

    public function dropDatabaseTables() {
        if (!DbQueries::dropMfFeatures() || !DbQueries::dropMfMain() || !DbQueries::dropDbActive() || !DbQueries::dropMfActiveFeatures() || !DbQueries::dropMfReserveFeatures()) {
            return false;
        }
        return true;
    }

    public function hookdisplayMainModelsFilterHook()
    {
            $carfilters = $this->getFilters();
            $univs = $this->getUniv($this->mainCategoryId);
            $this->context->smarty->assign(
                array(
                    'carfilters' => $carfilters,
                    'category_id' => $this->mainCategoryId,
                    'mainCategorySlug' => DbQueries::getMainCategorySlug()[0]['link_rewrite'],
                    'readies' => $this->takeReadyFilters($carfilters, $univs),
                    'univ' => Tools::str2url($univs[0]['uvalue'])
                )
            );
            return $this->display(__FILE__, 'main.tpl');
    }



    public function hookdisplayModelsFilterHook()
    {
//        var_dump($this->getFiltersByCategoryAndFeature(721, $this->fMarkaName, 4834));
        if (DbQueries::showMfFilter(Tools::getValue('id_category'))) {
            $carfilters = $this->getFilters();
            $univs = $this->getUniv($this->currentCategoryId);
            $this->context->smarty->assign(
                array(
                    'carfilters' => $carfilters,
                    'category_id' => $this->currentCategoryId,
                    'readies' => $this->takeReadyFilters($carfilters, $univs),
                    'univ' => Tools::str2url($univs[0]['uvalue'])
                )
            );
            return $this->display(__FILE__, 'index.tpl');
        }
        return false;
    }

    public function getUniv($id_category) {
        if ($id_category == $this->mainCategoryId) {
            $univs = DbQueries::getUnivByAllCategories($this->fUnivId);
            return $univs;
        }
        else {
            return DbQueries::getUnivByCategory($id_category, $this->fUnivId);
        }
    }

    public function takeReadyFilters($carfilters, $univs) {
        $get = $this->getArr;
        if (!empty($get[$this->fUnivName])){
            foreach ($univs as $row) {
                return $this->takeReadyFiltersGetArray($row['uid'], $this->fUnivName);
            }
        }
        else if (!empty($get[$this->fModifName])){
            foreach ($carfilters['modif'] as $id => $feature_name) {
                if ($get[$this->fModifName] == Tools::str2url($feature_name)) {
                    return $this->takeReadyFiltersGetArray($id, $this->fModifName);
                }
            }
        }
        else if (!empty($get[$this->fModelName])) {
            foreach ($carfilters['model'] as $id => $feature_name) {
                if ($get[$this->fModelName] == Tools::str2url($feature_name)) {
                    return $this->takeReadyFiltersGetArray($id, $this->fModelName);
                }
            }
        }
        else if (!empty($get[$this->fMarkaName])) {
            foreach ($carfilters['marka'] as $id => $feature_name) {
                if ($get[$this->fMarkaName] == Tools::str2url($feature_name)) {
                    return $this->takeReadyFiltersGetArray($id, $this->fMarkaName);
                }
            }
        }
        return $this->takeReadyFiltersGetArray('', '');
    }

    public function takeReadyFiltersGetArray($id, $select) {
        return ['id' => $id,
            'select' => $select,
            'fMarkaName' => $this->fMarkaName,
            'fModelName' => $this->fModelName,
            'fModifName' => $this->fModifName,
            'fUnivName' => $this->fUnivName,
            'fMarkaId' => $this->fMarkaId,
            'fModelId' => $this->fModelId,
            'fModifId' => $this->fModifId,
            'fUnivId' => $this->fUnivId
        ];
    }
    public function getFilters() {
        $get = $this->getArr;
        if (isset($get['id_category'])) {
            $this->currentCategoryId = $get['id_category'];
            return $this->getFiltersByCategory($get['id_category']);
        }
        else {
            return $this->getFiltersByCategory($this->mainCategoryId);
        }
    }

    public function getFiltersByCategory($id) {
        $linesArray = $this->choosingHowToByCategory($id);
        return $this->buildArray($linesArray);
    }

    public function choosingHowToByCategory($id) {
        if ($id == $this->mainCategoryId) {
            $linesArray = DbQueries::getFeaturesByAllCategories($this->activeFeaturesTable);
        }
        else
        {
            $linesArray = DbQueries::getFeaturesByCategory($id, $this->fMarkaId, $this->fModelId, $this->fModifId);
        }
        return $linesArray;
    }

    public function getFiltersByCategoryAndFeature($id_category, $feature_type, $id_feature) {

        if (empty($id_feature)) {
            $linesArray = $this->choosingHowToByCategory($id_category);
        }
        else {
            if ($feature_type == $this->fModifName) {
                $column = 'id_modif';
                $column2 = 'f59.id_feature_value';
            }
            else if ($feature_type == $this->fModelName) {
                $column = 'id_model';
                $column2 = 'f58.id_feature_value';
            }
            else if ($feature_type == $this->fMarkaName) {
                $column = 'id_marka';
                $column2 = 'f57.id_feature_value';
            }
            else {
                return false;
            }
            if ($id_category == $this->mainCategoryId) {
                $linesArray = DbQueries::getFeaturesByAllCategoriesAndFeature($this->activeFeaturesTable, $column, $id_feature);
            }
            else
            {
                $linesArray = DbQueries::getFeaturesByCategoryAndFeature($id_category, $column2, $id_feature, $this->fMarkaId, $this->fModelId, $this->fModifId);
            }
        }

        return $this->buildArray($linesArray);
    }

    //Final array method
    public function buildArray($linesArray) {
        $marksList = [];
        $modelsList = [];
        $modifsList = [];
        foreach ($linesArray as $line) {
            $marksList += [$line['id_marka'] => $line['marka']];
            $modelsList += [$line['id_model'] => $line['model']];
            $modifsList += [$line['id_modif'] => $line['modif']];
        }

        $finalList = [];
        $finalList += ['marka' => $marksList];
        $finalList += ['model' => $modelsList];
        $finalList += ['modif' => $modifsList];
        return $finalList;
    }


    //Helpers

    public function parseGetRequest() {
        if (isset($_GET)) {
            return $_GET;
        }
        return false;
    }

    //Adminka

    public function getContent() {
        $this->context->smarty->assign(
            array(
                'categories' => DbQueries::allCategories(),
                'features' => DbQueries::mfAllFeatures(),
                'mains' => DbQueries::mfAllCategoriesMain()
            )
        );
        $html = $this->display(__FILE__, 'views/templates/admin/configure.tpl');
        return $html;
    }

    public function hookDisplayBackOfficeHeader() {
//        $this->context->controller->css_files[$this->_path.'views/css/bootstrap.min.css'] = 'all';
        $this->context->controller->css_files[$this->_path.'views/css/back.css'] = 'all';
        $this->context->controller->addJquery();
        $this->context->controller->addJqueryUI('ui.tooltip');
        $this->context->controller->addJqueryUI('ui.sortable');
        $this->context->controller->js_files[] = $this->_path.'views/js/back.js';
        return;
    }
    
}