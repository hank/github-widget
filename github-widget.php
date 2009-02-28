<?php
/*
Plugin Name: Github Widget
Plugin URI: http://ralree.com
Description: Github in your sidebar, dawg!
Author: Erik Gregg
Version: 1
Author URI: http://ralree.com
*/

function widget_github() {
$x = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
  echo <<<EOCODE
<div id="github-badge" class="widget widget-text"></div>
<script type="text/javascript" charset="utf-8">
  GITHUB_USERNAME="hank";
  GITHUB_LIST_LENGTH=3;
  GITHUB_HEAD="h3"; // e.g. change to "h2" for wordpress sidebars
  GITHUB_THEME="white"; // try 'black'
  GITHUB_SHOW_ALL = "Show all";
  delete window.jQuery; // Dirty hack to make crap work
</script>
<script src="${x}dist/github-badge-launcher.js" type="text/javascript"></script>
EOCODE;
}

function myGithub_init()
{
  register_sidebar_widget(__('Github'), 'widget_github');
}
add_action("plugins_loaded", "myGithub_init");
?>
