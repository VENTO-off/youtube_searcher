/**
 * Modal window with video
 */
const modal =   "<div class='modal'>\
                    <div class='center'>\
                        <iframe width='100%' height='100%' src='https://www.youtube.com/embed/{VIDEO_ID}' frameborder='0' allowfullscreen></iframe>\
                    </div>\
                </div>";

/**
 * Open modal video to watch video
 */
function openVideo(video_id) {
    $(modal.replace("{VIDEO_ID}", video_id)).appendTo('body').modal();
}