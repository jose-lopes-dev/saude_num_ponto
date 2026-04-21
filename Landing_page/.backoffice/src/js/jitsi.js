document.addEventListener('DOMContentLoaded', () => {

    if (typeof JitsiMeetExternalAPI === 'undefined') {
        alert('Erro ao carregar Jitsi');
        return;
    }

    if (!window.JITSI_CONFIG) {
        alert('Configuração Jitsi em falta');
        return;
    }

    const domain = 'meet.jit.si';

    const options = {
        roomName: JITSI_CONFIG.room,
        parentNode: document.getElementById('jitsi-container'),

        subject: JITSI_CONFIG.subject,

        userInfo: {
            displayName: JITSI_CONFIG.user
        },

        configOverwrite: {
            prejoinPageEnabled: false,
            disableDeepLinking: true
        },

        interfaceConfigOverwrite: {
            TOOLBAR_ALWAYS_VISIBLE: true,
            SHOW_JITSI_WATERMARK: false,
            SHOW_WATERMARK_FOR_GUESTS: false,
            TOOLBAR_BUTTONS: JITSI_CONFIG.isPT
                ? ['microphone','camera','desktop','chat','participants-pane','fullscreen','hangup']
                : ['microphone','camera','chat','fullscreen','hangup']
        }
    };

    const api = new JitsiMeetExternalAPI(domain, options);

    api.addEventListener('readyToClose', () => {
        window.location.href = JITSI_CONFIG.isPT
            ? 'aulas_pt.php'
            : 'aulas_cliente.php';
    });

});
