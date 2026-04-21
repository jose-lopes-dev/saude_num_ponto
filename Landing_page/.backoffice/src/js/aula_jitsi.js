document.addEventListener("DOMContentLoaded", () => {

    if (typeof JitsiMeetExternalAPI === "undefined") {
        alert("Erro: Jitsi API não carregou");
        return;
    }

    if (
        typeof JITSI_ROOM === "undefined" ||
        typeof JITSI_USER === "undefined" ||
        typeof JITSI_IS_PT === "undefined" ||
        typeof JITSI_TITLE === "undefined"
    ) {
        alert("Erro: variáveis da aula não definidas");
        console.error({ JITSI_ROOM, JITSI_USER, JITSI_IS_PT, JITSI_TITLE });
        return;
    }

    const domain = "meet.jit.si";

    const options = {
        roomName: JITSI_ROOM,
        parentNode: document.querySelector('#jitsi-container'),

        subject: JITSI_TITLE,

        userInfo: {
            displayName: JITSI_USER
        },

        configOverwrite: {
            prejoinPageEnabled: false,
            startWithAudioMuted: false,
            disableDeepLinking: true
        },

        interfaceConfigOverwrite: {
            DISABLE_JOIN_LEAVE_NOTIFICATIONS: true,
            SHOW_JITSI_WATERMARK: false,
            SHOW_WATERMARK_FOR_GUESTS: false,
            TOOLBAR_ALWAYS_VISIBLE: true,
            TOOLBAR_BUTTONS: JITSI_IS_PT
                ? [
                    'microphone',
                    'camera',
                    'desktop',
                    'fullscreen',
                    'chat',
                    'participants-pane',
                    'raisehand',
                    'tileview',
                    'hangup'
                ]
                : [
                    'microphone',
                    'camera',
                    'chat',
                    'raisehand',
                    'fullscreen',
                    'hangup'
                ]
        }
    };

    let api;

    try {
        api = new JitsiMeetExternalAPI(domain, options);
    } catch (e) {
        console.error("Erro ao iniciar Jitsi", e);
        alert("Erro ao iniciar a sala virtual");
        return;
    }

    /*  Sair da aula  */
    
    api.addEventListener('readyToClose', () => {
        window.location.href = JITSI_IS_PT
            ? '/Projeto_Final_AIO/Landing_page/.backoffice/dashboard_pt.php'
            : '/Projeto_Final_AIO/Landing_page/.backoffice/dashboard_cliente.php';
    });

});
