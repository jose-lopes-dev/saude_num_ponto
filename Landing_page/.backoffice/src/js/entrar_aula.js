document.addEventListener('DOMContentLoaded', () => {

    document.querySelectorAll('.btn-entrar-aula').forEach(btn => {

        btn.addEventListener('click', () => {

            const idAula = btn.dataset.id;
            if (!idAula) return;

            // UI feedback
            btn.disabled = true;
            const originalText = btn.innerHTML;
            btn.innerHTML = 'A entrar...';

            fetch('ajax/entrar_aula.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `idAula=${idAula}`
            })
            .then(res => res.json())
            .then(data => {

                if (!data.success) {
                    alert(data.message);
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    return;
                }

                // sucesso → redirect
                window.location.href = data.redirect;
            })
            .catch(err => {
                console.error(err);
                alert('Erro ao entrar na aula');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });

        });

    });

});
