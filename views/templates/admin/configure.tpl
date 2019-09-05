<div class="bootstrap clearfix">
    <div class="col-lg-2 col-md-3 menu-panel">
        <div class="list-group">

            <a class="list-group-item list-group-item-mf active" href="#" data-id="tab-1" id="tab-1-trigger">Отображение в категориях</a>
            <a class="list-group-item list-group-item-mf" href="#" data-id="tab-2" id="tab-2-trigger">Обновить модели</a>
            <a class="list-group-item list-group-item-mf" href="#" data-id="tab-3" id="tab-3-trigger">Задать свойства</a>
            <a class="list-group-item list-group-item-mf" href="#" data-id="tab-4" id="tab-4-trigger">Главная категория</a>
            <a class="list-group-item list-group-item-mf" href="#" data-id="tab-5" id="tab-5-trigger">Информация о модуле</a>

        </div>
    </div>
    <div class="col-lg-10 col-md-9 panel tab-content">
        <div class="alarm-css">Не работает админка? Добавьте модуль к хуку displayBackOfficeHeader (но ни в коем случае
            не убирайте из displayModelsFilterHook!), и нужные css и js файлы подключатся. Делаем через
            Design->Расположение->Расположить модуль. Скорее всего, хук слетел после повторной установки модуля.
        </div>
        <div id="tab-1" class="tab-pane pane-active">
            <h3>Задайте категории и динамические метатеги:</h3>
            <form action="" id="choose-categories">
                <ul class="categories-tree">
                    {foreach $categories as $category}
                        <li class="categories-tree-item">
                            <div class="little-header-pack"><label>
                                    <input class="checkbox-category" type="checkbox" id="{$category.c_id}"
                                           value="{$category.c_id}" name="category_box[]"
                                           {if $category.c_active == 1}checked="checked"{/if}>&nbsp;{$category.c_name}
                                </label>&nbsp;<i class="icon-caret-down"
                                                 data-id="open-{$category.c_id}"></i></div>
                            <table class="table meta-hidden" id="open-{$category.c_id}">
                                <tr>
                                    <td>Title</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><textarea class="ta-meta" name="title-1-{$category.c_id}" id="title-1-{$category.c_id}" cols="10" rows="2"
                                                  placeholder="В конце пробел не ставить">{if $category.c_title_1}{$category.c_title_1}{/if}</textarea></td>
                                    <td>BMW X6</td>
                                    <td><textarea class="ta-meta" name="title-2-{$category.c_id}" id="title-2-{$category.c_id}" cols="50" rows="2"
                                                  placeholder="В начале пробел не ставить">{if $category.c_title_2}{$category.c_title_2}{/if}</textarea></td>
                                </tr>

                                <tr>
                                    <td>Description</td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><textarea class="ta-meta" name="desc-1-{$category.c_id}" id="desc-1-{$category.c_id}" cols="15" rows="2"
                                                  placeholder="В конце пробел не ставить">{if $category.c_desc_1}{$category.c_desc_1}{/if}</textarea></td>
                                    <td>BMW X6</td>
                                    <td><textarea class="ta-meta" name="desc-2-{$category.c_id}" id="desc-2-{$category.c_id}" cols="30" rows="2"
                                                  placeholder="В начале пробел не ставить">{if $category.c_desc_2}{$category.c_desc_2}{/if}</textarea></td>
                                </tr>
                            </table>
                        </li>
                    {/foreach}
                </ul>
                <div class="panel-footer">

                    <button type="submit" id="saving" class="saving-button btn btn-default">
                        <i class="process-icon-save"></i>
                        Сохранить
                    </button>
                </div>
            </form>


        </div>

        <div id="tab-2" class="tab-pane">
            <h3>Обновите базу данных после добавления или удаления товаров:</h3>
            <p><button type="button" id="generate-update" class="btn btn-primary">Обновить</button></p>
        </div>

        <div id="tab-3" class="tab-pane">
            <h3>Свойства, которые применяются в полях фильтра:</h3>
            <form action="" id="choose-features">
                <table class="table">
                    <thead>
                        <tr class="nodrag nodrop">
                            <th>Id</th>
                            <th>Позиция</th>
                            <th>Свойство</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr class="not_alt_row row_hover">
                        <td class="td-id">1</td>
                        <td class="td-pos">Марка</td>
                        <td>
                            <select name="select-f-1" id="select-f-1">
                                <option value="">Выберите свойство</option>
                                {foreach from=$features item=item}
                                    <option value="{$item.id_feature}"{if $item.id == '1'} selected = "selected"{/if}>{$item.name}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr class="not_alt_row row_hover">
                        <td class="td-id">2</td>
                        <td class="td-pos">Модель</td>
                        <td>
                            <select name="select-f-2" id="select-f-2">
                                <option value="">Выберите свойство</option>
                                {foreach from=$features item=item}
                                    <option value="{$item.id_feature}"{if $item.id == '2'} selected = "selected"{/if}>{$item.name}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr class="not_alt_row row_hover">
                        <td class="td-id">3</td>
                        <td class="td-pos">Уточните модель</td>
                        <td>
                            <select name="select-f-3" id="select-f-3">
                                <option value="">Выберите свойство</option>
                                {foreach from=$features item=item}
                                    <option value="{$item.id_feature}"{if $item.id == '3'} selected = "selected"{/if}>{$item.name}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    <tr class="not_alt_row row_hover">
                        <td class="td-id">4</td>
                        <td class="td-pos">Универсальные товары</td>
                        <td>
                            <select name="select-f-4" id="select-f-4">
                                <option value="">Выберите свойство</option>
                                {foreach from=$features item=item}
                                    <option value="{$item.id_feature}"{if $item.id == '4'} selected = "selected"{/if}>{$item.name}</option>
                                {/foreach}
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="panel-footer">
                    <button type="submit" id="saving-f" class="saving-button btn btn-default">
                        <i class="process-icon-save"></i>
                        Сохранить
                    </button>
                </div>
            </form>
        </div>

        <div id="tab-4" class="tab-pane">
            <h3>Категория для всех товаров</h3>
            <form action="" id="choose-main-category">
                <p>
                    <select name="select-katalog" id="select-katalog" class="select-katalog">
                        <option value="">Выберите категорию</option>
                        {foreach from=$mains item=item}
                            <option value="{$item.id_category}"{if $item.id == '1'} selected="selected"{/if}>{$item.name}</option>
                        {/foreach}
                    </select>
                </p>
                <div class="panel-footer">
                    <button type="submit" id="saving-kat" class="saving-button btn btn-default">
                        <i class="process-icon-save"></i>
                        Сохранить
                    </button>
                </div>
            </form>
        </div>
        <div id="tab-5" class="tab-pane">
            <h3>Информация о модуле</h3>
        </div>
    </div>
</div>