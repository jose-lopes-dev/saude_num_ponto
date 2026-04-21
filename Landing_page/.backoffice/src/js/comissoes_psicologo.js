$(document).ready(function () {

    function badgeEstado(id) {
        if (id == 13) return 'warning'
        if (id == 12) return 'success'
        return 'secondary'
    }

    function textoEstado(id) {
        if (id == 13) return 'Por receber'
        if (id == 12) return 'Recebida'
        return 'Outro'
    }

    function linha(c) {
        return `
            <tr>
                <td>${c.paciente}</td>
                <td>${c.criado_em}</td>
                <td>-</td>
                <td>${Number(c.valor_comissao).toFixed(2)} €</td>
                <td>
                    <span class="badge bg-${badgeEstado(c.id_estado)}">
                        ${textoEstado(c.id_estado)}
                    </span>
                </td>
                <td>-</td>
            </tr>
        `
    }

    $.ajax({
        url: 'src/controller/controllerComissoesPsicologo.php',
        method: 'POST',
        data: { op: 'listar' },
        dataType: 'json',
        success(res) {

            const tbody = $('#lista_comissoes')
            tbody.empty()

            let totalGanho = 0
            let totalRecebido = 0

            if (!res.flag || res.comissoes.length === 0) {
                tbody.html(`
                    <tr>
                        <td colspan="6" class="text-center">
                            Sem comissões
                        </td>
                    </tr>
                `)

                $('#kpiTotalGanho').text('0.00 €')
                $('#kpiTotalRecebido').text('0.00 €')
                $('#kpiTotalPorReceber').text('0.00 €')
                return
            }

            res.comissoes.forEach(c => {
                const valor = Number(c.valor_comissao) || 0
                totalGanho += valor

                if (c.id_estado == 12) {
                    totalRecebido += valor
                }

                tbody.append(linha(c))
            })

            const porReceber = totalGanho - totalRecebido

            $('#kpiTotalGanho').text(totalGanho.toFixed(2) + ' €')
            $('#kpiTotalRecebido').text(totalRecebido.toFixed(2) + ' €')
            $('#kpiTotalPorReceber').text(porReceber.toFixed(2) + ' €')
        }
    })
})
