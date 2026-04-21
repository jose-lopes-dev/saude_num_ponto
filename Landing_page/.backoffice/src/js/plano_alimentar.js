let ingredienteIndex = 0;
let planoAtual = 0;

function addIngredienteLinha(nome = "", cal = "") {
  ingredienteIndex++;

  $("#ingredientesLista").append(`
    <div class="row g-2 mb-2 ingrediente-item" data-id="${ingredienteIndex}">
      <div class="col-7">
        <input type="text" class="form-control ingrediente-nome" placeholder="Ingrediente" value="${nome}">
      </div>
      <div class="col-3">
        <input type="number" class="form-control ingrediente-cal" placeholder="Calorias" min="1" value="${cal}">
      </div>
      <div class="col-2">
        <button class="pt-action-btn pt-action-btn--red w-100 btnRemoverIng" type="button">
            <i class="ri-close-line"></i>
        </button>
      </div>
    </div>
  `);

  calcularTotalKcalNovoPlano();
}

function calcularTotalKcalNovoPlano() {
  let total = 0;
  $(".ingrediente-item").each(function () {
    const cal = parseInt($(this).find(".ingrediente-cal").val());
    if (!isNaN(cal) && cal > 0) total += cal;
  });
  $("#novoPlanoTotalKcal").text(total);
}

function carregarPlanos() {
  $.post("src/controller/controllerPlano.php", { op: 2 }, function (html) {
    $("#listaPlanos").html(html);
  });
}

function verPlano(id) {
  planoAtual = id;

  $.post("src/controller/controllerPlano.php", { op: 3, id }, function (res) {
    let data = JSON.parse(res);

    $("#modalPlanoTitulo").text(data.plano.titulo);
    $("#modalPlanoTotal").text(data.plano.total + " kcal");

    let html = "";
    data.ingredientes.forEach(i => {
      html += `<li>${i.nome} — ${i.calorias} kcal</li>`;
    });

    $("#modalPlanoIngredientes").html(html);

    const el = document.getElementById("modalVerPlano");
    const m = new bootstrap.Modal(el);
    m.show();
  });
}

function removerPlanoDireto(id) {
  Swal.fire({
    title: "Eliminar plano?",
    text: "Esta ação é irreversível.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Eliminar"
  }).then(res => {
    if (res.isConfirmed) {
      $.post("src/controller/controllerPlano.php", { op: 4, id }, function (ret) {
        let obj = JSON.parse(ret);
        Swal.fire("Feito!", obj.msg, "success");
        carregarPlanos();
      });
    }
  });
}

function carregarFicheirosNutricionista() {
  $.post("src/controller/controllerPlano.php", { op: 10 }, function (html) {
    $("#listaFicheirosNutricionista").html(html);
  });
}

// modal ficheiro nutri
function verFicheiroNutri(caminho, nome, data) {
  $("#nutriFileTitulo").text(nome || "Plano do Nutricionista");
  $("#nutriFileData").text(data || "");
  $("#nutriFileFrame").attr("src", caminho);
  $("#nutriFileDownload").attr("href", caminho);

  const el = document.getElementById("modalVerFicheiroNutri");
  const m = new bootstrap.Modal(el);
  m.show();
}

$(document).ready(function () {

  $("#btnAddIngrediente").on("click", function () {
    addIngredienteLinha();
  });

  $(document).on("click", ".btnRemoverIng", function () {
    $(this).closest(".ingrediente-item").remove();
    calcularTotalKcalNovoPlano();
  });

  $(document).on("input", ".ingrediente-cal", calcularTotalKcalNovoPlano);

  $("#btnLimparPlano").on("click", function () {
    $("#planoTitulo").val("");
    $("#ingredientesLista").empty();
    $("#novoPlanoTotalKcal").text("0");
  });

  $("#btnGuardarPlano").on("click", function () {
    const titulo = $("#planoTitulo").val().trim();
    if (!titulo) return Swal.fire("Atenção", "Dá um título ao plano!", "warning");

    let ingredientes = [];
    $(".ingrediente-item").each(function () {
      const nome = $(this).find(".ingrediente-nome").val().trim();
      const calorias = parseInt($(this).find(".ingrediente-cal").val());

      if (nome && !isNaN(calorias) && calorias > 0) {
        ingredientes.push({ nome, calorias });
      }
    });

    if (ingredientes.length === 0) {
      return Swal.fire("Atenção", "Adiciona pelo menos um ingrediente!", "warning");
    }

    $.post("src/controller/controllerPlano.php", {
      op: 1,
      titulo,
      ingredientes: JSON.stringify(ingredientes)
    }, function (data) {
      let obj = JSON.parse(data);
      Swal.fire(obj.flag ? "Sucesso" : "Erro", obj.msg, obj.flag ? "success" : "error");

      if (obj.flag) {
        $("#planoTitulo").val("");
        $("#ingredientesLista").empty();
        $("#novoPlanoTotalKcal").text("0");
        addIngredienteLinha();
        carregarPlanos();
      }
    });
  });

  $("#filtroPlanos").on("input", function () {
    const q = $(this).val().toLowerCase();
    $("#listaPlanos .plano-item, #listaPlanos .list-group-item, #listaPlanos > div").each(function () {
      const txt = $(this).text().toLowerCase();
      $(this).toggle(txt.includes(q));
    });
  });

  carregarPlanos();
  carregarFicheirosNutricionista();
});
