// public/js/contacto.js
document.addEventListener('DOMContentLoaded', function() {
    // Validación específica para formularios de contacto
    const contactoForms = document.querySelectorAll('form[action*="agregar_contacto"], form[action*="editar_contacto"]');
    contactoForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Validar nombre y apellido (mínimo 2 caracteres)
            const nombreInput = form.querySelector('input[name="nombre_contacto"]');
            const apellidoInput = form.querySelector('input[name="apellido_contacto"]');
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

            // Validar teléfono (solo números, guiones, espacios)
            const telefonoInput = form.querySelector('input[name="telefono_contacto"]');
            if (telefonoInput && !/^[0-9\-\s]+$/.test(telefonoInput.value)) {
                alert('El teléfono solo puede contener números, guiones y espacios.');
                isValid = false;
                telefonoInput.focus();
            }

            // Validar email (si se proporciona)
            const emailInput = form.querySelector('input[name="email_contacto"]');
            if (emailInput && emailInput.value && !/\S+@\S+\.\S+/.test(emailInput.value)) {
                alert('El email no es válido.');
                isValid = false;
                emailInput.focus();
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    });

    // Buscador en tiempo real
        document.getElementById('buscar-contacto')?.addEventListener('input', function(e) {
            const busqueda = e.target.value.toLowerCase();
            const filas = document.querySelectorAll('.contacto-row');
            
            filas.forEach(fila => {
                const texto = fila.textContent.toLowerCase();
                fila.style.display = texto.includes(busqueda) ? '' : 'none';
            });
        });

        function limpiarBusqueda() {
            document.getElementById('buscar-contacto').value = '';
            document.querySelectorAll('.contacto-row').forEach(fila => {
                fila.style.display = '';
            });
        }

   

    // Mensajes temporales 
    const messages = document.querySelectorAll('.success, .error');
    messages.forEach(msg => {
        setTimeout(() => {
            msg.style.display = 'none';
        }, 5000);  // Ocultar después de 5 segundos
    });
});