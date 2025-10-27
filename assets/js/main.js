// ----------------------------------------------------
// --- VARIABLES GLOBALES ---
// ----------------------------------------------------
let isUserLoggedIn = true;

document.addEventListener("DOMContentLoaded", function () {
    console.log('Tico Trips App Loaded - DOMContentLoaded');

    // ----------------------------------------------------
    // --- 1. MANEJO DE AUTENTICACIÓN (Login/Register) ---
    // ----------------------------------------------------
   
    // Captura de eventos de submit para los formularios de autenticación
    const loginForm = document.querySelector('#login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', submitAuthForm);
    }
    const userRegisterForm = document.querySelector('#user-register-form');
    if (userRegisterForm) {
        userRegisterForm.addEventListener('submit', submitAuthForm);
    }
    const businessRegisterForm = document.querySelector('#business-register-form');
    if (businessRegisterForm) {
        businessRegisterForm.addEventListener('submit', submitAuthForm);
    }

    // Manejo del cambio de pestañas de autenticación
    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', handleAuthTabSwitch);
    });

    // ----------------------------------------------------
    // --- 2. MANEJO DE RECUPERACIÓN DE CUENTA ---
    // ----------------------------------------------------
   
    const forgotPasswordLink = document.querySelector('#forgot-password-link');
    if (forgotPasswordLink) {
        forgotPasswordLink.addEventListener('click', openRecoveryModal);
    }
    const closeRecoveryBtn = document.querySelector('#close-recovery-modal');
    if (closeRecoveryBtn) {
        closeRecoveryBtn.addEventListener('click', closeRecoveryModal);
    }
    const recoveryForm = document.querySelector('#recovery-form');
    if (recoveryForm) {
        recoveryForm.addEventListener('submit', submitRecoveryForm);
    }

    // ----------------------------------------------------
    // --- 3. MANEJO DE FORMULARIOS ESPECÍFICOS ---
    // ----------------------------------------------------
   
    const businessApplicationForm = document.querySelector('#business-application-form');
    if (businessApplicationForm) {
        businessApplicationForm.addEventListener('submit', submitBusinessApplication);
    }

    const userProfileForm = document.querySelector('#user-profile-form');
    if (userProfileForm) {
        userProfileForm.addEventListener('submit', submitUserProfileForm);
    }
    const businessProfileForm = document.querySelector('#business-profile-form');
    if (businessProfileForm) {
        businessProfileForm.addEventListener('submit', submitBusinessProfileForm);
    }

    const roleSelect = document.querySelector('#user_role_select');
    if (roleSelect) {
        roleSelect.addEventListener('change', loadUserProfileData);
    }
   
    if (document.getElementById('user-profile-form') || document.getElementById('business-profile-form')) {
        loadUserProfileData();
    }    
});

// ------------------------------------------------------------------
// --- FUNCIONES DE AUTENTICACIÓN Y REGISTRO ---
// ------------------------------------------------------------------

/**
 * Función genérica para enviar formularios de Autenticación.
 * Se ajusta para manejar la redirección del negocio.
 * @param {Event} event - El evento de submit del formulario.
 */
const submitAuthForm = (event) => {
    event.preventDefault();
   
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    data.action = form.id;
   
    console.log(`Submitting form: ${form.id}`);
    console.log("Data to send (JSON):", JSON.stringify(data));
   
    alert(`Formulario ${form.id} enviado. Procesando...`);
   
    // LÓGICA DE REDIRECCIÓN Y ENLACE DE CORREO:
    if(form.id === 'business-register-form') {
        const businessEmail = data.correo;
        console.log(`Negocio registrado con correo: ${businessEmail}. Redirigiendo a formulario de aplicación.`);
       
        // Mensaje clave: el correo de registro se usará para contacto
        alert(`¡Registro de cuenta completado! Usaremos el correo ${businessEmail} para notificarle el estado de su aplicación.`);
       
        // ** REDIRECCIÓN SOLICITADA **
        window.location.href = 'business-application.html';
    }
};

/**
 * Función para cambiar la vista entre los formularios de login y registro.
 */
const handleAuthTabSwitch = (event) => {
    document.querySelectorAll('.auth-form').forEach(form => {
        form.classList.add('hidden');
    });
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-teal-500', 'text-teal-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });

    const targetFormId = event.target.dataset.form;
    document.getElementById(targetFormId).classList.remove('hidden');
    event.target.classList.add('border-teal-500', 'text-teal-600');
    event.target.classList.remove('border-transparent', 'text-gray-500');
};

// ------------------------------------------------------------------
// --- FUNCIONES DE RECUPERACIÓN DE CUENTA ---
// ------------------------------------------------------------------

/**
 * Abre el modal de recuperación de contraseña.
 * @param {Event} event - El evento de click.
 */
const openRecoveryModal = (event) => {
    event.preventDefault();
    document.getElementById('recovery-modal').classList.add('open');
};

/**
 * Cierra el modal de recuperación de contraseña.
 */
const closeRecoveryModal = () => {
    document.getElementById('recovery-modal').classList.remove('open');
    document.getElementById('recovery-form').reset();
};

/**
 * Maneja el envío del formulario de recuperación.
 * @param {Event} event - El evento de submit del formulario.
 */
const submitRecoveryForm = (event) => {
    event.preventDefault();
   
    const form = event.target;
    const formData = new FormData(form);
    const email = formData.get('correo');
   
    console.log(`Solicitud de recuperación para: ${email}`);
   
    // Simulación de respuesta de API: Preguntar el correo y enviar link
    alert(`Si la cuenta existe, se ha enviado un link de recuperación de contraseña al correo: ${email}.`);
   
    closeRecoveryModal();
};

