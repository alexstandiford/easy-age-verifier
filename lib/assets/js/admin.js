document.getElementById('js-copy-text').addEventListener('click',function(e){
  var text = document.getElementById('js-ticket-info');
  console.log(text);
  text.focus();
  text.select();
  document.execCommand('copy');
  window.open('https://wordpress.org/support/plugin/easy-age-verifier');
});