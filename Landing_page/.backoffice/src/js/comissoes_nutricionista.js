console.log('JS Comissões Nutricionista carregado ✅')

$(document).ready(function () {

    iniciarSelect()
    syncComissoes().done(refresh)

    $('#filtroEstado').on('change', refresh)

})

/* =========================
   SELECT2 SAFE INIT
========================= */
function iniciarSelect() {

    const $s = $('#filtroEstado')

    if (!$s.length) {
        console.warn('Select filtroEstado não encontrado')
        return
    }

    if (!$.fn.select2) {
        console.warn('Select2 não carregado — a usar select normal')
        return
    }

    if ($s.hasClass('select2-hidden-accessible')) {
        $s.select2('destroy')
    }

    $s.select2({
        width: '180px',
        minimumResultsForSearch: -1
    })
}

/* =========================
   REFRESH
========================= */
function refresh() {
    carregarComissoes()
    getResumoComissoes()
}

/* =========================
   HELPERS
========================= */
function badgeEstado(id) {
    if (id == 13) return 'warning'
    if (id == 12) return 'success'
    return 'secondary'
}

function textoEstado(id) {
    if (id == 13) return 'Por receber'
    if (id == 12) return 'Paga'
    return 'Outro'
}

function money(v) {
    return (parseFloat(v) || 0).toFixed(2)
}

function linha(c) {

    const data = new Date(c.data_hora).toLocaleString('pt-PT')

    let btn = '-'
    if (c.id_estado != 12) {
        btn = `<button class="btn btn-sm btn-success" onclick="marcarPago(${c.id})">Marcar Pago</button>`
    }

    return `
        <tr>
            <td>${c.cliente} | ${data}</td>
            <td>${money(c.valor_pago)} €</td>
            <td>${c.percentagem}%</td>
            <td>${money(c.valor_comissao)} €</td>
            <td>
                <span class="badge bg-${badgeEstado(c.id_estado)}">
                    ${textoEstado(c.id_estado)}
                </span>
            </td>
            <td class="text-end">${btn}</td>
        </tr>
    `
}

/* =========================
   LISTAR
========================= */
function carregarComissoes() {

    $.post(
        'src/controller/controllerComissoesNutricionista.php',
        { op: 'listar', estado: $('#filtroEstado').val() },
        function (r) {

            const tbody = $('#lista_comissoes').empty()

            if (!r.flag || !r.pendentes?.length) {
                tbody.html('<tr><td colspan="6" class="text-center">Sem comissões</td></tr>')
                return
            }

            r.pendentes.forEach(c => tbody.append(linha(c)))

        },
        'json'
    )
}

/* =========================
   RESUMO
========================= */
function getResumoComissoes() {

    $.post(
        'src/controller/controllerComissoesNutricionista.php',
        { op: 'resumo', estado: $('#filtroEstado').val() },
        function (r) {

            const total = parseFloat(r.totalComissao) || 0
            const porPagar = parseFloat(r.totalPorPagar) || 0

            $('#kpiTotalGanho').text(total.toFixed(2).replace('.', ',') + '€')
            $('#kpiTotalRecebido').text((total - porPagar).toFixed(2).replace('.', ',') + '€')
            $('#kpiTotalPorReceber').text(porPagar.toFixed(2).replace('.', ',') + '€')

        },
        'json'
    )
}

/* =========================
   SYNC
========================= */
function syncComissoes() {
    return $.post(
        'src/controller/controllerComissoesNutricionista.php',
        { op: 'sync' },
        null,
        'json'
    )
}

/* =========================
   MARCAR PAGO
========================= */
window.marcarPago = function (id) {

    $.post(
        'src/controller/controllerComissoesNutricionista.php',
        { op: 'marcarPago', id },
        function (r) {

            Swal.fire(
                r.flag ? 'Sucesso' : 'Erro',
                r.msg,
                r.flag ? 'success' : 'error'
            )

            if (r.flag) {
                $('#filtroEstado').trigger('change')
            }

        },
        'json'
    )
}