// ------------------------------------------------------------------
// --- FUNCIÓN DE APLICACIÓN DE NEGOCIO ---
// ------------------------------------------------------------------

/**
 * Maneja el submit del Formulario de Aplicación de Negocio.
 * @param {Event} event - El evento de submit del formulario.
 */
const submitBusinessApplication = (event) => {
    event.preventDefault();
   
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
   
    console.log('Submitting Business Application Form (Certificación)');
    console.log("Data de Certificación (JSON, sin archivos):", JSON.stringify(data));
   
    // Obtener el correo del negocio para el mensaje de confirmación
    const businessEmail = data.correo_negocio || 'el correo electrónico de registro';
   
    // Mensaje de alerta actualizado para incluir el tiempo de respuesta.
    alert(`
        ¡Solicitud Enviada!
        Su aplicación ha sido recibida y está en proceso de revisión administrativa.
        Recibirá una respuesta sobre el estado de su certificación en los próximos 7 días hábiles
        al correo electrónico: ${businessEmail}.
    `);
};

// ------------------------------------------------------------------
// --- FUNCIONES DE PERFIL Y VALIDACIÓN DE ROL ---
// ------------------------------------------------------------------

/**
 * Carga los datos del perfil y determina que vista mostrar.
 */
const loadUserProfileData = () => {
    const roleSelect = document.querySelector('#user_role_select');
    const userRole = roleSelect ? roleSelect.value : 'user';

    const mockUserData = {
        nombre: 'Bryan Cerdas Salas',
        correo: 'bryan.user@example.com',
        telefono: '8888-9999',
    };
   
    const mockBusinessData = {
        nombre_negocio_publico: 'Ticos Tours Aventuras S.A.',
        correo: 'contacto@ticotours.com',
        telefono: '2233-4455',
        provincia: 'Guanacaste',
        canton: 'Nicoya',
        distrito: 'Nosara',
        direccion_exacta: '50m sur de la playa principal, casa esquinera color azul.',
        google_maps_link: 'https://maps.app.goo.gl/Ejemplo',
        waze_link: 'https://waze.com/ul/Ejemplo'
    };

    if (userRole === 'business') {
        displayBusinessProfile(mockBusinessData);
    } else {
        displayUserProfile(mockUserData);
    }
}

/**
 * Muestra la vista y rellena los campos del Perfil de Usuario Básico.
 */
const displayUserProfile = (data) => {
    document.querySelector('#profile-view-user').classList.remove('hidden');
    document.querySelector('#profile-view-business')?.classList.add('hidden');
   
    const form = document.getElementById('user-profile-form');
    if (form) {
        form.elements['nombre'].value = data.nombre || '';
        form.elements['correo'].value = data.correo || '';
        form.elements['telefono'].value = data.telefono || '';
    }
    localStorage.setItem('user_current_name', data.nombre);
}

/**
 * Muestra la vista y rellena los campos del Perfil de Negocio Certificado.
 */
const displayBusinessProfile = (data) => {
    document.querySelector('#profile-view-business').classList.remove('hidden');
    document.querySelector('#profile-view-user')?.classList.add('hidden');

    const form = document.getElementById('business-profile-form');
    if (form) {
        form.elements['nombre_negocio_publico'].value = data.nombre_negocio_publico || '';
        form.elements['correo'].value = data.correo || '';
        form.elements['telefono'].value = data.telefono || '';
       
        form.elements['provincia'].value = data.provincia || 'San José';
        form.elements['canton'].value = data.canton || '';
        form.elements['distrito'].value = data.distrito || '';
        form.elements['direccion_exacta'].value = data.direccion_exacta || '';
        form.elements['google_maps_link'].value = data.google_maps_link || '';
        form.elements['waze_link'].value = data.waze_link || '';
    }
    localStorage.setItem('business_current_name', data.nombre_negocio_publico);
}


// ------------------------------------------------------------------
// --- FUNCIONES DE SUBMIT DE PERFILES ---
// ------------------------------------------------------------------

/**
 * Envía la actualización del Perfil de Usuario Básico.
 * Valida si el nombre legal ha cambiado.
 */
const submitUserProfileForm = (event) => {
    event.preventDefault();
   
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
   
    console.log('Submitting User Profile Update (User Role)');
    console.log("Profile Data to update (JSON):", JSON.stringify(data));

    if (data.nombre !== localStorage.getItem('user_current_name')) {
        alert('Cambio de Nombre Legal detectado. ¡Guardado! El cambio requiere aprobación administrativa para ser efectivo.');
    } else {
        alert('Perfil de usuario actualizado con éxito.');
    }
   
    localStorage.setItem('user_current_name', data.nombre);
};

/**
 * Envía la actualización del Perfil de Negocio Certificado.
 * Valida si el nombre público ha cambiado.
 */
const submitBusinessProfileForm = (event) => {
    event.preventDefault();
   
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
   
    console.log('Submitting Business Profile Update (Business Role)');
    console.log("Business Data to update (JSON):", JSON.stringify(data));

    if (data.nombre_negocio_publico !== localStorage.getItem('business_current_name')) {
        alert('Cambio de Nombre del Negocio detectado. ¡Guardado! El cambio requiere aprobación administrativa para ser efectivo');
    } else {
        alert('Perfil del negocio ha sido actualizado con éxito.');
    }
   
    localStorage.setItem('business_current_name', data.nombre_negocio_publico);
};