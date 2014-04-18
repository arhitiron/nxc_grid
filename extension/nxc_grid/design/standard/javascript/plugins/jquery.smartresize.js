(function($,sr){
    var debounce = function (func, threshold, execAsap) {
        var timeout;
// var tt,
// bb = function(){
//     setTimeout(function(){
//         if ((new Date()).getTime() - tt >= threshold) return console.log(1), true;
//         bb();
//     },0);
// };
        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
            if (!execAsap)
                func.apply(obj, args);
                timeout = null;
            };

            if (timeout) {
                clearTimeout(timeout);
            } else if (execAsap) {
                func.apply(obj, args);
            }
// tt = (new Date()).getTime();
// bb();
            timeout = setTimeout(delayed, threshold || 100);
        };
    }

    jQuery.fn[sr] = function(fn, th, exec){  return fn ? this.bind('resize', debounce(fn, th, exec)) : this.trigger(sr); };

})(jQuery,'smartresize');
