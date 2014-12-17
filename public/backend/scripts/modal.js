
var Modal = function() {

    var defaultValue = {
        name: '#basic',
        title: 'Confirm modal',
        description: 'Are sure want do this?',
        button: 'Yes',
        color: 'btn btn-primary',
        url: '#',
        removeClass: 'remove-it'
    };

    var open = function(){
        var modal = $(defaultValue.name);
        modal.find('.modal-title').empty().html(getAttribute($(this), 'title'));
        modal.find('.modal-body').empty().html(getAttribute($(this), 'description'));

        var button = modal.find('.modal-footer a');
        if (button.length){
            button.attr('href', getAttribute($(this), 'url'));
        } else {
            $('.'+defaultValue.removeClass).removeClass(defaultValue.removeClass);
            $(this).addClass(defaultValue.removeClass);
            button = modal.find('.modal-footer span');
        }
        button.empty().html(getAttribute($(this), 'button'));
        button.removeClass().addClass(getAttribute($(this), 'color'));

    };

    var close = function(){
        var modal = $(defaultValue.name);
        modal.find('.modal-title').empty().html(defaultValue['title']);
        modal.find('.modal-body').empty().html(defaultValue['description']);
        var button = modal.find('.modal-footer a');
        if (button.length){
            button.attr('href', defaultValue['url']);
        } else {
            button = modal.find('.modal-footer span');
        }

        button.empty().html(defaultValue['button']);
        button.removeClass().addClass(defaultValue['color']);
    };

    var getAttribute = function(element, attr){
        var value = element.attr(attr);
        if (typeof value !== typeof undefined && value !== false){
            return value;
        } else {
            return defaultValue[attr];
        }
    };

    var init = function(options) {
        $(document).on("click", 'span[href="'+options.name+'"]', open);
        $(options.name).on("click", 'button[data-dismiss="modal"]', close)
    };

    return {
        init: function(options){
            init($.extend(defaultValue, options));
        }
    }

}();