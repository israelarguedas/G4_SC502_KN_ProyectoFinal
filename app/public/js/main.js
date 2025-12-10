import { handleSearch } from "./handlers/searchHandler.js";

// --- VARIABLES GLOBALES (Para simular el estado de la aplicación) ---
let isUserLoggedIn = true;

document.addEventListener("DOMContentLoaded", async function () {
  console.log("Tico Trips App Loaded - DOMContentLoaded");

  try {
    handleSearch();
  } catch (error) {
    console.log(error);
  }

  // ----------------------------------------------------
  // --- 1. MANEJO DE AUTENTICACIÓN (Login/Register) ---
  // ----------------------------------------------------

  // Captura de eventos de submit para los formularios de autenticación
  const loginForm = document.querySelector("#login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", submitAuthForm);
  }
  const userRegisterForm = document.querySelector("#user-register-form");
  if (userRegisterForm) {
    userRegisterForm.addEventListener("submit", submitAuthForm);
  }
  const businessRegisterForm = document.querySelector(
    "#business-register-form"
  );
  if (businessRegisterForm) {
    businessRegisterForm.addEventListener("submit", submitAuthForm);
  }

  // Manejo del cambio de pestañas de autenticación
  document.querySelectorAll(".tab-button").forEach((button) => {
    button.addEventListener("click", handleAuthTabSwitch);
  });

  // ----------------------------------------------------
  // --- 2. MANEJO DE RECUPERACIÓN DE CUENTA ---
  // ----------------------------------------------------

  const forgotPasswordLink = document.querySelector("#forgot-password-link");
  if (forgotPasswordLink) {
    forgotPasswordLink.addEventListener("click", openRecoveryModal);
  }
  const closeRecoveryBtn = document.querySelector("#close-recovery-modal");
  if (closeRecoveryBtn) {
    closeRecoveryBtn.addEventListener("click", closeRecoveryModal);
  }
  const recoveryForm = document.querySelector("#recovery-form");
  if (recoveryForm) {
    recoveryForm.addEventListener("submit", submitRecoveryForm);
  }

  // ----------------------------------------------------
  // --- 3. MANEJO DE FORMULARIOS ESPECÍFICOS ---
  // ----------------------------------------------------

  const businessApplicationForm = document.querySelector(
    "#business-application-form"
  );
  if (businessApplicationForm) {
    businessApplicationForm.addEventListener(
      "submit",
      submitBusinessApplication
    );
  }

  const userProfileForm = document.querySelector("#user-profile-form");
  if (userProfileForm) {
    userProfileForm.addEventListener("submit", submitUserProfileForm);
  }
  const businessProfileForm = document.querySelector("#business-profile-form");
  if (businessProfileForm) {
    businessProfileForm.addEventListener("submit", submitBusinessProfileForm);
  }

  const roleSelect = document.querySelector("#user_role_select");
  if (roleSelect) {
    roleSelect.addEventListener("change", loadUserProfileData);
  }

  if (
    document.getElementById("user-profile-form") ||
    document.getElementById("business-profile-form")
  ) {
    loadUserProfileData();
  }

  // ----------------------------------------------------
  // --- 4. INICIALIZACIÓN DE PÁGINAS ESPECIALES ---
  // ----------------------------------------------------

  // Inicializar lógica de administración (Admin Page)
  initAdminPanel();

  // Inicializar lógica de reservas (business-reservations.php)
  initReservas(); 
});

// ------------------------------------------------------------------
// --- FUNCIONES DE AUTENTICACIÓN Y REGISTRO ---
// ------------------------------------------------------------------

/**
 * Función genérica para enviar formularios de Autenticación (Login o Register).
 * Se ajusta para manejar la redirección del negocio de forma efectiva.
 * @param {Event} event - El evento de submit del formulario.
 */
const submitAuthForm = (event) => {
  // Don't prevent default - let the PHP backend handle the form submission
  // event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);
  const data = Object.fromEntries(formData.entries());

  console.log(`Submitting form: ${form.id}`);
  console.log("Data to send (JSON):", JSON.stringify(data));

  // Let the form submit naturally to the PHP backend
  // The MVC controller will handle the processing and redirects
};

/**
 * Función para cambiar la vista entre los formularios de login y registro.
 */
const handleAuthTabSwitch = (event) => {
  document.querySelectorAll(".auth-form").forEach((form) => {
    form.classList.add("hidden");
  });
  document.querySelectorAll(".tab-button").forEach((button) => {
    button.classList.remove("border-teal-500", "text-teal-600");
    button.classList.add("border-transparent", "text-gray-500");
  });

  const targetFormId = event.target.dataset.form;
  document.getElementById(targetFormId).classList.remove("hidden");
  event.target.classList.add("border-teal-500", "text-teal-600");
  event.target.classList.remove("border-transparent", "text-gray-500");
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
  document.getElementById("recovery-modal").classList.add("open");
};

/**
 * Cierra el modal de recuperación de contraseña.
 */
const closeRecoveryModal = () => {
  document.getElementById("recovery-modal").classList.remove("open");
  document.getElementById("recovery-form").reset();
};

