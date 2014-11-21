
var Modal = function() {

    var modalName = null;
    var defaultValue = {
        title: 'Confirm modal',
        description: 'Are sure want do this?',
        button: 'Yes',
        color: 'btn btn-primary',
        url: '#'
    };

    var open = function(){
        var modal = $(modalName);
        modal.find('.modal-title').empty().html(getAttribute($(this), 'title'));
        modal.find('.modal-body').empty().html(getAttribute($(this), 'description'));
        modal.find('.modal-footer a').empty().html(getAttribute($(this), 'button'));
        modal.find('.modal-footer a').removeClass().addClass(getAttribute($(this), 'color'));
        modal.find('.modal-footer a').attr('href', getAttribute($(this), 'url'));
    };

    var close = function(){
        var modal = $(modalName);
        modal.find('.modal-title').empty().html(defaultValue['title']);
        modal.find('.modal-body').empty().html(defaultValue['description']);
        modal.find('.modal-footer a').empty().html(defaultValue['button']);
        modal.find('.modal-footer a').removeClass().addClass(defaultValue['color']);
        modal.find('.modal-footer a').attr('href', defaultValue['url']);

    };

    var getAttribute = function(element, attr){
        var value = element.attr(attr);
        if (typeof value !== typeof undefined && value !== false){
            return value;
        } else {
            return defaultValue[attr];
        }
    };

    var init = function() {
        var name = 'span[href="'+modalName+'"]';
        $(document).on("click", name, open);
        $(modalName).on("click", 'button[data-dismiss="modal"]', close)
    };

    return {
        init: function(name){
            modalName = name;
            init();
        }
    }

}();