<?php
require_once 'init.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>TicoTrips</title>
    <link rel="stylesheet" href="assets/css/index.css" />
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
          href="reservations.php"
          class="flex-1 text-center text-sm text-gray-700"
          >Reservar</a
        >
        <a
          href="promotions.php"
          class="flex-1 text-center text-teal-600 font-semibold"
          >Cupones</a
        >
        
        <?php if (isset($_SESSION['user_id']) && is_comercio()): ?>
        <a
          href="business_application.php"
          class="flex-1 text-center text-sm text-gray-700"
          >Registrar Negocio</a
        >
        <?php endif; ?>
        <a
          href="userProfile.php"
          class="flex-1 flex items-center justify-center"
        >
          <i class="fa-solid fa-circle-user fa-xl"></i>
        </a>
      </div>

      <div class="hidden sm:flex items-center justify-center h-16">
        <div class="flex items-center justify-between w-full">
          <div
            class="flex-1 flex space-x-6 text-gray-700 text-sm font-semibold"
          >
            <a href="reservations.php" class="hover:text-teal-600"
              >Reservaciones</a
            >
            <a href="promotions.php" class="hover:text-teal-600"
              >Cupones B2B</a
            >
            <?php if (isset($_SESSION['user_id']) && is_comercio()): ?>
            <a href="business-application.php" class="hover:text-teal-600">
              Mi Negocio
            </a>
            <?php endif; ?>
            </div>

          <a href="index.php" class="logo-text [word-spacing:0.35rem] tracking-wide text-2xl font-bold text-teal-600">
            TicoTrips
          </a>

          <ul class="flex-1 flex justify-end items-center space-x-4">
            
            <?php if (isset($_SESSION['user_id']) && is_admin()): ?>
            <li>
              <a
                href="admin-panel.php"
                class="text-red-500 hover:text-red-700 text-sm font-semibold"
              >
                <i class="fa-solid fa-user-gear"></i> Admin
              </a>
            </li>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['user_id'])): ?>
              <li>
                <a
                  href="userProfile.php"
                  aria-label="User profile"
                  class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center hover:bg-gray-200 focus:outline-none"
                >
                  <i class="fa-solid fa-circle-user fa-xl"></i>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link text-sm font-semibold text-gray-700 hover:text-teal-600" href="logout.php">Log Out</a>
              </li>

            <?php else: ?>
              <li class="nav-item">
                <a class="nav-link text-sm font-semibold text-gray-700 hover:text-teal-600" href="login.php">Log In</a>
              </li>
            <?php endif; ?>

          </ul>
        </div>
      </div>
    </nav>
    
    </body>
</html>