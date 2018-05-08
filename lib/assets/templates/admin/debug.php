<?php
$debug = new \eav\app\debugger();
?>
<h1>Debugging Your Verifier</h1>
<h2>Before you submit a ticket, check out the <a href="https://wordpress.org/plugins/easy-age-verifier/faq/" target="_blank">FAQ</a> for common problems, and how to resolve them.</h2>
<h3>Enable/Disable Debug Mode</h3>
<p>Debug mode will temporarily disable the age verifier on your site, but will leave the plugin activated so issues with the verifier can be debugged by our team. If Easy Age Verifier is causing your site problems, do not disable the plugin, instead just enable debug mode.</p>
<button id="js-enable-debug-mode" class="button button-primary"><span id="debug-toggle"><?= $debug->toggleButtonText(); ?></span> Debug Mode</button><br>
<em>Debug Mode is currently <strong id="debug-status"><?= $debug->debugModeStatus(); ?></strong></em><br>
<strong id="js-debug-mode-error-message"></strong>

<h3>Submit a Ticket</h3>
<p>We offer support on WordPress.org to people who are having issues with their verifier. To open up a support ticket, copy the text below, and paste it along with a description of the issue you're having.</p>
<button id="js-copy-text" class="button button-primary">Copy debug info & Submit Ticket</button>
<h3>Debug Info</h3>
<textarea title="ticket-info" id="js-ticket-info">
  <?php echo $debug->generateInfo(); ?>
</textarea>
