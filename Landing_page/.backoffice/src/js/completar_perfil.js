function guardarPerfil() {
    let idade = $('#idade').val().trim();
    let peso = $('#peso').val().trim();
    let altura = $('#altura').val().trim();
    let objetivo = $('#objetivo').val().trim();

    if (!idade || !peso || !altura || !objetivo) {
        Swal.fire({ icon: 'warning', title: 'Aviso', text: 'Preenche todos os campos antes de continuar.' });
        return;
    }

    idade = parseInt(idade, 10);
    peso = parseFloat(peso);
    altura = parseFloat(altura);

    if (isNaN(idade) || idade < 10 || idade > 100) {
        Swal.fire({ icon: 'error', title: 'Idade inválida', text: 'A idade deve estar entre 10 e 100 anos.' });
        return;
    }

    if (isNaN(peso) || peso < 30 || peso > 250) {
        Swal.fire({ icon: 'error', title: 'Peso inválido', text: 'O peso deve estar entre 30 e 250 kg.' });
        return;
    }

    if (isNaN(altura) || altura < 100 || altura > 250) {
        Swal.fire({ icon: 'error', title: 'Altura inválida', text: 'A altura deve estar entre 100 e 250 cm.' });
        return;
    }

    let dados = new FormData();
    dados.append("op", 7);
    dados.append("idade", idade);
    dados.append("peso", peso);
    dados.append("altura", altura);
    dados.append("objetivo", objetivo);

    $(".btn-guardar").prop("disabled", true).text("A guardar...");

    $.ajax({
        url: "src/controller/controllerLogin.php",
        method: "POST",
        data: dados,
        dataType: "json",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(obj) {
        if (obj && obj.flag) {
            Swal.fire({ icon: 'success', title: 'Sucesso', text: obj.msg, showConfirmButton: false, timer: 1400 });
            setTimeout(() => { window.location.href = "dashboard_cliente.html"; }, 1500);
        } else {
            Swal.fire({ icon: 'error', title: 'Erro', text: obj.msg || 'Erro no servidor' });
        }
    })
    .fail(function(jqXHR, textStatus, errorThrown) {
        console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText);
        Swal.fire({ icon: 'error', title: 'Erro', text: 'Falha na comunicação com o servidor.' });
    })
    .always(function() {
        $(".btn-guardar").prop("disabled", false).text("Guardar e Continuar");
    });
}

document.addEventListener("keypress", function (e) {
    if (e.key === "Enter") guardarPerfil();
});
