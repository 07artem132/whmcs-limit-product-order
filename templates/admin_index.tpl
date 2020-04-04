<div class="row pull-right" style="margin-right: 4px;">
    <div class="col-md-3">
        <div class="text-right">
            <!-- Split button -->
            <div class="btn-group">
                <form role="form" method="get" style="display: inline;">
                    <input type="hidden" name="module" value="LimitProductOrder">
                    <input type="hidden" name="action" value="add_limit">
                    <input type="submit" class="btn btn-success btn-sm" value="Добавить ограничение">
                </form>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div id="tableBackground" class="tablebg">
        <table id="tableLimitList" width="100%" class="datatable no-margin">
            <thead>
            <tr>
                <th>
                    Услуга
                </th>
                <th>
                    Лимит
                </th>
                <th style="width: 2%;"></th>
            </tr>
            </thead>
            <tbody>
            {foreach $LimitList as $limit}
                <tr class="product">
                    <td>
                        {if !empty($limit->service_url)}
                            <a href="{$limit->service_url}">{$limit.full_text}</a>
                        {else}
                            {$limit.product_name}
                        {/if}
                    </td>
                    <td>
                        {$limit.limit}
                    </td>
                    <td>
                        <a href="addonmodules.php?module=LimitProductOrder&action=delete_limit&id={$limit.id}"
                           title="Удалить ограничение"
                           onClick="return window.confirm('Вы точно хотите ограничение для продукта "{$limit.product_name}
                        " ?');"
                        >
                        <i class="fas fa-trash-alt"></i>
                        </a>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
</div>


