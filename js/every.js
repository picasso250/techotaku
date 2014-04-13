$(document).ajaxComplete(function (event, xhr) {
        // console.log('ajaxSetup',ret);
        var ret = xhr.responseText;
        var match = /^javascript:(.+)/.exec(ret);
        if (match) {
            eval(match[1]);
        };
    });
$(function () {
    $('.mask').each(function () {
        var h = $(this).parent().find('.thing').height()+'px';
        $(this).css('line-height', h);
        if ($(this).data('mask-disabled')) {
            $(this).hide();
        };
    });
    $('.upvote-btn').click(function () {
        var id = $(this).data('id');
        $(this).hide();
        $.post('/upvote', {id: id}, function (ret) {
            $('#Point'+id).text(ret.data);
        }, 'json');
    });
    $('form[role=ajax-form').submit(function (e) {
        var $this = $(this);
        var opts = {
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: 'json',
            success: function (ret) {
                console.log(ret);
                if (ret.code == 0) {
                    // ok
                    var code = $this.data('success');
                    console.log(code);
                    eval(code);
                } else {
                    alert(ret.msg);
                }
            }
        };
        console.log(opts);
        $.ajax(opts);
        e.preventDefault();
        return false;
    });
});
