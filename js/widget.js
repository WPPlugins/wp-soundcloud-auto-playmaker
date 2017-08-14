jQuery.ajaxSetup({
    cache: false
});

jQuery(document).ready(function() {
    jQuery("#pager").createNav({ClickCallback: PageClick});
});

PageClick = (function(buttonIndex){
    //first need to get which page we are currently on
    //so that we can calculate the offset based on the button's number
    //get the currently requested page from hidden div
    //
    pageIndex = parseInt(jQuery('#cur-offset').text());
    pageCount = parseInt(jQuery('#pagecount').text());
    nextIndex = _calcNextPage(pageIndex, parseInt(buttonIndex), pageCount);
    if (nextIndex == pageIndex)
        return;
    jQuery.ajax({
        type: 'POST',
        url: proxyUrl,
        data: 'listtype=' + listtype +
              '&feedtype=' + feedtype +
              '&profilelink=' + profilelink +
              '&tracklink=' + tracklink +
              '&color=' + color +
              '&user=' + user +
              '&limit=' + limit + 
              '&os=' + nextIndex,
        dataType: 'html',
        beforeSend: function(){
            jQuery('#ajax-loader').css('visibility','visible');
        },
        success: function(res, status){
            // Process the returned HTML and append it to some part of the page.
            //var elements = jQuery(data);

            jQuery('#cur-offset').text(nextIndex);
            jQuery('#playerarea').html(res);
        },
        error: function(){
            alert("Big stinky error..");
        },
        complete: function(){
            jQuery('#ajax-loader').css('visibility','hidden');
        }
    });
});

_calcNextPage = (function(curIndex, buttonIndex, maxIndex){
    switch(buttonIndex){
        case 0:
            return 0;
            break;
        case 1:
           return curIndex == 0 ? 0 : --curIndex;
           break; 
        case 2:
            return curIndex == maxIndex ? maxIndex : ++curIndex;
            break;
        case 3:
            return maxIndex;
            break;
    } 
});

jQuery.crossSite = (function(_ajax){
    
    var protocol = location.protocol,
        hostname = location.hostname,
        exRegex = RegExp(protocol + '//' + hostname),
        YQL = 'http' + (/^https/.test(protocol)?'s':'') + '://query.yahooapis.com/v1/public/yql?callback=?',
        query = 'select * from html where url="{URL}" and xpath="*"';
    
    function isExternal(url) {
        return !exRegex.test(url) && /:\/\//.test(url);
    }
    
    return function(o) {
        
        var url = o.url;
        
        if ( /get/i.test(o.type) && !/json/i.test(o.dataType) && isExternal(url) ) {
            
            // Manipulate options so that JSONP-x request is made to YQL
            
            o.url = YQL;
            o.dataType = 'json';
            
            o.data = {
                q: query.replace(
                    '{URL}',
                    url + (o.data ?
                        (/\?/.test(url) ? '&' : '?') + jQuery.param(o.data)
                    : '')
                ),
                format: 'xml'
            };
            
            // Since it's a JSONP request
            // complete === success
            if (!o.success && o.complete) {
                o.success = o.complete;
                delete o.complete;
            }
            
            o.success = (function(_success){
                return function(data) {
                    
                    if (_success) {
                        // Fake XHR callback.
                        _success.call(this, {
                            responseText: data.results[0]
                                // YQL screws with <script>s
                                // Get rid of them
                                .replace(/<script[^>]+?\/>|<script(.|\s)*?\/script>/gi, '')
                        }, 'success');
                    }
                    
                };
            })(o.success);
            
        }
        
        return _ajax.apply(this, arguments);
        
    };
    
})(jQuery.ajax);
