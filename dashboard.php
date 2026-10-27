<?php
    session_start();
    if (!isset($_SESSION["account_id"])) {
        header("Location: index.php");
        exit;
    }

    require __DIR__ . "/api/v1/config/conn.php";
    $stmt = $conn->prepare("SELECT * FROM roles WHERE id = ?");
    $stmt->execute([$_SESSION["account_role_id"]]);
    $role_fetch = $stmt->fetch();

    if (strtolower($role_fetch["name"]) !== "admin") {
        header("Location: index.php");
        exit;
    }

    $valid_modes = ["create_account", "accounts", "urls"];
    if (isset($_GET["m"]) && in_array($_GET["m"], $valid_modes)) {
        $mode = $_GET["m"];
    }
    else {
        $mode = "create_account";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <!--- stuff -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="favicon.ico" type="image/x-icon" />
  <link rel="stylesheet" href="styles.css">
  <!--- scripts -->
  <script src="js/popups.js" defer></script>
  <script src="js/noTitle.js" defer></script>
  <script src="js/hover.js" defer></script>
  <!--- seo -->
  <meta property="description" content="Hosting servers for everyone, and everyone.">
  <meta name="googlebot" content="notranslate">
  <!--- opengraph -->
  <meta property="og:title" content="toms3.CC Technologies™" />
  <meta property="og:type" content="website"/>
  <meta property="og:description" content="Hosting servers for everyone, and everyone.">
  <!--- main -->
  <title>url.toms3.cc</title>
</head>
<body>
  <!--                 

                       < toms3.CC Technologies™ >
                                Credits
  Famfamfam silk icons (https://github.com/legacy-icons/famfamfam-silk)

  -->
  <div class="introduction">
    <img src="resources/logo.png" alt="toms3.CC Technologies™" draggable="false">
    <h1 id="toms3-title">url.toms3.cc</h1>
  </div>
  <div class="windows no-gap" style="gap: 0;">
    <?php if ($mode == "create_account"): ?>
      <div class="window-container">
        <div class="dropdown-menu small-window-title window-title">
          <h1>menu</h1>
        </div>
        <div class="dropdown-menu small-window-body window-body">
          <div class="dropdown-selection">
            <button id="add-user-button" aria-current="page" onclick="window.location.href='dashboard.php'"><img id="lock-image" src="resources/icons/user_add.png" width="16" height="16" draggable="false">Add User</button>
            <button id="users-button" onclick="window.location.href='dashboard.php?m=accounts'"><img id="lock-image" src="resources/icons/user.png" width="16" height="16" draggable="false">User List</button>
            <button id="urls-button" onclick="window.location.href='dashboard.php?m=urls'"><img id="lock-image" src="resources/icons/database.png" width="16" height="16" draggable="false">URL List</button>
            <button id="go-back-button" onclick="window.location.href='view.php'"><img id="lock-image" src="resources/icons/door_out.png" width="16" height="16" draggable="false">Go Back</button>
          </div>
        </div>
      </div>
      <div class="window-container">
        <div class="url-menu small-window-title window-title">
          <h1>add user</h1>
        </div>
        <div class="url-menu small-window-body window-body">
          <script src="js/accounts/create.js" defer></script>
          <form id="form" class="form-area">
            <div class="double-form-input">
              <div class="form-input">
                <label for="username" data-hover="Account's username."><img src="resources/icons/user_edit.png" width="16" height="16" draggable="false"> Username</label>
                <input type="text" name="username" id="username" placeholder="..." autocomplete="off">
              </div>
              <div class="form-input">
                <label for="password" data-hover="Account's password."><img src="resources/icons/lock_edit.png" width="16" height="16" draggable="false"> Password</label>
                <input type="password" name="password" id="password" placeholder="..." autocomplete="off">
              </div>
            </div>
            <div class="double-form-input">
              <div class="form-input" style="flex: 0 0 calc(50% - 0.5em);">
                <label for="role" data-hover="Account's role."><img src="resources/icons/folder_key.png" width="16" height="16" draggable="false"> Role</label>
                <select name="role" id="role">
                    <?php 
                        $stmt = $conn->prepare("SELECT * FROM roles");
                        $stmt->execute();
                        $roles_fetch = $stmt->fetchAll();
                        foreach ($roles_fetch as $role) {
                            echo "<option value='" . $role["id"] . "'>" . $role["name"] . "</option>";
                        }
                    ?>
                </select>
              </div>
            </div>
          </form>
          <button id="submit-button" class="window-body-end" style="margin-bottom: 11px;"><img id="lock-image" src="resources/icons/user_add.png" width="16" height="16" draggable="false">Create</button>
        </div>
      </div>
    <?php endif; ?>
    <?php if ($mode == "accounts"): ?>
      <div class="window-container">
        <div class="dropdown-menu small-window-title window-title">
          <h1>menu</h1>
        </div>
        <div class="dropdown-menu small-window-body window-body">
          <div class="dropdown-selection">
            <button id="add-user-button" onclick="window.location.href='dashboard.php'"><img id="lock-image" src="resources/icons/user_add.png" width="16" height="16" draggable="false">Add User</button>
            <button id="users-button" aria-current="page" onclick="window.location.href='dashboard.php?m=accounts'"><img id="lock-image" src="resources/icons/user.png" width="16" height="16" draggable="false">User List</button>
            <button id="urls-button" onclick="window.location.href='dashboard.php?m=urls'"><img id="lock-image" src="resources/icons/database.png" width="16" height="16" draggable="false">URL List</button>
            <button id="go-back-button" onclick="window.location.href='view.php'"><img id="lock-image" src="resources/icons/door_out.png" width="16" height="16" draggable="false">Go Back</button>
          </div>
        </div>
      </div>
      <div class="window-container">
        <div class="url-menu small-window-title window-title">
          <h1>user list</h1>
        </div>
        <div class="url-menu small-window-body window-body">
          <script src="js/accounts/getList.js" defer></script>
          <table id="accounts">
            <tr>
              <th><img src="resources/icons/user_edit.png" width="16" height="16" draggable="false"> Username</th>
              <th><img src="resources/icons/lock_edit.png" width="16" height="16" draggable="false"> Role</th>
              <th><img src="resources/icons/brick_edit.png" width="16" height="16" draggable="false"> Actions</th>
            </tr>
          </table>
          <div class="window-body-end window-two-gap-buttons" style="margin-bottom: 11px;">
            <button id="previous-page" disabled><img src="resources/icons/arrow_left.png" width="16" height="16" draggable="false">Previous page</button>
            <button id="next-page" disabled><img src="resources/icons/arrow_right.png" width="16" height="16" draggable="false">Next page</button>
          </div>
        </div>
      </div>
    <?php endif; ?>
    <?php if ($mode == "urls"): ?>
      <div class="window-container">
        <div class="dropdown-menu small-window-title window-title">
          <h1>menu</h1>
        </div>
        <div class="dropdown-menu small-window-body window-body">
          <div class="dropdown-selection">
            <button id="add-user-button" onclick="window.location.href='dashboard.php'"><img id="lock-image" src="resources/icons/user_add.png" width="16" height="16" draggable="false">Add User</button>
            <button id="users-button" onclick="window.location.href='dashboard.php?m=accounts'"><img id="lock-image" src="resources/icons/user.png" width="16" height="16" draggable="false">User List</button>
            <button id="urls-button" aria-current="page" onclick="window.location.href='dashboard.php?m=urls'"><img id="lock-image" src="resources/icons/database.png" width="16" height="16" draggable="false">URL List</button>
            <button id="go-back-button" onclick="window.location.href='view.php'"><img id="lock-image" src="resources/icons/door_out.png" width="16" height="16" draggable="false">Go Back</button>
          </div>
        </div>
      </div>
      <div class="window-container">
        <div class="url-menu small-window-title window-title">
          <h1>url list</h1>
        </div>
        <div class="url-menu small-window-body window-body">
          <script src="js/urls/getGlobalList.js" defer></script>
          <table id="urls">
            <tr>
              <th><img src="resources/icons/link.png" width="16" height="16" draggable="false"> Alias</th>
              <th><img src="resources/icons/application_form_edit.png" width="16" height="16" draggable="false"> URL</th>
              <th><img src="resources/icons/brick_edit.png" width="16" height="16" draggable="false"> Actions</th>
            </tr>
          </table>
          <div class="window-body-end window-two-gap-buttons" style="margin-bottom: 11px;">
            <button id="previous-page" disabled><img src="resources/icons/arrow_left.png" width="16" height="16" draggable="false">Previous page</button>
            <button id="next-page" disabled><img src="resources/icons/arrow_right.png" width="16" height="16" draggable="false">Next page</button>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
  <div class="end">
    <div class="end-line" style="width: 300px;"></div>
    <p>powered by toms3.CC Technologies™<br><a href="https://cherryh4ck.cc">cherryh4ck.cc</a></p>
  </div>
  
  <!-- All data stuff yk !! -->
  <div class="hover-block"><p></p></div>
  <div id="popup" class="popup-window">
      <div class="popup-container">
          <div class="popup-title">
              <h1 id="popup-title"></h1>
              <button class="popup-close" onclick="closePopup()">✕</button>
          </div>
          <div class="popup-body">
              <div id="popup-body-content" class="popup-body-content"></div>
              <div class="popup-actions">
                  <button id="popup-button" class="popup-button" onclick="closePopup()">OK</button>
              </div>
          </div>
      </div>
  </div>
</body>
</html>