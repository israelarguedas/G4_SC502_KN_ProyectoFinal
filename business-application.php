<?php include 'header.php' ?>

<body class="bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 rounded-xl shadow-2xl">
            <h1 class="text-3xl font-bold text-center text-teal-600 mb-6">Solicitud de Certificación de Negocio Local</h1>
            <p class="text-center text-gray-500 mb-8">
                Complete este formulario para iniciar el proceso de validación. Esta información se usará para verificar la ciudadanía costarricense y la permanencia del negocio.
            </p>

            <form id="business-application-form" action="#" method="POST" class="space-y-6">
                
                <div class="border-b border-gray-200 pb-5">
                    <h2 class="text-xl font-semibold text-gray-900">1. Datos del Negocio</h2>
                    <p class="mt-1 text-sm text-gray-500">Información pública y de contacto del comercio.</p>

                    <div class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
                        <div>
                            <label for="nombre_negocio" class="block text-sm font-medium text-gray-700">Nombre Legal del Negocio</label>
                            <input type="text" id="nombre_negocio" name="nombre_negocio" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        </div>
                        <div>
                            <label for="tipo_negocio" class="block text-sm font-medium text-gray-700">Tipo de Negocio</label>
                            <select id="tipo_negocio" name="tipo_negocio" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                                <option value="" disabled selected>Seleccione un tipo</option>
                                <option value="hospedaje">Hospedaje</option>
                                <option value="tour">Tour / Experiencia</option>
                                <option value="gastronomia">Restaurante / Gastronomía</option>
                                <option value="artesania">Tienda de Artesanías / Souvenirs</option>
                                <option value="otros">Otros Comercios Locales</option>
                            </select>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="descripcion_negocio" class="block text-sm font-medium text-gray-700">Descripción Corta</label>
                            <textarea id="descripcion_negocio" name="descripcion_negocio" rows="3" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border"></textarea>
                        </div>
                        <div>
                            <label for="telefono_negocio" class="block text-sm font-medium text-gray-700">Teléfono de Contacto</label>
                            <input type="tel" id="telefono_negocio" name="telefono_negocio" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        </div>
                        <div>
                            <label for="correo_negocio" class="block text-sm font-medium text-gray-700">Correo Público</label>
                            <input type="email" id="correo_negocio" name="correo_negocio" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-200 pb-5">
                    <h2 class="text-xl font-semibold text-gray-900">2. Documentación de Validación y Hacienda</h2>
                    <p class="mt-1 text-sm text-gray-500">
                        Información requerida para la verificación de su negocio en Hacienda y comprobación de identidad.
                    </p>

                    <div class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-3 sm:gap-x-6">
                        <div>
                            <label for="tipo_cedula" class="block text-sm font-medium text-gray-700">Tipo de Cédula (Hacienda)</label>
                            <select id="tipo_cedula" name="tipo_cedula" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                                <option value="" disabled selected>Seleccione Tipo</option>
                                <option value="Fisica">Cédula Física (Nacional)</option>
                                <option value="Juridica">Cédula Jurídica</option>
                                <option value="Dimex">DIMEX</option>
                                <option value="Nite">NITE</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="cedula_hacienda" class="block text-sm font-medium text-gray-700">Cédula o Identificación (Hacienda)</label>
                            <input type="text" id="cedula_hacienda" name="cedula_hacienda" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" 
                                placeholder="Ej: 1-0000-0000 para Físicas">
                        </div>

                        <div class="sm:col-span-3">
                            <label for="nombre_representante_hacienda" class="block text-sm font-medium text-gray-700">Nombre del Representante Legal (en Hacienda)</label>
                            <input type="text" id="nombre_representante_hacienda" name="nombre_representante_hacienda" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        </div>

                        <div>
                            <label for="foto_cedula_frente" class="block text-sm font-medium text-gray-700">Subir Cédula (Frente)</label>
                            <input type="file" id="foto_cedula_frente" name="foto_cedula_frente" accept=".jpg,.png,.pdf" required
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        </div>

                        <div>
                            <label for="foto_cedula_reverso" class="block text-sm font-medium text-gray-700">Subir Cédula (Reverso)</label>
                            <input type="file" id="foto_cedula_reverso" name="foto_cedula_reverso" accept=".jpg,.png,.pdf" required
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                        </div>

                        <div>
                            <label for="registro_municipal" class="block text-sm font-medium text-gray-700">No. de Licencia Municipal/Permiso Sanitario</label>
                            <input type="text" id="registro_municipal" name="registro_municipal" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-200 pb-5">
                    <h2 class="text-xl font-semibold text-gray-900">3. Ubicación y Dirección</h2>
                    <p class="mt-1 text-sm text-gray-500">Datos necesarios para la búsqueda geolocalizada por provincia, cantón y distrito.</p>
                    
                    <div class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-3 sm:gap-x-6">
                        <div>
                            <label for="provincia" class="block text-sm font-medium text-gray-700">Provincia</label>
                            <select id="provincia" name="provincia" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                                <option value="" disabled selected>Seleccione</option>
                                <option value="San José">San José</option>
                                <option value="Alajuela">Alajuela</option>
                                <option value="Cartago">Cartago</option>
                                <option value="Heredia">Heredia</option>
                                <option value="Guanacaste">Guanacaste</option>
                                <option value="Puntarenas">Puntarenas</option>
                                <option value="Limón">Limón</option>
                            </select>
                        </div>
                        <div>
                            <label for="canton" class="block text-sm font-medium text-gray-700">Cantón</label>
                            <input type="text" id="canton" name="canton" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" placeholder="Ej: Montes de Oca">
                        </div>
                        <div>
                            <label for="distrito" class="block text-sm font-medium text-gray-700">Distrito</label>
                            <input type="text" id="distrito" name="distrito" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" placeholder="Ej: San Pedro">
                        </div>
                        
                        <div class="sm:col-span-3">
                            <label for="direccion_exacta" class="block text-sm font-medium text-gray-700">Dirección Exacta (Punto de Referencia)</label>
                            <textarea id="direccion_exacta" name="direccion_exacta" rows="3" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" placeholder="50 metros al norte del Banco Nacional..."></textarea>
                        </div>

                        <div class="sm:col-span-3 grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
                            <div>
                                <label for="google_maps_link" class="block text-sm font-medium text-gray-700">Link de Google Maps (Opcional)</label>
                                <input type="url" id="google_maps_link" name="google_maps_link"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            </div>
                            <div>
                                <label for="waze_link" class="block text-sm font-medium text-gray-700">Link de Waze (Opcional)</label>
                                <input type="url" id="waze_link" name="waze_link"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            </div>
                        </div>

                    </div>
                </div>


                <div class="pt-5">
                    <button type="submit"
                        class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-150">
                        Enviar Solicitud para Aprobación
                    </button>
                </div>
            </form>
        </div>
    </div>

<?php include 'footer.php' ?>