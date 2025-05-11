<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$nomeUtente = $_SESSION['nome'] ?? '';
?>

<header class="main-header">
  <div class="header-content">
    <h1 class="logo">Cine<span>Craft</span></h1>
    <div class="user-actions">
      <?php if ($isLoggedIn): ?>
        <div class="user-area">
          <div class="user-menu">
            <img src="https://cdn-icons-png.flaticon.com/128/1077/1077063.png" alt="User" class="user-icon">
            <div class="menu-dropdown">
              <a href="carrello.php">
                <img src="https://cdn-icons-png.flaticon.com/128/833/833314.png" alt="Icona Biglietti"
                  class="menu-icon">
                I miei acquisti
              </a>
            </div>
          </div>
          <span class="user-name"><?= htmlspecialchars($nomeUtente) ?></span>
          <form method="post" action="logout.php">
            <button type="submit" class="logout-btn"><img src="https://cdn-icons-png.flaticon.com/128/1828/1828427.png"
                alt="Logout" class="logout-icon"> Logout</button>
          </form>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn-login"><i class="fas fa-user"></i> Log-in</a>
        <a href="login.php#signup" class="btn-register">Sign-Up</a>
      <?php endif; ?>
    </div>
  </div>
</header>