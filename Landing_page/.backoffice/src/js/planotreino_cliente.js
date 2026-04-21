let bufferSemana = {};

function alerta(t, m, i) {
    Swal.fire({ position: 'center', icon: i, title: t, text: m, showConfirmButton: true });
}

function renderResumoDia(dia) {
    const lista = bufferSemana[dia] || [];
    if (lista.length === 0) {
        $("#diaResumo")
          .addClass("is-empty")
          .html(`<div class="pt-empty">Nenhum exercício adicionado.</div>`);
        return;
    }

    $("#diaResumo").removeClass("is-empty");

    let html = `<div class="pt-sum-list">`;
    lista.forEach((it, idx) => {
        html += `
            <div class="pt-sum-row">
                <div class="pt-sum-main">
                    <div class="pt-sum-title">${it.nome}</div>
                    <div class="pt-sum-meta">${it.series} séries • ${it.reps} reps • ${it.descanso}s descanso</div>
                </div>
                <button class="pt-action-btn pt-action-btn--red pt-sum-del" type="button" title="Remover" data-idx="${idx}">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        `;
    });

    html += `</div>`;
    $("#diaResumo").html(html);
}

$("#diaResumo").on("click", ".pt-sum-del", function () {
  const dia = $("#diaSemana").val();          
  const idx = parseInt($(this).data("idx"), 10);

  if (!bufferSemana[dia]) return;

  bufferSemana[dia].splice(idx, 1);
  renderResumoDia(dia);
});

function initSelect2Exercicios(){
    $("#selExercicio").select2({
        placeholder: "Pesquisar exercício...",
        allowClear: true,
        width: "100%",
        ajax: {
            url: "src/controller/controllerPlanotreino_cliente.php",
            method: "POST",
            dataType: "json",
            delay: 250,
            data: function (params) {
                return {
                    op: 1,
                    q: params.term || "",
                    grupo: $("#f_grupo").val(),
                    equipamento: $("#f_equip").val()
                };
            },
            processResults: function (data) {
                if (!data.flag) return { results: [] };
                return { results: data.items.map(x => ({ id: x.id_exercicio, text: x.nome })) };
            }
        }
    });
}

function carregarPlanosCliente(){
    let dados = new FormData();
    dados.append("op", 4);

    $.ajax({
        url: "src/controller/controllerPlanotreino_cliente.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg){
        let obj;
        try{ obj = JSON.parse(msg); } catch(e){
            $("#listaPlanosCliente").html("<p class='text-danger'>Erro a carregar planos.</p>");
            return;
        }

        if(!obj.flag){
            $("#listaPlanosCliente").html("<p class='text-muted'>Nenhum plano criado.</p>");
            return;
        }

        $("#listaPlanosCliente").html(obj.html);
    })
    .fail(function(){ $("#listaPlanosCliente").html("<p class='text-danger'>Erro a carregar planos.</p>"); });
}

function carregarPlanosPT(){
    let dados = new FormData();
    dados.append("op", 8);

    $.ajax({
        url: "src/controller/controllerPlanotreino_cliente.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg){
        let obj;
        try{ obj = JSON.parse(msg); }catch(e){
            $("#listaPlanosPT").html("<p class='text-danger'>Erro ao carregar planos.</p>");
            return;
        }

        if(!obj.flag){
            $("#listaPlanosPT").html("<p class='text-muted'>Nenhum plano recebido.</p>");
            return;
        }

        $("#listaPlanosPT").html(obj.html);
    })
    .fail(function(){
        $("#listaPlanosPT").html("<p class='text-danger'>Erro ao carregar planos.</p>");
    });
}

function verPlano(id){
    let dados = new FormData();
    dados.append("op", 5);
    dados.append("id", id);

    $.ajax({
        url: "src/controller/controllerPlanotreino_cliente.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg){
        let obj;
        try{ obj = JSON.parse(msg); } catch(e){
            alerta("Plano","Erro ao abrir detalhes.","error");
            return;
        }

        if(!obj.flag){
            alerta("Plano", obj.msg || "Não foi possível obter o plano.", "error");
            return;
        }

        $("#mTitulo").text(obj.titulo);
        $("#mCorpo").html(obj.html);
        $("#modalPlano").modal("show");
    })
    .fail(function(){ alerta("Plano","Erro de comunicação com o servidor.","error"); });
}

