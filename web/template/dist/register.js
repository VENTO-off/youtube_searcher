/**
 * Register user
 */
function register(button) {
    let login = $("#loginField").val();
    let password = $("#passwordField").val();
    let password2 = $("#passwordField2").val();

    if (login === "" || password === "" || password2 === "") {
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'ajax/register.php',
        dataType: 'json',
        data: {
            login: login,
            password: password,
            password2: password2
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