/**
 * Maneja el envío del formulario de recuperación (simulación de envío de link).
 * @param {Event} event - El evento de submit del formulario.
 */
const submitRecoveryForm = (event) => {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);
  const email = formData.get("correo");

  console.log(`Solicitud de recuperación para: ${email}`);

  alert(
    `Si la cuenta existe, se ha enviado un link de recuperación de contraseña al correo: ${email}.`
  );

  closeRecoveryModal();
};

// ------------------------------------------------------------------
// --- FUNCIÓN DE APLICACIÓN DE NEGOCIO ---
// ------------------------------------------------------------------

/**
 * Maneja el submit del Formulario de Aplicación de Negocio (Certificación).
 * Incluye alerta de tiempo de respuesta (7 días hábiles).
 * @param {Event} event - El evento de submit del formulario.
 */
const submitBusinessApplication = (event) => {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);
  const data = Object.fromEntries(formData.entries());

  console.log("Submitting Business Application Form (Certificación)");
  console.log(
    "Data de Certificación (JSON, sin archivos):",
    JSON.stringify(data)
  );

  // Obtener el correo del negocio para el mensaje de confirmación
  const businessEmail =
    data.correo_negocio || "el correo electrónico de registro";

  // Mensaje de alerta actualizado para incluir el tiempo de respuesta.
  alert(`
        ¡Solicitud Enviada!
        Su aplicación ha sido recibida y está en proceso de revisión administrativa. 
        Recibirá una respuesta sobre el estado de su certificación en los próximos 7 días hábiles 
        al correo electrónico: ${businessEmail}.
    `);

  // La solicitud va a la cola de aprobación del Administrador (RF-09.2)
};

// ------------------------------------------------------------------
// --- FUNCIONES DE PERFIL Y VALIDACIÓN DE ROL ---
// ------------------------------------------------------------------

/**
 * Carga los datos del perfil y determina qué vista mostrar (Usuario vs. Negocio).
 */
const loadUserProfileData = () => {
  const roleSelect = document.querySelector("#user_role_select");
  const userRole = roleSelect ? roleSelect.value : "user";

  const mockUserData = {
    nombre: "Bryan Cerdas Salas",
    correo: "bryan.user@example.com",
    telefono: "8888-9999",
  };

  const mockBusinessData = {
    nombre_negocio_publico: "Ticos Tours Aventuras S.A.",
    correo: "contacto@ticotours.com",
    telefono: "2233-4455",
    provincia: "Guanacaste",
    canton: "Nicoya",
    distrito: "Nosara",
    direccion_exacta:
      "50m sur de la playa principal, casa esquinera color azul.",
    google_maps_link: "https://maps.app.goo.gl/Ejemplo",
    waze_link: "https://waze.com/ul/Ejemplo",
  };

  if (userRole === "business") {
    displayBusinessProfile(mockBusinessData);
  } else {
    displayUserProfile(mockUserData);
  }
};

/**
 * Muestra la vista y rellena los campos del Perfil de Usuario Básico.
 */
const displayUserProfile = (data) => {
  document.querySelector("#profile-view-user").classList.remove("hidden");
  document.querySelector("#profile-view-business")?.classList.add("hidden");

  const form = document.getElementById("user-profile-form");
  if (form) {
    form.elements["nombre"].value = data.nombre || "";
    form.elements["correo"].value = data.correo || "";
    form.elements["telefono"].value = data.telefono || "";
  }
  localStorage.setItem("user_current_name", data.nombre);
};

/**
 * Muestra la vista y rellena los campos del Perfil de Negocio Certificado.
 */
const displayBusinessProfile = (data) => {
  document.querySelector("#profile-view-business").classList.remove("hidden");
  document.querySelector("#profile-view-user")?.classList.add("hidden");

  const form = document.getElementById("business-profile-form");
  if (form) {
    form.elements["nombre_negocio_publico"].value =
      data.nombre_negocio_publico || "";
    form.elements["correo"].value = data.correo || "";
    form.elements["telefono"].value = data.telefono || "";

    form.elements["provincia"].value = data.provincia || "San José";
    form.elements["canton"].value = data.canton || "";
    form.elements["distrito"].value = data.distrito || "";
    form.elements["direccion_exacta"].value = data.direccion_exacta || "";
    form.elements["google_maps_link"].value = data.google_maps_link || "";
    form.elements["waze_link"].value = data.waze_link || "";
  }
  localStorage.setItem("business_current_name", data.nombre_negocio_publico);
};

// ------------------------------------------------------------------
// --- FUNCIONES DE SUBMIT DE PERFILES ---
// ------------------------------------------------------------------

/**
 * Envía la actualización del Perfil de Usuario Básico.
 */
const submitUserProfileForm = (event) => {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);
  const data = Object.fromEntries(formData.entries());

  console.log("Submitting User Profile Update (User Role)");
  console.log("Profile Data to update (JSON):", JSON.stringify(data));

  if (data.nombre !== localStorage.getItem("user_current_name")) {
    alert(
      "Cambio de Nombre Legal detectado. ¡Guardado! El cambio requiere **aprobación administrativa** para ser efectivo."
    );
  } else {
    alert("Perfil de usuario actualizado con éxito (Teléfono y Correo).");
  }

  localStorage.setItem("user_current_name", data.nombre);
};

