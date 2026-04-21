let paginaAtual = 1;
const porPagina = 10;

$(document).ready(function () {

    getListaExercicios();

    $("#btnRegistarExercicio").on("click", function () {
        registaExercicio();
    });

    $("#btnFiltrar").on("click", function () {
        getListaExercicios();
    });

    $("#btnLimparFiltros").on("click", function () {
        $("#f_grupo").val("todos");
        $("#f_equip").val("todos");
        $("#f_pesquisa").val("");
        getListaExercicios();
    });

    $("#btnAbrirModalNovo").on("click", function () {
        $("#modalNovoExercicio").modal("show");
    });

    let t = null;
        $("#f_pesquisa").on("keyup", function(){
        clearTimeout(t);
        t = setTimeout(function(){
            getListaExercicios();
        }, 300);
    });

    $("#f_pesquisa").on("keypress", function(e){
        if(e.which === 13) getListaExercicios();
    });

    $(document).on("click", "#paginacaoExercicios a.page-link", function(e){
        e.preventDefault();
        const p = parseInt($(this).data("page"), 10);
        if (!isNaN(p)) getListaExercicios(p);
    });

    $("#f_grupo, #f_equip").select2({
        width: "100%",
        placeholder: "Todos",
        allowClear: false
        });
});

function registaExercicio() {

    let dados = new FormData();
    dados.append("op", 1);
    dados.append("nome", $("#nome").val());
    dados.append("grupo", $("#grupo").val());
    dados.append("equipamento", $("#equipamento").val());
    dados.append("tipo", $("#tipo").val());
    dados.append("dificuldade", $("#dificuldade").val());
    dados.append("descricao", $("#descricao").val());
    dados.append("video_url", $("#video_url").val());
    dados.append("imagem_url", $("#imagem_url").val());

    $.ajax({
        url: "src/controller/controllerExercicio_pt.php",
        method: "POST",
        data: dados,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (r) {

            if (!r.flag)
                return Swal.fire("Erro", r.msg || "Erro desconhecido", "error");

            Swal.fire("Sucesso", r.msg, "success");
            $("#modalNovoExercicio").modal("hide");
            limpaFormNovo();
            getListaExercicios();
        },
        error: function () {
            Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
        }
    });
}

function getListaExercicios(p = 1) {

    paginaAtual = p;

    let dados = new FormData();
    dados.append("op", 2);
    dados.append("f_grupo", $("#f_grupo").val());
    dados.append("f_equip", $("#f_equip").val());
    dados.append("f_pesquisa", $("#f_pesquisa").val());
    dados.append("page", paginaAtual);
    dados.append("per_page", porPagina);

    $.ajax({
        url: "src/controller/controllerExercicio_pt.php",
        method: "POST",
        data: dados,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (r) {
            if (!r.flag) {
                Swal.fire("Erro", r.msg || "Erro desconhecido", "error");
                return;
            }

            $("#listagemExercicios").html(r.html);
            $("#paginacaoExercicios").html(r.paginacao || "");
            $("#exerciciosInfo").text(r.info || "");
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
        }
    });
}

function removerExercicio(id) {

    Swal.fire({
        title: "Remover exercício?",
        text: "Isto remove o exercício da lista.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Remover",
        cancelButtonText: "Cancelar"
    }).then((result) => {

        if (!result.isConfirmed) return;

        let dados = new FormData();
        dados.append("op", 3);
        dados.append("id", id);

        $.ajax({
            url: "src/controller/controllerExercicio_pt.php",
            method: "POST",
            data: dados,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (r) {

                if (!r.flag)
                    return Swal.fire("Erro", r.msg || "Erro desconhecido", "error");

                Swal.fire("Sucesso", r.msg, "success");
                getListaExercicios();
            },
            error: function () {
                Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
            }
        });
    });
}

function getDadosExercicio(id) {

    let dados = new FormData();
    dados.append("op", 4);
    dados.append("id", id);

    $.ajax({
        url: "src/controller/controllerExercicio_pt.php",
        method: "POST",
        data: dados,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (r) {

            if (!r.flag)
                return Swal.fire("Erro", r.msg || "Erro desconhecido", "error");

            $("#idEdit").val(r.id_exercicio);
            $("#nomeEdit").val(r.nome);
            $("#grupoEdit").val(r.grupo);
            $("#equipamentoEdit").val(r.equipamento);
            $("#tipoEdit").val(r.tipo);
            $("#dificuldadeEdit").val(r.dificuldade);
            $("#descricaoEdit").val(r.descricao);
            $("#video_urlEdit").val(r.video_url);
            $("#imagem_urlEdit").val(r.imagem_url);

            $("#modalEditExercicio").modal("show");
        },
        error: function () {
            Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
        }
    });
}

function guardaEditExercicio() {

    let dados = new FormData();
    dados.append("op", 5);
    dados.append("id", $("#idEdit").val());
    dados.append("nome", $("#nomeEdit").val());
    dados.append("grupo", $("#grupoEdit").val());
    dados.append("equipamento", $("#equipamentoEdit").val());
    dados.append("tipo", $("#tipoEdit").val());
    dados.append("dificuldade", $("#dificuldadeEdit").val());
    dados.append("descricao", $("#descricaoEdit").val());
    dados.append("video_url", $("#video_urlEdit").val());
    dados.append("imagem_url", $("#imagem_urlEdit").val());

    $.ajax({
        url: "src/controller/controllerExercicio_pt.php",
        method: "POST",
        data: dados,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function (r) {

            if (!r.flag)
                return Swal.fire("Erro", r.msg || "Erro desconhecido", "error");

            Swal.fire("Sucesso", r.msg, "success");
            $("#modalEditExercicio").modal("hide");
            getListaExercicios();
        },
        error: function () {
            Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
        }
    });
}

function limpaFormNovo() {
    $("#nome").val("");
    $("#grupo").val("costas");
    $("#equipamento").val("maquina");
    $("#tipo").val("composto");
    $("#dificuldade").val("");
    $("#descricao").val("");
    $("#video_url").val("");
    $("#imagem_url").val("");
}
