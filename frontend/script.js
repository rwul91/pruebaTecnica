$(document).ready(function () {
    // Función para mostrar la pantalla de registro
  
    // Función para mostrar la pantalla de login
  
    // Función para registrar un nuevo usuario
    function registrarUsuario() {
      var nombre = $("#nombre").val();
      var email = $("#email").val();
      var password = $("#password").val();
      var nivel = $("#nivel").val();
  
      if (nombre === "" || email === "" || password === "" || nivel === "") {
        alert("Por favor, complete todos los campos");
        return;
      }
  
      $.post(
        "http://localhost:8090/backend/register",
        { nombre: nombre, email: email, password: password, nivel: nivel },
        function (data) {
          console.log(data)
          if (data.success) {
            alert("Usuario registrado exitosamente");
            $('#login-tab').trigger('click');
          } else {
            alert("Error al registrar usuario: " + data.message);
          }
        }
      );
    }
  
    // Función para iniciar sesión
    function iniciarSesion() {
      var email = $("#email-login").val();
      var password = $("#password-login").val();
  
      if (email === "" || password === "") {
        alert("Por favor, complete todos los campos");
        return;
      }
  
      $.post(
        "http://localhost:8090/backend/login",
        { email: email, password: password },
        function (data) {
          console.log(data, "loco")
          if (data.success) {
            alert("Inicio de sesión exitoso");
  
            // Redirigir a la página de proyectos window.location.href = 'http://localhost/frontend/proyectos.html';
          } else {
            alert("Error al iniciar sesión: " + data.message);
          }
        }
      );
    }
  
    // Asignar eventos a los botones
    $("#registrar").click(registrarUsuario);
    $("#iniciar-sesion").click(iniciarSesion);
  });
  