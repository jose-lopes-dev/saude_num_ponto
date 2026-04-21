$(document).ready(function () {

    validarSessao();
    carregarSessoes();

    function validarSessao() {
        $.post("./src/controller/consultaPsicologoController.php", {
            acao: "sessionPsicologo"
        }, function (r) {
            r = JSON.parse(r);
            if (!r.id) {
                Swal.fire("Sessão expirada", "Volte a iniciar sessão", "error");
            }
        });
    }

    function carregarSessoes() {

        $.post("./src/controller/consultaPsicologoController.php", {
            acao: "listar"
        }, function (dados) {

            dados = JSON.parse(dados);

            $("#lista_pendentes").html("");
            $("#lista_aceites").html("");
            $("#lista_recusadas").html("");

            dados.pendentes.forEach(s => {
                $("#lista_pendentes").append(`
                    <div class="list-group-item">
                        <b>${s.paciente}</b><br>
                        <small>${s.data_hora}</small><br>
                        <button class="btn btn-success btn-sm aceitar" data-id="${s.id}">Aceitar</button>
                        <button class="btn btn-danger btn-sm recusar" data-id="${s.id}">Recusar</button>
                    </div>
                `);
            });

            dados.aceites.forEach(s => {
                $("#lista_aceites").append(`
                    <div class="list-group-item">
                        ${s.paciente} • <small>${s.data_hora}</small>
                    </div>
                `);
            });

            dados.recusadas.forEach(s => {
                $("#lista_recusadas").append(`
                    <div class="list-group-item">
                        ${s.paciente} • <small>${s.data_hora}</small>
                    </div>
                `);
            });

        });
    }

    $(document).on("click", ".aceitar", function () {

        let id = $(this).data("id");

        Swal.fire({
            title: "Aceitar sessão?",
            text: "Esta sessão será adicionada ao teu calendário.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sim, aceitar",
            cancelButtonText: "Cancelar"
        }).then(res => {

            if (!res.isConfirmed) return;

            $.post("./src/controller/consultaPsicologoController.php", {
                acao: "aceitar",
                id_consulta: id
            }, () => {

                Swal.fire(
                    "Sessão confirmada",
                    "O cliente foi notificado. A sessão ficou confirmada.",
                    "success"
                );

                carregarSessoes();
            });
        });
    });


    $(document).on("click", ".recusar", function () {

        let id = $(this).data("id");

        Swal.fire({
            title: "Recusar sessão?",
            text: "Esta ação não pode ser revertida.",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Sim, recusar",
            cancelButtonText: "Cancelar"
        }).then(res => {

            if (!res.isConfirmed) return;

            $.post("./src/controller/consultaPsicologoController.php", {
                acao: "recusar",
                id_consulta: id
            }, () => {

                Swal.fire(
                    "Sessão recusada",
                    "O cliente foi notificado.",
                    "success"
                );

                carregarSessoes();
            });
        });
    });
});
