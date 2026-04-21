$(function () {

    if (!$("#lista_pendentes").length) return

    $.post("./src/controller/consultaPTController.php", {
        acao: "sessionPT"
    }, function (r) {
        r = JSON.parse(r)
        if (!r.id) {
            Swal.fire({
                icon: "error",
                title: "Sessão expirada",
                text: "Volte a iniciar sessão"
            }).then(() => {
                location.href = "login.html"
            })
        }
    })

    function carregar() {
        $.post("./src/controller/consultaPTController.php", {
            acao: "listar"
        }, function (r) {
            r = JSON.parse(r)

            $("#lista_pendentes").empty()
            $("#lista_aceites").empty()
            $("#lista_recusadas").empty()

            r.pendentes.forEach(c => {
                $("#lista_pendentes").append(`
                    <div class="list-group-item">
                        <b>${c.cliente}</b><br>
                        <small>${c.data_hora}</small><br>
                        <button class="btn btn-success btn-sm aceitar" data-id="${c.id}">Aceitar</button>
                        <button class="btn btn-danger btn-sm recusar" data-id="${c.id}">Recusar</button>
                    </div>
                `)
            })

            r.aceites.forEach(c => {
                $("#lista_aceites").append(`
                    <div class="list-group-item">
                        ${c.cliente} • <small>${c.data_hora}</small>
                    </div>
                `)
            })

            r.recusadas.forEach(c => {
                $("#lista_recusadas").append(`
                    <div class="list-group-item">
                        ${c.cliente} • <small>${c.data_hora}</small>
                    </div>
                `)
            })
        })
    }

    carregar()

    $(document).on("click", ".aceitar", function () {
        let id = $(this).data("id")

        Swal.fire({
            title: "Aceitar consulta?",
            text: "Esta consulta será adicionada ao seu calendário",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Aceitar",
            cancelButtonText: "Cancelar"
        }).then(res => {
            if (res.isConfirmed) {
                $.post("./src/controller/consultaPTController.php", {
                    acao: "aceitar",
                    id_consulta: id
                }, () => {
                    Swal.fire("Consulta confirmada", "O cliente foi notificado. A consulta ficou confirmada.", "success")
                    carregar()
                })
            }
        })
    })
    

    $(document).on("click", ".recusar", function () {
        let id = $(this).data("id")

        Swal.fire({
            title: "Recusar consulta?",
            text: "Esta ação não pode ser revertida",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Recusar",
            cancelButtonText: "Cancelar"
        }).then(res => {
            if (res.isConfirmed) {
                $.post("./src/controller/consultaPTController.php", {
                    acao: "recusar",
                    id_consulta: id
                }, () => {
                    Swal.fire("Consulta recusada", "O cliente foi notificado. A consulta foi recusada.", "success")
                    carregar()
                })
            }
        })
    })

    
function carregarProximasConsultasPT() {

    const tbody = $("#tabela-proximas-consultas-pt")
    if (!tbody.length) return

    tbody.html(`
        <tr>
            <td colspan="5" class="text-center text-muted">
                A carregar...
            </td>
        </tr>
    `)

    $.post(
        "./src/controller/consultaPTController.php",
        { acao: "proximasConsultasPT" },
        function (r) {

            tbody.empty()

            if (!Array.isArray(r) || r.length === 0) {
                tbody.append(`
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Sem consultas confirmadas.
                        </td>
                    </tr>
                `)
                return
            }

            r.forEach(c => {
                tbody.append(`
                    <tr>
                        <td>${c.cliente}</td>
                        <td>${c.servico}</td>
                        <td>${c.data}</td>
                        <td>${c.hora}</td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-success">
                                Entrar na consulta
                            </button>
                        </td>
                    </tr>
                `)
            })
        },
        "json"
    )
}
carregarProximasConsultasPT()

})
