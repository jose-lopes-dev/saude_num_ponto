(() => {

    const URL = "src/controller/notificacoesController.php"

    const list  = document.getElementById("notification-list")
    const badge = document.getElementById("notification-count")
    const btnClear = document.getElementById("btn-clear-notifications")
    const dropdownBtn = document.getElementById("page-header-notifications-dropdown")

    if (!list || !badge) return

    function renderEmpty() {
        list.innerHTML = `
            <div class="notification-empty">
                Sem notificações
            </div>
        `
    }

    function carregarNotificacoes() {

        fetch(URL, {
            method: "POST",
            body: new URLSearchParams({ op: "listar" })
        })
        .then(r => r.json())
        .then(data => {

            list.innerHTML = ""

            let naoLidas = 0
            data.forEach(n => {
                if (n.lida == 0) naoLidas++
            })

            if (naoLidas > 0) {
                badge.textContent = naoLidas
                badge.classList.remove("d-none")
            } else {
                badge.textContent = ""
                badge.classList.add("d-none")
            }

            if (data.length === 0) {
                renderEmpty()
                return
            }

            data.forEach(n => {
                list.innerHTML += `
                    <div class="notification-item ${n.lida == 0 ? "unread" : ""}"
                        data-id="${n.id}">

                        <div class="notif-title">${n.titulo}</div>

                        <div class="notif-text">${n.texto}</div>

                        <div class="notif-date">${formatarData(n.criada_em)}</div>

                        <hr>
                    </div>
                `
            })
        })

        .catch(() => {
            renderEmpty();
            badge.textContent = "";
            badge.classList.add("d-none");
        });
    }

    list.addEventListener("click", e => {
        const item = e.target.closest(".notification-item")
        if (!item) return

        fetch(URL, {
            method: "POST",
            body: new URLSearchParams({
                op: "ler",
                id: item.dataset.id
            })
        }).then(carregarNotificacoes)
    })

    if (btnClear) {
        btnClear.addEventListener("click", () => {
            fetch(URL, {
                method: "POST",
                body: new URLSearchParams({ op: "limpar" })
            }).then(carregarNotificacoes)
        })
    }

    if (dropdownBtn) {
        dropdownBtn.addEventListener("shown.bs.dropdown", () => {

            fetch(URL, {
                method: "POST",
                body: new URLSearchParams({ op: "ler_todas" })
            }).then(carregarNotificacoes)

            badge.textContent = ""
            badge.classList.add("d-none")
        })
    }

    function formatarData(dataSql) {

        if (!dataSql) return ""

        const d = new Date(dataSql.replace(" ", "T"))

        const dia = String(d.getDate()).padStart(2, "0")
        const mes = String(d.getMonth() + 1).padStart(2, "0")
        const ano = d.getFullYear()

        const hora = String(d.getHours()).padStart(2, "0")
        const min  = String(d.getMinutes()).padStart(2, "0")

        return `${dia}/${mes}/${ano} · ${hora}:${min}`
    }

    renderEmpty();

    carregarNotificacoes()
    setInterval(carregarNotificacoes, 5000)

})()
