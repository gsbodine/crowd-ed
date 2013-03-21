if (!Omeka) {
    var Omeka = {};
}

Omeka.Elements = {};

jQuery('document').ready(function ($) {
    
    Omeka.Elements.elementFormRequest = function (fieldDiv, params, elementFormPartialUri, recordType, recordId) {

        var elementId = fieldDiv.attr('id').replace(/element-/, '');
        
        fieldDiv.find('input, textarea, select').each(function () {
            var element = $(this);
            params[this.name] = element.val();
        });
        
        recordId = typeof recordId !== 'undefined' ? recordId : 0;
        
        params.element_id = elementId;
        params.record_id = recordId;
        params.record_type = recordType;
        
        $.ajax({
            url: elementFormPartialUri,
            type: 'POST',
            dataType: 'html',
            data: params,
            success: function (response) {
                fieldDiv.html(response);
                fieldDiv.trigger('omeka:elementformload');
            }
            
        }); 
    };
    
    Omeka.Elements.makeElementControls = function (element, elementFormPartialUrl, recordType, recordId) {
        var addSelector = '.add-element';
        var removeSelector = '.remove-element';
        var fieldSelector = 'div.field';
        var inputBlockSelector = 'div.input-block';
        var context = $(element);
        var fields;
        
        if (context.is(fieldSelector)) {
            fields = context;
        } else {
            fields = context.find(fieldSelector);
        }

        fields.each(function () {
            var removeButtons = $(this).find(removeSelector);
            if (removeButtons.length > 1) {
                removeButtons.show();
            } else {
                removeButtons.hide();
            }
        });
        
        context.find(addSelector).click(function (event) {
            event.preventDefault();
            var fieldDiv = $(this).parents(fieldSelector);
            Omeka.Elements.elementFormRequest(fieldDiv, {add: '1'}, elementFormPartialUrl, recordType, recordId);
        });

        context.find(removeSelector).click(function (event) {
            event.preventDefault();
            var removeButton = $(this);

            if (removeButton.parents(fieldSelector).find(inputBlockSelector).length == 1) {
                return;
            }

            if (!confirm('Are you sure you want to delete this person\'s information?')) {
                return;
            }

            var inputBlock = removeButton.parents(inputBlockSelector);
            inputBlock.remove(); 

            $(fieldSelector).each(function () {
                var removeButtons = $(this).find(removeSelector);
                if (removeButtons.length === 1) {
                    removeButtons.hide();
                }
            });
        });
    };

});
