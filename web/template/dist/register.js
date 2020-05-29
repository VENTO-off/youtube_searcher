/**
 * Register user
 */
function register(button) {
    let login = $("#loginField").val();
    let password = $("#passwordField").val();

    if (login === "" || password === "") {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'ajax/register.php',
        dataType: 'json',
        data: {
            login: login,
            password: password
        },
        beforeSend: function () {
            $(button).prop("disabled", true);
        },
        error: function () {
            $(button).prop("disabled", false);
            ohSnap("An error occurred while processing your request.");
        },
        success: function (result) {
            ohSnap(result.message);

            if (!result.error) {
                setTimeout(function () {
                    document.location.href = "/";
                }, 1000);
            } else {
                $(button).prop("disabled", false);
            }
        }
    });
}