<?php
// NOTE: Process login is done here instead of on a seperate file because it's way easier

declare(strict_types=1);
session_start();
require_once(__DIR__ . '/../templates/common_tpl.php');
require_once(__DIR__ . '/../database/connection_db.php');
require_once(__DIR__ . '/../utils/function_utils.php');

// Check if user is allowed to access this page
handleUser(true, false, false, false);

$is_invalid = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $db = databaseConnection();

  // Apply XSS protection
  $username = htmlspecialchars($_POST['username'], ENT_QUOTES | ENT_HTML5, 'UTF-8');
  $password = htmlspecialchars($_POST['password'], ENT_QUOTES | ENT_HTML5, 'UTF-8');

  $query = 'SELECT * FROM User WHERE username = :username';
  $statement = $db->prepare($query);
  $statement->bindParam(':username', $username);
  if (!$statement->execute()) {
    // Handle database error
    die('Database error');
  }

  $user = $statement->fetch(PDO::FETCH_ASSOC);

  // If user was found
  if ($user) {
    if (password_verify($password, $user['password'])) {

      session_start();

      $_SESSION['user_id'] = $user['id'];

      // Client check
      if (getClientId($user['id']) != null) {
        $client_id = getClientId($user['id']);
        $_SESSION['client_id'] = $client_id;
      } else {
        $_SESSION['client_id'] = 0; // client_id is 0 for non client users
      }

      // Admin check
      if (getAdminId($user['id']) != null) {
        $admin_id = getAdminId($user['id']);
        $_SESSION['admin_id'] = $admin_id;
      } else {
        $_SESSION['admin_id'] = 0; // admin_id is 0 for non admin users
      }

      // Agent check
      if (getAgentId($user['id']) != null) {
        $agent_id = getAgentId($user['id']);
        $_SESSION['agent_id'] = $agent_id;
      } else {
        $_SESSION['agent_id'] = 0; // agent_id is 0 for non agent users
      }

      $location = getMainPage($user['id']);
      header("Location: $location");
      exit;
    }
  }

  $is_invalid = true;
}

drawHeader("loginpage");
?>

<body id="loginpage">
  <div class="container-loginpage">
    <div class="sign_in_text">Sign In</div>
    <form method="post" class="login">
      <input type="text" name="username" placeholder="Enter username">
      <input type="password" name="password" placeholder="Password">
      <div class="text-to-other-page">Don't have an account yet? <a href="/pages/registerpage.php"><span>Register
            here.</span></a></div>
      <button type="submit">Log in</button>
    </form>
    <?php if ($is_invalid) { ?>
      <div class="text_invalid_login">Invalid login! Please try again.</div>
    <?php } ?>
  </div>
</body>