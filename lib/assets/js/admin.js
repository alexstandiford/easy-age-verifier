jQuery(document).ready(function($){
  $("#js-copy-text").on('click', function(){
    var text = document.getElementById('js-ticket-info');
    text.focus();
    text.select();
    document.execCommand('copy');
    window.open('https://wordpress.org/support/plugin/easy-age-verifier');
  });

  $("#js-enable-debug-mode").on('click', function(){
    $.ajax({
      url: eavAdmin.debugModeUrl,
      type: "POST",
      data: {nonce: eavAdmin.nonce},
      success: function(res){
        var toggle = res === true ? "Disable" : "Enable";
        var status = res === true ? "Enabled" : "Disabled";
        $('#debug-toggle').html(toggle);
        $('#debug-status').html(status);
      },
      error: function(res){
        $("#js-debug-mode-error-message").html('An Error Occured.' + res.responseJSON.message);
      },
      beforeSend: function(xhr){
        $("#js-debug-mode-error-message").html('');
        xhr.setRequestHeader('X-WP-Nonce', eavAdmin.nonce);
      }
    });
  })
});