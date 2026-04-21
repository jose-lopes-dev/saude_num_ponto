$(function(){
    carregarAulas();

    $('#searchAulas').on('input', function(){
        const q = $(this).val().toLowerCase();
        $('#listaAulas .aula-card').each(function(){
            const t = $(this).find('.card-title').text().toLowerCase();
            const d = $(this).find('.card-text').text().toLowerCase();
            $(this).parent().toggle(t.includes(q) || d.includes(q));
        });
    });

     $('#btnInscreverAula').off('click').on('click', function () {
        const id = $(this).data('id');
        if (!id) return;
        inscreverAulaConfirm(id);
    });

});

window.carregarAulas = function () {
    let fd = new FormData();
    fd.append('op','2');

    $.ajax({
        url: 'src/controller/controllerAula.php',
        method: 'POST',
        data: fd,
        contentType: false,
        processData: false,
        dataType: 'html'
    })
    .done(function(html){
        $('#listaAulas').html(html);
    })
    .fail(function(){
        $('#listaAulas').html(
            '<div class="alert alert-danger">Erro a carregar aulas</div>'
        );
    });
};



window.abrirModalAula = function (id) {
    let fd = new FormData();
    fd.append('op','3');
    fd.append('id', id);

    $.ajax({
        url:'src/controller/controllerAula.php',
        method:'POST',
        data:fd,
        contentType:false,
        processData:false
    }).done(function(resp){
        try {
            const obj = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            if (!obj || !obj.id) {
                Swal.fire('Erro','Aula não encontrada','error');
                return;
            }

            $('#modalAulaTitulo').text(obj.titulo);
            $('#modalAulaDescricao').text(obj.descricao || '');
            $('#modalAulaData').text(
                obj.data_inicio ? new Date(obj.data_inicio).toLocaleString('pt-pt') : ''
            );
            $('#modalAulaDuracao').text(obj.duracao_min);
            $('#modalAulaLimite').text(obj.limite_participantes);
            $('#modalAulaPreco').text(Number(obj.preco).toFixed(2));

            /* =========================
   Botão de inscrição
========================= */
if (obj.ja_inscrito) {
    $('#btnInscreverAula')
        .prop('disabled', true)
        .text('Já inscrito')
        .removeData('id');
} else {
    $('#btnInscreverAula')
        .prop('disabled', false)
        .text('Inscrever')
        .data('id', obj.id);
}


            if (obj.pode_entrar) {
    $('#modalAulaSala').html(`
        <a href="aula_virtual.php?id=${obj.id}"
           class="btn btn-success">
           Entrar na Aula
        </a>
    `);
} else {
    $('#modalAulaSala').html(
        '<small class="text-muted"></small>'
    );
}
            $('#btnInscreverAula').data('id', obj.id);
            $('#modalAulaDetalhe').modal('show');

        } catch(e) {
            console.error(resp);
            Swal.fire('Erro','Resposta inválida do servidor','error');
        }
    });
};
   

function inscreverAulaConfirm(idAula) {

    Swal.fire({
        title: 'Confirmar inscrição?',
        text: 'Queres inscrever-te nesta aula?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, inscrever',
        cancelButtonText: 'Cancelar'
    }).then((result) => {

        if (!result.isConfirmed) return;

        let fd = new FormData();
        fd.append('op', '4');
        fd.append('id_aula', idAula);

        $.ajax({
            url: 'src/controller/controllerAula.php',
            method: 'POST',
            data: fd,
            contentType: false,
            processData: false,
            dataType: 'json'
        })
        .done(function (obj) {

            if (obj.flag) {
                Swal.fire('Sucesso', obj.msg, 'success');
                carregarAulas();
            } else {
                Swal.fire('Erro', obj.msg, 'error');
            }

        })
        .fail(function (xhr) {
            console.error(xhr.responseText);
            Swal.fire('Erro', 'Falha na comunicação', 'error');
        });

    });
}








