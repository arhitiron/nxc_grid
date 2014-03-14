<div style = "width:{$data.width}px; height: {$data.height}px; position: absolute; top: {$data.top}; left: {$data.left};" class="cell {$class} size-x-{$data.size_x} size-y-{$data.size_y}">
    <div class="cell-content">
	    <h3>{$node.name}</h3>
        {attribute_view_gui attribute=$node.data_map.image image_class=medium}
    </div>
</div>