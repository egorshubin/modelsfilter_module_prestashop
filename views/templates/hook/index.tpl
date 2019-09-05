<div class="modelsfilter-wrapper">
<div class="models-filter-h">Подберите товар по модели автомобиля</div>
<div id="filter-error-message"></div>
<form action="" method="get" id="models-filter-form" class = "models-filter-form-js models-filter-form" data-category-id="{$category_id}">
    <input type="hidden" id="ready-helper"
           data-id = "{$readies.id}"
           data-select = "{$readies.select}"
           data-fMarkaName = "{$readies.fMarkaName}"
           data-fModelName = "{$readies.fModelName}"
           data-fModifName = "{$readies.fModifName}"
           data-fUnivName = "{$readies.fUnivName}"
           data-fMarkaId = "{$readies.fMarkaId}"
           data-fModelId = "{$readies.fModelId}"
           data-fModifId = "{$readies.fModifId}"
           data-fUnivId = "{$readies.fUnivId}"
    >
    <div class="row">
        <div class="col-lg-9 col-xs-12">
            <div class="row models-row">
                <div class="col-sm-4">
                    <div class="model-select-outer-wrap">
                        <div class="model-select-wrap">
                            <select name="{$readies.fMarkaName}" id="marka-avto"
                                    class="select-js">
                                <option value="" id="marka-avto-empty">Марка</option>
                                {foreach from=$carfilters.marka item=car key=key}
                                    <option value="{$key}" id="{$key}">{$car}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="model-select-below">
                            <i class="fa fa-chevron-down"></i>
                        </div>
                        <div class="model-select-deep-below"></div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="model-select-outer-wrap">
                        <div class="model-select-wrap">
                            <select name="{$readies.fModelName}" id="model-avto" class="select-js">
                                <option value="" id="model-avto-empty">Модель авто</option>
                                {foreach from=$carfilters.model item=car key=key}
                                    <option value="{$key}" id="{$key}">{$car}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="model-select-below">
                            <i class="fa fa-chevron-down"></i>
                        </div>
                        <div class="model-select-deep-below"></div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="model-select-outer-wrap">
                        <div class="model-select-wrap">
                            <select name="{$readies.fModifName}" id="modifikaciya-modeli" class="select-js">
                                <option value="" id="modifikaciya-modeli-empty">Уточните&nbsp;модель</option>
                                {foreach from=$carfilters.modif item=car key=key}
                                    <option value="{$key}" id="{$key}">{$car}</option>
                                {/foreach}
                            </select>
                        </div>
                        <div class="model-select-below">
                            <i class="fa fa-chevron-down"></i>
                        </div>
                        <div class="model-select-deep-below"></div>
                    </div>
                </div>
            </div>

            <div class="model-checkbox-wrap" {if !$univ}style="display:none"{/if}>
                <input type="checkbox"
                       name="{$readies.fUnivName}"
                       id="univ"
                       value="{$univ}"
                       class="model-checkbox">

                <label for="univ">
                    Подобрать универсальные товары для разных
                    моделей</label></div></div>


        <div class="col-lg-3 col-xs-12">
            <div class="inputs-flex-group">
                <input class="btn btn-primary models-filter-button" type="submit"
                                                   value="Подобрать!">
                <span id="reset-button" class="reset-button" title="Начать выбирать заново"><i class="fa fa-times" aria-hidden="true"></i></span>
            </div>
        </div>
    </div>
</form>
</div>

