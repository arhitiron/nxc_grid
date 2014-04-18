{def $parentNode = fetch("content", "node", hash("node_id", $cellNode.parent_node_id))}
<div class="gridresize__cell" data-row="{$data.row}" data-col="{$data.col}" data-sizex="{$data.size_x}" data-sizey="{$data.size_y}">
    {*<div class="cell-content">*}
        {*<figure class="gridresize__content template-element-{$parentNode.data_map.color.content}">
            <i class="front-gallery__icon {if $cellNode.data_map.page_video.has_content|not()}front-gallery__icon_article{/if}"></i>
            <img src="{$cellNode.data_map.page_image.content[cell_2x2].url}" alt="">
            <a href={$cellNode.url|ezurl} class="front-gallery__content-hover-effect"></a>
            <figcaption>
                <h4>{$cellNode.name|char_cut(50)}</h4>
		<p>{$cellNode.data_map.page_content.data_text|strip_tags()|char_cut(100)}</p>
            </figcaption>
        </figure>*}
    <figure class="gridresize__content template-element-{$parentNode.data_map.color.content}">
        <a href={$cellNode.url|ezurl}>
	<i class="gridresize__icon {if $cellNode.data_map.page_video.has_content|not()}front-gallery__icon_article{/if}"></i>
	<img src={$cellNode.data_map.page_image.content[cell_2x2].url|ezroot} alt="">
	<i class="gridresize__content-hover-effect"></i>
        </a>
	<figcaption>
	    <h4>{$cellNode.name|char_cut(20)}</h4>
	    <p>{$cellNode.data_map.page_content.data_text|strip_tags()|char_cut(100)}</p>
	</figcaption>
    </figure>
{*    </div>*}
</div>