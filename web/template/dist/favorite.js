/**
 * Add / remove video from favorites
 */
function favoriteVideo(button, video_data) {
    if (video_data === "") {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'ajax/favorite.php',
        dataType: 'json',
        data: {
            video: video_data
        },
        success: function (result) {
            if (!result.error) {
                $(button).toggleClass("selected");
            }

            ohSnap(result.message);
        }
    });
}