// public/js/persona.js
document.addEventListener('DOMContentLoaded', function() {
    // Validación específica para formularios de persona
    const personaForms = document.querySelectorAll('form[action*="registro"], form[action*="editar_persona"]');
    personaForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Validar nombre y apellido (mínimo 2 caracteres)
            const nombreInput = form.querySelector('input[name="nombre_persona"]');
            const apellidoInput = form.querySelector('input[name="apellido_persona"]');
            if (nombreInput && nombreInput.value.trim().length < 2) {
                alert('El nombre debe tener al menos 2 caracteres.');
                isValid = false;
                nombreInput.focus();
            }
            if (apellidoInput && apellidoInput.value.trim().length < 2) {
                alert('El apellido debe tener al menos 2 caracteres.');
                isValid = false;
                apellidoInput.focus();
            }

            // Validar usuario (mínimo 3 caracteres, solo letras/números/guiones bajos)
            const usuarioInput = form.querySelector('input[name="usuario"]');
            if (usuarioInput && (!usuarioInput.value.trim() || usuarioInput.value.length < 3 || !/^[a-zA-Z0-9_]+$/.test(usuarioInput.value))) {
                alert('El usuario debe tener al menos 3 caracteres y solo contener letras, números y guiones bajos.');
                isValid = false;
                usuarioInput.focus();
            }

            // Validar contraseña (solo si es requerida o se proporciona)
            const passwordInput = form.querySelector('input[name="contraseña"]');
            if (passwordInput && passwordInput.hasAttribute('required') && passwordInput.value.length < 6) {
                alert('La contraseña debe tener al menos 6 caracteres.');
                isValid = false;
                passwordInput.focus();
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    });
    // Script para mejorar la experiencia del login
       
            
            // Validación básica del formulario
            const loginForm = document.querySelector('.login-form');
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    const usuario = document.getElementById('usuario').value.trim();
                    const password = document.getElementById('contraseña').value.trim();
                    
                    if (!usuario || !password) {
                        e.preventDefault();
                        // Aquí puedes agregar un mensaje de error visual
                        return false;
                    }
                });
            }
            
            // Efecto de carga en el botón
            const submitBtn = document.querySelector('.btn-login');
            if (submitBtn) {
                submitBtn.addEventListener('click', function() {
                    if (loginForm.checkValidity()) {
                        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Accediendo...';
                        this.disabled = true;
                    }
                });
            }
        });


