$(document).ready(function(){

    function setGridsretHeight() {
        var cells = $(".gridster > .cell");
        var maxBottom = 0;
        var marginBottom = 20;
        var i = 0;
        for (i = 0; i < cells.length; i++) {
            var bottom = cells.eq(i).position().top+cells.eq(i).outerHeight(true);
            if (maxBottom < bottom) {
                maxBottom = bottom;
            }
        }
        maxBottom += marginBottom;
        $(".gridster").css({"height":maxBottom});
    }
    setGridsretHeight();

});