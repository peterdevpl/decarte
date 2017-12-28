require('../scss/main.scss');

var $sliderImages = $('#slider img');
if ($sliderImages.length > 1) {
    $thumbnails = $('<ul id="slider-thumbnails" />');
    $sliderImages.each(function(index) {
        $thumbnails.append('<li><img src="' + $(this).attr('src') + '" data-index="' + (index + 1) + '"></li>');
    });
    $('#slider').after($thumbnails);

    $('#slider-thumbnails img').click(function() {
        var index = $(this).data('index');
        $('#slider img:not(:nth-child(' + index + '))').css('opacity', 0);
        $('#slider img:nth-child(' + index + ')').css('opacity', 1);
    })
}
