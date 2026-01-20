// ============================================
// CONFIGURACIÓN Y CONSTANTES
// ============================================
const CONFIG = {
    password: {
        minLength: 8,
        requirements: {
            upper: /[A-Z]/,
            lower: /[a-z]/,
            number: /\d/,
            special: /[@$!%*?&]/
        }
    },
    api: {
        endpoint: 'index.php?action=process',  
        timeout: 10000 // 10 segundos
    }
};

// ============================================
// ELEMENTOS DEL DOM
// ============================================
const elements = {
    form: document.getElementById('formularioRegistro'),
    alertContainer: document.getElementById('alertContainer'),
    passwordInput: document.getElementById('password'),
    passwordConfirm: document.getElementById('password_confirm'),
    strengthBar: document.getElementById('strengthBar'),
    passwordReqs: document.getElementById('passwordReqs'),
    togglePasswordBtn: document.getElementById('togglePassword'),
    fechaNacimiento: document.getElementById('fecha_nacimiento'),
    btnRegistro: document.getElementById('btnRegistro'),
    btnText: document.getElementById('btnText'),
    btnSpinner: document.getElementById('btnSpinner'),
    successMessage: document.getElementById('successMessage')
};

// ============================================
// INICIALIZACIÓN
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    initForm();
    setupEventListeners();
    setMaxDateForBirthday();
});

// ============================================
// FUNCIONES DE INICIALIZACIÓN
// ============================================
function initForm() {
    elements.form.classList.remove('was-validated');
    elements.alertContainer.innerHTML = '';
    elements.strengthBar.className = 'password-strength-bar';
}

function setupEventListeners() {
    elements.togglePasswordBtn.addEventListener('click', togglePasswordVisibility);
    
    // Debounce para mejor rendimiento
    const debouncedValidate = debounce(validatePasswordStrength, 300);
    elements.passwordInput.addEventListener('input', debouncedValidate);
    elements.passwordInput.addEventListener('focus', showPasswordRequirements);
    elements.passwordInput.addEventListener('blur', hidePasswordRequirements);
    
    elements.passwordConfirm.addEventListener('input', validatePasswordMatch);
    elements.fechaNacimiento.addEventListener('change', validateBirthDate);
    elements.form.addEventListener('submit', handleFormSubmit);
    
    const inputs = elements.form.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('blur', validateField);
        input.addEventListener('input', clearFieldValidation);
    });
}

