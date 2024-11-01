<?php
/*
Plugin Name: WPNoFollow All Post Links
Plugin URI: http://michaeljacksonben.com/add-nofollow-to-all-links-based-on-category-with-wpnofollow-wordpress-plugin/
Description: WPNoFollow All Post Links plugin will automatically add rel="nofollow" to all links in the post in chosen categories
Version: 1.1
Author: MichaelJacksonBen.com
Author URI: http://MichaelJacksonBen.com
Licence: GPL
*/

// Frontend

function wpnofollow_alter_content($text)
{
	$options = get_option('wpnofollow_category');
	if (!$options)
	{
		$options = array();
	}
	$valid = false;
		
	foreach (get_the_category() AS $cat)
	{
		if (in_array($cat->slug, $options))
		{
			$valid = true;
			break;
		}
	}
	
	if ($valid)
	{
		return str_replace('<a ', '<a rel="nofollow" ', $text);
	}
				 
	return $text;
}

add_filter('the_content', 'wpnofollow_alter_content');

// Backend

add_action('admin_menu', 'wpnofollow_create_menu');

function wpnofollow_create_menu() 
{
	add_submenu_page('options-general.php', 'WPNoFollow Config', 'WPNoFollow Config', 'administrator', __FILE__, 'wpnofollow_settings_page'); 
	add_action( 'admin_init', 'register_wpnofollow_settings');
}

function register_wpnofollow_settings() 
{
	register_setting( 'wpnofollow-settings-group', 'wpnofollow_category');
}

function wpnofollow_settings_page() 
{
?>
	<div class="wrap">
	<h2>
		WPNoFollow All Post Links<br />
		<span style="font-size: 17px; position:relative; top:-10px; font-style: italic; padding-left:120px">Brought to you by <a href="http://MichaelJacksonBen.com">MichaelJacksonBen.com</a></span>
	</h2>	
		
	<form method="post" action="options.php">
	    
		<?php settings_fields('wpnofollow-settings-group'); ?>
	    
	    <table class="form-table">
	        <tr valign="top">
	        <th scope="row">Select post categories</th>
	        <td>
	        <?php
				$options = get_option('wpnofollow_category');
				if (!$options)
				{
					$options = array();
				}
				foreach (get_categories('type=post&hide_empty=0') AS $cat)
				{
					if (in_array($cat->slug, $options))
					{
						$checked = ' checked="checked"';
					} 
					else
					{
						$checked = '';
					}
					echo '<input type="checkbox" name="wpnofollow_category[]" value="' . $cat->slug .'"' . $checked . '> ' . $cat->name .' ';
				}
			?>
			<br /><br />
			<b>Info:</b><br />
			<a href="http://MichaelJacksonBen.com">MichaelJacksonBen.com</a>s WPNoFollow All Post Links plugin will add attribute rel="nofollow" to all links in your all posts in these categories.
				        </tr>
	        
	    </table>
	    
	    <p class="submit">
	    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	    </p>
	
	</form>
	</div>
<?php 
} 
?>
