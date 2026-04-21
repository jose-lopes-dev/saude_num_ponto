$(function () {
    carregarAulasPT();
});

function carregarAulasPT() {
    let fd = new FormData();
    fd.append('op', '20');
    fd.append('id_pt', ID_PT);

    $.ajax({
        url: 'src/controller/controllerAula.php',
        method: 'POST',
        data: fd,
        contentType: false,
        processData: false
    }).done(function (html) {
        $('#listaAulasPT').html(html);
    });
}

function verInscritosPT(idAula) {

    let fd = new FormData();
    fd.append('op', '6'); // mesma operação de listar inscritos
    fd.append('id_aula', idAula);

    $.ajax({
        url: 'src/controller/controllerAula.php',
        method: 'POST',
        data: fd,
        contentType: false,
        processData: false
    })
    .done(function (html) {
        $('#modalInscritosBody').html(html);
        $('#modalInscritos').modal('show');
    })
    .fail(function () {
        Swal.fire('Erro', 'Falha ao carregar inscritos', 'error');
    });
}
