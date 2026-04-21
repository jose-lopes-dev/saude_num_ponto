$(document).ready(function () {
    carregarPerfilPrestador()

    $("#uploadFoto").on("change", function () {
        const file = this.files[0]
        if (!file) return

        const reader = new FileReader()
        reader.onload = e => $("#fotoPerfil").attr("src", e.target.result)
        reader.readAsDataURL(file)
    })

    $("#btnGuardarPerfil").on("click", function (e) {
        e.preventDefault()
        guardarPerfil()
    })
})

function carregarPerfilPrestador() {

    const dados = new FormData()
    dados.append("op", 1)

    $.ajax({
        url: "src/controller/controllerPerfilPrestador.php",
        type: "POST",
        data: dados,
        contentType: false,
        processData: false,
        dataType: "json"
    }).done(res => {

        if (!res.flag || !res.dados) return

        const d = res.dados

        $("#nome_completo").val(d.nome_completo ?? "")
        $("#nif").val(d.nif ?? "")
        $("#contacto").val(d.contacto ?? "")
        $("#qualificacao").val(d.qualificacao ?? "")
        $("#experiencia_anos").val(d.experiencia_anos ?? "")

        $("#nomePerfil").text(d.nome_completo ?? "---")
        $("#funcaoPerfil").text(d.funcao || "Prestador")

        if (d.foto) {
            $("#fotoPerfil").attr("src", d.foto)
        }
    })
}

function guardarPerfil() {

    const form = document.getElementById("formPerfil")
    if (!form) return

    const dados = new FormData(form)
    dados.append("op", 2)

    $.ajax({
        url: "src/controller/controllerPerfilPrestador.php",
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
            carregarPerfilPrestador()
        }
    })
    .fail(xhr => {
        console.error(xhr.responseText)
        Swal.fire({
            icon: "error",
            text: "Erro ao guardar perfil"
        })
    })
}
