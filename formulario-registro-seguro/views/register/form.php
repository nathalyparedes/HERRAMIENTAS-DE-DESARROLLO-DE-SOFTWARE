<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro Seguro</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Nuestro archivo CSS personalizado -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
        <div class="registro-container">
            <!-- Header con gradiente -->
            <div class="registro-header">
                <h1>Crear Cuenta</h1>
                <p>Completa el formulario para registrarte</p>
            </div>
            
            <!-- Cuerpo del formulario -->
            <div class="registro-body">
                <!-- Contenedor para alertas -->
                <div id="alertContainer"></div>
                
                <!-- Formulario principal -->
                <form id="formularioRegistro" novalidate>
                    <!-- Campo: Nombre Completo -->
                    <div class="form-group mb-4">
                        <label for="nombre" class="form-label">
                            <i class="fas fa-user"></i> Nombre Completo
                        </label>
                        <input type="text" class="form-control" id="nombre" name="nombre" 
                               placeholder="Ej: Juan Pérez García" required>
                        <div class="invalid-feedback">Por favor ingresa tu nombre completo</div>
                    </div>
                    
                    <!-- Campo: Correo Electrónico -->
                    <div class="form-group mb-4">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Correo Electrónico
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-at"></i></span>
                            <input type="email" class="form-control" id="email" name="email" 
                                   placeholder="ejemplo@correo.com" required>
                        </div>
                        <div class="invalid-feedback">Por favor ingresa un email válido</div>
                    </div>
                    
                    <!-- Campo: Teléfono -->
                    <div class="form-group mb-4">
                        <label for="telefono" class="form-label">
                            <i class="fas fa-phone"></i> Teléfono
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-mobile-alt"></i></span>
                            <input type="tel" class="form-control" id="telefono" name="telefono" 
                                   placeholder="12345678" pattern="[0-9]{8,15}" required>
                        </div>
                        <div class="invalid-feedback">Ingresa un teléfono válido (8-15 dígitos)</div>
                    </div>
                    
                    <!-- Campo: Contraseña -->
                    <div class="form-group mb-4">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Contraseña
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="••••••••" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        
                        <!-- Barra de fortaleza de contraseña -->
                        <div class="password-strength">
                            <div class="password-strength-bar" id="strengthBar"></div>
                        </div>
                        
                        <!-- Requisitos de contraseña -->
                        <div class="password-requirements" id="passwordReqs">
                            <div class="requirement" id="req-length">
                                <i class="fas fa-circle"></i> Mínimo 8 caracteres
                            </div>
                            <div class="requirement" id="req-upper">
                                <i class="fas fa-circle"></i> Una letra mayúscula
                            </div>
                            <div class="requirement" id="req-lower">
                                <i class="fas fa-circle"></i> Una letra minúscula
                            </div>
                            <div class="requirement" id="req-number">
                                <i class="fas fa-circle"></i> Un número
                            </div>
                            <div class="requirement" id="req-special">
                                <i class="fas fa-circle"></i> Un carácter especial (@$!%*?&)
                            </div>
                        </div>
                    </div>
                    
                    <!-- Campo: Confirmar Contraseña -->
                    <div class="form-group mb-4">
                        <label for="password_confirm" class="form-label">
                            <i class="fas fa-lock"></i> Confirmar Contraseña
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control" id="password_confirm" 
                                   name="password_confirm" placeholder="••••••••" required>
                        </div>
                        <div class="invalid-feedback">Las contraseñas no coinciden</div>
                    </div>
                    
                    <!-- Campo: Fecha de Nacimiento -->
                    <div class="form-group mb-4">
                        <label for="fecha_nacimiento" class="form-label">
                            <i class="fas fa-calendar"></i> Fecha de Nacimiento
                        </label>
                        <input type="date" class="form-control" id="fecha_nacimiento" 
                               name="fecha_nacimiento" required>
                        <div class="invalid-feedback">Debes ser mayor de 18 años</div>
                    </div>
                    
                    <!-- Checkbox: Términos y Condiciones -->
                    <div class="form-group form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="terminos" 
                               name="terminos" required>
                        <label class="form-check-label" for="terminos">
                            Acepto los <a href="#" class="terms-link" data-bs-toggle="modal" data-bs-target="#terminosModal">términos y condiciones</a>
                        </label>
                        <div class="invalid-feedback">Debes aceptar los términos y condiciones</div>
                    </div>
                    
                    <!-- Botón de Registro -->
                    <button type="submit" class="btn btn-registro w-100" id="btnRegistro">
                        <span id="btnText">
                            <i class="fas fa-user-check"></i> Registrarse
                        </span>
                        <span id="btnSpinner" class="d-none">
                            <span class="spinner-border spinner-border-sm" role="status"></span>
                            Procesando...
                        </span>
                    </button>
                </form>
                
                <!-- Enlace para iniciar sesión -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        ¿Ya tienes cuenta? <a href="#" class="login-link">Iniciar Sesión</a>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Términos y Condiciones -->
    <div class="modal fade" id="terminosModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-file-contract"></i> Términos y Condiciones
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Al registrarte en nuestra plataforma, aceptas los siguientes términos:</p>
                    <ul class="terms-list">
                        <li><i class="fas fa-check-circle text-primary"></i> Proporcionar información veraz y actualizada</li>
                        <li><i class="fas fa-check-circle text-primary"></i> Mantener la confidencialidad de tu contraseña</li>
                        <li><i class="fas fa-check-circle text-primary"></i> No compartir tu cuenta con terceros</li>
                        <li><i class="fas fa-check-circle text-primary"></i> Cumplir con las políticas de uso de la plataforma</li>
                        <li><i class="fas fa-check-circle text-primary"></i> Respetar los derechos de otros usuarios</li>
                    </ul>
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle"></i> Puedes actualizar tus datos en cualquier momento desde tu perfil.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendido</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Registro Exitoso -->
    <div class="modal fade" id="successModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body modal-success">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h3 class="success-title">¡Registro Exitoso!</h3>
                    <p class="success-message" id="successMessage">
                        Tu cuenta ha sido creada correctamente. Hemos enviado un email de confirmación a tu correo.
                    </p>
                    <div class="success-actions">
                        <button type="button" class="btn btn-registro w-100" data-bs-dismiss="modal">
                            <i class="fas fa-home"></i> Ir al Inicio
                        </button>
                        <button type="button" class="btn btn-outline-primary w-100 mt-2" data-bs-dismiss="modal">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <!-- Nuestro archivo JS personalizado -->
    <script src="js/script.js"></script>
</body>
</html>