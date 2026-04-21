$(document).ready(function () {

    $.post(
        "src/controller/utilizador.php",
        {},
        function (res) {

            if (!res || !res.username) return

            $("#user-name").text(res.username)
            $("#welcome-username").text(res.username)

        },
        "json"
    )

})
