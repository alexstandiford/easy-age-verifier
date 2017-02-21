<?php
$debug = new \eav\app\debugger();
?>
<h1>Debugging Your Verifier</h1>
<h2>Before you submit a ticket, check out the <a href="https://wordpress.org/plugins/easy-age-verifier/faq/" target="_blank">FAQ</a> for common problems, and how to resolve them.</h2>
<p>We offer support on WordPress.org to people who are having issues with their verifier. To open up a support ticket, copy the text below, and paste it along with a description of the issue you're having.</p>
<button id="js-copy-text" class="button button-primary">Copy debug info & Submit Ticket</button>
<h3>Debug Info</h3>
<textarea title="ticket-info" id="js-ticket-info">
  <?php echo $debug->generateInfo(); ?>
</textarea>
