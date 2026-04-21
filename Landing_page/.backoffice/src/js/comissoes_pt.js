function getListaComissoes(){

    if ($.fn.DataTable.isDataTable('#tblComissoes')) {
        $('#tblComissoes').DataTable().destroy();
    }

    let dados = new FormData();
    dados.append("op", 1);
    dados.append("idPt", $('#idPt').val());
    dados.append("estado", $('#filtroEstado').val() || "");

    $.ajax({
        url: "src/controller/controllerComissoes_pt.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg){
        $('#listagemComissoes').html(msg);
        $('#tblComissoes').DataTable();
    });
}


function marcarPago(id){

    let dados = new FormData();
    dados.append("op", 2);
    dados.append("idPt", $('#idPt').val());
    dados.append("id", id);

    $.ajax({
        url: "src/controller/controllerComissoes_pt.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg){

        let obj = JSON.parse(msg);

        Swal.fire({
            position:'center',
            icon: obj.flag ? 'success':'error',
            title:'Comissões',
            text: obj.msg
        });

        if(obj.flag){
            getResumoComissoes();
            getListaComissoes();
        }

    });
}


function getResumoComissoes(){

    let dados = new FormData();
    dados.append("op", 3); 
    dados.append("idPt", $('#idPt').val());
    dados.append("estado", $('#filtroEstado').val() || "");

    $.ajax({
        url: "src/controller/controllerComissoes_pt.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg){

        let obj = JSON.parse(msg);

        $('#kpiTotalBase').html(obj.totalBase + "€");
        $('#kpiTotalComissao').html(obj.totalComissao + "€");
        $('#kpiTotalPorPagar').html(obj.totalPorPagar + "€");

    })
    .fail(function(){

        $('#kpiTotalBase').html("0€");
        $('#kpiTotalComissao').html("0€");
        $('#kpiTotalPorPagar').html("0€");

    });
}


function syncComissoes(){

    let dados = new FormData();
    dados.append("op", 4);
    dados.append("idPt", $('#idPt').val());

    return $.ajax({
        url: "src/controller/controllerComissoes_pt.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    });
}


$(function(){

    // ✅ ATIVAR SELECT2
    $('#filtroEstado').select2({
        allowClear: true,
        width: "100%"
    });

    // sync inicial
    syncComissoes().done(function(){
        getListaComissoes();
        getResumoComissoes();
    });

    // quando muda filtro
    $('#filtroEstado').on('change', function(){
        getListaComissoes();
        getResumoComissoes();
    });

});
