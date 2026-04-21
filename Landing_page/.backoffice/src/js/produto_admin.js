$(function(){
    getListaProdutos();
    getSelectParceiros();
    getSelectEstados();
});

function getListaProdutos(){

    // Se estiver a usar DataTable, destrói antes
    if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tblProdutosAdmin')) {
        $('#tblProdutosAdmin').DataTable().destroy();
    }

    let dados = new FormData();
    dados.append("op", 2);

    $.ajax({
        url:"src/controller/controllerProduto.php",
        method:"POST",
        data:dados,
        dataType:"html",
        cache:false,
        contentType:false,
        processData:false
    })
    .done(function(msg){
        $('#listaProdutosAdmin').html(msg);
        if ($.fn.DataTable) $('#tblProdutosAdmin').DataTable();
    })
    .fail(function(){
        alerta("Produtos","Erro ao listar produtos","error");
    });
}

function getSelectParceiros(){

    let dados = new FormData();
    dados.append("op", 6);

    $.ajax({
        url:"src/controller/controllerProduto.php",
        method:"POST",
        data:dados,
        dataType:"html",
        cache:false,
        contentType:false,
        processData:false
    })
    .done(function(msg){
        $('#id_parceiro').html(msg);
        $('#parceiroEdit').html(msg);
    })
    .fail(function(){
        alerta("Produtos","Erro ao carregar parceiros","error");
    });
}

function getSelectEstados(){

    let dados = new FormData();
    dados.append("op", 7);

    $.ajax({
        url:"src/controller/controllerProduto.php",
        method:"POST",
        data:dados,
        dataType:"html",
        cache:false,
        contentType:false,
        processData:false
    })
    .done(function(msg){
        $('#estado').html(msg);
        $('#editEstado').html(msg);
    })
    .fail(function(){
        alerta("Produtos","Erro ao carregar estados","error");
    });
}

function registarProduto(){

    let dados = new FormData();
    dados.append("op", 1);
    dados.append("nome", $('#nome').val());
    dados.append("preco", $('#preco').val());
    dados.append("descricao", $('#descricao').val());
    dados.append("stock", $('#stock').val());
    dados.append("id_parceiro", $('#id_parceiro').val());
    dados.append("id_estado", $('#estado').val());

    if($('#imagem').prop('files')[0]){
        dados.append("imagem", $('#imagem').prop('files')[0]);
    }

    $.ajax({
        url:"src/controller/controllerProduto.php",
        method:"POST",
        data:dados,
        dataType:"html",
        cache:false,
        contentType:false,
        processData:false
    })
    .done(function(msg){

        let obj = JSON.parse(msg);

        if(obj.flag){
            alerta("Produtos", obj.msg, "success");
            $('#modalProduto').modal('hide');
            getListaProdutos();
        }else{
            alerta("Produtos", obj.msg, "error");
        }

    })
    .fail(function(){
        alerta("Produtos","Erro ao registar produto","error");
    });
}

function removerProduto(id){

    Swal.fire({
        title: "Remover produto?",
        text: "Esta ação é irreversível.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim",
        cancelButtonText: "Não"
    }).then((result) => {

        if(!result.isConfirmed) return;

        let dados = new FormData();
        dados.append("op", 3);
        dados.append("id", id);

        $.ajax({
            url:"src/controller/controllerProduto.php",
            method:"POST",
            data:dados,
            dataType:"html",
            cache:false,
            contentType:false,
            processData:false
        })
        .done(function(msg){

            let obj = JSON.parse(msg);

            if(obj.flag){
                alerta("Produtos", obj.msg, "success");
                getListaProdutos();
            }else{
                alerta("Produtos", obj.msg, "error");
            }

        })
        .fail(function(){
            alerta("Produtos","Erro ao remover produto","error");
        });
    });
}

function abrirModalEditarProduto(id){

    let dados = new FormData();
    dados.append("op", 4);
    dados.append("id", id);

    $.ajax({
        url:"src/controller/controllerProduto.php",
        method:"POST",
        data:dados,
        dataType:"html",
        cache:false,
        contentType:false,
        processData:false
    })
    .done(function(msg){

        let obj = JSON.parse(msg);

        $('#produto_id').val(obj.id);
        $('#nomeEdit').val(obj.nome);
        $('#precoEdit').val(obj.preco);
        $('#descricaoEdit').val(obj.descricao);
        $('#stockEdit').val(obj.stock);
        $('#parceiroEdit').val(obj.id_parceiro);
        $('#editEstado').val(obj.id_estado);

        $('#modalEditarProduto').modal('show');
    })
    .fail(function(){
        alerta("Produtos","Erro ao obter dados do produto","error");
    });
}

function guardaEditProduto(){

    let id = $('#produto_id').val();

    let dados = new FormData();
    dados.append("op", 5);
    dados.append("id", id);
    dados.append("nome", $('#nomeEdit').val());
    dados.append("preco", $('#precoEdit').val());
    dados.append("descricao", $('#descricaoEdit').val());
    dados.append("stock", $('#stockEdit').val());
    dados.append("id_parceiro", $('#parceiroEdit').val());
    dados.append("id_estado", $('#editEstado').val());

    if($('#imagemEdit').prop('files')[0]){
        dados.append("imagem", $('#imagemEdit').prop('files')[0]);
    }

    $.ajax({
        url:"src/controller/controllerProduto.php",
        method:"POST",
        data:dados,
        dataType:"html",
        cache:false,
        contentType:false,
        processData:false
    })
    .done(function(msg){

        let obj = JSON.parse(msg);

        if(obj.flag){
            alerta("Produtos", obj.msg, "success");
            $('#modalEditarProduto').modal('hide');
            getListaProdutos();
        }else{
            alerta("Produtos", obj.msg, "error");
        }

    })
    .fail(function(){
        alerta("Produtos","Erro ao guardar edição","error");
    });
}

function alerta(titulo,msg,icon){
    Swal.fire({
        position: 'center',
        icon: icon,
        title: titulo,
        text: msg,
        showConfirmButton: true
    });
}