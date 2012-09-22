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
    
    $('.success').addClass("alert alert-success").prepend("<h4><i class='icon-ok-sign icon-large'></i> Success!</h4>");
    $('.failure').addClass("alert alert-error").prepend("<h4><i class='icon-remove-sign icon-large'></i> Sorry!</h4>");
    
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
    
 // following http://omeka.org/forums/topic/customize-advanced-search-1 -- hopefully temporary.
    var blackListGroups = [
        "Item Type Metadata",
        "Contribution Form"
    ];
    var blackListElements = [
        "Contributor",
        "Coverage",
        "Format",
        "Language",
        "Relation",
        "Rights",
        "Source",
        "Type",
        "Publisher",
        "Subject"
    ];
    var blackListItemTypes = [
        "Moving Image",
        "Oral History",
        "Sound",
        "Moving Image",
        "Website",
        "Event",
        "Email",
        "Lesson Plan",
        "Hyperlink",
        "Interactive Resource",
        "Person"
    ];
    jQuery.each(blackListGroups, function (index, value) {
        jQuery("#advanced-0-element_id optgroup[label='" + value + "']").remove();
    });
    jQuery.each(blackListElements, function (index, value) {
        jQuery("#advanced-0-element_id option[label='" + value + "']").remove();
    });
    jQuery.each(blackListItemTypes, function (index, value) {
        jQuery("#item-type-search option[label='" + value + "']").remove();
    });
    
    
});