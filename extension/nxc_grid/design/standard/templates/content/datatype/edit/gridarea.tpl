<div id = "serialisation" data-max-cols="{$gridMaxCols}" data-grid-dimensionx="{$gridDimensionX}" data-grid-dimensiony="{$gridDimensionY}" style ="display:none;">{$gridData}</div>
<div class="gridster gridster-border" attribute_id="{$attributeId}" style = "width:{$gridWidth}px;">
    <ul></ul>
    <input type="text" class="grid-input" hidden="hidden" id="grid-input" name="grid" value='{$gridData}'>
    <div class="buttons">
        <a class="add">add</a>
    </div>
</div>