/**
 * Envía la actualización del Perfil de Negocio Certificado.
 */
const submitBusinessProfileForm = (event) => {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);
  const data = Object.fromEntries(formData.entries());

  console.log("Submitting Business Profile Update (Business Role)");
  console.log("Business Data to update (JSON):", JSON.stringify(data));

  if (
    data.nombre_negocio_publico !==
    localStorage.getItem("business_current_name")
  ) {
    alert(
      "Cambio de Nombre del Negocio detectado. ¡Guardado! El cambio requiere **aprobación administrativa** para ser efectivo (RF-08.2)."
    );
  } else {
    alert("Perfil del negocio (Contacto/Ubicación) actualizado con éxito.");
  }

  localStorage.setItem("business_current_name", data.nombre_negocio_publico);
};

// ------------------------------------------------------------------
// --- NUEVAS FUNCIONES DE PANEL DE ADMINISTRACIÓN ---
// ------------------------------------------------------------------

/**
 * Inicializa la lógica para el Panel de Administración (Tab Switching y Acciones).
 */
function initAdminPanel() {
  const tabs = document.querySelectorAll(".admin-tab");

  if (tabs.length > 0) {
    tabs.forEach((tab) => {
      tab.addEventListener("click", handleAdminTabSwitch);
    });

    // Simular acciones de aprobación/rechazo
    const table = document.getElementById("comercios-solicitudes-table");
    if (table) {
      table.addEventListener("click", handleAdminApprovalActions);
    }

    console.log("Admin Panel initialized.");
  }
}

/**
 * Maneja el cambio de pestañas en el panel de administración.
 * Esta es una versión simplificada, ya que la lógica principal se añadió
 * directamente al HTML del admin-panel.php.
 */
function handleAdminTabSwitch(event) {
  const target = event.currentTarget.dataset.tab;
  console.log(`Admin tab switched to: ${target}`);
  // La lógica visual de la pestaña se maneja en el JS integrado en admin-panel.php
}

/**
 * Maneja las acciones de Aprobar/Rechazar comercios (Simulación de RF-09.3).
 * @param {Event} event - El evento de click en el botón.
 */
function handleAdminApprovalActions(event) {
  const button = event.target;
  if (button.tagName !== "BUTTON") return;

  const row = button.closest("tr");
  const businessName = row ? row.cells[1].textContent : "Negocio Desconocido";
  const action = button.textContent.trim();

  if (confirm(`¿Está seguro de ${action} a ${businessName}?`)) {
    if (action.includes("Aprobar")) {
      console.log(`ADMIN: ${businessName} APROBADO.`);
      alert(
        `Negocio ${businessName} APROBADO. Se enviará notificación al correo.`
      );
      // Simular cambio de estado visual (RF-02.3)
      row.remove();
    } else if (action.includes("Rechazar")) {
      const justification = prompt("Ingrese justificación para el rechazo:");
      if (justification) {
        console.log(
          `ADMIN: ${businessName} RECHAZADO. Justificación: ${justification}`
        );
        alert(
          `Negocio ${businessName} RECHAZADO. Se enviará justificación al correo.`
        );
        row.remove();
      }
    } else if (action.includes("Ver Documentos")) {
      alert(`ADMIN: Mostrando documentos de ${businessName}.`);
    }
  }
}

// Sistema de reservas (basado en el contexto previo)
function initReservas() {
  const form = document.querySelector("#reservation-form");
  if (form) {
    form.addEventListener("submit", hacerReserva);
    console.log("Form de reservas inicializado");
  }

  const filtro = document.querySelector("#status-filter");
  if (filtro) {
    filtro.addEventListener("change", filtrarPorEstado);
  }
}

function hacerReserva(e) {
  e.preventDefault();
  let form = e.target;

  // Lógica básica de validación y simulación de envío
  let todoBien = true;
  form.querySelectorAll("[required]").forEach((campo) => {
    if (!campo.value.trim()) {
      campo.classList.add("border-red-500");
      todoBien = false;
    }
  });

  if (!todoBien) {
    alert("Hay campos incompletos");
    return;
  }

  let datos = new FormData(form);
  let reserva = Object.fromEntries(datos.entries());

  // Simulación de validación de cupón
  // (Se asume la existencia de funciones validarCupon y aplicarDescuento si se usan cupones en make-reservation.php)

  console.log("Datos de reserva:", reserva);
  alert(
    "¡Reserva recibida! Te contactaremos pronto por correo para la confirmación y pago."
  );
  form.reset();
}

function filtrarPorEstado() {
  let filtro = document.querySelector("#status-filter");
  if (!filtro) return;
  let estado = filtro.value;
  let filas = document.querySelectorAll("#reservations-table tr[data-status]");

  filas.forEach((fila) => {
    fila.style.display =
      estado === "all" || fila.dataset.status === estado ? "" : "none";
  });
}