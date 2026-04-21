document.addEventListener('DOMContentLoaded', function () {
    var el = document.getElementById('calendar-full');
    if (!el) return;

    var params = new URLSearchParams(window.location.search);
    var agora  = new Date();
    var year   = parseInt(params.get('year'))  || agora.getFullYear();
    var month  = parseInt(params.get('month')) || (agora.getMonth() + 1);
    var day    = params.get('day');

    function pad(n) {
        return n < 10 ? '0' + n : '' + n;
    }

    var initialDate = year + '-' + pad(month) + '-' + (day || '01');

    var calendar = new FullCalendar.Calendar(el, {
        locale: 'pt',
        initialDate: initialDate,
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'myMonth,myWeek,myDay'
        },
        customButtons: {
            myMonth: {
                text: 'Mês',
                click: function () {
                    calendar.changeView('dayGridMonth');
                }
            },
            myWeek: {
                text: 'Semana',
                click: function () {
                    calendar.changeView('timeGridWeek');
                }
            },
            myDay: {
                text: 'Dia',
                click: function () {
                    calendar.changeView('timeGridDay');
                }
            }
        },



        events: function (info, successCallback, failureCallback) {
            var meio = new Date(info.start.getTime());
            meio.setDate(meio.getDate() + 15);

            var anoReq   = meio.getFullYear();
            var mesReq   = meio.getMonth() + 1;

            $.ajax({
                url: 'src/controller/controllerConsultas.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    op: 4,
                    year: anoReq,
                    month: mesReq
                },
                success: function (resp) {
                    var rows;
                    if (Array.isArray(resp)) {
                        rows = resp;
                    } else if (resp && Array.isArray(resp.rows)) {
                        rows = resp.rows;
                    } else {
                        rows = [];
                    }

                    var events = rows.map(function (r) {
                        var data = r.date || r.data_consulta;
                        var hora = r.hora || r.hora_consulta || '00:00';

                        if (hora && hora.length === 5) {
                            hora = hora + ':00';
                        }

                        return {
                            title: r.servico || 'Consulta',
                            start: data && hora ? (data + 'T' + hora) : data,
                            extendedProps: {
                                cliente: r.cliente || '',
                                profissional: r.profissional || '',
                                servico_extra: r.servico_extra || '',
                                estado: r.estado || '',
                                preco: (r.valor != null) ? r.valor : r.preco
                            }
                        };
                    });

                    successCallback(events);
                },
                error: function (xhr, status, error) {
                    console.error('Erro AJAX calendário de consultas:', status, error, xhr.responseText);
                    if (typeof failureCallback === 'function') {
                        failureCallback(error || status);
                    }
                }
            });
        },

        eventClick: function (info) {
            var e   = info.event;
            var ext = e.extendedProps || {};

            var dt = '';
            if (e.start) {
                dt = e.start.toLocaleString('pt-PT', {
                    year: 'numeric',
                    month: '2-digit',
                    day: '2-digit',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            var html = '';
            if (dt) html += '<b>Data/Hora:</b> ' + dt + '<br>';
            if (ext.cliente) html += '<b>Cliente:</b> ' + ext.cliente + '<br>';
            if (ext.profissional) html += '<b>Profissional:</b> ' + ext.profissional + '<br>';
            if (ext.servico_extra) html += '<b>Serviço Extra:</b> ' + ext.servico_extra + '<br>';
            if (ext.estado) html += '<b>Estado:</b> ' + ext.estado + '<br>';
            if (ext.preco != null) {
                html += '<b>Preço:</b> € ' + Number(ext.preco).toFixed(2);
            }

            Swal.fire({
                title: e.title || 'Consulta',
                html: html || 'Sem detalhes adicionais.',
                icon: 'info'
            });
        }
    });

    calendar.render();
});
