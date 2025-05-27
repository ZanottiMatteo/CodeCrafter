<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$isLoggedIn = isset($_SESSION['user_id']);
$nomeUtente = $_SESSION['nome'] ?? '';
$mailUtente = $_SESSION['mail'] ?? '';
?>

<header class="main-header">
  <div class="header-content">
    <h1 class="logo">Code<span>Crafters</span></h1>
    <div class="user-actions">
      <?php if ($isLoggedIn): ?>
        <div class="user-area">
          <span class="user-name"><?= htmlspecialchars($nomeUtente) ?></span>
          <form method="post" action="../login_logout/logout.php">
            <button type="submit" class="logout-btn"><img src="https://cdn-icons-png.flaticon.com/128/1828/1828427.png"
                alt="Logout" class="logout-icon"> Logout</button>
          </form>
        </div>
      <?php else: ?>
        <a href="../login_logout/login.php" class="btn-login"><i class="fas fa-user"></i> Login</a>
        <a href="../login_logout/login.php#signup" class="btn-register">SignUp</a>
      <?php endif; ?>
    </div>
  </div>
  <?php if ($isLoggedIn && !empty($mailUtente)): ?>
    <script>
      window.userMail = <?= json_encode($mailUtente); ?>;
    </script>
  <?php endif; ?>
</header>