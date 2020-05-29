<div class="video">
    <div class="image_area" onclick="openVideo('{VIDEO_ID}');"><img src="{IMAGE_URL}"></div>
    <div class="info" onclick="openVideo('{VIDEO_ID}');">
        <div class="row">
            <div class="text">
                <p class="title">{TITLE}</p>
            </div>
        </div>
        <div class="row">
            <div class="text">
                <p class="gray">{PUBLISHED_AT}</p>
                <p class="date">{DATE}</p>
            </div>
        </div>
    </div>
    <div class="action">
        <button onclick="favoriteVideo(this, '{FAVORITE_REQUEST}');" class="{SELECTED}"><i class="fa fa-thumbs-up"></i></button>
    </div>
</div>