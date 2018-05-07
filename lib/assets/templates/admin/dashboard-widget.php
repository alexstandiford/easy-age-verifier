<?php
/**
 * Dashboard widget markup
 * @author: Alex Standiford
 * @date  : 5/7/18
 */


use eav\extras\wpApiQuery;

if(!defined('ABSPATH')) exit;

$fyt = new wpApiQuery(['domain' => 'fillyourtaproom.com', 'posts_per_page' => 10]);
if($fyt->havePosts()):

  ?>
  <ul>
    <?php while($fyt->havePosts()): $fyt->thePost(); ?>
      <li><strong><a href="<?php echo $fyt->permalink; ?>" target="_blank"><?php echo $fyt->title; ?></a></strong></li>
    <?php endwhile; ?>
  </ul>

<?php endif; ?>