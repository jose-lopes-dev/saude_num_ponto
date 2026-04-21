function registaPrestador() {
    let dados = new FormData();
    dados.append("op", 1);
    dados.append("username", $('#usernamePrestador').val());
    dados.append("email", $('#emailPrestador').val());
    dados.append("id_tipo_user", $('#tipo_user').val());

    dados.append("nome_completo", $('#nomePrestador').val());
    dados.append("nif", $('#nifPrestador').val());
    dados.append("contacto", $('#contactoPrestador').val());
    dados.append("id_funcao", $('#funcaoPrestador').val());
    dados.append("qualificacao", $('#qualificacaoPrestador').val());
    dados.append("experiencia_anos", $('#experienciaPrestador').val());
    dados.append("id_tipo_contrato", $('#tipoContrato').val());
    dados.append("id_estado", $('#estado').val());
    dados.append("recibo", $('#reciboPrestador').val());

    
    if ($('#linkContrato')[0].files.length > 0) {
        dados.append("contrato", $('#linkContrato')[0].files[0]);
    }

    $.ajax({
        url: "src/controller/controllerPrestador.php",
        method: "POST",
        data: dados,
        dataType: "json", 
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function (obj) {
        console.log("Resposta do servidor:", obj); 

        if (obj.flag) {
            alerta("Prestador", obj.msg, "success");
            getListaPrestadores();
            $('#showModal').modal('hide');
        } else {
            alerta("Prestador", obj.msg, "error");
        }
    })
    .fail(function (jqXHR, textStatus) {
        console.error("Erro AJAX:", jqXHR.responseText);
        alerta("Prestador", "Erro na comunicação com o servidor", "error");
    });
}



function getListaPrestadores() {
    if ($.fn.DataTable.isDataTable('#tabelaPrestadores')) {
        $('#tabelaPrestadores').DataTable().destroy();
    }

    let dados = new FormData();
    dados.append("op", 2);

    $.ajax({
       url: "src/controller/controllerPrestador.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg) {
        $('#listagemColaboradores').html(msg);
        $('#tabelaPrestadores').DataTable({
    language: {
        lengthMenu: "Mostrar _MENU_ registos",
        zeroRecords: "Nenhum registo encontrado",
        info: "A mostrar _START_ a _END_ de _TOTAL_ registos",
        infoEmpty: "A mostrar 0 a 0 de 0 registos",
        infoFiltered: "(filtrado de _MAX_ registos)",
        search: "Pesquisar:",
        paginate: {
            first: "Primeiro",
            last: "Último",
            next: "Seguinte",
            previous: "Anterior"
        }
    }
});

    })
    .fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });
}

function removerPrestador(codigo) {
    let dados = new FormData();
    dados.append("op", 3);
    dados.append("codigo", codigo);

    $.ajax({
        url: "src/controller/controllerPrestador.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg) {
        let obj = JSON.parse(msg);
        if (obj.flag) {
            alerta("Prestador", obj.msg, "success");
            getListaPrestadores();
        } else {
            alerta("Prestador", obj.msg, "error");
        }
    })
    .fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });
}

function getDadosPrestador(codigoOld) {
    let dados = new FormData();
    dados.append("op", 4);
    dados.append("codigo", codigoOld);

    $.ajax({
        url: "src/controller/controllerPrestador.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg) {
        let obj = JSON.parse(msg);
        $('#editCodigo').val(obj.codigo);
        $('#editNome').val(obj.nome);
        $('#editNif').val(obj.nif);
        $('#editFuncao').val(obj.id_funcao);
        $('#editTipoContrato').val(obj.id_tipo_contrato);
        $('#editEmail').val(obj.email);
        $('#editEstado').val(obj.id_estado);
        $('#editRecibo').val(obj.recibo_path);
        
        $('#btnGuardar').attr("onclick", "guardaEditPrestador(" + codigoOld + ")");
        $('#modalEditar').modal('show');
    })
    .fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });
}

function guardaEditPrestador(codigoOld) {
    let dados = new FormData();
    dados.append("op", 5);
    dados.append("codigo", $('#editCodigo').val());
    dados.append("nome", $('#editNome').val());
    dados.append("nif", $('#editNif').val());
    dados.append("funcao", $('#editFuncao').val());
    dados.append("tipo", $('#editTipoContrato').val());
    dados.append("email", $('#editEmail').val());
    dados.append("estado", $('#editEstado').val());
    dados.append("recibo", $('#editRecibo').val());    
    dados.append("codigoOld", codigoOld);

    if ($('#editContrato')[0].files.length > 0) {
        dados.append("link", $('#editContrato')[0].files[0]);
    }

    $.ajax({
        url: "src/controller/controllerPrestador.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg) {
        let obj = JSON.parse(msg);
        if (obj.flag) {
            alerta("Prestador", obj.msg, "success");
            getListaPrestadores();
            $('#modalEditar').modal('hide');
        } else {
            alerta("Prestador", obj.msg, "error");
        }
    })
    .fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });
}

function getFuncao(){

  let dados = new FormData();
  dados.append('op', 6);
  $.ajax({
     url: "src/controller/controllerPrestador.php",
    method: "POST",
    data: dados,
    dataType: "html",
    cache: false,
    contentType: false,
    processData:false,
  })
  
  .done(function( msg ) {
   $('#funcaoPrestador').html(msg);
   $('#editFuncao').html(msg);
  })
  
  .fail(function( jqXHR, textStatus ) {
    alert( "Request failed: " + textStatus );
  });
}

