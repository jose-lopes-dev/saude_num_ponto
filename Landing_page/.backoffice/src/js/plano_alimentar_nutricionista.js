$(function () {

    carregarClientes()
    carregarPlanos()

    $('#btnEnviarPlano').on('click', function () {
        enviarPlano()
    })

})


function carregarClientes(){

    let dados = new FormData()
    dados.append('op','clientes')

    $.ajax({
        url: 'src/controller/controllerPlanoNutricionista.php',
        method: 'POST',
        data: dados,
        dataType: 'json',
        contentType:false,
        processData:false
    })
    .done(function(res){

        if(!res.flag) return

        $('#clienteId').html(res.html)

        $('#clienteId').select2({
            width:'100%',
            theme:'bootstrap-5'
        })
    })
}


function enviarPlano(){

    const cliente = $('#clienteId').val()
    const ficheiro = $('#ficheiroPlano')[0].files[0]

    if(!cliente || !ficheiro){
        Swal.fire('Atenção','Seleciona cliente e ficheiro','warning')
        return
    }

    let dados = new FormData()
    dados.append('cliente', cliente)
    dados.append('ficheiro', ficheiro)

    $.ajax({
        url: 'src/controller/controllerPlanoNutricionista.php',
        method: 'POST',
        data: dados,
        dataType: 'json',
        contentType:false,
        processData:false
    })
    .done(function(res){

        Swal.fire(
            res.flag ? 'Sucesso' : 'Erro',
            res.msg,
            res.flag ? 'success' : 'error'
        )

        if(res.flag){
            $('#ficheiroPlano').val('')
            $('#clienteId').val('').trigger('change')
            carregarPlanos()
        }
    })
}


function carregarPlanos(){

    let dados = new FormData()
    dados.append('op','enviados')

    $.ajax({
        url: 'src/controller/controllerPlanoNutricionista.php',
        method: 'POST',
        data: dados,
        dataType: 'json',
        contentType:false,
        processData:false
    })
    .done(function(res){

        if(!res.flag) return

        $('#listaPlanosEnviados').html(res.html)
    })
}
