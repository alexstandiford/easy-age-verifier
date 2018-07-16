jQuery(document).ready(function($){
  $.post(ajaxurl,{
    action: 'fyt_cache_articles'
  },() => {
    console.log('ran cache');
  });
});