function removerPlano(id){
    Swal.fire({
        title: "Remover plano?",
        text: "Esta ação é irreversível.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Remover",
        cancelButtonText: "Cancelar"
    }).then((res) => {
        if(!res.isConfirmed) return;

        let dados = new FormData();
        dados.append("op", 6);
        dados.append("id", id);

        $.ajax({
            url: "src/controller/controllerPlanotreino_cliente.php",
            method: "POST",
            data: dados,
            dataType: "html",
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(msg){
            let obj;
            try{ obj = JSON.parse(msg); } catch(e){
                alerta("Plano","Erro ao remover.","error");
                return;
            }

            if(obj.flag){
                alerta("Plano", obj.msg, "success");
                carregarPlanosCliente();
            }else{
                alerta("Plano", obj.msg || "Erro ao remover.", "error");
            }
        })
        .fail(function(){ alerta("Plano","Erro de comunicação com o servidor.","error"); });
    });
}

function carregarFicheirosPT(){
    let dados = new FormData();
    dados.append("op", 7);

    $.ajax({
        url: "src/controller/controllerPlanotreino_cliente.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg){
        let obj;
        try{ obj = JSON.parse(msg); }catch(e){
            $("#listaFicheirosPT").html("<p class='text-danger'>Erro ao carregar ficheiros.</p>");
            return;
        }

        if(!obj.flag){
            $("#listaFicheirosPT").html("<p class='text-muted'>Nenhum ficheiro recebido.</p>");
            return;
        }

        $("#listaFicheirosPT").html(obj.html);
    })
    .fail(function(){
        $("#listaFicheirosPT").html("<p class='text-danger'>Erro ao carregar ficheiros.</p>");
    });
}

function verPlanoPT(id){
    let dados = new FormData();
    dados.append("op", 9);
    dados.append("id", id);

    $.ajax({
        url: "src/controller/controllerPlanotreino_cliente.php",
        method: "POST",
        data: dados,
        dataType: "html",
        cache: false,
        contentType: false,
        processData: false
    })
    .done(function(msg){
        let obj;
        try{ obj = JSON.parse(msg); }catch(e){
            alerta("Plano","Erro ao abrir detalhes.","error");
            return;
        }

        if(!obj.flag){
            alerta("Plano", obj.msg || "Plano não encontrado.", "error");
            return;
        }

        $("#mTitulo").text(obj.titulo);
        $("#mCorpo").html(obj.html);
        $("#modalPlano").modal("show");
    })
    .fail(function(){
        alerta("Plano","Erro de comunicação.","error");
    });
}

$(function(){

    initSelect2Exercicios();

    $(document).on("click", "#bodyMap [data-grupo]", function () {

        $("#bodyMap [data-grupo]").removeClass("ativo");

        $(this).addClass("ativo");

        let g = $(this).data("grupo");
        $("#f_grupo").val(g).trigger("change");
    });

    $('#diaSemana, #f_equip, #f_grupo').select2({
        width: '100%',
        minimumResultsForSearch: Infinity
    });

    $("#f_grupo, #f_equip").on("change", function(){
        $("#selExercicio").val(null).trigger("change");
    });

    $("#diaSemana").on("change", function(){
        renderResumoDia(parseInt($(this).val(),10));
    });

    $("#btnAddEx").on("click", function(){
        const dia = parseInt($("#diaSemana").val(),10);
        const exId = $("#selExercicio").val();
        const exNome = $("#selExercicio").find(":selected").text();

        if(!exId){
            alerta("Plano","Seleciona um exercício.","warning");
            return;
        }

        const series = parseInt($("#inSeries").val(),10) || 3;
        const reps = ($("#inReps").val() || "8-12").trim();
        const descanso = parseInt($("#inDesc").val(),10) || 0;

        if(!bufferSemana[dia]) bufferSemana[dia] = [];
        bufferSemana[dia].push({
            id_exercicio: parseInt(exId,10),
            nome: exNome,
            series: series,
            reps: reps,
            descanso: descanso
        });

        renderResumoDia(dia);
        $("#selExercicio").val(null).trigger("change");
    });

    $("#btnGuardarPlano").on("click", function(){

        const titulo = ($("#tituloPlano").val() || "").trim();
        if(titulo.length < 3){
            alerta("Plano","Plano não tem título!","warning");
            return;
        }

        let tem = false;
        for(const d in bufferSemana){
            if((bufferSemana[d]||[]).length>0){ tem=true; break; }
        }
        if(!tem){
            alerta("Plano","Adiciona pelo menos 1 exercício.","warning");
            return;
        }

        // criar plano
        let dados = new FormData();
        dados.append("op", 2);
        dados.append("titulo", titulo);

        $.ajax({
            url: "src/controller/controllerPlanotreino_cliente.php",
            method: "POST",
            data: dados,
            dataType: "html",
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(msg){
            let obj;
            try{ obj = JSON.parse(msg); } catch(e){
                alerta("Plano","Erro ao criar plano.","error");
                return;
            }

            if(!obj.flag){
                alerta("Plano", obj.msg || "Erro ao criar plano.", "error");
                return;
            }

            const plano_id = obj.id;

            // guardar dias
            let dados2 = new FormData();
            dados2.append("op", 3);
            dados2.append("plano_id", plano_id);
            dados2.append("dias", JSON.stringify(bufferSemana));

            $.ajax({
                url: "src/controller/controllerPlanotreino_cliente.php",
                method: "POST",
                data: dados2,
                dataType: "html",
                cache: false,
                contentType: false,
                processData: false
            })
            .done(function(msg2){
                let obj2;
                try{ obj2 = JSON.parse(msg2); } catch(e){
                    alerta("Plano","Erro ao guardar exercícios.","error");
                    return;
                }

                if(obj2.flag){
                    alerta("Plano", obj2.msg, "success");
                    bufferSemana = {};
                    $("#tituloPlano").val("");
                    renderResumoDia(parseInt($("#diaSemana").val(),10));
                    carregarPlanosCliente();
                }else{
                    alerta("Plano", obj2.msg || "Erro ao guardar.", "error");
                }
            })
            .fail(function(){ alerta("Plano","Erro de comunicação.","error"); });

        })
        .fail(function(){ alerta("Plano","Erro de comunicação.","error"); });

    });

    renderResumoDia(parseInt($("#diaSemana").val(),10));
    carregarPlanosCliente();
    carregarFicheirosPT();
    carregarPlanosPT();
});