// Helper para crear una opción
const createOption = (value, text) => {
  const option = document.createElement("option");
  option.value = value;
  option.textContent = text;
  return option;
};

// Helper para limpiar un select
const clearSelect = (selectElement) => {
  while (selectElement.firstChild) {
    selectElement.removeChild(selectElement.firstChild);
  }
};

// Helper para poblar un select
const populateSelect = (selectElement, options, placeholder) => {
  clearSelect(selectElement);

  // Agregar placeholder
  selectElement.appendChild(createOption("", placeholder));

  // Agregar cada opción
  options.forEach((option) => {
    selectElement.appendChild(createOption(option, option));
  });
};

// Helper para resetear un select
const resetSelect = (selectElement, placeholder) => {
  clearSelect(selectElement);
  selectElement.appendChild(createOption("", placeholder));
  selectElement.disabled = true;
};

export { createOption, clearSelect, populateSelect, resetSelect };
