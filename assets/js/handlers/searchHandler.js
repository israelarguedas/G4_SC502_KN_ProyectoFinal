import { GeoService } from "../services/GeoService.js";
import {
  createOption,
  clearSelect,
  populateSelect,
  resetSelect,
} from "../common/domUtils.js";

const provinciasSelect = document.getElementById("provinciasSelect");
const cantonesSelect = document.getElementById("cantonesSelect");
const distritosSelect = document.getElementById("distritosSelect");

const geoService = new GeoService();

const setupProvincias = async () => {
  try {
    const provincias = await geoService.getProvincias();
    populateSelect(provinciasSelect, provincias, "Seleccione una provincia");
  } catch (error) {
    console.error("Error al cargar provincias:", error);
    clearSelect(provinciasSelect);
    provinciasSelect.appendChild(
      createOption("", "Error al cargar provincias")
    );
  }
};

const handleProvinciaChange = async () => {
  const selectedProvincia = provinciasSelect.value;

  resetSelect(cantonesSelect, "Seleccione un cantón");
  resetSelect(distritosSelect, "Seleccione un distrito");

  if (!selectedProvincia) return;

  try {
    const cantones = await geoService.getCantonesByProvincia(selectedProvincia);
    populateSelect(cantonesSelect, cantones, "Seleccione un cantón");
    cantonesSelect.disabled = false;
  } catch (error) {
    console.error("Error al cargar cantones:", error);
  }
};

const handleCantonChange = async () => {
  const selectedCanton = cantonesSelect.value;

  resetSelect(distritosSelect, "Seleccione un distrito");

  if (!selectedCanton) return;

  try {
    const distritos = await geoService.getDistritosByCanton(selectedCanton);
    populateSelect(distritosSelect, distritos, "Seleccione un distrito");
    distritosSelect.disabled = false;
  } catch (error) {
    console.error("Error al cargar distritos:", error);
  }
};

export const handleSearch = () => {
  setupProvincias();

  provinciasSelect.addEventListener("change", handleProvinciaChange);
  cantonesSelect.addEventListener("change", handleCantonChange);
};
