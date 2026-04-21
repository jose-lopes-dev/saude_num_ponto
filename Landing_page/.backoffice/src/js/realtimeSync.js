(() => {

    let lastEventId = 0
    const URL = "src/controller/realtimeStream.php"

    function poll() {

        fetch(URL + "?last_id=" + lastEventId)
            .then(r => r.json())
            .then(events => {

                if (!Array.isArray(events) || events.length === 0) return

                events.forEach(ev => {
                    lastEventId = ev.id
                    dispatch(ev)
                })
            })
            .catch(() => {})
    }

    function dispatch(ev) {

        if (!ev || !ev.evento) return

        switch (ev.evento) {

            case "consulta_criada":
            case "consulta_atualizada":
                if (typeof window.carregarConsultas === "function") {
                    window.carregarConsultas()
                }
                break

            case "novo_pedido_suporte":
            case "resposta_suporte":
                if (typeof window.carregarPedidos === "function") {
                    window.carregarPedidos()
                }
                break

            case "nova_notificacao":
                if (typeof window.carregarNotificacoes === "function") {
                    window.carregarNotificacoes()
                }
                break
        }

        document.dispatchEvent(
            new CustomEvent("realtime:event", { detail: ev })
        )
    }

    setInterval(poll, 3000)

})()
