var Collection = function(){

    var init = function(id){

        $(document).on('change', id, function(){

            $('.language').hide();
            $('.language-'+$(this).find(':selected').val()).show();

        });

        //$('.language').hide();
        //$('.language-'+$(id).find(':selected').val()).show();

    };

  return {
        init: function(id){
            init(id);
        }
  };
}();