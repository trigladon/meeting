if (typeof jQuery === "undefined") { throw new Error("Bootstrap requires jQuery") }

$(document).ready(function(){

    $('span.refresh').click(function(){
        var url = $(this).data('url');
        var data = $('[name = "captcha[id]"]');
        var spinner = $('.captcha-loading');
        var captcha = $('.captcha-output');

        captcha.hide();
        spinner.show();

        $.ajax({
            url: url,
            type: "post",
            dataType: 'json',
            success: function(msg){
                data.attr('value', msg.id);
                data.parent().find('img').attr('src', msg.src);
                spinner.hide();
                captcha.show();

            }
        })

    })
//
    $('[name = "captcha[input]"]').wrap('<div class="input-icon"></div>');
    $('[name = "captcha[input]"]').before('<i class="fa fa-cog"></i>');
});