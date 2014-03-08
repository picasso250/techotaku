$(function () {
    $('.upvote-btn').click(function () {
        var id = $(this).data('id');
        $.post('/upvote', {id: id}, function (ret) {
            $('#Point'+id).text(ret.data);
        }, 'json');
    });
});
