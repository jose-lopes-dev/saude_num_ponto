let DIA_ATUAL = 1;
let DIA_DATA = { 1: [], 2: [], 3: [], 4: [], 5: [], 6: [], 7: [] };

let DIA_NOME = { 1: "", 2: "", 3: "", 4: "", 5: "", 6: "", 7: "" };

$(document).ready(function () {
    carregarClientes();
    initSelectCliente(); 
    initSelectExercicios();
    initSelectGrupo();
    initSelectEquipamento();

    $("#tabsDias .nav-link").on("click", function () {
        $("#tabsDias .nav-link").removeClass("active");
        $(this).addClass("active");
        DIA_ATUAL = parseInt($(this).data("dia"));
        $("#diaAtualLabel").text(nomeDiaCurto(DIA_ATUAL));
        $("#nomeDia").val(DIA_NOME[DIA_ATUAL] || "");
        renderDia();
    });

    $("#f_grupo_ex, #f_equip_ex").on("change", function () {
        $('#id_exercicio').val(null).trigger('change');
    });

    $("#btnCriarPlano").on("click", function () {
        criarPlanoOuAbrir();
    });

    $("#btnAddExercicio").on("click", function () {
        adicionarExercicioAoDia();
    });

    $("#btnGuardarDia").on("click", function () {
        guardarDiaAtual();
    });

    $("#clienteId").on("change", function () {
        $("#planoId").val("");
        limparSemana();
        listarPlanosCliente();
    });

    $("#nomeDia").on("input", function () {
        DIA_NOME[DIA_ATUAL] = $(this).val();
    });
});

function initSelectCliente(){
    $('#clienteId').select2({
        placeholder: "Seleciona o cliente",
        allowClear: false,
        width: "100%"
    });
}

function initSelectGrupo(){
    $('#f_grupo_ex').select2({
        placeholder: "Seleciona grupo",
        allowClear: false,
        width: "100%"
    });
}

function initSelectEquipamento(){
    $('#f_equip_ex').select2({
        placeholder: "Seleciona equipamento",
        allowClear: false,
        width: "100%"
    });
}

function initSelectExercicios() {
    $('#id_exercicio').select2({
        placeholder: "Pesquisar exercício...",
        allowClear: false,
        width: "100%",
        ajax: {
        url: "src/controller/controllerPlanoPT.php",
        type: "POST",
        dataType: "json",
        delay: 250,
        title: false,
        data: function (params) {
            return {
            op: 2,
            q: params.term || "",
            grupo: $("#f_grupo_ex").val() || "todos",
            equipamento: $("#f_equip_ex").val() || "todos"
            };
        },
        processResults: function (res) {
            if (!res || !res.flag) return { results: [] };
            return { results: res.items || [] };
        }
        },
        minimumInputLength: 0
    });
}

function carregarClientes() {
    let dados = new FormData();
    dados.append("op", 1);

    $.ajax({
        url: "src/controller/controllerPlanoPT.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function (res) {
        if (!res.flag) {
        Swal.fire("Erro", res.msg || "Sessão inválida", "error");
        return;
        }

        let html = `<option value="">Seleciona o cliente</option>`;
        (res.clientes || []).forEach(c => {
        html += `<option value="${c.codigo}">${c.nome_completo}</option>`;
        });

        $("#clienteId").html(html);
        $("#clienteId").trigger("change.select2");
    })
    .fail(function () {
        Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
    });
}

function criarPlanoOuAbrir() {
    const cliente_id = $("#clienteId").val();
    const titulo = $("#tituloPlano").val();

    if (!cliente_id) return Swal.fire("Atenção", "Seleciona um cliente", "warning");

    let dados = new FormData();
    dados.append("op", 3);
    dados.append("cliente_id", cliente_id);
    dados.append("titulo", titulo);

    $.ajax({
        url: "src/controller/controllerPlanoPT.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function (r) {
        if (!r.flag) return Swal.fire("Erro", r.msg || "Erro", "error");
        $("#planoId").val(r.plano_id);
        Swal.fire("OK", "Plano pronto. Agora mete exercícios nos dias.", "success");
        listarPlanosCliente();
    })
    .fail(function () {
        Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
    });
}

function adicionarExercicioAoDia() {
    const planoId = $("#planoId").val();
    if (!planoId) return Swal.fire("Atenção", "Cria/abre um plano primeiro", "warning");

    const sel = $("#id_exercicio").select2("data");
    if (!sel || sel.length === 0) return Swal.fire("Atenção", "Seleciona um exercício", "warning");

    const id_exercicio = parseInt(sel[0].id);
    const nome = sel[0].text;

    DIA_DATA[DIA_ATUAL].push({
        ordem: DIA_DATA[DIA_ATUAL].length + 1,
        id_exercicio,
        nome,
        series: "",
        reps: "",
        descanso_seg: "",
        rpe: "",
        tempo: "",
        observacoes: ""
    });

    $('#id_exercicio').val(null).trigger('change');
    renderDia();
}

