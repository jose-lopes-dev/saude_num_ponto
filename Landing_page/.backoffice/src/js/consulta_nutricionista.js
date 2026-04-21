$(document).ready(function () {

    /* ========= VARIÁVEIS PRIMEIRO (IMPORTANTE) ========= */

    const paginas = {
        13: 1,
        15: 1,
        4: 1
    };


    /* ========= INIT ========= */

    validarSessao();
    carregarConsultas();
    carregarProximasConsultasNutri();


    /* ========= SESSÃO ========= */

    function validarSessao() {
        $.post("./src/controller/consultaNutriController.php", {
            acao: "sessionNutri"
        }, function (r) {
            try { r = JSON.parse(r); } catch { return; }

            if (!r.id) {
                Swal.fire("Sessão expirada", "Volte a iniciar sessão", "error");
            }
        });
    }


    /* ========= PAGINAÇÃO LISTAS ========= */

    function carregarLista(estado, container, page = 1) {

        paginas[estado] = page;

        const box = $(container);

        box.html(`<div class="text-center text-muted p-2">A carregar...</div>`);

        $.post("./src/controller/consultaNutriController.php", {
            acao: "listar",
            estado: estado,
            page: page
        }, function (resp) {

            let r;
            try { r = JSON.parse(resp); } catch { return; }

            box.empty();

            if (!r.dados || !r.dados.length) {
                box.html(`<div class="text-muted p-2">Sem consultas</div>`);
                return;
            }

            r.dados.forEach(c => {

                let botoes = "";

                if (estado === 13) {
                    botoes = `
                        <br>
                        <button class="btn btn-success btn-sm aceitar" data-id="${c.id}">Aceitar</button>
                        <button class="btn btn-danger btn-sm recusar" data-id="${c.id}">Recusar</button>
                    `;
                }

                box.append(`
                    <div class="list-group-item">
                        <b>${c.cliente}</b><br>
                        <small>${c.data_hora}</small>
                        ${botoes}
                    </div>
                `);
            });

            if (r.paginas > 1) {

                let pagHtml = `<div class="mt-2 text-center">`;

                for (let i = 1; i <= r.paginas; i++) {

                    let active = i === page ? "btn-primary" : "btn-outline-secondary";

                    pagHtml += `
                        <button class="btn btn-sm ${active} mx-1 page-btn"
                                data-estado="${estado}"
                                data-page="${i}">
                            ${i}
                        </button>
                    `;
                }

                pagHtml += `</div>`;

                box.append(pagHtml);
            }
        });
    }


    function carregarConsultas() {
        carregarLista(13, "#lista_pendentes");
        carregarLista(15, "#lista_aceites");
        carregarLista(4, "#lista_recusadas");
    }


    /* ========= CLICK PAGINAÇÃO ========= */

    $(document).on("click", ".page-btn", function () {

        const estado = $(this).data("estado");
        const page = $(this).data("page");

        const container =
            estado == 13 ? "#lista_pendentes" :
            estado == 15 ? "#lista_aceites" :
            "#lista_recusadas";

        carregarLista(estado, container, page);
    });


    /* ========= AÇÕES ========= */

    $(document).on("click", ".aceitar", function () {

        let id = $(this).data("id");

        Swal.fire({
            title: "Aceitar consulta?",
            icon: "question",
            showCancelButton: true
        }).then(res => {

            if (!res.isConfirmed) return;

            $.post("./src/controller/consultaNutriController.php", {
                acao: "aceitar",
                id_consulta: id
            }, () => {

                Swal.fire("Confirmada!", "", "success");
                carregarConsultas();
            });
        });
    });


    $(document).on("click", ".recusar", function () {

        let id = $(this).data("id");

        Swal.fire({
            title: "Recusar consulta?",
            icon: "question",
            showCancelButton: true
        }).then(res => {

            if (!res.isConfirmed) return;

            $.post("./src/controller/consultaNutriController.php", {
                acao: "recusar",
                id_consulta: id
            }, () => {

                Swal.fire("Recusada!", "", "success");
                carregarConsultas();
            });
        });
    });

    function carregarProximasConsultasNutri() {

    const tbody = $("#tabela-proximas-consultas-nutri");

    if (!tbody.length) return;

    tbody.html(`
        <tr>
            <td colspan="5" class="text-center text-muted">
                A carregar...
            </td>
        </tr>
    `);

    $.post(
        "./src/controller/consultaNutriController.php",
        { acao: "proximasConsultasNutri" },
        function (r) {

            tbody.empty();

            if (!Array.isArray(r) || !r.length) {
                tbody.append(`
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            Sem consultas confirmadas.
                        </td>
                    </tr>
                `);
                return;
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
                `);
            });
        },
        "json"
    );
}


});
