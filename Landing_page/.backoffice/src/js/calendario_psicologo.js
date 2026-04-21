$(document).ready(function () {

    const modal = new bootstrap.Modal($("#event-modal")[0])
    let calendar

    new FullCalendar.Draggable($("#external-events")[0], {
        itemSelector: ".external-event:not([data-categoria='Concluído'])",
        eventData: function (el) {
            return {
                title: $(el).text().trim(),
                extendedProps: {
                    categoria: $(el).data("categoria")
                }
            }
        }
    })

    function cor(cat) {
        if (cat === "Sessão") return "#6f42c1"
        if (cat === "Evento Próprio") return "#6c757d"
        if (cat === "Concluído") return "#198754"
        return "#3788d8"
    }

    calendar = new FullCalendar.Calendar($("#calendar")[0], {
        initialView: "dayGridMonth",
        locale: "pt",
        height: "auto",
        editable: false,
        droppable: true,

        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: ""
        },

        events: function (_, success) {

            $.post("src/controller/calendario_psicologo.php", { op: "listar" }, function (res) {

                const eventos = JSON.parse(res).map(e => ({
                    id: "e_" + e.id,
                    title: e.title,
                    start: e.start,
                    end: e.end,
                    extendedProps: e.extendedProps
                }))

                $.post("src/controller/calendario_psicologo.php", { op: "listar_sessoes" }, function (res2) {

                    const sessoes = JSON.parse(res2).map(c => ({
                        id: "c_" + c.id,
                        title: "Consulta",
                        start: c.data_hora,
                        end: c.data_hora,
                        extendedProps: {
                            categoria: "Sessão",
                            readonly: true,
                            cliente: c.cliente
                        }
                    }))

                    success(eventos.concat(sessoes))
                })
            })
        },

        eventDidMount: function (info) {
            const categoria = info.event.extendedProps.categoria
            const c = cor(categoria)
            info.el.style.backgroundColor = c
            info.el.style.borderColor = c
        },

        eventReceive: function (info) {
            info.event.remove()

            $("#form-event")[0].reset()
            $("#event-id").val("")
            $("#event-name").val(info.event.title)
            $("#event-category").val(info.event.extendedProps.categoria)

            const data = info.event.startStr.slice(0, 16)
            $("#event-date").val(data)
            $("#event-date-end").val(data)

            $("#btn-delete-event, #btn-conclude-event").addClass("d-none")
            modal.show()
        },

        eventClick: function (info) {

            if (info.event.extendedProps.readonly) {

                const p = info.event.extendedProps

                Swal.fire({
                    title: "Consulta de Psicologia",
                    html: `
                        <b>Cliente:</b> ${p.cliente}<br>
                        <b>Data:</b> ${info.event.start.toLocaleDateString("pt-PT")}<br>
                        <b>Hora:</b> ${info.event.start.toLocaleTimeString("pt-PT", {
                            hour: "2-digit",
                            minute: "2-digit"
                        })}
                    `,
                    icon: "info"
                })

                return
            }

            const e = info.event

            $("#event-id").val(e.id.replace("e_", ""))
            $("#event-name").val(e.title)
            $("#event-category").val(e.extendedProps.categoria)
            $("#event-date").val(e.startStr.slice(0, 16))
            $("#event-date-end").val(e.endStr.slice(0, 16))
            $("#event-description").val(e.extendedProps.descricao || "")

            $("#btn-delete-event").removeClass("d-none")

            if (e.extendedProps.categoria === "Concluído") {
                $("#btn-conclude-event").addClass("d-none")
            } else {
                $("#btn-conclude-event").removeClass("d-none")
            }

            modal.show()
        }
    })

    calendar.render()

    $("#btn-new-event").click(function () {
        $("#form-event")[0].reset()
        $("#event-id").val("")
        $("#btn-delete-event, #btn-conclude-event").addClass("d-none")
        modal.show()
    })

    $("#form-event").submit(function (e) {
        e.preventDefault()

        $.post("src/controller/calendario_psicologo.php", {
            op: "guardar",
            id: $("#event-id").val(),
            titulo: $("#event-name").val(),
            categoria: $("#event-category").val(),
            inicio: $("#event-date").val(),
            fim: $("#event-date-end").val(),
            descricao: $("#event-description").val()
        }, function () {
            modal.hide()
            calendar.removeAllEvents()
            calendar.refetchEvents()
            Swal.fire("Sucesso", "Evento guardado", "success")
        })
    })

    $("#btn-delete-event").click(function () {
        Swal.fire({
            title: "Remover evento?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Remover"
        }).then(r => {
            if (r.isConfirmed) {
                $.post("src/controller/calendario_psicologo.php",
                    { op: "remover", id: $("#event-id").val() },
                    function () {
                        modal.hide()
                        calendar.removeAllEvents()
                        calendar.refetchEvents()
                        Swal.fire("Removido", "Evento removido", "success")
                    }
                )
            }
        })
    })

    $("#btn-conclude-event").click(function () {
        Swal.fire({
            title: "Concluir evento?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Concluir"
        }).then(r => {
            if (r.isConfirmed) {

                const id = $("#event-id").val()

                $.post("src/controller/calendario_psicologo.php",
                    { op: "concluir", id },
                    function () {
                        modal.hide()
                        calendar.removeAllEvents()
                        calendar.refetchEvents()
                        Swal.fire("Concluído", "Evento concluído", "success")
                    }
                )
            }
        })
    })

})