function getTipo(){

    let dados = new FormData();
    dados.append('op', 7);
    $.ajax({
       url: "src/controller/controllerPrestador.php",
        method: "POST", 
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false,
    })
    .done(function( msg ) {
        $('#tipoContrato').html(msg);
        $('#editTipoContrato').html(msg);
    }
    )
    .fail(function( jqXHR, textStatus ) {
        alert( "Request failed: " + textStatus );
    });
}

function getEstado(){
    let dados = new FormData();
    dados.append('op', 8);
    $.ajax({
        url: "src/controller/controllerPrestador.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false,
    })
    .done(function( msg ) {
        $('#estado').html(msg);
        $('#editEstado').html(msg);
    }
    )
    .fail(function( jqXHR, textStatus ) {
        alert( "Request failed: " + textStatus );
    });
}

function abrirModalRecibo(codigo){
  $('#reciboCodigo').val(codigo);
  $('#fileRecibo').val('');
  $('#modalRecibo').modal('show');
}

function enviarRecibo(){
  let formData = new FormData($('#formRecibo')[0]);
  formData.append('op', 'uploadRecibo');

  $.ajax({
    url: "src/controller/controllerPrestador.php",
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json"
  })
  .done(function(resp){
    if(resp.flag){
      Swal.fire('Sucesso!', resp.msg, 'success');
      $('#modalRecibo').modal('hide');
      getListaPrestadores(); 
    } else {
      Swal.fire('Erro!', resp.msg, 'error');
    }
  })
  .fail(function(){
    Swal.fire('Erro!', 'Não foi possível enviar o recibo.', 'error');
  });
}

function getListaImpostos() {
    if ($.fn.DataTable.isDataTable('#TabelaImpostos')) {
        $('#TabelaImpostos').DataTable().destroy();
    }

    let dados = new FormData();
    dados.append("op", 9);

    $.ajax({
       url: "src/controller/controllerPrestador.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg) {
        $('#listagemImpostos').html(msg);
        $('#TabelaImpostos').DataTable({
    language: {
        lengthMenu: "Mostrar _MENU_ registos",
        zeroRecords: "Nenhum registo encontrado",
        info: "A mostrar _START_ a _END_ de _TOTAL_ registos",
        infoEmpty: "A mostrar 0 a 0 de 0 registos",
        infoFiltered: "(filtrado de _MAX_ registos)",
        search: "Pesquisar:",
        paginate: {
            first: "Primeiro",
            last: "Último",
            next: "Seguinte",
            previous: "Anterior"
        }
    }
});

    })
    .fail(function(jqXHR, textStatus) {
        alert("Request failed: " + textStatus);
    });
}

function abrirModalDMR(id){
  $('#dmrID').val(id);
  $('#fileDMR').val('');
  $('#modalDMR').modal('show');
}  

function enviarDMR(){
  let formData = new FormData($('#formDMR')[0]);
  formData.append('op', 'uploadDMR');

  $.ajax({
    url: "src/controller/controllerPrestador.php",
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json"
  })
  .done(function(resp){
    if(resp.flag){
      Swal.fire('Sucesso!', resp.msg, 'success');
      $('#modalDMR').modal('hide');
      getListaImpostos(); 
    } else {
      Swal.fire('Erro!', resp.msg, 'error');
    }
  })
  .fail(function(){
    Swal.fire('Erro!', 'Não foi possível enviar o DMR.', 'error');
  });
}

function abrirModalDRI(id){
  $('#driID').val(id);
  $('#fileDRI').val('');
  $('#modalDRI').modal('show');
}  

function enviarDRI(){
  let formData = new FormData($('#formDRI')[0]);
  formData.append('op', 'uploadDRI');

  $.ajax({
    url: "src/controller/controllerPrestador.php",
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    dataType: "json"
  })
  .done(function(resp){
    if(resp.flag){
      Swal.fire('Sucesso!', resp.msg, 'success');
      $('#modalDRI').modal('hide');
      getListaImpostos(); 
    } else {
      Swal.fire('Erro!', resp.msg, 'error');
    }
  })
  .fail(function(){
    Swal.fire('Erro!', 'Não foi possível enviar o DRI.', 'error');
  });
}

function alerta(titulo, msg, icon) {
    Swal.fire({
        position: 'center',
        icon: icon,
        title: titulo,
        text: msg,
        showConfirmButton: true
    });
}

$(function() {
    getListaPrestadores();
    getListaImpostos();    
    getFuncao();
    getTipo();
    getEstado();
    

    
    
$('#editFuncao').select2({
        dropdownParent: $('#modalEditar')
    });

$('#editEstado').select2({
        dropdownParent: $('#modalEditar')
    });   
    
$('#editTipoContrato').select2({
        dropdownParent: $('#modalEditar')
    });

$('#funcaoPrestador').select2({
        dropdownParent: $('#showModal')
    });   
    
$('#estado').select2({
        dropdownParent: $('#showModal')
    });
$('#tipoContrato').select2({
        dropdownParent: $('#showModal')
      });       
});

// Resetar modal de "Adicionar Prestador" sempre que abrir
$('#showModal').on('show.bs.modal', function () {
    const form = $(this).find('form')[0];
    if (form) form.reset(); 

    // Limpar selects 
    $('#funcaoPrestador').val(null).trigger('change');
    $('#tipoContrato').val(null).trigger('change');
    $('#estado').val(null).trigger('change');

    // Se existir campo contrato, resetar
    $('#linkContrato').val('');
});


