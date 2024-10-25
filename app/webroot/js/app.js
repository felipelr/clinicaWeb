//var $NOME_APLICACAO = "/sistema";
var $NOME_APLICACAO = "/sistema";

jQuery(function () {
    $(".dropdown-custom a").click(function () {
        if ($(this).closest('li').hasClass('active')) {
            $(this).closest('li').removeClass('active');
            $(this).next('.sub-custom').slideUp('slow');
        } else {
            $(this).closest('li').addClass('active');
            $(this).next('.sub-custom').slideDown('slow');
        }
    });
});