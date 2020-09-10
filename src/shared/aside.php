<?php

use App\Classes\User;
use App\Classes\Post;
use App\Classes\Topic;

if (url_contain(['post/','preview/'])) $user = User::findById($post->user_id);

$posts = App\Classes\Post::findWhere(
  ['approved' => '1'],
  'ORDER BY RAND() LIMIT 3'
);

$topics = Topic::findAll();

?>
<div class="sidebar-content mt-2 pt-1">

  <?php if (url_contain(['post/','preview/']) && $user->about_appear): ?>
    <section class="widget mt-5">
      <h3 class="title mb-3">About Author</h3>

      <div class="about-widget">
        <div class="about-image d-flex align-items-center justify-content-center">
          <img class="rounded-circle" src="<?php echo url_for('assets/images' . $user->about_image) ?>" alt="About Us">
        </div>
        <div class="about-text">
          <p><?php echo $user->about_text ?></p>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="widget mt-5">
    <h3 class="title">Recent Posts</h3>

    <div class="recent-posts-widget">
      <?php foreach ($posts as $post): ?>
        <div class="post">
          <div class="post-image ">
            <a href="<?php echo url_for('post/' . u($post->title) . '?id=' . $post->id) ?>">
              <img src="<?php echo url_for('render_img.php?img='. u($post->image) .'&w=420') ?>" style="object-fit:cover" width="150" height="150">
            </a>
          </div> 
          <div class="post-content">
            <a href="<?php echo url_for('post/' . u($post->title) . '?id=' . $post->id) ?>"><?php echo $post->title ?></a>
            <span class="date">- 05 Oct , 2016</span>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="widget">
    <div class="search-widget pt-5 pb-3">
      <form role="search" method="get" class="form-search" action="<?php echo url_for('index.php') ?>">
        <div class="input-group">
          <label class="screen-reader-text" for="s">Search for:</label>
          <input type="text" class="form-control search-query" placeholder="Search…" value="" name="s" title="Search for:">
          <div class="input-group-append">
            <button type="submit" class="btn btn-default" name="submit" id="searchsubmit" value="Search">Search</button>
          </div>
        </div>
      </form>
    </div>
  </section>

  <section class="widget">
    <h3 class="title">Follow Us</h3>

    <div class="social-links-widget more-space-between">
      <?php include '_social_links_list.php' ?>
    </div>
  </section>

  <section class="widget">
    <h3 class="title">Topics</h3>
    
    <div class="cats-widget">
      <ul>
        <?php foreach ($topics as $topic): ?>
          <li class="cat-item">
            <a href="<?php echo url_for('topic/') . u($topic->name) . '?id=' . $topic->id ?>" title="<?php $topic->name ?>"><?php echo $topic->name ?></a>
            <span><?php echo Post::countAll(
              ['topic_id' => $topic->id, 'approved' => '1']
            ) ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>

  <section class="widget widget_text mt-5">
    <div class="textwidget">Any text goes here</div>
  </section>

</div>