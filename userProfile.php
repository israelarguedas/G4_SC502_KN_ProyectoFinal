<?php include 'header.php' ?>

<body class="bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 rounded-xl shadow-2xl">
            <h1 class="text-3xl font-bold text-center text-teal-600 mb-8">Mi Perfil</h1>

            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <label for="user_role_select" class="block text-sm font-medium text-blue-800 mb-2">Simular Rol de Usuario:</label>
                <select id="user_role_select" 
                    class="block w-full rounded-md border-blue-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2 border">
                    <option value="user">Usuario Básico</option>
                    <option value="business">Negocio Certificado</option>
                </select>
            </div>
            
            <div id="profile-view-user" class="profile-view">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Información de la Cuenta</h2>
                
                <form id="user-profile-form" action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="border-b border-gray-200 pb-5">
                        <h3 class="text-xl font-medium text-gray-700 mb-4">Foto de Perfil</h3>
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <img id="preview-foto" src="https://via.placeholder.com/100" alt="Foto de perfil" 
                                    class="h-24 w-24 rounded-full object-cover">
                            </div>
                            <div class="flex-1">
                                <label for="foto-perfil" class="block text-sm font-medium text-gray-700 mb-2">
                                    Cambiar Foto
                                </label>
                                <input type="file" id="foto-perfil" name="foto_perfil" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF o WebP. Máximo 5MB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-b border-gray-200 pb-5">
                        <div class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
                            
                            <div class="sm:col-span-2">
                                <label for="user-profile-nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                                <input type="text" id="user-profile-nombre" name="nombre_completo" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                                <p class="mt-1 text-xs text-yellow-600">Cambios en el nombre requieren aprobacion administrativa.</p>
                            </div>

                            <div>
                                <label for="user-profile-correo" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                <input type="email" id="user-profile-correo" name="email" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            </div>

                            <div>
                                <label for="user-profile-telefono" class="block text-sm font-medium text-gray-700">Número de Teléfono</label>
                                <input type="tel" id="user-profile-telefono" name="telefono"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            </div>


                            <!--<div> 
                                <label for="user-profile-genero" class="block text-sm font-medium text-gray-700">Género</label>
                                <select id="user-profile-genero" name="genero"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                                    <option value="">Prefiero no especificar</option>
                                    <option value="M">Masculino</option>
                                    <option value="F">Femenino</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div> //No vamos a usar genero para el perfil -->

                            <div>
                                <label for="user-profile-fecha-nacimiento" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                                <input type="date" id="user-profile-fecha-nacimiento" name="fecha_nacimiento"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="user-profile-biografia" class="block text-sm font-medium text-gray-700">Biografía (Opcional)</label>
                                <textarea id="user-profile-biografia" name="biografia" rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border"
                                    placeholder="Cuéntanos un poco sobre ti..."></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <button type="submit"
                            class="w-full py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-150">
                            Guardar Cambios de Perfil
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Mi Historial</h2>
                    <a href="#" class="text-teal-600 hover:text-teal-500 block mb-2">Ver Historial de Reservaciones</a>
                    <a href="#" class="text-teal-600 hover:text-teal-500 block">Ver Mis Comercios Favoritos</a>
                </div>
            </div>

            <div id="profile-view-business" class="profile-view hidden">
                <h2 class="text-2xl font-semibold text-gray-900 mb-4">Gestión del Negocio <span class="bg-green-100 text-green-800 text-sm font-medium mr-2 px-2.5 py-0.5 rounded-full">CERTIFICADO</span></h2>
                
                <form id="business-profile-form" action="#" method="POST" class="space-y-6">
                    <div class="border-b border-gray-200 pb-5">
                        <h3 class="text-xl font-medium text-gray-700 mb-4">Datos de Contacto Públicos</h3>
                        
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-md mb-6">
                            Cualquier cambio en el Nombre Público requiere Aprobación Administrativa. 
                        </div>

                        <div class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
                            <div class="sm:col-span-2">
                                <label for="bus-profile-nombre-negocio" class="block text-sm font-medium text-gray-700">Nombre Público del Negocio</label>
                                <input type="text" id="bus-profile-nombre-negocio" name="nombre_negocio_publico" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            </div>
                            
                            <div>
                                <label for="bus-profile-correo" class="block text-sm font-medium text-gray-700">Correo de Contacto</label>
                                <input type="email" id="bus-profile-correo" name="correo" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            </div>

                            <div>
                                <label for="bus-profile-telefono" class="block text-sm font-medium text-gray-700">Teléfono de Contacto</label>
                                <input type="tel" id="bus-profile-telefono" name="telefono" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-b border-gray-200 pb-5">
                        <h3 class="text-xl font-medium text-gray-700 mb-4">Ubicación Geográfica</h3>
                        <p class="mt-1 text-sm text-gray-500">Actualice la ubicación precisa para la búsqueda geolocalizada.</p>

                        <div class="mt-4 grid grid-cols-1 gap-y-4 sm:grid-cols-3 sm:gap-x-6">
                            <div>
                                <label for="bus-profile-provincia" class="block text-sm font-medium text-gray-700">Provincia</label>
                                <select id="bus-profile-provincia" name="provincia" required
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
                                <label for="bus-profile-canton" class="block text-sm font-medium text-gray-700">Cantón</label>
                                <input type="text" id="bus-profile-canton" name="canton" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" placeholder="Ej: Montes de Oca">
                            </div>
                            <div>
                                <label for="bus-profile-distrito" class="block text-sm font-medium text-gray-700">Distrito</label>
                                <input type="text" id="bus-profile-distrito" name="distrito" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" placeholder="Ej: San Pedro">
                            </div>
                            
                            <div class="sm:col-span-3">
                                <label for="bus-profile-direccion" class="block text-sm font-medium text-gray-700">Dirección Exacta</label>
                                <textarea id="bus-profile-direccion" name="direccion_exacta" rows="3" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border" placeholder="50 metros al norte del Banco Nacional..."></textarea>
                            </div>

                            <div class="sm:col-span-3 grid grid-cols-1 gap-y-4 sm:grid-cols-2 sm:gap-x-6">
                                <div>
                                    <label for="bus-profile-google-maps" class="block text-sm font-medium text-gray-700">Link de Google Maps (Opcional)</label>
                                    <input type="url" id="bus-profile-google-maps" name="google_maps_link"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                                </div>
                                <div>
                                    <label for="bus-profile-waze" class="block text-sm font-medium text-gray-700">Link de Waze (Opcional)</label>
                                    <input type="url" id="bus-profile-waze" name="waze_link"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 p-2 border">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-5 flex justify-between space-x-4">
                        <a href="business-application.php" class="flex-1 py-3 px-4 text-center rounded-md shadow-sm text-base font-medium text-teal-600 border border-teal-600 hover:bg-teal-50 transition duration-150">
                            Actualizar Documentación Legal
                        </a>
                        <button type="submit"
                            class="flex-1 py-3 px-4 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150">
                            Guardar Cambios Públicos
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Gestión Avanzada</h2>
                    <a href="#" class="text-teal-600 hover:text-teal-500 block mb-2">Gestionar Cupones B2B y Ofertas</a>
                    <a href="#" class="text-teal-600 hover:text-teal-500 block">Gestionar Calendario y Disponibilidad</a>
                </div>
            </div>

        </div>
    </div>

<?php include 'footer.php' ?>