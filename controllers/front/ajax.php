<?php
require_once(_PS_MODULE_DIR_ . 'modelsfilter/classes/DbQueries.php');
require_once(_PS_MODULE_DIR_ . 'modelsfilter/classes/Generate.php');
class ModelsFilterAjaxModuleFrontController extends ModuleFrontControllerCore
{
    public function initContent()
    {
        if ($params_string = Tools::getValue('slug')) {
            $result = DbQueries::getFeatureById($params_string);
            echo $this->generateLink($result['0']['value']);
            exit;
        }
        else if (Tools::getValue('flag') == '1') {
            $module = new ModelsFilter();

            $array = $module->getFiltersByCategoryAndFeature(Tools::getValue('category'), Tools::getValue('select'), Tools::getValue('option'));
            $marka = [];
            $model = [];
            $modif = [];
            foreach ($array['marka'] AS $key => $value) {
                array_push($marka, [$key, $value]);
            }
            foreach ($array['model'] AS $key => $value) {
                array_push($model, [$key, $value]);
            }
            foreach ($array['modif'] AS $key => $value) {
                array_push($modif, [$key, $value]);
            }
            $finalList = [];
            $finalList += ['marka' => $marka];
            $finalList += ['model' => $model];
            $finalList += ['modif' => $modif];
            echo json_encode($finalList);
            exit;
        }
        else if (Tools::getValue('flag') == '2'){

            $metas1 = Tools::getValue('metas');
            if (!empty($metas1)) {
                $metas = json_decode($metas1, true);
                foreach($metas as $meta) {
                    if (DbQueries::readyCategories($meta['id'])) {
                        DbQueries::updateMetas($meta['id'], $meta['title_1'], $meta['title_2'], $meta['desc_1'], $meta['desc_2'], $meta['active']);
                    }
                    else {
                        DbQueries::addCategory($meta['id'], $meta['title_1'], $meta['title_2'], $meta['desc_1'], $meta['desc_2'], $meta['active']);
                    }
                }
            }

            $inactives1 = Tools::getValue('inactives');
            if (!empty($inactives1)) {
                $inactives = json_decode($inactives1, true);
                foreach($inactives as $item) {
                    if (DbQueries::readyCategories($item)) {
                        DbQueries::updateActive($item, '0');
                    }
                }
            }

            $newchecks1 = Tools::getValue('newchecks');
            if (!empty($newchecks1)) {
                $newchecks = json_decode($newchecks1, true);
                foreach($newchecks as $item) {
                    if (DbQueries::readyCategories($item)) {
                        DbQueries::updateActive($item, '1');
                    }
                }
            }

            echo 'Изменения успешно сохранены!';
            exit;
        }

        else if (Tools::getValue('flag') == '3'){
            $mfs = Tools::getValue('mfs');
            $mfs1 = json_decode($mfs, true);
            foreach($mfs1 as $key=>$value) {
                DbQueries::updateMfFeatures($key, $value);
            }

            echo 'Изменения успешно сохранены!';
            exit;
        }


        else if (Tools::getValue('flag') == '4'){
            echo Generate::updateNames();
            exit;
        }
        else if (Tools::getValue('flag') == '5'){
            $cat = Tools::getValue('cat');
            DbQueries::updateMfMain($cat);

            echo 'Изменения успешно сохранены!';
            exit;
        }
        else if (Tools::getValue('flag') == '6'){
            $result = DbQueries::countingFeaturesActive()[0]["COUNT(*)"];

            echo $result;
            exit;
        }
    }


    public function generateLink($string, $identifier = '')
    {
        $link = Tools::str2url($string);
        // add identifier in order to avoid possible duplicates
//            if ($identifier) {
//                $link = $identifier.($link ? '-'.$link : '');
//            }
        return $link;
        // $string = Tools::str2url($string);
        // $string = mb_strtolower(preg_replace('/[ \/\:\,\#\!\@]+/', '-', trim($string)));
        // $string = preg_replace('/[^\p{L}\p{N}\_]/u', '', $string);
        // return $string;
    }
}