function renderDia() {
    const itens = DIA_DATA[DIA_ATUAL] || [];
    let html = "";

    itens.forEach((it, idx) => {
        html += `
        <tr>
            <td>${idx + 1}</td>

            <td class="td-exercicio">
                <div>
                    <strong>${escapeHtml(it.nome)}</strong>
                </div>
                <div class="mt-2">
                    <input class="form-control form-control-sm ex-obs"
                           placeholder="Observações..."
                           value="${escapeAttr(it.observacoes || "")}"
                           oninput="upd(${idx}, 'observacoes', this.value)">
                </div>
            </td>

            <td><br><input class="form-control form-control-sm ex-series mt-2"
                       value="${escapeAttr(it.series || "")}"
                       oninput="upd(${idx}, 'series', this.value)"></td>
            <td><br><input class="form-control form-control-sm ex-reps mt-2"
                       value="${escapeAttr(it.reps || "")}"
                       oninput="upd(${idx}, 'reps', this.value)"></td>
            <td><br><input class="form-control form-control-sm ex-descanso mt-2"
                       value="${escapeAttr(it.descanso_seg || "")}"
                       oninput="upd(${idx}, 'descanso_seg', this.value)"></td>
            <td><br><input class="form-control form-control-sm ex-rpe mt-2"
                       value="${escapeAttr(it.rpe || "")}"
                       oninput="upd(${idx}, 'rpe', this.value)"></td>
            <td><br><input class="form-control form-control-sm ex-tempo mt-2"
                       value="${escapeAttr(it.tempo || "")}"
                       oninput="upd(${idx}, 'tempo', this.value)"></td>

            <td class="text-end"><br>
                <button class="btn btn-icon btn-outline-danger mt-2" onclick="rem(${idx})" title="Remover">
                    <i class="ri-close-line"></i>
                </button>
            </td>
        </tr>`;
    });

    $("#tbodyDia").html(html);
}

function upd(idx, campo, valor) {
    DIA_DATA[DIA_ATUAL][idx][campo] = valor;
}

function rem(idx) {
    DIA_DATA[DIA_ATUAL].splice(idx, 1);
    
    DIA_DATA[DIA_ATUAL].forEach((x, i) => x.ordem = i + 1);
    renderDia();
}

function guardarDiaAtual() {
    const planoId = $("#planoId").val();
    if (!planoId) return Swal.fire("Atenção", "Cria/abre um plano primeiro", "warning");

    const nomeDia = DIA_NOME[DIA_ATUAL] || "";
    const itens = DIA_DATA[DIA_ATUAL] || [];

    let dados = new FormData();
    dados.append("op", 4);
    dados.append("plano_id", planoId);
    dados.append("dia_semana", DIA_ATUAL);
    dados.append("nome_dia", nomeDia);
    dados.append("itens", JSON.stringify(itens));

    $.ajax({
        url: "src/controller/controllerPlanoPT.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function (r) {
        if (!r.flag) return Swal.fire("Erro", r.msg || "Erro", "error");
        Swal.fire("Sucesso", "Dia guardado", "success");
        listarPlanosCliente();
    })
    .fail(function () {
        Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
    });
}

