<?php
    session_start();
    if (!isset($_SESSION["account_id"])) {
        header("Location: index.php");
        exit;
    }

    require __DIR__ . "/api/v1/config/conn.php";
    $valid_modes = ["home", "create", "urls"];
    if (isset($_GET["m"]) && in_array($_GET["m"], $valid_modes)) {
        $mode = $_GET["m"];
    }
    else {
        $mode = "home";
    }

    $stmt = $conn->prepare("SELECT * FROM roles WHERE id = ?");
    $stmt->execute([$_SESSION["account_role_id"]]);
    $role_fetch = $stmt->fetch();
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
  <script src="js/auth.js" defer></script>
  <script src="js/hover.js" defer></script>
  <!--- ackee analytics -->
  <script async src="https://ackee.toms3.cc/tracker.js" data-ackee-server="https://ackee.toms3.cc" data-ackee-domain-id="254b8ce6-e5d9-4212-b449-3193427e2caa"></script>
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
    <?php if ($mode == "home"): ?>
      <div class="window-container">
        <div class="dropdown-menu small-window-title window-title">
          <h1>menu</h1>
        </div>
        <div class="dropdown-menu small-window-body window-body">
          <div class="dropdown-selection">
            <button id="home-button" aria-current="page" onclick="window.location.href='view.php'"><img id="lock-image" src="resources/icons/house.png" width="16" height="16" draggable="false">Home</button>
            <button id="create-button" onclick="window.location.href='view.php?m=create'"><img id="lock-image" src="resources/icons/bricks.png" width="16" height="16" draggable="false">Create</button>
            <button id="urls-button" onclick="window.location.href='view.php?m=urls'"><img id="lock-image" src="resources/icons/database_edit.png" width="16" height="16" draggable="false">My URLs</button>
            <button id="logout-button" onclick="window.location.href='logout.php'"><img id="lock-image" src="resources/icons/key_go.png" width="16" height="16" draggable="false">Log Out</button>
          </div>
        </div>
      </div>
      <div class="window-container">
        <div class="url-menu small-window-title window-title">
          <h1>home</h1>
        </div>
        <div class="url-menu small-window-body window-body">
          <p class="window-body-start"><img id="lock-image" src="resources/icons/group.png" width="16" height="16" draggable="false"> Welcome, <b><?php echo $_SESSION["account_username"]; ?></b>.<br><br><img id="lock-image" src="resources/icons/status_online.png" width="16" height="16" draggable="false"> Your current role is <b><?php echo $role_fetch["name"]; ?></b>.</p>
          <p class="window-body-end" style="margin-bottom: 11px;"><img id="lock-image" src="resources/icons/user_comment.png" width="16" height="16" draggable="false"> Send your feedback at <b>root@toms3.cc</b></p>
        </div>
      </div>
    <?php endif; ?>
    <?php if ($mode == "create"): ?>
      <div class="window-container">
        <div class="dropdown-menu small-window-title window-title">
          <h1>menu</h1>
        </div>
        <div class="dropdown-menu small-window-body window-body">
          <div class="dropdown-selection">
            <button id="home-button" onclick="window.location.href='view.php'"><img id="lock-image" src="resources/icons/house.png" width="16" height="16" draggable="false">Home</button>
            <button id="create-button" aria-current="page" onclick="window.location.href='view.php?m=create'"><img id="lock-image" src="resources/icons/bricks.png" width="16" height="16" draggable="false">Create</button>
            <button id="urls-button" onclick="window.location.href='view.php?m=urls'"><img id="lock-image" src="resources/icons/database_edit.png" width="16" height="16" draggable="false">My URLs</button>
            <button id="logout-button" onclick="window.location.href='logout.php'"><img id="lock-image" src="resources/icons/key_go.png" width="16" height="16" draggable="false">Log Out</button>
          </div>
        </div>
      </div>
      <div class="window-container">
        <div class="url-menu small-window-title window-title">
          <h1>create</h1>
        </div>
        <div class="url-menu small-window-body window-body">
          <form id="form" class="form-area">
            <div class="double-form-input">
              <div class="form-input">
                <label for="url" data-hover="Must start with https:// and be a valid URL."><img src="resources/icons/application_form_edit.png" width="16" height="16" draggable="false"> URL</label>
                <input type="text" name="url" id="" placeholder="https://">
              </div>
              <div class="form-input">
                <label for="alias" data-hover="If not filled, a random one will be generated."><img src="resources/icons/link.png" width="16" height="16" draggable="false"> Alias <span>optional</span></label>
                <input type="text" name="alias" id="" placeholder="apple-orange">
              </div>
            </div>
            <div class="double-form-input">
              <div class="form-input">
                <label for="expires" data-hover="When will this URL expire?"><img src="resources/icons/time.png" width="16" height="16" draggable="false"> Expires on</label>
                <select name="expires" id="">
                  <option value="30m">30 minutes</option>
                  <option value="1h">1 hour</option>
                  <option value="1d">1 day</option>
                  <option value="1w">1 week</option>
                  <option value="never" selected>Never</option>
                </select>
              </div>
              <div class="form-input">
                <label for="token" data-hover="Key for API authentication."><img src="resources/icons/key_add.png" width="16" height="16" draggable="false"> Token <span>optional</span></label>
                <input type="text" name="token" id="" placeholder="...">
              </div>
            </div>
          </form>
          <button id="create-button" class="window-body-end" style="margin-bottom: 11px;"><img id="lock-image" src="resources/icons/brick_add.png" width="16" height="16" draggable="false">Create</button>
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
            <button id="home-button" onclick="window.location.href='view.php'"><img id="lock-image" src="resources/icons/house.png" width="16" height="16" draggable="false">Home</button>
            <button id="create-button" onclick="window.location.href='view.php?m=create'"><img id="lock-image" src="resources/icons/bricks.png" width="16" height="16" draggable="false">Create</button>
            <button id="urls-button" aria-current="page" onclick="window.location.href='view.php?m=urls'"><img id="lock-image" src="resources/icons/database_edit.png" width="16" height="16" draggable="false">My URLs</button>
            <button id="logout-button" onclick="window.location.href='logout.php'"><img id="lock-image" src="resources/icons/key_go.png" width="16" height="16" draggable="false">Log Out</button>
          </div>
        </div>
      </div>
      <div class="window-container">
        <div class="url-menu small-window-title window-title">
          <h1>create</h1>
        </div>
        <div class="url-menu small-window-body window-body">
          <table>
            <tr>
              <th><img src="resources/icons/link.png" width="16" height="16" draggable="false"> Alias</th>
              <th><img src="resources/icons/application_form_edit.png" width="16" height="16" draggable="false"> URL</th>
              <th><img src="resources/icons/brick_edit.png" width="16" height="16" draggable="false"> Actions</th>
            </tr>
            <tr>
              <td>google.com</td>
              <td>google-url</td>
              <td>
                <button><img src="resources/icons/bomb.png" width="16" height="16" draggable="false">Delete</button>
              </td>
            </tr>
          </table>
          <div class="window-body-end window-two-gap-buttons" style="margin-bottom: 11px;">
            <button><img src="resources/icons/arrow_left.png" width="16" height="16" draggable="false">Previous page</button>
            <button><img src="resources/icons/arrow_right.png" width="16" height="16" draggable="false">Next page</button>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>
  <div class="end">
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