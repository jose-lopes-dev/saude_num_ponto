<footer class="footer border-top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                © <?= date('Y') ?> Saúde Num Ponto
            </div>
        </div>
    </div>
</footer>

</div> <!-- end layout-wrapper -->

<script>
    const USER_SESSION = {
    id: <?php echo json_encode($_SESSION['id']); ?>,
    tipo: <?php echo json_encode($_SESSION['tipo']); ?>,
    cliente_id: <?php echo json_encode($_SESSION['cliente_id']); ?>
    };
    </script>
    


        <!-- JAVASCRIPT -->
        
        <!-- JS GLOBAL -->
        <script src="src/js/lib/jquery3.6.0.min.js"></script>
        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/feather-icons/feather.min.js"></script>
        <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
        <script src="assets/js/plugins.js"></script>
        <script src="assets/js/app.js"></script>

        <script src="src/js/utilizador.js"></script>

        <script src="src/js/header-user.js"></script>

        <script src="src/js/notificacoesGlobal.js"></script>

        <script src="src/js/login.js"></script>



<!-- JITSI -->
<script src="https://meet.jit.si/external_api.js"></script>

</body>
</html>
