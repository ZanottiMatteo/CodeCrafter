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
          <i class="fas fa-user-circle fa-2x"></i>
          <span class="user-name"><?= htmlspecialchars($nomeUtente) ?></span>
          <form method="post" action="logout.php" style="display:inline;">
            <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</button>
          </form>
        </div>
      <?php else: ?>
        <a href="login.php" class="btn-login"><i class="fas fa-user"></i> Log-in</a>
        <a href="login.php#signup" class="btn-register">Sign-Up</a>
      <?php endif; ?>
    </div>
  </div>
</header>
