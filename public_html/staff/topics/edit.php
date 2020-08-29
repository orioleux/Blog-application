<?php
use App\Classes\Topic;

require_once('../../../src/initialize.php');

// Check Admin >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
if (!$session->isAdmin()) redirect_to(url_for('index.php'));
// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Check Admin

$topic = false;

if (is_get_request()) {

  // Post ID must be provided
  if(isset($_GET['id'])) {
    $topic = App\Classes\Topic::findById($_GET['id']);
  }
  if($topic === false) {
    redirect_to(url_for('/staff/topics/index.php'));
  }

} elseif (is_post_request()) {

  $id = $_POST['topic']['id'] ?? 0;
  $topic = Topic::findById($id);
  $topic->mergeAttributes($_POST['topic']);
  $result = $topic->save();

  if($result === true) {
    $session->message("The Topic '" . $topic->name . "' was updated!");
    redirect_to(url_for('/staff/topics/index.php'));
  }

}

$page_title = 'Admin - Edit Topic';
include SHARED_PATH . '/staff_header.php'

?>
<div class="container-xl">
  <div class="page-admin">

    <div class="row">
      <div class="topbox col-12"></div>
    </div>

    <div class="row">
      <?php include SHARED_PATH . '/staff_sidebar.php' ?>
      
      <div class="main col-lg-9">
        <?php include('./_form.php') ?>
      </div>

    </div>
  </div>
</div>

<?php include SHARED_PATH . '/staff_footer.php'; ?>