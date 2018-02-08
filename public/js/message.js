$(function() {
    $('.removeMessage').on('click', function(){
        var id = $(this).data('id');
        var _this = $(this);
        $.ajax({
            type: "GET",
            url: '/msg/remove',
            data: { id: id },
            dataType:'json'
        }).done(function(result) {
            _this.parents('tr').slideUp();

        }).fail(function() {
            console.log(result);
        });

    });
});