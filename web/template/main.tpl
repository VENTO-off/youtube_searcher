<!DOCTYPE html>
<html lang="en">

<head>
    <!-- General -->
    <title>YouTube Searcher</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <!-- Styles -->
    <link rel="stylesheet" href="dist/style.css">
    <!-- Fonts -->
    <link rel="stylesheet" href="dist/fonts.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css">
    <!-- jQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- jQuery Modal -->
    <script src="dist/jquery.modal-0.9.1.js"></script>
    <link rel="stylesheet" href="dist/jquery.modal-0.9.1.css">
    <!-- OhSnap -->
    <script src="dist/ohsnap.js"></script>
    <link rel="stylesheet" href="dist/ohsnap.css">
    <!-- AJAX Scripts -->
    <script type="text/javascript" src="dist/search.js"></script>
    <script type="text/javascript" src="dist/watch.js"></script>
    <script type="text/javascript" src="dist/favorite.js"></script>
    <script type="text/javascript" src="dist/register.js"></script>
    <script type="text/javascript" src="dist/login.js"></script>
</head>

<body>
    <!-- NOTIFICATIONS -->
    <div id="notifications"></div>
    <!-- END OF NOTIFICATIONS -->

    <div class="layout">
        <!-- LOGO -->
        <div class="logo">
            <a href="/">
                <img src="assets/img/logo.png" alt="LOGO">
            </a>
        </div>
        <!-- END OF LOGO -->

        <!-- SEARCH BAR -->
        {SEARCH_BAR}
        <!-- END OF SEARCH BAR -->

        <!-- CABINET -->
        {CABINET}
        <!-- END OF CABINET -->

        <!-- VIDEOS -->
        {VIDEOS}
        <!-- END OF VIDEOS -->
    </div>

    <!-- FOOTER -->
    {FOOTER}
    <!-- END OF FOOTER -->
</body>
</html>