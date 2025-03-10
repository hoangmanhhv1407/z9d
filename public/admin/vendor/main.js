// active course ajax
$(function(){
    $(".msContact").click(function(e){
        e.preventDefault();
        $url = $(this).attr('data-url-contact');
        $id = $(this).attr('data-id-contact');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url : $url,
            type : 'POST',
            dataType:'JSON',
            data: { id : $id},
            success : function(responsive)
            {
                $('.model-contact').html('').append(responsive.c_content);
            },

        });
    });
});