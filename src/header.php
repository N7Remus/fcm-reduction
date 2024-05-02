<header class="p-3 border-bottom">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
      <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
        <svg width="76" height="66">
          <image xlink:href="/duck.svg" src="" width="76" height="66" />
        </svg>
      </a>
      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
        <!-- <li><a href="python-server" class="nav-link px-2 link-secondary">Áttekintő nézet</a></li> -->
        <li>
          <div class="dropdown">
            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle nav-link text-primary" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
              FCM
            </a>
            <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
              <li><a href="/models" class="nav-link px-2 link-dark">Modelek</a></li>
              <li><a href="/ui" class="nav-link px-2 link-dark">Import</a></li>
              <li><a href="/new" class="nav-link px-2 link-dark">Új FCM készítése</a></li>
            </ul>
          </div>
        </li>
        <li><a href="/simulations" class="nav-link px-2 link-dark">Szimuláció(k)</a></li>
        <li><a href="/reductions" class="nav-link px-2 link-dark">Redukció(k)</a></li>

        <li>
          <div class="dropdown text-end ">
            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle nav-link text-danger" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
              Adminisztáció
            </a>
            <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
              <li><a class="dropdown-item" href="/users">Felhasználók</a></li>
            </ul>
          </div>
        </li>
      </ul>
      <?php if (isset($_SESSION)) {
      ?>
        <div class="text-end me-2">
          <a href="/logout" class="text-decoration-none">Kijelentkezés</a>
        </div>
      <?php } else { ?>
        <div class="text-end me-2">
        <a href="/login">Bejelentkezés</a>

        </div>
      <?php
      }
      ?>
    </div>
</header>