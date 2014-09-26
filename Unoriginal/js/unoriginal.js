$ = jQuery;
$(document).ready(function(){
    
    var action;
    
    function navCall() {
        var path = $('#lyric-data').attr('data-path');
        var song = $('#lyric-data').attr('data-lyric-song');
        var chosen = $('#lyric-data').attr('data-lyric-current');
        var max = $('#lyric-data').attr('data-lyric-count');
        $.ajax({
            type: "POST",
            url: path+"nav.php",
            data:{action: action, path: path, song: song, chosen: chosen, max: max},
            timeout: 6000,
            error: function(request,error) {
            },
            success: function(data) {
                $('#lyric').html(data);
            }
        });
        console.log(path);
        console.log(song);
        console.log(chosen);
        console.log(max);
    }
    $(document).on('click','.lyric-next',function(){
        action = "next";
        navCall();
    });
    
    $(document).on('click','.lyric-prev',function(){
        action = "prev";
        navCall();
    });
});
