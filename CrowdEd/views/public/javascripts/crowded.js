/* 
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

jQuery(document).ready(function($) {
    // $(".explanation").before("<span class='help_icon ui-widget ui-icon ui-icon-info' style='float: left; margin-right: .3em;'></span>").addClass("ui-widget");
    /* $(".help_icon").bind("mouseover mouseout", function() {
        $(this).next(".explanation").toggle();
    });*/
    $("input:submit, a, button", ".buttonbar").button();
    
    $(".success").addClass("alert alert-success").prepend("<i class='icon-ok-sign'></i> ");
    $(".failure").addClass("alert alert-error").prepend("<i class='icon-remove-sign'></i> ");
    
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