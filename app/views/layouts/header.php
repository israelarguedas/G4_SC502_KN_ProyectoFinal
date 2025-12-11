<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $pageTitle ?? 'TicoTrips' ?></title>
    <link rel="stylesheet" href="app/public/css/index.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script
      src="https://kit.fontawesome.com/f62f5c1b62.js"
      crossorigin="anonymous"
    ></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body class="pb-16 sm:pb-0">
    <nav
      class="fixed bottom-0 left-0 right-0 z-50 bg-white/95 shadow-lg border border-t-slate-100 px-4 sm:static sm:mx-auto sm:w-[95%] sm:rounded-xl sm:my-6"
    >
      <div class="flex items-center justify-between h-14 sm:hidden">
        <a
          href="index.php?controller=home&action=search"
          class="flex-1 text-center text-sm text-gray-700"
          >Reservar</a
        >
        <a
          href="index.php?controller=business&action=showPromotions"
          class="flex-1 text-center text-teal-600 font-semibold"
          >Cupones</a
        >
        
        <?php if (isset($_SESSION['user_id']) && isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], [3, 4, 5, 6])): ?>
        <a
          href="index.php?controller=business&action=showApplication"
          class="flex-1 text-center text-sm text-gray-700"
          >Registrar Negocio</a
        >
        <?php endif; ?>
        <a
          href="index.php?controller=profile&action=show"
          class="flex-1 flex items-center justify-center"
        >
          <i class="fa-solid fa-circle-user fa-xl"></i>
        </a>
      </div>

      <div class="hidden sm:flex items-center justify-center h-16">
        <div class="container flex justify-around items-center">
          <a href="index.php" class="text-2xl font-bold text-gray-800"
            >TicoTrips</a
          >
          <ul class="flex space-x-6 items-center">
            <li>
              <a
                href="index.php?controller=home&action=search"
                class="hover:text-teal-600 transition duration-150"
                >Reservar</a
              >
            </li>
            <li>
              <a
                href="index.php?controller=business&action=showPromotions"
                class="hover:text-teal-600 transition duration-150"
                >Cupones</a
              >
            </li>
            <?php if (isset($_SESSION['user_id']) && isset($_SESSION['id_rol']) && in_array($_SESSION['id_rol'], [3, 4, 5, 6])): ?>
            <li>
              <a
                href="index.php?controller=business&action=showApplication"
                class="hover:text-teal-600 transition duration-150"
                >Registrar Negocio</a
              >
            </li>
            <li>
              <a
                href="index.php?controller=business&action=manageCoupons"
                class="hover:text-teal-600 transition duration-150"
                >Mis Cupones</a
              >
            </li>
            <?php endif; ?>
            <?php if (isset($_SESSION['user_id'])): ?>
              <?php if (isset($_SESSION['id_rol']) && $_SESSION['id_rol'] == 1): ?>
              <li>
                <a
                  href="index.php?controller=admin&action=index"
                  class="text-red-500 hover:text-red-700 transition duration-150 font-semibold"
                  ><i class="fa-solid fa-user-gear"></i> Admin</a
                >
              </li>
              <?php endif; ?>
              <li>
                <a
                  href="index.php?controller=profile&action=show"
                  class="hover:text-teal-600 transition duration-150"
                  >Perfil</a
                >
              </li>
              <li>
                <a
                  href="index.php?action=logout"
                  class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-150"
                  >Cerrar Sesión</a
                >
              </li>
            <?php else: ?>
              <li>
                <a
                  href="index.php?action=login"
                  class="hover:text-teal-600 transition duration-150"
                  >Iniciar Sesión</a
                >
              </li>
              <li>
                <a
                  href="index.php?action=register"
                  class="bg-teal-600 text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition duration-150"
                  >Registrarse</a
                >
              </li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>
