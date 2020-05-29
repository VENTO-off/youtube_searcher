/**
 * Search videos
 */
function search() {
    let search = $("#searchField").val();

    if (search === "") {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'ajax/search.php',
        dataType: 'json',
        data: {
            q: search
        },
        beforeSend: onSend,
        error: onError,
        success: onSuccess
    });
}

/**
 * Navigate between pages
 */
function navigatePage(search, numberPage) {
    if (search === "" || numberPage === "") {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'ajax/search.php',
        dataType: 'json',
        data: {
            q: search,
            page: numberPage
        },
        beforeSend: onSend,
        error: onError,
        success: onSuccess
    });
}

/**
 * Show preloader
 */
function onSend() {
    let preloader = '<img class="preloader" src="assets/img/preloader.gif">';
    render(preloader);
}

/**
 * Show error message
 */
function onError() {
    let error = '<p class="error">An error occurred while processing your request.</p>';
    render(error);
}

/**
 * Show videos / error message
 */
function onSuccess(json) {
    if (json === "") {
        return;
    }

    if (json.hasOwnProperty("error")) {
        let error = '<p class="error">' + json.error + '</p>';
        render(error);
        return;
    }

    render(json.html);
}

/**
 * Render response from server
 */
function render(element) {
    $('.videos').empty();
    $(element).appendTo('.videos');
}

