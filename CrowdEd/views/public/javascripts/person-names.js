/* 
 * @copyright Garrick S. Bodine, 2012
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 */

if (typeof Omeka === 'undefined') {
    Omeka = {};
}

Omeka.Search = {};

/**
 * Activate onclick handlers for the dynamic add/remove buttons on the
 * advanced search form.
 * 
 * This based on the Omeka search button related functions largely
 * 
 */
Omeka.Search.activatePersonFields = function () {
    var addButton = jQuery('.add_person');
    var removeButtons = jQuery('.remove_person');
    handleRemovePersonButtons();

    /**
     * Callback for adding a new row of person name fields.
     */
    function addPersonNames() {
        //Copy the div that is already on the search form
        var oldDiv = jQuery('.personName').last();

        //Clone the div and append it to the form
        //Passing true should copy listeners, interacts badly with Prototype.
        var div = oldDiv.clone();

        oldDiv.parent().append(div);

        var inputs = div.find('input');

        //Find the index of the last advanced search formlet and inc it
        //I.e. if there are two entries on the form, they should be named advanced[0], advanced[1], etc
        var inputName = inputs.last().attr('name');

        //Match the index, parse into integer, increment and convert to string again
        var index = inputName.match(/advanced\[(\d+)\]/)[1];
        var newIndex = (parseInt(index) + 1).toString();

        //Reset the selects and inputs
        inputs.val('');
        inputs.attr('name', function () {
            return this.name.replace(/\d+/, newIndex);
        });

        selects.val('');
        selects.attr('name', function () {
            return this.name.replace(/\d+/, newIndex);
        });

        //Add the event listener.
        div.find('button.remove_search').click(function () {
            removeAdvancedSearch(this);
        });

        handleRemovePersonButtons();
    }

    /**
     * Callback for removing an advanced search row.
     *
     * @param {Element} button The clicked delete button.
     */
    function removePerson(button) {
        jQuery(button).parent().remove();
        handleRemovePersonButtons();
    }

    /**
     * Check the number of advanced search elements on the page and only enable
     * the remove buttons if there is more than one.
     */
    function handleRemovePersonButtons() {
        var removeButtons = jQuery('.remove_person');
        if (removeButtons.length <= 1) {
            removeButtons.attr('disabled', 'disabled').hide();
        } else {
            removeButtons.removeAttr('disabled').show();
        }
    }

    //Make each button respond to clicks
    addButton.click(function () {
        addPerson();
    });
    removeButtons.click(function () {
        removePerson(this);
    });
};


