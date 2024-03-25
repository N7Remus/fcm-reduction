<header class="p-3 border-bottom">
  <div class="container">
    <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
      <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-dark text-decoration-none">
        <svg width="76" height="66">
          <image xlink:href="duck.svg" src="" width="76" height="66" />

        </svg>

      </a>

      <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
        
        <li><a href="table2" class="nav-link px-2 link-dark">Sigma.js UI (demo)</a></li>
        <li><a href="table3" class="nav-link px-2 link-dark">Sigma.js UI (demo nagy mátrix)</a></li>
        <li><a href="table4" class="nav-link px-2 link-dark">Sigma.js UI (demo szinezett)</a></li>
        <li><a href="python" class="nav-link px-2 link-dark">Python(PyScript)</a></li>
        <li><a href="python-server" class="nav-link px-2 link-secondary">Python(server)</a></li>
        <li>
          <div class="dropdown text-end ">
            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle nav-link text-danger" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
              Admin
            </a>
            <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
              <li><a class="dropdown-item" href="dashboard.php">Áttekintő nézet</a></li>
              <li><a class="dropdown-item" href="mail.php"></a></li>
              <li><a class="dropdown-item" href="table.php"></a></li>
              <li><a class="dropdown-item" href="users.php"></a></li>
              <li><a class="dropdown-item" href="items"></a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="admins.php">Rendszergazdák</a></li>
            </ul>
          </div>
        </li>
      </ul>

      <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
        <input type="search" class="form-control" placeholder="Termék keresés..." aria-label="Search">
      </form>
      <?php if ($is_logged_in) {
      ?>




        <div class="dropdown text-end">
          <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="https://github.com/mdo.png" alt="mdo" width="32" height="32" class="rounded-circle">
          </a>
          <ul class="dropdown-menu text-small" aria-labelledby="dropdownUser1">
            <li><a class="dropdown-item" href="profile">Profil</a></li>
            <li><a class="dropdown-item" href="orders">Rendelések</a></li>
            <li><a class="dropdown-item" href="kutatas">Kedvencek</a></li>
            <!-- <li><a class="dropdown-item" href="#">Beállítások</a></li>  -->
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="logout">Kijelentkezés</a></li>
          </ul>
        </div>
    </div>
  <?php } else { ?>
    <div class="text-end me-2">
      Bejelentkezés
    </div>
  <?php
      }
  ?>
  </div>
</header>