// Función de debounce
function debounce(func, delay) {
    let timeout;
    return function(...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

// ============================================
// FUNCIONES DE VALIDACIÓN
// ============================================
function togglePasswordVisibility() {
    const icon = this.querySelector('i');
    const type = elements.passwordInput.type === 'password' ? 'text' : 'password';
    elements.passwordInput.type = type;
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
    this.title = type === 'password' ? 'Mostrar contraseña' : 'Ocultar contraseña';
}

function validatePasswordStrength() {
    const password = this.value.trim();
    let strength = 0;
    const requirements = CONFIG.password.requirements;
    
    const checks = {
        length: password.length >= CONFIG.password.minLength,
        upper: requirements.upper.test(password),
        lower: requirements.lower.test(password),
        number: requirements.number.test(password),
        special: requirements.special.test(password)
    };
    
    strength = Object.values(checks).filter(Boolean).length;
    updateRequirementUI(checks);
    updateStrengthBar(strength);
    
    if (elements.passwordConfirm.value) {
        validatePasswordMatch();
    }
}

function updateRequirementUI(checks) {
    const requirements = {
        'length': 'req-length',
        'upper': 'req-upper',
        'lower': 'req-lower',
        'number': 'req-number',
        'special': 'req-special'
    };
    
    Object.entries(requirements).forEach(([key, id]) => {
        const element = document.getElementById(id);
        const icon = element.querySelector('i');
        const isMet = checks[key];
        
        element.classList.toggle('met', isMet);
        icon.classList.toggle('fa-circle', !isMet);
        icon.classList.toggle('fa-check-circle', isMet);
        icon.style.color = isMet ? '#10B981' : '#9CA3AF';
    });
}

function updateStrengthBar(strength) {
    elements.strengthBar.className = 'password-strength-bar';
    
    if (strength <= 2) {
        elements.strengthBar.classList.add('strength-weak');
    } else if (strength <= 4) {
        elements.strengthBar.classList.add('strength-medium');
    } else {
        elements.strengthBar.classList.add('strength-strong');
    }
}

function showPasswordRequirements() {
    elements.passwordReqs.classList.add('show');
}

function hidePasswordRequirements() {
    if (!elements.passwordInput.value) {
        setTimeout(() => {
            if (document.activeElement !== elements.passwordInput) {
                elements.passwordReqs.classList.remove('show');
            }
        }, 300);
    }
}

function validatePasswordMatch() {
    const password = elements.passwordInput.value;
    const confirm = elements.passwordConfirm.value;
    
    if (confirm && password !== confirm) {
        elements.passwordConfirm.setCustomValidity('Las contraseñas no coinciden');
        elements.passwordConfirm.classList.add('is-invalid');
        elements.passwordConfirm.classList.remove('is-valid');
    } else {
        elements.passwordConfirm.setCustomValidity('');
        if (confirm) {
            elements.passwordConfirm.classList.add('is-valid');
            elements.passwordConfirm.classList.remove('is-invalid');
        }
    }
}

function setMaxDateForBirthday() {
    const today = new Date();
    const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
    elements.fechaNacimiento.max = maxDate.toISOString().split('T')[0];
}

function validateBirthDate() {
    const birthDate = new Date(this.value);
    const today = new Date();
    let age = today.getFullYear() - birthDate.getFullYear();
    const monthDiff = today.getMonth() - birthDate.getMonth();
    
    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    
    if (age < 18) {
        this.setCustomValidity('Debes ser mayor de 18 años para registrarte');
        this.classList.add('is-invalid');
        this.classList.remove('is-valid');
    } else {
        this.setCustomValidity('');
        this.classList.add('is-valid');
        this.classList.remove('is-invalid');
    }
}

function validateField() {
    if (this.checkValidity()) {
        this.classList.remove('is-invalid');
        this.classList.add('is-valid');
    } else {
        this.classList.remove('is-valid');
        this.classList.add('is-invalid');
    }
}

function clearFieldValidation() {
    this.classList.remove('is-invalid', 'is-valid');
}

// ============================================
// MANEJO DEL ENVÍO DEL FORMULARIO
// ============================================
async function handleFormSubmit(e) {
    e.preventDefault();
    
    if (!elements.form.checkValidity()) {
        e.stopPropagation();
        elements.form.classList.add('was-validated');
        showAlert('Por favor, completa todos los campos requeridos correctamente.', 'danger');
        scrollToTop();
        return;
    }
    
    showLoadingState(true);
    
    try {
        const formData = new FormData(elements.form);
        
        const response = await fetch(CONFIG.api.endpoint, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'  
            },
            signal: AbortSignal.timeout(CONFIG.api.timeout)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showSuccessModal(result.message);
            elements.form.reset();
            resetFormUI();
        } else {
            showValidationErrors(result.errors || ['Error desconocido']);
        }
        
    } catch (error) {
        handleConnectionError(error);
    } finally {
        showLoadingState(false);
    }
}

// ============================================
// FUNCIONES DE UI
// ============================================
function showLoadingState(show) {
    if (show) {
        elements.btnRegistro.disabled = true;
        elements.btnText.classList.add('d-none');
        elements.btnSpinner.classList.remove('d-none');
    } else {
        elements.btnRegistro.disabled = false;
        elements.btnText.classList.remove('d-none');
        elements.btnSpinner.classList.add('d-none');
    }
}

function showAlert(message, type = 'danger') {
    // Sanitización básica contra XSS
    const safeMessage = message.replace(/</g, '&lt;').replace(/>/g, '&gt;');
    
    const alertTypes = {
        danger: { icon: 'exclamation-triangle', color: '#EF4444', bg: '#FEE2E2' },
        success: { icon: 'check-circle', color: '#10B981', bg: '#D1FAE5' },
        warning: { icon: 'exclamation-circle', color: '#F59E0B', bg: '#FEF3C7' }
    };
    
    const config = alertTypes[type] || alertTypes.danger;
    
    const alertHTML = `
        <div class="alert alert-${type}" role="alert" style="border-left-color: ${config.color};">
            <i class="fas fa-${config.icon}" style="color: ${config.color};"></i>
            <div>
                <strong>${type === 'danger' ? 'Error' : type === 'success' ? 'Éxito' : 'Advertencia'}</strong>
                <div>${safeMessage}</div>
            </div>
        </div>
    `;
    
    elements.alertContainer.innerHTML = alertHTML;
    scrollToTop();
}

function showValidationErrors(errors) {
    let errorHTML = '<div class="alert alert-danger" role="alert">';
    errorHTML += '<i class="fas fa-exclamation-triangle"></i> <strong>Errores de validación:</strong>';
    errorHTML += '<ul class="error-list">';
    errors.forEach(error => {
        errorHTML += `<li>${error.replace(/</g, '&lt;').replace(/>/g, '&gt;')}</li>`;  // Sanitización
    });
    errorHTML += '</ul></div>';
    
    elements.alertContainer.innerHTML = errorHTML;
    scrollToTop();
}

function showSuccessModal(message) {
    elements.successMessage.textContent = message;
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    successModal.show();
}

function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function resetFormUI() {
    elements.strengthBar.className = 'password-strength-bar';
    elements.passwordReqs.classList.remove('show');
    
    const inputs = elements.form.querySelectorAll('input');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });
    
    elements.alertContainer.innerHTML = '';
}

function handleConnectionError(error) {
    console.error('Error de conexión:', error);
    
    const errorHTML = `
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-circle"></i> 
            <strong>Error de conexión</strong>
            <div>No se pudo conectar con el servidor. Por favor, verifica tu conexión e intenta nuevamente.</div>
            <small class="mt-2 d-block">Detalle técnico: ${error.message}</small>
        </div>
    `;
    
    elements.alertContainer.innerHTML = errorHTML;
    scrollToTop();
}

// ============================================
// EXPORTAR FUNCIONES PARA PRUEBAS
// ============================================
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        validatePasswordStrength,
        validatePasswordMatch,
        validateBirthDate
    };
} else if (typeof window !== 'undefined') {
    // Evita conflictos globales (para SyntaxError de CONFIG)
    window.RegistroSecure = {
        validatePasswordStrength,
        validatePasswordMatch,
        validateBirthDate
    };
}