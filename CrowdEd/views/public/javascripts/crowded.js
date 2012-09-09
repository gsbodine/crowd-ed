/* 
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

jQuery(document).ready(function($) {
    $(".explanation").before("<span class='help_icon ui-widget ui-icon ui-icon-info'></span>").addClass("ui-widget");
    $(".help_icon").bind("mouseover mouseout", function() {
        $(this).next(".explanation").toggle();
    });
    $("input:submit, a, button", ".buttonbar").button();
    
    $(".fieldheader").addClass("ui-widget")
    
    $(".success").addClass("ui-state-highlight ui-corner-all");
    $(".failure").addClass("ui-state-error ui-corner-all");
    
    var cache = {}, lastXhr;
    $("#tags").autocomplete({
            minLength: 2,
            source: function( request, response ) {
                    var term = request.term;
                    if ( term in cache ) {
                            response( cache[ term ] );
                            return;
                    }

                    lastXhr = $.getJSON( "tags-search.php", request, function( data, status, xhr ) {
                            cache[ term ] = data;
                            if ( xhr === lastXhr ) {
                                    response( data );
                            }
                    });
            }
    });
    
});