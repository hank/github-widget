<?php
/*
Plugin Name: Github Widget
Plugin URI: http://www.ralree.com/2009/02/28/github-widget-for-wordpressgithub-widget-for-wordpress/
Description: Github in your sidebar, dawg!
Author: Erik Gregg
Version: 1.3.0
Author URI: http://ralree.com
*/
$GITHUB_DEFAULTS = array(  
    'title' => 'My Projects',
    'user' => 'hank',
    'listlen' => '3',
    'headerelem' => 'h3',
    'theme' => "white",
    'showall' => "Show all",
    'showfooter' => "true",
    );  

function activate() {
  global $GITHUB_DEFAULTS;
  $options = $GITHUB_DEFAULTS;
  if(!get_option('github_widget', $options)) {
    add_option('github_widget', $options);
  } else {
    update_option('github_widget', $options);
  }
}

function deactivate() {
  delete_option('github_widget');
}

function widget ($args) {
  extract($args);
  $options = get_option("github_widget");  
  $x = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)); 
  //echo $before_widget;
  echo <<<OMGROFLCOPTER
  <div id="github-badge" class="widget"></div>
  <script type="text/javascript" charset="utf-8">
  GITHUB_TITLE="{$options['title']}";
  GITHUB_USERNAME="{$options['user']}";
  GITHUB_LIST_LENGTH="{$options['listlen']}";
  GITHUB_HEAD="{$options['headerelem']}"; // e.g. change to "h2" for wordpress sidebars
  GITHUB_THEME="{$options['theme']}"; // try 'black'
  GITHUB_SHOW_ALL = "{$options['showall']}";
  GITHUB_SHOWFOOTER = "{$options['showfooter']}";
  delete window.jQuery; // Dirty hack to make crap work
  </script>
  <script src="${x}dist/github-badge-launcher.js" type="text/javascript"></script>
OMGROFLCOPTER;
  //echo $after_widget;
}

function register()
{
  register_sidebar_widget('Github', 'widget');
  register_widget_control('Github', 'control');
}

function control() {
  global $GITHUB_DEFAULTS;
  $options = get_option("github_widget");  

  if (!is_array( $options ))  
  {  
    $options = $GITHUB_DEFAULTS;
  }  

  if ($_POST['github-submit']) {  
    $options['title'] = htmlspecialchars($_POST['github-title']);  
    $options['user'] = htmlspecialchars($_POST['github-user']);  
    $options['listlen'] = htmlspecialchars($_POST['github-listlen']);  
    $options['headerelem'] = htmlspecialchars($_POST['github-headerelem']);  
    $options['theme'] = htmlspecialchars($_POST['github-theme']);  
    $options['showall'] = htmlspecialchars($_POST['github-showall']);  
    $options['showfooter'] = isset($_POST['github-showfooter']); 

    update_option("github_widget", $options);  
  }
	$showfooter = $options['showfooter'] ? 'checked="checked"' : '';
?>
    <p><label for="github-title"><?php _e('Title:'); ?> <input class="widefat" id="github-title" name="github-title" type="text" value="<?php echo $options['title']; ?>" /></label></p>
    <p><label for="github-user"><?php _e('User:'); ?> <input class="widefat" id="github-user" name="github-user" type="text" value="<?php echo $options['user']; ?>" /></label></p>
    <p><label for="github-listlen"><?php _e('Number of projects to show:'); ?> <input class="widefat" id="github-listlen" name="github-listlen" type="text" value="<?php echo $options['listlen']; ?>" /></label></p>
    <p><label for="github-headerelem"><?php _e('Header Element Type:'); ?> <input class="widefat" id="github-headerelem" name="github-headerelem" type="text" value="<?php echo $options['headerelem']; ?>" /></label></p>
    <p>
    <label for="github-theme"><?php _e( 'Theme:' ); ?>
    <select name="github-theme" id="github-theme" class="widefat">
    <option value="white"<?php selected( $options['theme'], 'white' ); ?>><?php _e('White'); ?></option>
    <option value="black"<?php selected( $options['theme'], 'black' ); ?>><?php _e('Black'); ?></option>
    </select>
    </label>
    </p>
    <p><label for="github-showall"><?php _e('Show All Text:'); ?> <input class="widefat" id="github-showall" name="github-showall" type="text" value="<?php echo $options['showall']; ?>" /></label></p>
    <p><label for="github-showfooter"><?php _e('Show Footer'); ?> <input class="checkbox" <?php echo $showfooter ?> type="checkbox" id="github-showfooter" name="github-showfooter" value="<?php echo $options['showfooter']; ?>"/></label></p>
    <input type="hidden" id="github-submit" name="github-submit" value="1" />
    <?php  
}
// ACTIONS
add_action("widgets_init", "register");
register_activation_hook( __FILE__, 'activate');
register_deactivation_hook( __FILE__, 'deactivate');

?>