function listarPlanosCliente() {
    const cliente_id = $("#clienteId").val();
    if (!cliente_id) {
        $("#listaPlanosCliente").html(`<div class="text-muted">Seleciona um cliente…</div>`);
        return;
    }

    let dados = new FormData();
    dados.append("op", 5);
    dados.append("cliente_id", cliente_id);

    $.ajax({
        url: "src/controller/controllerPlanoPT.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function (r) {
        if (!r.flag) return;

        let html = "";
        (r.planos || []).forEach(p => {

            const publicado = parseInt(p.publicado || 0);

            const badge = (publicado === 1)
                ? `<span class="badge badge-publicado">Publicado</span>`
                : `<span class="badge badge-rascunho">Rascunho</span>`;

            const btnPublicar = (publicado === 1)
            ? `<button class="btn btn-icon btn-outline-warning" onclick="publicarPlano(${p.id},0)">
                <i class="ri-edit-2-line"></i>
                </button>`
            : `<button class="btn btn-icon btn-action-blue" onclick="publicarPlano(${p.id},1)">
                <i class="ri-send-plane-2-line"></i>
                </button>`;

            html += `
                <div class="d-flex align-items-center justify-content-between border-top border-bottom py-2">
                    <div>
                        <div class="d-flex align-items-center gap-2">
                            ${badge}
                            <strong>${escapeHtml(p.titulo)}</strong>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-icon btn-outline-primary" onclick="abrirPlano(${p.id})">
                            <i class="ri-folder-open-line"></i>
                        </button>
                        ${btnPublicar}
                        <button class="btn btn-icon btn-outline-danger" onclick="eliminarPlano(${p.id})">
                            <i class="ri-delete-bin-6-line"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        if (html === "") html = `<div class="text-muted">Sem planos.</div>`;
        $("#listaPlanosCliente").html(html);
    })
    .fail(function () {
        $("#listaPlanosCliente").html(`<div class="text-danger">Erro de comunicação com o servidor.</div>`);
    });
}

function abrirPlano(plano_id) {
    let dados = new FormData();
    dados.append("op", 6);
    dados.append("plano_id", plano_id);

    $.ajax({
        url: "src/controller/controllerPlanoPT.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function (r) {
        if (!r.flag) return Swal.fire("Erro", r.msg || "Erro", "error");

        $("#planoId").val(r.plano.id);
        $("#tituloPlano").val(r.plano.titulo);

        limparSemana();

        (r.dias || []).forEach(d => {

        DIA_DATA[d.dia_semana] = (d.exercicios || []).map((x, i) => ({
            ordem: x.ordem || (i + 1),
            id_exercicio: x.id_exercicio,
            nome: x.nome,
            series: x.series || "",
            reps: x.reps || "",
            descanso_seg: x.descanso_seg || "",
            rpe: x.rpe || "",
            tempo: x.tempo || "",
            observacoes: x.observacoes || ""
        }));

        DIA_NOME[d.dia_semana] = d.nome || "";

        if (d.dia_semana === DIA_ATUAL) {
            $("#nomeDia").val(DIA_NOME[DIA_ATUAL] || "");
        }

        });

        renderDia();
        Swal.fire("OK", "Plano carregado", "success");
    })
    .fail(function () {
        Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
    });
}

function limparSemana() {
    DIA_DATA = { 1: [], 2: [], 3: [], 4: [], 5: [], 6: [], 7: [] };
    DIA_NOME = { 1: "", 2: "", 3: "", 4: "", 5: "", 6: "", 7: "" };
    DIA_ATUAL = 1;

    $("#tabsDias .nav-link").removeClass("active");
    $('#tabsDias .nav-link[data-dia="1"]').addClass("active");
    $("#diaAtualLabel").text(nomeDiaCurto(DIA_ATUAL));
    $("#nomeDia").val("");

    renderDia();
}

function eliminarPlano(plano_id) {
    Swal.fire({
        title: "Eliminar plano?",
        text: "Isto vai apagar o plano e todos os dias/exercícios.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, eliminar",
        cancelButtonText: "Cancelar"
    }).then((res) => {
        if (!res.isConfirmed) return;

        let dados = new FormData();
        dados.append("op", 7);
        dados.append("plano_id", plano_id);

        $.ajax({
        url: "src/controller/controllerPlanoPT.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
        })
        .done(function(r){
        if (!r.flag) return Swal.fire("Erro", r.msg || "Erro", "error");
        Swal.fire("OK", "Plano eliminado", "success");
        listarPlanosCliente();
        
        if ($("#planoId").val() == plano_id) {
            $("#planoId").val("");
            limparSemana();
        }
        })
        .fail(function(){
        Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
        });
    });
}

function publicarPlano(plano_id, publicado) {
    const txt = (publicado == 1)
        ? "Publicar este plano para o cliente?"
        : "Voltar este plano a rascunho?";

    Swal.fire({
        title: "Confirmar",
        text: txt,
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Sim",
        cancelButtonText: "Cancelar"
    }).then((res) => {
        if (!res.isConfirmed) return;

        let dados = new FormData();
        dados.append("op", 8);
        dados.append("plano_id", plano_id);
        dados.append("publicado", publicado);

        $.ajax({
            url: "src/controller/controllerPlanoPT.php",
            method: "POST",
            data: dados,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function (r) {
            if (!r.flag) return Swal.fire("Erro", r.msg || "Erro", "error");
            Swal.fire("OK", r.msg || "Atualizado", "success");
            listarPlanosCliente();
        })
        .fail(function () {
            Swal.fire("Erro", "Erro de comunicação com o servidor", "error");
        });
    });
}

function nomeDiaCurto(n) {
    return ["", "Segunda", "Terça", "Quarta", "Quinta", "Sexta", "Sábado", "Domingo"][n] || "Dia";
}

function escapeHtml(s) {
    return String(s || "").replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
}
function escapeAttr(s) { return escapeHtml(s).replace(/"/g, "&quot;"); }