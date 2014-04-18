{def $parentNode = fetch("content", "node", hash("node_id", $cellNode.parent_node_id))}
{*$node.data_map.page_video|attribute(show)*}
<div  class="gridresize__cell" data-row="{$data.row}" data-col="{$data.col}" data-sizex="{$data.size_x}" data-sizey="{$data.size_y}">
    <div class="cell-content">
        {*<figure class="gridresize__content template-element-{$parentNode.data_map.color.content}">
            <i class="gridresize__icon {if $cellNode.data_map.page_video.has_content|not()}gridresize__icon_article{/if}"></i>
	    <img src="{$cellNode.data_map.page_image.content[cell_1x1].url}" alt="">
            <a href={$cellNode.url|ezurl} class="gridresize__content-hover-effect"></a>
            <figcaption>
                <h4>{$cellNode.name|char_cut(20)}</h4>
            </figcaption>
        </figure>*}
    <figure class="gridresize__content template-element-{$parentNode.data_map.color.content}">
        <a href={$cellNode.url|ezurl}>
	<i class="gridresize__icon {if $cellNode.data_map.page_video.has_content|not()}gridresize__icon_article{/if}"></i>
	{*<img src={$cellNode.data_map.page_image.content[cell_1x1].url|ezroot} alt="">*}
	{attribute_view_gui attribute=$cellNode.data_map.page_image image_class="cell_1x1"}
	<i class="gridresize__content-hover-effect"></i>
        </a>
	<figcaption>
	    <h4>{$cellNode.name|char_cut(20)}</h4>
	</figcaption>
    </figure>
    </div>
</div>