$(function () {

    const modal = new bootstrap.Modal($("#event-modal")[0])

    function cor(event) {
        if (event.extendedProps.readonly) return "#6f42c1"
        if (event.extendedProps.categoria === "Concluído") return "#198754"
        return "#6c757d"
    }

    const calendar = new FullCalendar.Calendar($("#calendar")[0], {
        locale: "pt",
        initialView: "dayGridMonth",
        height: "auto",
        editable: false,

        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: ""
        },

        events: function (_, success) {

            $.post(
                "src/controller/calendario_nutricionista.php",
                { op: "listar" },
                function (eventos) {

                    $.post(
                        "src/controller/calendario_nutricionista.php",
                        { op: "listar_consultas" },
                        function (consultasRaw) {

                            const consultas = consultasRaw.map(c => ({
                                id: "c_" + c.id,
                                title: "Consulta - " + c.cliente,
                                start: c.data_hora.replace(" ", "T"),
                                end: c.data_fim.replace(" ", "T"),
                                extendedProps: {
                                    readonly: true,
                                    cliente: c.cliente
                                }
                            }))

                            success(eventos.concat(consultas))
                        },
                        "json"
                    )
                },
                "json"
            )
        },

        eventDidMount(info) {
            const c = cor(info.event)
            info.el.style.backgroundColor = c
            info.el.style.borderColor = c
        },

        dateClick(info) {

            $("#form-event")[0].reset()
            $("#event-id").val("")

            const data = info.dateStr + "T09:00"

            $("#event-name").val("")
            $("#event-category").val("Evento Próprio")
            $("#event-date").val(data)
            $("#event-date-end").val(data)
            $("#event-description").val("")

            $("#btn-delete-event, #btn-conclude-event").addClass("d-none")

            modal.show()
        },

        eventClick(info) {

            const e = info.event
            const p = e.extendedProps

            if (p.readonly) {
                Swal.fire({
                    title: "Consulta",
                    html: `
                        <b>Cliente:</b> ${p.cliente}<br>
                        <b>Data:</b> ${e.start.toLocaleDateString("pt-PT")}<br>
                        <b>Hora:</b> ${e.start.toLocaleTimeString("pt-PT", { hour: "2-digit", minute: "2-digit" })}
                    `,
                    icon: "info"
                })
                return
            }

            $("#form-event")[0].reset()

            $("#event-id").val(e.id)
            $("#event-name").val(e.title)
            $("#event-category").val(p.categoria)
            $("#event-date").val(e.startStr.slice(0, 16))
            $("#event-date-end").val(e.endStr.slice(0, 16))
            $("#event-description").val(p.descricao || "")

            $("#btn-delete-event").removeClass("d-none")

            if (p.categoria === "Concluído") {
                $("#btn-conclude-event").addClass("d-none")
            } else {
                $("#btn-conclude-event").removeClass("d-none")
            }

            modal.show()
        }
    })

    calendar.render()

    $("#form-event").on("submit", function (e) {
        e.preventDefault()

        $.post("src/controller/calendario_nutricionista.php", {
            op: "guardar",
            id: $("#event-id").val(),
            titulo: $("#event-name").val(),
            categoria: $("#event-category").val(),
            inicio: $("#event-date").val(),
            fim: $("#event-date-end").val(),
            descricao: $("#event-description").val()
        }, function () {
            modal.hide()
            calendar.refetchEvents()
            Swal.fire("Sucesso", "Evento guardado", "success")
        })
    })

    $("#btn-delete-event").on("click", function () {
        Swal.fire({
            title: "Remover evento?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Remover"
        }).then(r => {
            if (r.isConfirmed) {
                $.post("src/controller/calendario_nutricionista.php", {
                    op: "remover",
                    id: $("#event-id").val()
                }, function () {
                    modal.hide()
                    calendar.refetchEvents()
                    Swal.fire("Removido", "Evento removido", "success")
                })
            }
        })
    })

    $("#btn-conclude-event").on("click", function () {
        Swal.fire({
            title: "Concluir evento?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Concluir"
        }).then(r => {
            if (r.isConfirmed) {
                $.post("src/controller/calendario_nutricionista.php", {
                    op: "concluir",
                    id: $("#event-id").val()
                }, function () {
                    modal.hide()
                    calendar.refetchEvents()
                    Swal.fire("Concluído", "Evento concluído", "success")
                })
            }
        })
    })

})
