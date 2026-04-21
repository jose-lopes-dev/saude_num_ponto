$(function () {
    listarAulas();
    listarIDPT();
    listarEstados();

    $('#modalNovaAula').on('show.bs.modal', function () {
        $('#tituloAula, #descricaoAula, #dataInicio, #duracaoAula, #limiteAula, #precoAula').val('');
        $('#nivelAula, #ptAula, #estadoAula').val('');
        $('#sala_nome').val('');
    });

    $('#btnGuardar').on('click', function () {
        guardaEditAula($('#aula_id_edit').val());
    });
});

function gerarSalaJitsi(titulo) {
    let slug = titulo.toLowerCase().replace(/[^a-z0-9]+/g, '-');
    return slug + '-' + Math.random().toString(36).substring(2, 8);
}

function listarAulas() {
    $.post("src/controller/controllerAula.php", { op: 7 }, function (html) {
        $('#listaAulasAdmin').html(html);
        if ($.fn.DataTable) $('#tblAulasAdmin').DataTable();
    });
}

function registarAula() {
    let fd = new FormData();
    fd.append("op", 1);
    fd.append("titulo", $('#tituloAula').val());
    fd.append("descricao", $('#descricaoAula').val());
    fd.append("data_inicio", $('#dataInicio').val());
    fd.append("duracao_min", $('#duracaoAula').val());
    fd.append("limite_participantes", $('#limiteAula').val());
    fd.append("nivel", $('#nivelAula').val());
    fd.append("preco", $('#precoAula').val());
    fd.append("id_pt", $('#ptAula').val());
    fd.append("id_estado", $('#estadoAula').val());
    fd.append("sala_nome", $('#sala_nome').val());

    $.ajax({
        url: "src/controller/controllerAula.php",
        method: "POST",
        data: fd,
        contentType: false,
        processData: false
    }).done(r => {
        let o = JSON.parse(r);
        Swal.fire(o.flag ? 'Sucesso' : 'Erro', o.msg, o.flag ? 'success' : 'error');
        if (o.flag) {
            $('#modalNovaAula').modal('hide');
            listarAulas();
        }
    });
}

function removerAula(id) {
    Swal.fire({
        title: 'Remover aula?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim'
    }).then(res => {
        if (!res.isConfirmed) return;
        $.post("src/controller/controllerAula.php", { op: 9, id }, () => listarAulas());
    });
}

function listarEstados() {
    $.post("src/controller/controllerAula.php", { op: 10 }, r => {
        $('#estadoAula, #estadoAulaEdit').html(r);
    });
}

function listarIDPT() {
    $.post("src/controller/controllerAula.php", { op: 11 }, r => {
        $('#ptAula, #ptAulaEdit').html(r);
    });
}

function verInscritos(id) {
    let fd = new FormData();
    fd.append('op','6');
    fd.append('id_aula', id);

    $.ajax({
        url:'src/controller/controllerAula.php',
        method:'POST',
        data:fd,
        contentType:false,
        processData:false
    })
    .done(function(html){
        $('#modalInscritosBody').html(html);
        $('#modalInscritos').modal('show');
    })
    .fail(()=>Swal.fire('Erro','Falha','error'));
}

function abrirEditarAula(id){
    let fd = new FormData();
    fd.append('op','13');
    fd.append('id', id);

    $.ajax({
        url:'src/controller/controllerAula.php',
        method:'POST',
        data:fd,
        contentType:false,
        processData:false
    })
    .done(function(r){
        let a = JSON.parse(r);

        $('#aula_id_edit').val(a.id);
        $('#tituloAulaEdit').val(a.titulo);
        $('#descricaoAulaEdit').val(a.descricao);
        $('#dataInicioEdit').val(a.data_inicio.replace(' ', 'T'));
        $('#duracaoAulaEdit').val(a.duracao_min);
        $('#limiteAulaEdit').val(a.limite_participantes);
        $('#nivelAulaEdit').val(a.nivel);
        $('#precoAulaEdit').val(a.preco);
        $('#ptAulaEdit').val(a.id_pt);
        $('#estadoAulaEdit').val(a.id_estado);
        $('#sala_nomeEdit').val(a.sala_nome);

        $('#modalEditarAula').modal('show');
    });
}


function guardaEditAula(id){
    let fd = new FormData();
    fd.append("op", 12);
    fd.append("id", id);
    fd.append("titulo", $('#tituloAulaEdit').val());
    fd.append("descricao", $('#descricaoAulaEdit').val());
    fd.append("data_inicio", $('#dataInicioEdit').val());
    fd.append("duracao_min", $('#duracaoAulaEdit').val());
    fd.append("limite_participantes", $('#limiteAulaEdit').val());
    fd.append("nivel", $('#nivelAulaEdit').val());
    fd.append("preco", $('#precoAulaEdit').val());
    fd.append("id_pt", $('#ptAulaEdit').val());
    fd.append("id_estado", $('#estadoAulaEdit').val());
    fd.append("sala_nome", $('#sala_nomeEdit').val());

    $.ajax({
        url: "src/controller/controllerAula.php",
        method: "POST",
        data: fd,
        contentType: false,
        processData: false
    }).done(r => {
        console.log(r);
        let o = JSON.parse(r);
        Swal.fire(o.flag ? 'Sucesso' : 'Erro', o.msg, o.flag ? 'success' : 'error');
        if (o.flag) {
            $('#modalEditarAula').modal('hide');
            listarAulas();
        }
    });
}

