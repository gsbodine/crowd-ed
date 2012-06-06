/* 
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

jQuery(document).ready(function($) {
    $(".explanation").before("<div class='help_icon'><img src='/plugins/CrowdEd/views/public/images/info_icon.png' alt='help' /></div>");
    $(".help_icon").bind("mouseover mouseout", function() {
        $(this).next(".explanation").toggle();
    });
});