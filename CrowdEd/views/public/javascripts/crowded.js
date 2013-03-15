/* 
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

 function extractor(query) {
    var result = /([^,]+)$/.exec(query);
    if(result && result[1])
        return result[1].trim();
    return '';
}

jQuery(document).ready(function($) {
    
    $("input:submit, a, button", ".buttonbar").button();
    
    $('#flash').modal('show');
    $('#flash ul').addClass("unstyled");
    $('.success').addClass("unstyled alert alert-success").prepend("<h4><i class='icon-ok-sign icon-large'></i> Success!</h4>");
    $('.failure').addClass("unstyled alert alert-error").prepend("<h4><i class='icon-remove-sign icon-large'></i> Uh oh!</h4>");
    $('.error').addClass("unstyled alert alert-error").prepend("<h4><i class='icon-remove-sign icon-large'></i> Uh oh!</h4>");
    $('.helpText').tooltip({html:true});
    $(".explanation").tooltip({html:true});
    
    $('.tagManager').tagsManager();
    $('.popHelp').tooltip({html:true});
    
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