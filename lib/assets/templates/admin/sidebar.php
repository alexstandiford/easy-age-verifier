<?php
use eav\extras\wpApiQuery;

$fyt = new wpApiQuery(['domain' => 'fillyourtaproom.com', 'posts_per_page' => 5]);
?>
<div class="eav-wrapper">
  <div class="eav-content">
    <?php
    if($fyt->havePosts()):?>
      <div class="eav-articles">
        <h1>Recent Articles on Fill Your Taproom:</h1>
        <?php while($fyt->havePosts()): $fyt->thePost(); ?>
          <article class="eav-post">
            <h3><a href="<?php echo $fyt->permalink; ?>" target="_blank"><?php echo $fyt->title; ?></a></h3>
            <img src="<?php echo $fyt->featuredImage['thumbnail']; ?>">
            <p><?php echo $fyt->excerpt; ?></p><a href="<?php echo $fyt->permalink; ?>" target="_blank">Read More</a>
          </article>
        <?php endwhile; ?>
      </div>
    <?php endif; ?>
  </div>
  <div class="eav-sidebar">
    <div class="eav-heading">
      <h2>Easy Age Verifier was proudly made by Alex Standiford</h2>
      <p>I am here to help breweries manage their online presence faster. I do that by providing breweries with tools,
        tips, and tricks that make their lives easier.</p>
      <p>If you ever have <em>any</em> questions about WordPress, or need customizations to your website don't hesitate
        to
        send me a message. I'll be happy to help you out in any way I can.</p>
      <ul>
        <li><strong>Email:</strong> <a href="mailto:a@alexstandiford.com">a@alexstandiford.com</a></li>
        <li><strong>Twitter:</strong> <a href="http://www.twitter.com/fillyourtaproom" target="blank">Follow me on Twitter</a></li>
        <li><strong>Website:</strong> <a href="http://www.fillyourtaproom.com" target="blank">Visit my website</a></li>
      </ul>
    </div>
    <div id="mc_embed_signup">
      <form action="//alexstandiford.us2.list-manage.com/subscribe/post?u=f39d9629a4dd9dd976f09f6e5&amp;id=b6a3d349e7"
            method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate"
            target="_blank" novalidate>
        <div id="mc_embed_signup_scroll">
          <h2>Spend Less Time Updating Your Website</h2>
          <h3>Fill out the form below, and I'll send you</h3>
          <ul>
            <li>A list of my 3 must-have free plugins for brewers and bars</li>
            <li>Learn about the free tool that I use to spend less time managing social media</li>
            <li>A complete workflow of how to quickly promote events on Facebook, Instagram, and Twitter</li>
            <li>PDF to-do checklists that walk you through the process quickly</li>
            <li>Ongoing WordPress tips and tricks for breweries</li>
          </ul>
          <div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
          <div class="mc-field-group">
            <label for="mce-EMAIL">Email Address <span class="asterisk">*</span>
            </label>
            <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
          </div>
          <div class="mc-field-group">
            <label for="mce-FNAME">First Name </label>
            <input type="text" value="" name="FNAME" class="" id="mce-FNAME">
          </div>
          <div class="mc-field-group">
            <label for="mce-LNAME">Last Name </label>
            <input type="text" value="" name="LNAME" class="" id="mce-LNAME">
          </div>
          <div class="mc-field-group input-group hidden">
            <li><input checked type="checkbox" value="8" name="group[18977][8]" id="mce-group[18977]-18977-3"><label
                for="mce-group[18977]-18977-3">Easy Age Verifier User</label></li>
            <li><input checked type="checkbox" value="2" name="group[18977][2]" id="mce-group[18977]-18977-1"><label
                for="mce-group[18977]-18977-1">Website Efficiency Workflow</label></li>
          </div>
          <div id="mce-responses" class="clear">
            <div class="response" id="mce-error-response" style="display:none"></div>
            <div class="response" id="mce-success-response" style="display:none"></div>
          </div>
          <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
          <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text"
                                                                                    name="b_f39d9629a4dd9dd976f09f6e5_b6a3d349e7"
                                                                                    tabindex="-1" value=""></div>
          <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe"
                                    class="button"></div>
        </div>
      </form>
    </div>
    <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
    <script type='text/javascript'>(function($){
        window.fnames = new Array();
        window.ftypes = new Array();
        fnames[0] = 'EMAIL';
        ftypes[0] = 'email';
        fnames[1] = 'FNAME';
        ftypes[1] = 'text';
        fnames[2] = 'LNAME';
        ftypes[2] = 'text';
      }(jQuery));
      var $mcj = jQuery.noConflict(true);</script>
    <!--End mc_embed_signup-->
  </div>
</div>