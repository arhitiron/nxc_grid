jQuery(document).ready(function(){

    function gridsterInit(){
        var xDimension = $("#serialisation").data("grid-dimensionx");
        var yDimension = $("#serialisation").data("grid-dimensiony");
        var maxCols = $("#serialisation").data("max-cols");
        var marginX = $("#serialisation").data("margin-x");
        var marginY = $("#serialisation").data("margin-y");
        var cellMaxSizeX = $("#serialisation").data("max-size-x");
        var cellMaxSizeY = $("#serialisation").data("max-size-y");

        if (typeof(marginX) == "undefined") {
            marginX = 10;
        }

        if (typeof(marginY) == "undefined") {
            marginY = 10;
        }

        var gridster = $(".gridster ul").gridster({
            widget_margins: [marginX, marginY],
            max_cols: maxCols,
            widget_base_dimensions: [xDimension, yDimension],
            serialize_params: function($w, wgd) {
                return {
                    col: wgd.col,
                    row: wgd.row,
                    size_x: wgd.size_x,
                    size_y: wgd.size_y,
        	    max_size_x: cellMaxSizeX,
        	    max_size_y: cellMaxSizeY,		    
                    top: $(wgd.el[0]).css("top"),
                    left: $(wgd.el[0]).css("left"),
                    height: wgd.el[0].clientHeight,
                    width: wgd.el[0].clientWidth,
                    node_id: $(wgd.el[0]).attr("node_id")
                };
            },
            draggable:{
                stop: function(e, ui, $widget) {
                    setData(this);
                }
            },
            helper: 'clone',
            resize: {
                enabled: true,
	//	max_size:[cellMaxSizeX, cellMaxSizeY],
	//	min_size:[1,1],
                stop:function(e,ui, $widget){
                    setData(this);
                }
            }
        }).data('gridster');
        return gridster;
    }

    var gridster = gridsterInit();

    var attributeId = $(".gridster").attr("attribute_id");
    var key;
    $(".add").click(function(){
        key = jQuery(".gridster ul li").length;
        gridster.add_widget("<li class='new'><a class='delete'>&times;</a><input id='block-choose-source' class='button block-control' name='CustomActionButton[" + attributeId + "_custom_attribute_browse-" + key + "]' type='submit' value='Choose source' /></li>", 1, 1);
        setData(gridster);
    });

    function serialiseGrid(gridster){
        if ($("#serialisation").length > 0 && $("#serialisation").text().length > 0) {
            var serialization = $("#serialisation").text();
            serialization = $.parseJSON(serialization);
            gridster.remove_all_widgets();
            $.each(serialization, function() {
                key = jQuery(".gridster ul li").length;
                var nodeId = this.node_id;
                var title = this.title;
                if (typeof nodeId == 'undefined') {
                    nodeId = 0;
                }
                if (typeof title == 'undefined') {
                    title = '';
                }
                gridster.add_widget("<li node_id ='"+nodeId+"'><a class='delete'>&times;</a><input id='block-choose-source' class='button block-control' name='CustomActionButton[" + attributeId + "_custom_attribute_browse-" + key + "]' type='submit' value='Choose source' /><p>"+title+"</p></li>", this.size_x, this.size_y, this.col, this.row);
            });
        }
    }

    serialiseGrid(gridster);



    $(".delete").click(function(){
        gridster.remove_widget($(this).parent());
        setData(gridster);
    });

    function reloadGrid(){
        var gridster = gridsterInit();
        var siteaccess = $('#accessname').attr("val");
        var gridWidth = $(".grid-width input").val();
        var attributeId = $('.grid-width').attr("attribute_id");
        $("#grid-input").attr("value",JSON.stringify(gridster.serialize()));
        $.ajax({
            url: siteaccess+"/temporarygrid/action",
            type: "POST",
            dataType: 'json',
            data: {
                'grid': JSON.stringify(gridster.serialize()),
                'gridWidth': gridWidth,
                'attributeId': attributeId,
                'action': "gridbydata"
            },
            success: function(data){
                location.reload();


            }
        });
    }

    $(".gridster-reload").click(function(){
        var newWidth = $(".grid-width input").val();
        reloadGrid();
    });
    function getDimension(str){
        var cellType = str.split("x");
        return cellType;
    }

    function setData(gridster){
        setTimeout(function(){gridSession(gridster);}, 1000);
    }

    function gridSession(gridster) {
        var siteaccess = $('#accessname').attr("val");
        $("#grid-input").attr("value",JSON.stringify(gridster.serialize()));
        $.ajax({
            url: siteaccess+"/temporarygrid/action",
            type: "POST",
            data: {
                'grid': JSON.stringify(gridster.serialize()),
                'action': 'temporarygridtosession'
            }
        });
    }

});