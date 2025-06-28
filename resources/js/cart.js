(function ($) {

    // this js code event when change and update silent into the backend
    $('.item-quantity').on('change', function (e) {
        $.ajax({
            url: "/cart/" + $(this).data('id'), // get the attribute have the data-id
            method: 'put',
            data: {
                quantity: $(this).val(),
                _token: csrf_token
            }
        });
    });

    // and i need to make silent event when clike remove item
    $('.remove-item').on('click', function (e) {
        let id = $(this).data('id');
        $.ajax({
            url: "/cart/" + id, // get the attribute have the data-id
            method: 'delete',
            data: {
                _token: csrf_token
            },
            success: response => {
                // select element the id = ${id}
                $(`#${id}`).remove();
            }
        });
    });

    // and i need to make anther event to add new item
    $('.add-to-cart').on('click', function (e) {
        $.ajax({
            url: "/cart",
            method: 'post',
            data: {
                product_id: $(this).data('id'),
                quantity: $(this).data('quantity'),
                _token: csrf_token
            },
            success: response => {
                // in here you can to use the switeAllert library is so Gooood Lib view
                allert('Product added successfully');
            }
        });
    });


})(jQuery);
