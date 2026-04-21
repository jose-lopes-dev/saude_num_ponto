$(document).ready(function () {

    function tempoRelativo(data) {
        const diff = Math.floor((new Date() - new Date(data)) / 1000)
        if (diff < 60) return "agora mesmo"
        if (diff < 3600) return Math.floor(diff / 60) + " min atrás"
        if (diff < 86400) return Math.floor(diff / 3600) + " h atrás"
        return Math.floor(diff / 86400) + " dias atrás"
    }

    function carregarNotificacoes() {

        $.post(
            "src/controller/notificacoes.php",
            { op: "listar" },
            function (res) {

                const dados = JSON.parse(res)
                let html = ""
                let naoLidas = 0

                dados.forEach(n => {

                    if (n.lida == 0) naoLidas++

                    html += `
                        <div class="dropdown-item notification-item ${n.lida == 0 ? 'bg-soft-light' : ''}"
                             data-id="${n.id}">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fs-13">${n.titulo}</h6>
                                    <p class="mb-1 fs-12 text-muted">${n.texto ?? ""}</p>
                                    <small class="text-muted">${tempoRelativo(n.criada_em)}</small>
                                </div>
                            </div>
                        </div>
                    `
                })

                $("#notification-list").html(html)

                if (naoLidas > 0) {
                    $("#notification-count")
                        .removeClass("d-none")
                        .text(naoLidas)
                } else {
                    $("#notification-count").addClass("d-none")
                }
            }
        )
    }

    carregarNotificacoes()
    setInterval(carregarNotificacoes, 30000)

    $(document).on("click", ".notification-item", function () {

        const id = $(this).data("id")

        $.post(
            "src/controller/notificacoes.php",
            { op: "ler", id }
        )

        $(this).removeClass("bg-soft-light")
    })

    $("#btn-clear-notifications").click(function () {

        Swal.fire({
            title: "Remover todas as notificações?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Remover"
        }).then(r => {

            if (r.isConfirmed) {

                $.post(
                    "src/controller/notificacoes.php",
                    { op: "remover_todas" },
                    function () {

                        $("#notification-list").html("")
                        $("#notification-count").addClass("d-none")

                        Swal.fire(
                            "Removidas",
                            "Notificações limpas com sucesso",
                            "success"
                        )
                    }
                )
            }
        })
    })

})
