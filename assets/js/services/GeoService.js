export class GeoService {
  async getProvincias() {
    const response = await fetch("assets/js/apiMockup/infoGeografica.json");
    const provincias = await response.json();

    if (!provincias || provincias.length === 0) {
      throw new Error("No se encontraron provincias");
    }

    return provincias.map((rec) => rec.provincia);
  }

  async getCantonesByProvincia(provinciaABuscar) {
    const provinciasValidas = await this.getProvincias();

    const provinciaMatched = provinciasValidas.find(
      (provincia) => provincia.toLowerCase() === provinciaABuscar.toLowerCase()
    );

    if (!provinciaMatched) {
      throw new Error("Provincia no válida");
    }

    const response = await fetch("assets/js/apiMockup/infoGeografica.json");
    const provincias = await response.json();

    const provinciaData = provincias.find(
      (prov) => prov.provincia.toLowerCase() === provinciaMatched.toLowerCase()
    );

    if (!provinciaData || !provinciaData.cantones) {
      throw new Error("No se encontraron cantones para la provincia dada");
    }

    return provinciaData.cantones.map((canton) => canton.canton);
  }

  async getDistritosByCanton(canton) {
    const response = await fetch("assets/js/apiMockup/infoGeografica.json");
    const provincias = await response.json();
    let distritos = [];

    for (const provincia of provincias) {
      const cantonData = provincia.cantones.find(
        (c) => c.canton.toLowerCase() === canton.toLowerCase()
      );

      if (cantonData) {
        distritos = cantonData.distritos;
        break;
      }
    }

    console.log(distritos);

    if (!distritos || distritos.length === 0) {
      throw new Error("Canton no válido");
    }
  }
}
