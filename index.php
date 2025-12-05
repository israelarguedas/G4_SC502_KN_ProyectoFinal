<?php include 'header.php' ?>

    <div
      class="sm:w-[95%] min-h-[60vh] mx-auto rounded-b-md pt-8 sm:pt-0 grid grid-cols-1 sm:grid-cols-[70%_30%] gap-4 bg-gray-100"
    >
      <section
        class="h-full py-4 mx-auto w-full px-6 flex flex-col justify-center gap-4"
      >
        <h1 class="text-4xl">
          Explorá Costa Rica
          <span class="text-green-600">autenticamente</span>.
        </h1>
        <h2 class="text-lg">Sin sorpresas, sin sobreprecio.</h2>
        <div>
          <div class="flex justify-between gap-4 mt-6">
            <div
              class="cursor-pointer text-center p-4 shadow-sm rounded-lg border w-4/12 border-gray-200 text-gray-600 hover:bg-gray-600 ease-in duration-200 hover:text-white"
            >
              <i class="fa-solid fa-house fa-lg"></i>
              <h2 class="text-sm mt-1">Hospedaje</h2>
            </div>
            <div
              class="cursor-pointer text-center p-4 shadow-sm rounded-lg border w-4/12 border-gray-200 text-gray-600 hover:bg-gray-600 ease-in duration-200 hover:text-white"
            >
              <i class="fa-solid fa-volcano fa-lg"></i>
              <h2 class="text-sm mt-1">Tours</h2>
            </div>
            <div
              class="cursor-pointer text-center p-4 shadow-sm rounded-lg border w-4/12 border-gray-200 text-gray-600 hover:bg-gray-600 ease-in duration-200 hover:text-white"
            >
              <i class="fa-solid fa-utensils fa-lg"></i>
              <h2 class="text-sm mt-1">Gastronomía</h2>
            </div>
          </div>
        </div>
        <div>
          <div class="flex items-center gap-4 search-home-page">
            <select
              name="provincias"
              id="provinciasSelect"
              class="shadow-md border border-gray-300 rounded-lg p-3 w-4/12 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            ></select>
            <select
              name="cantones"
              id="cantonesSelect"
              class="shadow-md border border-gray-300 rounded-lg p-3 w-4/12 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            ></select>
            <select
              name="distritos"
              id="distritosSelect"
              class="shadow-md border border-gray-300 rounded-lg p-3 w-4/12 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            ></select>
          </div>
        </div>
        <div class="mt-4 flex flex-col items-center w-full">
          <button
            class="shadow-md border border-gray-300 rounded-lg p-3 w-full bg-white hover:bg-gray-50 flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            aria-label="Usar ubicación actual"
          >
            <i class="fa-solid fa-location-crosshairs"></i>
            <span>Usar mi ubicación</span>
          </button>
          <button
            class="w-full mt-4 bg-teal-600 text-white py-3 rounded-lg font-semibold hover:bg-teal-700 transition shadow-md"
          >
            <i class="fa-solid fa-magnifying-glass mr-2"></i>
            Buscar
          </button>
        </div>
      </section>
      <section class="hidden sm:block overflow-hidden">
        <div class="grid grid-cols-2 gap-6 items-center">
          <div class="grid gap-6 mt-[-64px]">
            <div class="overflow-hidden rounded-3xl sm:h-40 md:h-52">
              <img
                src="assets/images/monteverde.jpg"
                alt="Imagen 1"
                class="w-full h-full object-cover object-top"
              />
            </div>
            <div class="overflow-hidden rounded-3xl sm:h-40 md:h-52">
              <img
                src="assets/images/irazu.jpg"
                alt="Imagen 2"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="overflow-hidden rounded-3xl sm:h-40 md:h-52">
              <img
                src="assets/images/santa-tere.jpg"
                alt="Imagen 3"
                class="w-full h-full object-cover"
              />
            </div>
          </div>
          <div class="grid gap-6 mb-[-64px]">
            <div class="overflow-hidden rounded-3xl sm:h-40 md:h-52">
              <img
                src="assets/images/rio-celeste.jpg"
                alt="Imagen 4"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="overflow-hidden rounded-3xl sm:h-40 md:h-52">
              <img
                src="assets/images/kayak.jpg"
                alt="Imagen 4"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="overflow-hidden rounded-3xl sm:h-40 md:h-52">
              <img
                src="assets/images/puerto-viejo.jpg"
                alt="Imagen 4"
                class="w-full h-full object-cover"
              />
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- Sección de Comercios Destacados -->
    <section class="w-[95%] mx-auto mt-12 mb-8">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-gray-800">
          Comercios <span class="text-teal-600">Destacados</span>
        </h2>
        <a
          href="#"
          class="text-teal-600 hover:text-teal-700 font-semibold flex items-center gap-2"
        >
          Ver todos
          <i class="fa-solid fa-arrow-right"></i>
        </a>
      </div>

      <!-- Filtros de categoría -->
      <div class="flex gap-3 mb-6 overflow-x-auto pb-2">
        <button
          class="filter-btn active px-4 py-2 rounded-full bg-teal-600 text-white font-semibold whitespace-nowrap hover:bg-teal-700 transition"
          data-filter="all"
        >
          <i class="fa-solid fa-grip"></i> Todos
        </button>
        <button
          class="filter-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 font-semibold whitespace-nowrap hover:bg-gray-300 transition"
          data-filter="hotel"
        >
          <i class="fa-solid fa-hotel"></i> Hoteles
        </button>
        <button
          class="filter-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 font-semibold whitespace-nowrap hover:bg-gray-300 transition"
          data-filter="tour"
        >
          <i class="fa-solid fa-volcano"></i> Tours
        </button>
        <button
          class="filter-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 font-semibold whitespace-nowrap hover:bg-gray-300 transition"
          data-filter="restaurante"
        >
          <i class="fa-solid fa-utensils"></i> Restaurantes
        </button>
        <button
          class="filter-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 font-semibold whitespace-nowrap hover:bg-gray-300 transition"
          data-filter="tienda"
        >
          <i class="fa-solid fa-store"></i> Tiendas
        </button>
        <button
          class="filter-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 font-semibold whitespace-nowrap hover:bg-gray-300 transition"
          data-filter="otros"
        >
          <i class="fa-solid fa-ellipsis"></i> Otros
        </button>
      </div>

      <!-- Carrusel de comercios -->
      <div class="relative">
        <!-- Botón anterior -->
        <button
          id="prevBtn"
          class="hidden sm:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 bg-white shadow-lg rounded-full w-12 h-12 items-center justify-center text-gray-700 hover:bg-gray-50 hover:text-teal-600 transition"
        >
          <i class="fa-solid fa-chevron-left"></i>
        </button>

        <!-- Contenedor del carrusel -->
        <div
          id="comerciosCarousel"
          class="flex gap-6 overflow-x-auto pb-4 snap-x snap-mandatory scrollbar-hide scroll-smooth"
        >
          <!-- Card 1: Hotel -->
          <div
            class="commerce-card min-w-[280px] bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition snap-start"
            data-category="hotel"
          >
            <div class="h-48 bg-gray-200 overflow-hidden relative">
              <img
                src="assets/images/monteverde.jpg"
                alt="Hotel Vista Monteverde"
                class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
              />
              <span
                class="absolute top-3 left-3 bg-blue-500 text-white text-xs font-semibold px-3 py-1 rounded-full"
              >
                <i class="fa-solid fa-hotel"></i> Hotel
              </span>
            </div>
            <div class="p-4">
              <h3 class="font-bold text-lg text-gray-800">
                Hotel Vista Monteverde
              </h3>
              <p class="text-gray-600 text-sm mt-1 flex items-center gap-1">
                <i class="fa-solid fa-location-dot text-teal-500"></i>
                Monteverde, Puntarenas
              </p>
              <div class="flex items-center mt-2">
                <span class="text-yellow-400">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-regular fa-star"></i>
                </span>
                <span class="text-gray-500 text-sm ml-2">(128)</span>
              </div>
              <div class="flex items-center justify-between mt-4">
                <p class="text-teal-600 font-bold text-lg">
                  ₡45,000
                  <span class="text-sm text-gray-500 font-normal">/noche</span>
                </p>
                <button
                  class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-semibold"
                >
                  Ver más
                </button>
              </div>
            </div>
          </div>

          <!-- Card 2: Tour -->
          <div
            class="commerce-card min-w-[280px] bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition snap-start"
            data-category="tour"
          >
            <div class="h-48 bg-gray-200 overflow-hidden relative">
              <img
                src="assets/images/rio-celeste.jpg"
                alt="Tour Río Celeste"
                class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
              />
              <span
                class="absolute top-3 left-3 bg-green-500 text-white text-xs font-semibold px-3 py-1 rounded-full"
              >
                <i class="fa-solid fa-volcano"></i> Tour
              </span>
            </div>
            <div class="p-4">
              <h3 class="font-bold text-lg text-gray-800">Tour Río Celeste</h3>
              <p class="text-gray-600 text-sm mt-1 flex items-center gap-1">
                <i class="fa-solid fa-location-dot text-teal-500"></i>
                Guatuso, Alajuela
              </p>
              <div class="flex items-center mt-2">
                <span class="text-yellow-400">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                </span>
                <span class="text-gray-500 text-sm ml-2">(245)</span>
              </div>
              <div class="flex items-center justify-between mt-4">
                <p class="text-teal-600 font-bold text-lg">
                  ₡35,000
                  <span class="text-sm text-gray-500 font-normal"
                    >/persona</span
                  >
                </p>
                <button
                  class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-semibold"
                >
                  Ver más
                </button>
              </div>
            </div>
          </div>

          <!-- Card 3: Restaurante -->
          <div
            class="commerce-card min-w-[280px] bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition snap-start"
            data-category="restaurante"
          >
            <div class="h-48 bg-gray-200 overflow-hidden relative">
              <img
                src="assets/images/santa-tere.jpg"
                alt="Soda La Tica"
                class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
              />
              <span
                class="absolute top-3 left-3 bg-orange-500 text-white text-xs font-semibold px-3 py-1 rounded-full"
              >
                <i class="fa-solid fa-utensils"></i> Restaurante
              </span>
            </div>
            <div class="p-4">
              <h3 class="font-bold text-lg text-gray-800">Soda La Tica</h3>
              <p class="text-gray-600 text-sm mt-1 flex items-center gap-1">
                <i class="fa-solid fa-location-dot text-teal-500"></i>
                Santa Teresa, Puntarenas
              </p>
              <div class="flex items-center mt-2">
                <span class="text-yellow-400">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star-half-stroke"></i>
                </span>
                <span class="text-gray-500 text-sm ml-2">(89)</span>
              </div>
              <div class="flex items-center justify-between mt-4">
                <p class="text-teal-600 font-bold text-lg">
                  ₡₡
                  <span class="text-sm text-gray-500 font-normal"
                    >Precio medio</span
                  >
                </p>
                <button
                  class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-semibold"
                >
                  Ver más
                </button>
              </div>
            </div>
          </div>

          <!-- Card 4: Tienda -->
          <div
            class="commerce-card min-w-[280px] bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition snap-start"
            data-category="tienda"
          >
            <div class="h-48 bg-gray-200 overflow-hidden relative">
              <img
                src="assets/images/puerto-viejo.jpg"
                alt="Souvenirs Caribeños"
                class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
              />
              <span
                class="absolute top-3 left-3 bg-purple-500 text-white text-xs font-semibold px-3 py-1 rounded-full"
              >
                <i class="fa-solid fa-store"></i> Tienda
              </span>
            </div>
            <div class="p-4">
              <h3 class="font-bold text-lg text-gray-800">
                Souvenirs Caribeños
              </h3>
              <p class="text-gray-600 text-sm mt-1 flex items-center gap-1">
                <i class="fa-solid fa-location-dot text-teal-500"></i>
                Puerto Viejo, Limón
              </p>
              <div class="flex items-center mt-2">
                <span class="text-yellow-400">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-regular fa-star"></i>
                </span>
                <span class="text-gray-500 text-sm ml-2">(56)</span>
              </div>
              <div class="flex items-center justify-between mt-4">
                <p
                  class="text-teal-600 font-bold text-sm flex items-center gap-2"
                >
                  <i class="fa-solid fa-clock"></i> Abierto ahora
                </p>
                <button
                  class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-semibold"
                >
                  Ver más
                </button>
              </div>
            </div>
          </div>

          <!-- Card 5: Hotel de paso -->
          <div
            class="commerce-card min-w-[280px] bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition snap-start"
            data-category="hotel"
          >
            <div class="h-48 bg-gray-200 overflow-hidden relative">
              <img
                src="assets/images/kayak.jpg"
                alt="Cabinas Playa Hermosa"
                class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
              />
              <span
                class="absolute top-3 left-3 bg-indigo-500 text-white text-xs font-semibold px-3 py-1 rounded-full"
              >
                <i class="fa-solid fa-bed"></i> Estadía
              </span>
            </div>
            <div class="p-4">
              <h3 class="font-bold text-lg text-gray-800">
                Cabinas Playa Hermosa
              </h3>
              <p class="text-gray-600 text-sm mt-1 flex items-center gap-1">
                <i class="fa-solid fa-location-dot text-teal-500"></i>
                Jacó, Puntarenas
              </p>
              <div class="flex items-center mt-2">
                <span class="text-yellow-400">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-regular fa-star"></i>
                </span>
                <span class="text-gray-500 text-sm ml-2">(92)</span>
              </div>
              <div class="flex items-center justify-between mt-4">
                <p class="text-teal-600 font-bold text-lg">
                  ₡25,000
                  <span class="text-sm text-gray-500 font-normal">/noche</span>
                </p>
                <button
                  class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-semibold"
                >
                  Ver más
                </button>
              </div>
            </div>
          </div>

          <!-- Card 6: Otro -->
          <div
            class="commerce-card min-w-[280px] bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition snap-start"
            data-category="otros"
          >
            <div class="h-48 bg-gray-200 overflow-hidden relative">
              <img
                src="assets/images/irazu.jpg"
                alt="Lavandería Express"
                class="w-full h-full object-cover hover:scale-110 transition-transform duration-300"
              />
              <span
                class="absolute top-3 left-3 bg-gray-600 text-white text-xs font-semibold px-3 py-1 rounded-full"
              >
                <i class="fa-solid fa-spray-can-sparkles"></i> Limpieza
              </span>
            </div>
            <div class="p-4">
              <h3 class="font-bold text-lg text-gray-800">
                Lavandería Express
              </h3>
              <p class="text-gray-600 text-sm mt-1 flex items-center gap-1">
                <i class="fa-solid fa-location-dot text-teal-500"></i>
                Cartago, Cartago
              </p>
              <div class="flex items-center mt-2">
                <span class="text-yellow-400">
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i>
                </span>
                <span class="text-gray-500 text-sm ml-2">(142)</span>
              </div>
              <div class="flex items-center justify-between mt-4">
                <p
                  class="text-teal-600 font-bold text-sm flex items-center gap-2"
                >
                  <i class="fa-solid fa-check-circle"></i> Servicio rápido
                </p>
                <button
                  class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition text-sm font-semibold"
                >
                  Ver más
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Botón siguiente -->
        <button
          id="nextBtn"
          class="hidden sm:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 bg-white shadow-lg rounded-full w-12 h-12 items-center justify-center text-gray-700 hover:bg-gray-50 hover:text-teal-600 transition"
        >
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div>
    </section>

    <style>
      /* Ocultar scrollbar pero mantener funcionalidad */
      .scrollbar-hide::-webkit-scrollbar {
        display: none;
      }
      .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
      }
    </style>

    <script>
      // Funcionalidad del carrusel
      const carousel = document.getElementById("comerciosCarousel");
      const prevBtn = document.getElementById("prevBtn");
      const nextBtn = document.getElementById("nextBtn");

      prevBtn.addEventListener("click", () => {
        carousel.scrollBy({
          left: -300,
          behavior: "smooth",
        });
      });

      nextBtn.addEventListener("click", () => {
        carousel.scrollBy({
          left: 300,
          behavior: "smooth",
        });
      });

      // Funcionalidad de filtros
      const filterButtons = document.querySelectorAll(".filter-btn");
      const commerceCards = document.querySelectorAll(".commerce-card");

      filterButtons.forEach((button) => {
        button.addEventListener("click", () => {
          const filter = button.dataset.filter;

          // Actualizar estilos de botones
          filterButtons.forEach((btn) => {
            btn.classList.remove("bg-teal-600", "text-white", "active");
            btn.classList.add("bg-gray-200", "text-gray-700");
          });
          button.classList.remove("bg-gray-200", "text-gray-700");
          button.classList.add("bg-teal-600", "text-white", "active");

          // Filtrar tarjetas
          commerceCards.forEach((card) => {
            if (filter === "all" || card.dataset.category === filter) {
              card.style.display = "block";
            } else {
              card.style.display = "none";
            }
          });
        });
      });
    </script>

<?php include 'footer.php' ?>
