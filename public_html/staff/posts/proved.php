<?php
use App\Classes\Post;
use App\Classes\File;
use App\Classes\User;

require_once('../../../src/initialize.php');

// Check Admin >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
if (!$session->isAdmin()) redirect_to(url_for('index.php'));
// <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<< Check Admin

if (isset($_GET['id'])) {

  $cmd = $_GET['cmd'] ?? false;
  $post = Post::findById($_GET['id']);

  if (!$cmd || !$post) redirect_to(url_for('index.php'));

  if ($cmd == 'disprove') {
    $post->proved = '0';

    if ($post->save() === true) {
      $session->message("Post '" . $post->title . "' was disproved.");
      redirect_to(url_for('/staff/posts/unproved.php'));
    }
  }

}

$posts = Post::findWhere(
  ['proved' => 1],
  ['updated_at' => 'DESC']
);

$page_title = 'Proved Posts';
include SHARED_PATH . '/staff_header.php';
require '_common-posts-html.php';

?>
<div class="container-xl">
  <div class="page-admin">

    <div class="row">
      <div class="topbox col-12"></div>
    </div>
  
    <div class="row">
      <?php include SHARED_PATH . '/staff_sidebar.php' ?>

      <main class="main col-lg-9">
        <div class="main-content">
          <?php echo page_back_button() ?>

          <h2><?php echo $page_title ?></h2>

          <?php if (empty($posts)): ?>
            <p class="lead">You have not posts yet.</p>
          
          <?php else: ?>
            <?php echo display_session_message('msg success') ?>

            <table class="table table-bordered table-hover table-light table-sm">
              <thead class="bg-muted-lk text-muted">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Title</th>
                  <th scope="col">Author</th>
                  <th scope="col">Status</th>
                  <th scope="col">Edited</th>
                  <th scope="colgroup" colspan="1">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($posts as $key => $post): ?>
                  <tr>
                    <th scope="row"><?php echo $key + 1 ?></th>
                    <td>
                      <a href="<?php echo url_for('post/' . u($post->title) . '?id=' . $post->id) ?>">
                        <?php echo $post->title ?>
                      </a>
                    </td>
                    <td><?php echo (User::findById($post->user_id))->username ?></td>
                      <?php
                      if ($post->published == 0): ?>
                        <td class="text-secondary font-weight-bold">draft</td><?php
                      elseif ($post->published == 1 && $post->proved == 0): ?>
                        <td class="text-danger font-weight-bold">published</td><?php
                      elseif ($post->published == 1 && $post->proved == 1): ?>
                        <td class="text-success font-weight-bold">proved</td><?php
                      endif; ?>
                    </td>
                    <td>
                      <span><?php echo date('M j, Y', strtotime($post->updated_at)) ?></span>
                    </td>
                    <?php echo td_colgroup_actions_admin($post); ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
      
          <?php endif; ?>

        </div>
      </main>
    </div><!-- row -->

  </div><!--page admin-->
</div><!--container-->

<?php include SHARED_PATH . '/staff_footer.php'; ?>