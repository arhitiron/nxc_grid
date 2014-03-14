{* DO NOT EDIT THIS FILE! Use an override template instead. *}
{def $node_id = 0}
{if $data.node_id}
    {set $node_id = $data.node_id}
{/if}

<div class = "cell {$attribute.id}_custom_attribute-{$key}" node_id="{$node_id}">
    <div class="cell-title">{$data.name} </div>
    <div class = "cell-content">
        <input id="block-choose-source" class="button block-control" name="CustomActionButton[{$attribute.id}_custom_attribute_browse-{$key}]" type="submit" value="{'Choose source'|i18n( 'design/standard/block/edit' )}" />
    </div>
</div>