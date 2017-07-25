jQuery(document).ready(function($){
    $("p.sermon-info").bind("click", function(){
        $(this).next(".mediaSlide").slideToggle();
    });
});

function popVideoWin(sermonID) {
    popvideo = window.open(mbcMediaPlayerDir+'inc/video-player.php?id='+sermonID,'videoplayer','width=700,height=600,status=no');
    return false;
}

function popAudioWin(sermonID) {
    popaudio = window.open(mbcMediaPlayerDir+'inc/audio-player.php?id='+sermonID,'audioplayer','width=350,height=200,status=no');
    return false;
}
