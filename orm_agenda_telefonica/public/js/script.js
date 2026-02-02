// public/js/script.js
document.addEventListener('DOMContentLoaded', function() {
    // Validación básica de formularios
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredInputs = form.querySelectorAll('input[required]');
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    alert('El campo "' + input.placeholder + '" es requerido.');
                    isValid = false;
                    input.focus();
                    return false;
                }
            });

            // Validación específica para email
            const emailInput = form.querySelector('input[type="email"]');
            if (emailInput && emailInput.value && !/\S+@\S+\.\S+/.test(emailInput.value)) {
                alert('El email no es válido.');
                isValid = false;
                emailInput.focus();
                return false;
            }

            // Validación para teléfono (solo números, guiones, espacios)
            const phoneInput = form.querySelector('input[name*="telefono"]');
            if (phoneInput && phoneInput.value && !/^[0-9\-\s]+$/.test(phoneInput.value)) {
                alert('El teléfono solo puede contener números, guiones y espacios.');
                isValid = false;
                phoneInput.focus();
                return false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    });


    // Mejora UX: Mostrar/ocultar contraseña (opcional para edición)
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        const toggle = document.createElement('button');
        toggle.type = 'button';
        toggle.textContent = 'Mostrar';
        toggle.style.marginTop = '5px';
        toggle.style.width = 'auto';
        input.parentNode.insertBefore(toggle, input.nextSibling);
        toggle.addEventListener('click', function() {
            input.type = input.type === 'password' ? 'text' : 'password';
            toggle.textContent = input.type === 'password' ? 'Mostrar' : 'Ocultar';
        });
    });

    // Mensajes de éxito/error temporales (si se agregan clases en PHP)
    const messages = document.querySelectorAll('.success, .error');
    messages.forEach(msg => {
        setTimeout(() => {
            msg.style.display = 'none';
        }, 5000);  // Ocultar después de 5 segundos
    });
});