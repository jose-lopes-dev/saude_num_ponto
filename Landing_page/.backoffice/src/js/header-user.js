$(document).ready(function () {

    $.ajax({
        url: "src/controller/controllerHeaderUser.php",
        type: "GET",
        dataType: "json"
    }).done(res => {

        if (!res.flag) return

        const d = res.dados

        $("#user-name").text(d.nome)
        $("#user-role").text(d.role)

        if (d.foto) {
            $("#user-avatar").attr("src", d.foto)
        }
    })
})
