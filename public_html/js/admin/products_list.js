$(document).ready(function() {
    $('.products li input').blur(function() {
        $.post(
            $(this).data('action'),
            {
                productId: $(this).data('product-id'),
                position: $(this).val()
            }
        );
    });
});
