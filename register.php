<?php include 'header.php' ?>

<body class="bg-gray-100 min-h-screen">

    <div class="w-full max-w-lg bg-white p-8 rounded-xl shadow-2xl mx-auto sm:mt-20 my-10">

        <div class="flex border-b border-gray-200 mb-6" id="tabContainer">
            <button id="tab-user-register" data-form="user-register-form"
                class="tab-button flex-1 py-2 text-sm font-medium border-b-2 border-teal-500 text-teal-600 transition duration-150">
                Registro Usuario
            </button>
            <button id="tab-business-register" data-form="business-register-form"
                class="tab-button flex-1 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 transition duration-150">
                Registro Negocio
            </button>
        </div>
                
        <form id="user-register-form" class="auth-form" action="#" method="POST">
            <div class="space-y-4">
                <div>
                    <label for="reg-user-nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                    <input type="text" id="reg-user-nombre" name="nombre" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>
                
                <div>
                    <label for="reg-user-email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" id="reg-user-email" name="correo" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>
                <div>
                    <label for="reg-user-password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="reg-user-password" name="password" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>

                <div>
                    <label for="reg-user-telefono" class="block text-sm font-medium text-gray-700">Teléfono (Opcional)</label>
                    <input type="tel" id="reg-user-telefono" name="telefono"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>

                <div>
                    <label for="reg-user-genero" class="block text-sm font-medium text-gray-700">Género (Opcional)</label>
                    <select id="reg-user-genero" name="genero"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                        <option value="">Prefiero no especificar</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <button type="submit"
                    class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-teal-600 hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-150">
                    Registrarme
                </button>
            </div>
        </form>

        <form id="business-register-form" class="auth-form hidden" action="#" method="POST">
            <div class="space-y-4">
                <div class="p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-md">
                    El registro de negocios requiere la aprobacion administrativa. Usaremos su correo para notificarle el estado.
                </div>
                <div>
                    <label for="reg-bus-nombre" class="block text-sm font-medium text-gray-700">Nombre (Dueño/Representante)</label>
                    <input type="text" id="reg-bus-nombre" name="nombre" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>
                <div>
                    <label for="reg-bus-email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" id="reg-bus-email" name="correo" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>
                <div>
                    <label for="reg-bus-password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" id="reg-bus-password" name="password" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-teal-500 focus:ring-teal-500 sm:text-sm p-2 border">
                </div>
                <button type="submit"
                    class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150">
                    Registrar Cuenta
                </button>
            </div>
            <p class="mt-4 text-center text-sm text-gray-500">
                Será redirigido para completar el <a href="business-application.php" class="font-medium text-teal-600 hover:text-teal-500">Formulario de Aplicación</a>.
            </p>
        </form>
    </div>

<?php include 'footer.php' ?>