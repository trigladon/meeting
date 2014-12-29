var AssetPage = function(){

    var init = function(){

        view($('input[name=type]:checked').val());

        $('input[name=type]').click(function(){
            view($('input[name=type]:checked').val());
        });
    }

    var viewImageRows = function(){
        $('input[type=file]').closest('.form-group').show();
    };

    var hideImageRows = function(){
        $('input[type=file]').closest('.form-group').hide();
    };

    var viewVideoRows = function(){
        $('input[type=url]').closest('.form-group').show();
    };

    var hideVideoRows = function(){
        $('input[type=url]').closest('.form-group').hide();
    };

    var view = function(typeName){

        if (typeName == 'image') {
            hideVideoRows();
            viewImageRows();
        } else {
            hideImageRows();
            viewVideoRows();
        }
    }

    return {
        init: function(){
            init();
        }
    }
}();

var AssetAdd = function()
{
    var settings = {
        button: 'span.btn-block',
        video: 'video-block',
        image: 'image-block',
        youtube: {
            width: 199,
            height: 150,
            allowfullscreen: true
        }
    };



    var checkLink = function() {

        var url = $(this).closest('div.input-group').find('input').val();
        var match = url.match(/^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/);
        var youtube = $(this).closest('div.input-group').find('div.youtube-iframe');

        if (match && match[1].length == 11){
            $(this).closest('div').removeClass('has-error').find('span.help-block').hide();
            youtube.hide().html(createYoutubeIframe(match[1])).toggle('fast');
        } else {

            if (youtube.html().length > 0) {
                youtube.toggle('fast');
                youtube.html("");
            }
            $(this).closest('div').addClass('has-error').find('span.help-block').show();
        }
    };

    var maxAssetsIndex = function(){
        var aIndex = -1;

        $('.assets-blocks input.asset-id').each(function(index, element){
            var i = assetIndex($(element));
            if (aIndex < i){
                aIndex = i;
            }
        });

        return aIndex;
    };

    var assetIndex = function(element){
        return element.attr('name').match(/assets\[(\d+)\]\[id\]/)[1];
    };

    var createYoutubeIframe = function(url){
        var html = '<iframe ' +
            'frameborder="0" ' +
            'width="'+settings.youtube.width+'" ' +
            'height="'+settings.youtube.height+'" ' +
            'src="http://www.youtube.com/embed/'+url+'" '+
            (settings.youtube.allowfullscreen ? ' allowfullscreen ' : '') +
            '></iframe>';

        return html;
    };

    var removeBlock = function(){
        $('.remove-it').closest('.asset-block').remove();
    };

    var addBlock = function(){
        var $this = $(this);
        var name = $this.attr('name');

        if (settings.hasOwnProperty(name)) {
            var block = $('.'+settings[name]).clone(true).removeClass(settings[name]);
            index = index === null ?  maxAssetsIndex() : index;
            var template = block.html();
            block.html(template.replace(/___INDEX___/g, (index < 0 ? 0 : parseInt(index) + 1)));
            block.insertBefore($this.parent());
            index++;
        }
    };

    var init = function(options){
        $(options.button).click(addBlock);
        $('span[name=modal-action]').click(removeBlock);
        $(document).on('click', 'span.check-youtube-url', checkLink);
    };

    var index = maxAssetsIndex();

    return {
        init: function(options){
            init($.extend(settings, options));
        }
    };

}();

