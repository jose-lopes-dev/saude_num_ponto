<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../../vendor/autoload.php';

function enviarEmailAcesso($emailDestino, $nome, $username, $passwordTemp, $tipoUser)
{
    $mail = new PHPMailer(true);

    
        // Configuração SMTP (Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'saudenumponto@gmail.com';
        $mail->Password   = 'yyqk jxco yrkx hzuv';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Remetente e destinatário
        $mail->setFrom('saudenumponto@gmail.com', 'Admin Saude num Ponto');
        $mail->addAddress($emailDestino, $nome);

        // Mensagem conforme tipo de utilizador
        if ($tipoUser == 1) {
            $mensagemTipo = "O seu registo como <b>Administrador</b> foi efetuado com sucesso!";
        } elseif ($tipoUser == 2) {
            $mensagemTipo = "O seu registo como <b>PT</b> foi efetuado com sucesso!";
        } elseif ($tipoUser == 3) {
            $mensagemTipo = "O seu registo como <b>Cliente</b> foi efetuado com sucesso!";
        } elseif ($tipoUser == 4) {
            $mensagemTipo = "O seu registo como <b>Nutricionista</b> foi efetuado com sucesso!";  
        } elseif ($tipoUser == 5) {
            $mensagemTipo = "O seu registo como <b>Psicólogo</b> foi efetuado com sucesso!";  
        } else {
            $mensagemTipo = "O seu registo foi efetuado com sucesso!";
        }

        // Corpo do e-mail
        $mail->isHTML(true);
        $mail->Subject = "Bem-vindo(a) a Saude num Ponto - Dados de Acesso";
        $mail->Body = "
            <h3>Olá, $nome</h3>
            <p>$mensagemTipo</p>
            <p>Aqui estão os seus dados de acesso:</p>
            <ul>
                <li><b>Utilizador:</b> $username</li>
                <li><b>Palavra-passe temporária:</b> $passwordTemp</li>
            </ul>
            <p><b>Por razões de segurança</b>, deverá alterar a sua palavra-passe no primeiro login.</p>
            <br>
            <p>Atenciosamente,<br><b>Equipa Saúde num Ponto</b></p>
        ";

        $mail->AltBody = "Olá $nome, o seu utilizador é $username e a palavra-passe temporária é $passwordTemp. Por favor, altere-a após o primeiro login.";

       if ($mail->send()) {
        return ["flag" => true, "msg" => "Email enviado com sucesso!"];
    } else {
        return ["flag" => false, "msg" => "Erro ao enviar email: " . $mail->ErrorInfo];
    }
}
