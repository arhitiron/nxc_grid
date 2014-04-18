(function ($, undefined) {

    "use strict"

    //  no-js
    $('html').removeClass('no-js');

    $('#mainmenuToggler').on('click', function() {
        $(this).parents('.mainmenu').toggleClass('mainmenu_extend');
    });

    if($("#gridresize").length){
        NXC.Grid.resize();
        $(window).smartresize(NXC.Grid.resize, 100);
    }

})(jQuery);