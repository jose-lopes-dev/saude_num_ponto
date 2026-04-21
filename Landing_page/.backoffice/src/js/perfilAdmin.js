$(document).ready(function () {
    carregarPerfilAdmin()

    $("#uploadFoto").on("change", function () {
        const file = this.files[0]
        if (!file) return

        const reader = new FileReader()
        reader.onload = e => $("#fotoPerfil").attr("src", e.target.result)
        reader.readAsDataURL(file)
    })

    $("#btnGuardarPerfil").on("click", function (e) {
        e.preventDefault()
        guardarPerfilAdmin()
    })
})

function carregarPerfilAdmin() {

    const dados = new FormData()
    dados.append("op", 1)

    $.ajax({
        url: "src/controller/controllerPerfilAdmin.php",
        type: "POST",
        data: dados,
        contentType: false,
        processData: false,
        dataType: "json"
    }).done(res => {

        if (!res.flag || !res.dados) return

        const d = res.dados

        $("#nome_completo").val(d.nome_completo ?? "")
        $("#email").val(d.email ?? "")
        $("#nif").val(d.nif ?? "")
        $("#contacto").val(d.contacto ?? "")

        $("#nomePerfil").text(d.nome_completo ?? "Administrador")
        $("#funcaoPerfil").text(d.tipo_utilizador ?? "Administrador")

        if (d.foto) {
            $("#fotoPerfil").attr("src", d.foto)
        }
    })
}

function guardarPerfilAdmin() {

    const form = document.getElementById("formPerfil")
    if (!form) return

    const dados = new FormData(form)
    dados.append("op", 2)

    $.ajax({
        url: "src/controller/controllerPerfilAdmin.php",
        type: "POST",
        data: dados,
        contentType: false,
        processData: false,
        dataType: "json"
    })
    .done(res => {
        Swal.fire({
            icon: res.flag ? "success" : "error",
            text: res.msg
        })

        if (res.flag) {
            $("#uploadFoto").val("")
            carregarPerfilAdmin()
        }
    })
}
