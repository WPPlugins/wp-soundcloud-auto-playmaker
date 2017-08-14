/*
 * Adapted Heavily from
 *       .....well.. almost entirely..
 * Example at: http://jonpauldavies.github.com/JQuery/Pager/PagerDemo.html
 */
(function($) {
     jQuery.fn.createNav = function(options) {
         return this.each(function() {
             jQuery(this).empty().append(
                 renderPager(0, options.ClickCallback));
             
             jQuery('.pages li').mouseover(function(){ 
                 document.body.style.cursor = "pointer"; }).mouseout(function(){ 
                     document.body.style.cursor = "auto"; });
        });
     };

    function renderPager(pagenumber, buttonClickCallback) {
        var $pager  = $('<ul class="pages"></ul>');
        $pager.append(
            renderButton(
                '<<', 
                0, 
                buttonClickCallback)
            ).append(
                renderButton(
                    '<', 
                    1, 
                    buttonClickCallback));
        $pager.append(
            renderButton(
                '>',   
                2,          
                buttonClickCallback)
            ).append(
                renderButton(
                    '>>',  
                    3, 
                    buttonClickCallback)
            );
        return $pager;
    }

    function renderButton(buttonLabel, pageNumber, buttonClickCallback) {
        var $Button = $('<li class="pgNext">' + buttonLabel + '</li>');
        $Button.click(function() { buttonClickCallback(pageNumber); });
        return $Button;
    }
})(jQuery);





