<?php
// Left Sidebar

use App\Classes\Post;

$published = Post::countAll([
  'published' => 1,
  'proved' => 0,
  'user_id' => ['!=' => $session->getUserId()]
]);

?>
<ul class="sidebar-nav">
  <li class="nav-item logo">
    <a href="<?php echo url_for('staff/index.php') ?>" class="nav-link">
      <span class="link-text logo-text">Dashboard</span>
      <svg
        aria-hidden="true"
        focusable="false"
        data-prefix="fad"
        data-icon="angle-double-right"
        role="img"
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 448 512"
        class="svg-inline--fa fa-angle-double-right fa-w-14 fa-5x"
      >
        <g class="fa-group">
          <path
            fill="currentColor"
            d="M224 273L88.37 409a23.78 23.78 0 0 1-33.8 0L32 386.36a23.94 23.94 0 0 1 0-33.89l96.13-96.37L32 159.73a23.94 23.94 0 0 1 0-33.89l22.44-22.79a23.78 23.78 0 0 1 33.8 0L223.88 239a23.94 23.94 0 0 1 .1 34z"
            class="fa-secondary"
          ></path>
          <path
            fill="currentColor"
            d="M415.89 273L280.34 409a23.77 23.77 0 0 1-33.79 0L224 386.26a23.94 23.94 0 0 1 0-33.89L320.11 256l-96-96.47a23.94 23.94 0 0 1 0-33.89l22.52-22.59a23.77 23.77 0 0 1 33.79 0L416 239a24 24 0 0 1-.11 34z"
            class="fa-primary"
          ></path>
        </g>
      </svg>
    </a>
  </li>

  <!-- Check Author >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> -->
  <?php if ($session->isAuthor()):
    $active = (url_contain('staff/posts/create') ? ' active' : ''); ?>
    <li class="nav-item">
      <a href="<?php echo url_for('staff/posts/create.php') ?>" class="nav-link<?php echo $active ?>">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="feather" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-icon svg-icon--smaller svg-inline--fa fa-feather fa-w-16 fa-5x"><path fill="currentColor" d="M467.14 44.84c-62.55-62.48-161.67-64.78-252.28 25.73-78.61 78.52-60.98 60.92-85.75 85.66-60.46 60.39-70.39 150.83-63.64 211.17l178.44-178.25c6.26-6.25 16.4-6.25 22.65 0s6.25 16.38 0 22.63L7.04 471.03c-9.38 9.37-9.38 24.57 0 33.94 9.38 9.37 24.6 9.37 33.98 0l66.1-66.03C159.42 454.65 279 457.11 353.95 384h-98.19l147.57-49.14c49.99-49.93 36.38-36.18 46.31-46.86h-97.78l131.54-43.8c45.44-74.46 34.31-148.84-16.26-199.36z" class="svg-icon"></path></svg>
        <span class="link-text">Add Post</span>
      </a>
    </li>
  <?php endif; ?>
  <!-- <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Check Author -->

  <!-- Check Admin >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> -->
  <?php if ($session->isAdmin()):
    $active = (url_contain('staff/posts/index') ? ' active' : ''); ?>
    <li class="nav-item border-bottom">
      <a href="<?php echo url_for('staff/posts/index.php') ?>" class="nav-link<?php echo $active ?>">
      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="industry" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-icon svg-icon--smaller svg-inline--fa fa-industry fa-w-16 fa-5x"><path fill="currentColor" d="M475.115 163.781L336 252.309v-68.28c0-18.916-20.931-30.399-36.885-20.248L160 252.309V56c0-13.255-10.745-24-24-24H24C10.745 32 0 42.745 0 56v400c0 13.255 10.745 24 24 24h464c13.255 0 24-10.745 24-24V184.029c0-18.917-20.931-30.399-36.885-20.248z" class="svg-icon"></path></svg>
        <span class="link-text">Admin Posts</span>
      </a>
    </li>

    <?php $active = (url_contain('staff/posts/drafts') ? ' active' : ''); ?>
    <li class="nav-item">
      <a href="<?php echo url_for('staff/posts/drafts.php') ?>" class="nav-link<?php echo $active ?>">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="pencil-ruler" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-icon svg-icon--smaller svg-inline--fa fa-pencil-ruler fa-w-16 fa-5x"><path fill="currentColor" d="M109.46 244.04l134.58-134.56-44.12-44.12-61.68 61.68a7.919 7.919 0 0 1-11.21 0l-11.21-11.21c-3.1-3.1-3.1-8.12 0-11.21l61.68-61.68-33.64-33.65C131.47-3.1 111.39-3.1 99 9.29L9.29 99c-12.38 12.39-12.39 32.47 0 44.86l100.17 100.18zm388.47-116.8c18.76-18.76 18.75-49.17 0-67.93l-45.25-45.25c-18.76-18.76-49.18-18.76-67.95 0l-46.02 46.01 113.2 113.2 46.02-46.03zM316.08 82.71l-297 296.96L.32 487.11c-2.53 14.49 10.09 27.11 24.59 24.56l107.45-18.84L429.28 195.9 316.08 82.71zm186.63 285.43l-33.64-33.64-61.68 61.68c-3.1 3.1-8.12 3.1-11.21 0l-11.21-11.21c-3.09-3.1-3.09-8.12 0-11.21l61.68-61.68-44.14-44.14L267.93 402.5l100.21 100.2c12.39 12.39 32.47 12.39 44.86 0l89.71-89.7c12.39-12.39 12.39-32.47 0-44.86z" class="svg-icon"></path></svg>
        <span class="link-text">Author's Drafts</span>
      </a>
    </li>

    <?php $active = (url_contain('staff/posts/published') ? ' active' : ''); ?>
    <li class="nav-item">
      <a href="<?php echo url_for('staff/posts/published.php') ?>" class="nav-link<?php echo $active ?>">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="toggle-on" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-icon svg-icon--smaller svg-inline--fa fa-toggle-on fa-w-18 fa-5x"><path fill="currentColor" d="M384 64H192C86 64 0 150 0 256s86 192 192 192h192c106 0 192-86 192-192S490 64 384 64zm0 320c-70.8 0-128-57.3-128-128 0-70.8 57.3-128 128-128 70.8 0 128 57.3 128 128 0 70.8-57.3 128-128 128z" class="svg-icon"></path></svg>
        <span class="link-text">Author's Published &nbsp;<span class="show-number"><?php echo $published ?></span></span>
      </a>
    </li>

    <?php $active = (url_contain('staff/posts/proved') ? ' active' : ''); ?>
    <li class="nav-item border-bottom" id="themeButton">
      <a href="<?php echo url_for('staff/posts/proved.php') ?>" class="nav-link<?php echo $active ?>">
      <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="check-double" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-icon svg-icon--smaller svg-inline--fa fa-check-double fa-w-16 fa-5x"><path fill="currentColor" d="M505 174.8l-39.6-39.6c-9.4-9.4-24.6-9.4-33.9 0L192 374.7 80.6 263.2c-9.4-9.4-24.6-9.4-33.9 0L7 302.9c-9.4 9.4-9.4 24.6 0 34L175 505c9.4 9.4 24.6 9.4 33.9 0l296-296.2c9.4-9.5 9.4-24.7.1-34zm-324.3 106c6.2 6.3 16.4 6.3 22.6 0l208-208.2c6.2-6.3 6.2-16.4 0-22.6L366.1 4.7c-6.2-6.3-16.4-6.3-22.6 0L192 156.2l-55.4-55.5c-6.2-6.3-16.4-6.3-22.6 0L68.7 146c-6.2 6.3-6.2 16.4 0 22.6l112 112.2z" class="svg-icon"></path></svg>
        <span class="link-text">Author's Proved Posts</i></span>
      </a>
    </li>

    <?php $active = (url_contain('staff/topics/index') ? ' active' : ''); ?>
    <li class="nav-item border-bottom" id="themeButton">
      <a href="<?php echo url_for('staff/topics/index.php') ?>" class="nav-link<?php echo $active ?>">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="sign" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-icon svg-icon--smaller svg-inline--fa fa-sign fa-w-16 fa-5x"><path fill="currentColor" d="M496 64H128V16c0-8.8-7.2-16-16-16H80c-8.8 0-16 7.2-16 16v48H16C7.2 64 0 71.2 0 80v32c0 8.8 7.2 16 16 16h48v368c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V128h368c8.8 0 16-7.2 16-16V80c0-8.8-7.2-16-16-16zM160 384h320V160H160v224z" class="svg-icon"></path></svg>
        <span class="link-text">Topics</span>
      </a>
    </li>

    <?php $active = (url_contain('staff/users/index') ? ' active' : ''); ?>
    <li class="nav-item" id="themeButton">
      <a href="<?php echo url_for('staff/users/index.php') ?>" class="nav-link<?php echo $active ?>">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="users" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="svg-icon svg-icon--smaller svg-inline--fa fa-users fa-w-20 fa-5x"><path fill="currentColor" d="M96 224c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm448 0c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64zm32 32h-64c-17.6 0-33.5 7.1-45.1 18.6 40.3 22.1 68.9 62 75.1 109.4h66c17.7 0 32-14.3 32-32v-32c0-35.3-28.7-64-64-64zm-256 0c61.9 0 112-50.1 112-112S381.9 32 320 32 208 82.1 208 144s50.1 112 112 112zm76.8 32h-8.3c-20.8 10-43.9 16-68.5 16s-47.6-6-68.5-16h-8.3C179.6 288 128 339.6 128 403.2V432c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48v-28.8c0-63.6-51.6-115.2-115.2-115.2zm-223.7-13.4C161.5 263.1 145.6 256 128 256H64c-35.3 0-64 28.7-64 64v32c0 17.7 14.3 32 32 32h65.9c6.3-47.4 34.9-87.3 75.2-109.4z" class="svg-icon"></path></svg>
        <span class="link-text">Users</span>
      </a>
    </li>
  <!-- Check Admin >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> -->

  <!-- Check Author >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> -->
  <?php elseif ($session->isAuthor()):
    $active = (url_contain('staff/posts/index') ? ' active' : ''); ?>
    <li class="nav-item">
      <a href="<?php echo url_for('staff/posts/index.php') ?>" class="nav-link<?php echo $active ?>">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="industry" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-icon svg-icon--smaller svg-inline--fa fa-industry fa-w-16 fa-5x"><path fill="currentColor" d="M475.115 163.781L336 252.309v-68.28c0-18.916-20.931-30.399-36.885-20.248L160 252.309V56c0-13.255-10.745-24-24-24H24C10.745 32 0 42.745 0 56v400c0 13.255 10.745 24 24 24h464c13.255 0 24-10.745 24-24V184.029c0-18.917-20.931-30.399-36.885-20.248z" class="svg-icon"></path></svg>
        <span class="link-text">User Posts</span>
      </a>
    </li>
  <?php endif; ?>
  <!-- <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Check Author -->
  
  <?php if ($session->isLoggedIn() && !$session->isAdmin()):
    $active = (url_contain('staff/users/edit') ? ' active' : ''); ?>
    <li class="nav-item" id="themeButton">
      <a href="<?php echo url_for('staff/users/edit.php?id=' . $session->getUserId()) ?>" class="nav-link<?php echo $active ?>">
        <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-icon svg-icon--smaller svg-inline--fa fa-user fa-w-14 fa-5x"><path fill="currentColor" d="M224 256c70.7 0 128-57.3 128-128S294.7 0 224 0 96 57.3 96 128s57.3 128 128 128zm89.6 32h-16.7c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16h-16.7C60.2 288 0 348.2 0 422.4V464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48v-41.6c0-74.2-60.2-134.4-134.4-134.4z" class="svg-icon"></path></svg>
        <span class="link-text">User Settings</span>
      </a>
    </li>
  <?php endif; ?>
</ul>
