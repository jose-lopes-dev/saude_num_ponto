// src/js/treinos_cliente.js
$(function(){
    getListaTreinos();

    // Quando o modal do workout for fechado (por X, ESC ou clique fora)
    $('#modalWorkout').on('hidden.bs.modal', function () {
    $('#workVideoBox').html(''); // remove o iframe e para o vídeo
});


    // pesquisa simples
    $('#searchTreinos').on('input', function(){
        const q = $(this).val().toLowerCase();
        $('#listaTreinos .treino-card').each(function(){
            const title = $(this).find('.card-title').text().toLowerCase();
            const txt = $(this).find('.card-text').text().toLowerCase();
            $(this).parent().toggle(title.indexOf(q) !== -1 || txt.indexOf(q) !== -1);
        });
    });

    // iniciar treino no modalWorkout
    $('#btnIniciarTreino').on('click', function(){
        const video = $(this).data('video');
        const titulo = $(this).data('titulo') || 'Treino';
        if(video) {
            const embed = video.replace('watch?v=', 'embed/') + '?autoplay=1';
            $('#workVideoBox').html('<iframe src="'+embed+'" style="border:0;width:100%;height:100%" allow="autoplay; fullscreen" allowfullscreen></iframe>');
            $('#workoutTitulo').text(titulo);
            var m = new bootstrap.Modal(document.getElementById('modalWorkout'));
            m.show();
            // fechar o detalhe modal
            $('#modalTreino').modal('hide');
        }
    });

    // terminar workout (limpa iframe)
    $('#btnEndWorkout').on('click', function(){
        $('#workVideoBox').html('');
        var m = bootstrap.Modal.getInstance(document.getElementById('modalWorkout'));
        if(m) m.hide();
    });
});

function getListaTreinos(){
    let dados = new FormData();
    dados.append('op', 2);

    $.ajax({
        url: 'src/controller/controllerTreino.php',
        method: 'POST',
        data: dados,
        dataType: 'html',
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg){
        $('#listaTreinos').html(msg);
        // adiciona event handlers a cada card (abrir modal com id)
        $('.treino-card .btn-ver').off('click');
        // os botões já usam inline onclick abrirModalTreino(id) no HTML gerado pelo model
    })
    .fail(function(jqXHR, textStatus){
        $('#listaTreinos').html('<div class="col-12"><div class="alert alert-danger">Erro ao carregar treinos: '+textStatus+'</div></div>');
        console.error('Erro AJAX:', jqXHR.responseText);
    });
}

function abrirModalTreino(id){
    let dados = new FormData();
    dados.append('op', 3);
    dados.append('id', id);

    $.ajax({
        url: 'src/controller/controllerTreino.php',
        method: 'POST',
        data: dados,
        dataType: 'html', // controller devolve JSON - mas a tua infra anterior usa JSON.parse de string
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg){
        // msg é JSON string; certificar que é JSON válido
        let obj;
        try {
            obj = JSON.parse(msg);
        } catch(e) {
            console.error('Resposta inválida:', msg);
            $('#modalTreino').modal('hide');
            Swal.fire('Erro','Resposta inválida do servidor.','error');
            return;
        }

        if(obj){
            $('#modalTituloTreino').text(obj.titulo);
            $('#modalDescricaoTreino').text(obj.descricao);
            $('#modalDuracaoTreino').text(obj.duracao_min);

            // coloca o iframe do vídeo (mas sem autoplay)
            const embed = obj.video_url.replace('watch?v=', 'embed/');
            $('#modalVideoWrap').html('<iframe src="'+embed+'" style="border:0;width:100%;height:100%" allowfullscreen></iframe>');

            // guarda dados no botão iniciar
            $('#btnIniciarTreino').data('video', obj.video_url);
            $('#btnIniciarTreino').data('titulo', obj.titulo);

            // mostra o modal de detalhe
            var m = new bootstrap.Modal(document.getElementById('modalTreino'));
            m.show();
        } else {
            Swal.fire('Erro','Treino não encontrado','error');
        }
    })
    .fail(function(jqXHR, textStatus){
        console.error('Erro AJAX:', jqXHR.responseText);
        Swal.fire('Erro','Falha na comunicação com o servidor: '+textStatus,'error');
    });
}
