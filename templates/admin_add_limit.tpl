<script src="/modules/addons/LimitProductOrder/templates/js/shared/bootstrap-multiselect.js"></script>
<fieldset>
    <br/>
    <h3>Общие настройки</h3>
    <form role="form" id="settings" method="post" class="label-form">
        <div class="row">

            <div class="col-md-3 text-right title">
                <label for="rel_id">Связанный продукт/дополнение</label>
            </div>
            <div class="col-md-3">
                <div>
                    <select class="form-control" name="rel_id[]" id="rel_id" multiple="multiple" required>
                        {foreach key=$associateType item=$associateGroupItems from=$associateList}
                            {foreach key=$associateKey item=$associateItems from=$associateGroupItems}
                                <optgroup label="{$associateKey}">
                                    {foreach  item=$associateItem from=$associateItems}
                                        <option value="{$associateItem.id}">{$associateItem.text}</option>
                                    {/foreach}
                                </optgroup>
                            {/foreach}
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="rel_id">Выберите продукт/дополнение для которого будет применено
                    ограничение</label>
            </div>

            <div class="col-md-3 text-right title">
                <label for="limit">Лимит активных</label>
            </div>
            <div class="col-md-3">
                <div>
                    <input type="number" class="form-control" name="limit" id="limit" value="-1" required>
                </div>
            </div>
            <div class="col-md-6">
                <label class="info_text" for="limit">"-1" - не ограничено</label>
            </div>
        </div>
        <div class="row text-center"><br/>
            <input class="btn btn-primary" type="submit" value="Сохранить изменения"/>
        </div>
    </form>
</fieldset>
<script>
    $("#rel_id").multiselect({
        enableClickableOptGroups: true,
        enableCollapsibleOptGroups: true,
        enableFiltering: true,
        includeSelectAllOption: true,
        selectAllText: 'Выбрать все',
        numberDisplayed: 1,
        buttonWidth: '293px',
        maxHeight: 400,
        filterPlaceholder: 'Поиск',
        nonSelectedText: 'Не выбрано'
    });
</script>