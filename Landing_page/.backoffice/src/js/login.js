(function () {
  "use strict";

  /* --------------------
     Helpers
  -------------------- */
  function alerta(titulo, msg, icon) {
    if (typeof Swal !== "undefined") {
      Swal.fire({
        position: 'center',
        icon: icon,
        title: titulo,
        text: msg,
        showConfirmButton: true
      });
    } else {
      alert(titulo + "\n\n" + msg);
    }
  }

  function togglePassword(inputId, iconElement) {
    var input = document.getElementById(inputId);
    if (!input && iconElement) {
      var grupo = iconElement.closest('.form-group');
      if (grupo) input = grupo.querySelector('input');
    }
    if (!input) return;

    if (input.type === 'password') {
      input.type = 'text';
      if (iconElement) {
        iconElement.classList.remove('uil-eye');
        iconElement.classList.add('uil-eye-slash');
      }
    } else {
      input.type = 'password';
      if (iconElement) {
        iconElement.classList.remove('uil-eye-slash');
        iconElement.classList.add('uil-eye');
      }
    }
  }

  function ajaxForm(dados, callback) {
    $.ajax({
      url: "src/controller/controllerLogin.php",
      type: "POST",
      data: dados,
      processData: false,
      contentType: false,
      dataType: "json",
      success: function (res) {
        callback(res);
      },
      error: function (xhr, status, error) {
        console.log("AJAX ERROR:", status, error, xhr.responseText);
        callback({ flag: false, msg: "Erro ao comunicar com o servidor." });
      }
    });
  }

  function bloqueiaBotao($btn) {
    if (!$btn || $btn.length === 0) return false;
    if ($btn.data('once')) return false;
    $btn.data('once', true);
    return true;
  }

  function desbloqueiaBotao($btn) {
    if (!$btn || $btn.length === 0) return;
    $btn.removeData('once');
  }

  /* --------------------
     REGISTAR
  -------------------- */
  function registaUser() {
    var $btn = $('.card-back .btn');

    if (!bloqueiaBotao($btn)) return;

    var username = $('#username').val() ? $('#username').val().trim() : '';
    var nome = $('#nome').val() ? $('#nome').val().trim() : '';
    var telefone = $('#telefone').val() ? $('#telefone').val().trim() : '';
    var email = $('#email').val() ? $('#email').val().trim() : '';
    var dataNascimento = $('#dataNascimento').val() ? $('#dataNascimento').val().trim() : '';
    var nif = $('#nif').val() ? $('#nif').val().trim() : '';
    var password = $('#password').val() ? $('#password').val().trim() : '';

    if (!username || !nome || !telefone || !email || !dataNascimento || !nif || !password) {
      alerta("Aviso", "Preenche todos os campos antes de continuar.", "warning");
      desbloqueiaBotao($btn);
      return;
    }

    // username validation (simple)
    var usernameRe = /^[a-zA-Z0-9._-]{3,30}$/;
    if (!usernameRe.test(username)) {
      alerta("Aviso", "O username deve ter 3-30 caracteres (letras, números, . _ -).", "warning");
      desbloqueiaBotao($btn);
      return;
    }

    var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRe.test(email)) {
      alerta("Aviso", "Insere um email válido.", "warning");
      desbloqueiaBotao($btn);
      return;
    }

    $btn.prop('disabled', true).text('A registar...');

    var dados = new FormData();
    dados.append("op", 1);
    dados.append("username", username);
    dados.append("nome", nome);
    dados.append("telefone", telefone);
    dados.append("email", email);
    dados.append("dataNascimento", dataNascimento);
    dados.append("nif", nif);
    dados.append("password", password);

    ajaxForm(dados, function (res) {
      $btn.prop('disabled', false).text('Registar');
      desbloqueiaBotao($btn);

      if (res && res.flag) {
        alerta("Sucesso", res.msg || "Registado com sucesso!", "success");
        // limpar campos
        $('#username, #nome, #telefone, #email, #dataNascimento, #nif, #password').val('');
      } else {
        alerta("Erro", (res && res.msg) ? res.msg : "Erro ao registar", "error");
      }
    });
  }

  /* --------------------
     LOGIN
  -------------------- */
  function login() {
    var $btn = $('.card-front .btn');

    if (!bloqueiaBotao($btn)) return;

    var username = $('#usernameLogin').val() ? $('#usernameLogin').val().trim() : '';
    var password = $('#passwordLogin').val() ? $('#passwordLogin').val().trim() : '';

    if (!username || !password) {
      alerta("Aviso", "Preenche o email/username e a password.", "warning");
      desbloqueiaBotao($btn);
      return;
    }

    $btn.prop('disabled', true).text('Entrando...');

    var dados = new FormData();
    dados.append("op", 2);
    dados.append("username", username);
    dados.append("password", password);

    ajaxForm(dados, function (res) {
      $btn.prop('disabled', false).text('Iniciar Sessão');
      desbloqueiaBotao($btn);

      if (res && res.flag) {
        alerta("Sucesso", res.msg || "Login efetuado com sucesso", "success");
        setTimeout(function () {
          if (res.redirect) window.location.href = res.redirect;
        }, 700);
      } else {
        alerta("Erro", (res && res.msg) ? res.msg : "Credenciais inválidas", "error");
      }
    });
  }

  /* --------------------
     RECUPERAR PASSWORD (simples)
  -------------------- */
  function recuperarPassword() {
    var $btn = $('.recuperar-btn');

    if (!bloqueiaBotao($btn)) return;

    var email = $('#emailRecuperar').val() ? $('#emailRecuperar').val().trim() : '';
    var novaPass = $('#novaPassword').val() ? $('#novaPassword').val().trim() : '';

    if (!email || !novaPass) {
      alerta("Aviso", "Preenche todos os campos.", "warning");
      desbloqueiaBotao($btn);
      return;
    }

    var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRe.test(email)) {
      alerta("Aviso", "Insere um email válido.", "warning");
      desbloqueiaBotao($btn);
      return;
    }

    $btn.prop('disabled', true).text('A atualizar...');

    var dados = new FormData();
    dados.append("op", 6);
    dados.append("email", email);
    dados.append("novaPassword", novaPass);

    ajaxForm(dados, function (res) {
      $btn.prop('disabled', false).text('Atualizar password');
      desbloqueiaBotao($btn);

      if (res && res.flag) {
        alerta("Sucesso", res.msg || "Password atualizada!", "success");
        setTimeout(function () {
          window.location.href = "login.html";
        }, 900);
      } else {
        alerta("Erro", (res && res.msg) ? res.msg : "Erro ao atualizar password", "error");
      }
    });
  }

  /* --------------------
     COMPLETAR PERFIL
  -------------------- */
  function completarPerfil() {
    var $btn = $('.completar-perfil-btn');

    if (!bloqueiaBotao($btn)) return;

    var idade = $('#idade').val() ? $('#idade').val().trim() : '';
    var peso = $('#peso').val() ? $('#peso').val().trim() : '';
    var altura = $('#altura').val() ? $('#altura').val().trim() : '';
    var objetivo = $('#objetivo').val() ? $('#objetivo').val().trim() : '';

    if (!idade || !peso || !altura || !objetivo) {
      alerta("Aviso", "Preenche todos os campos.", "warning");
      desbloqueiaBotao($btn);
      return;
    }

    $btn.prop('disabled', true).text('A guardar...');

    var dados = new FormData();
    dados.append("op", 7);
    dados.append("idade", idade);
    dados.append("peso", peso);
    dados.append("altura", altura);
    dados.append("objetivo", objetivo);

    ajaxForm(dados, function (res) {
      $btn.prop('disabled', false).text('Guardar');
      desbloqueiaBotao($btn);

      if (res && res.flag) {
        alerta("Sucesso", res.msg || "Perfil completado com sucesso!", "success");
      } else {
        alerta("Erro", (res && res.msg) ? res.msg : "Erro ao guardar os dados", "error");
      }
    });
  }

  /* --------------------
     LOGOUT
  -------------------- */
  function logout() {
    var dados = new FormData();
    dados.append("op", 3);
    ajaxForm(dados, function () {
      sessionStorage.removeItem('welcomeShown');
      window.location.href = '/Projeto_Final_AIO/Landing_page/.backoffice/login.html';
    });
  }

  /* --------------------
     Ready
  -------------------- */
  $(document).ready(function () {


    $("form").on("submit", function (e) {
      e.preventDefault();
    });

    // ligar botões
    $('.card-front .btn').off('click').on('click', function (e) {
      e.preventDefault();
      login();
    });

    $('.card-back .btn').off('click').on('click', function (e) {
      e.preventDefault();
      registaUser();
    });

    $('.recuperar-btn').off('click').on('click', function (e) {
      e.preventDefault();
      recuperarPassword();
    });

    $('.completar-perfil-btn').off('click').on('click', function (e) {
      e.preventDefault();
      completarPerfil();
    });

    $('.toggle-password').off('click').on('click', function () {
      var input = $(this).closest('.form-group').find('input')[0];
      togglePassword(input.id, this);
    });

    $('#telefone').on("input", function () {
      this.value = this.value.replace(/[^0-9]/g, "");
    });

    $("#usernameLogin, #passwordLogin").on("keydown", function (e) {
    if (e.key === "Enter") {
        e.preventDefault()
        login()
    }
});

function updateToggleLabels() {
  const checked = $("#reg-log").is(":checked");
  $("#lbl-login").toggleClass("active", !checked);
  $("#lbl-reg").toggleClass("active", checked);
  $("#cardWrap").toggleClass("is-flipped", checked);
}

updateToggleLabels();
$("#reg-log").on("change", updateToggleLabels);

// ENTER no LOGIN
$(".card-front input").on("keydown", function (e) {
    if (e.key === "Enter") {
        e.preventDefault()
        login()
    }
})

// ENTER no REGISTO
$(".card-back input").on("keydown", function (e) {
    if (e.key === "Enter") {
        e.preventDefault()
        registaUser()
    }
})
  
});

  // exportar
  window.logout = logout;
  window.registaUser = registaUser;
  window.recuperarPassword = recuperarPassword;
  window.completarPerfil = completarPerfil;
  window.logout = logout;
  window.togglePassword = togglePassword;
})();
