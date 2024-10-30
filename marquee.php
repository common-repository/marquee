<?php
/*
Plugin Name: Marquee
Plugin URI: http://wordpress.org/extend/plugins/marquee/
Description: This plugin will let you embed, in the desired section of your page, a scrolling text fully configurable. Thanks to Markus Bordihn (markusbordihn.de) for its cool Scroller Script used in this plugin.
Author: Pierre Sudarovich
Author URI: http://www.monblogamoua.fr/
Version: 2.76
*/

define('marquee', 'marquee/lang/marquee');

$level_for_admin=10;
$ver="2.75";

if(function_exists('load_plugin_textdomain')) load_plugin_textdomain(marquee);

function marquee_add_to_head() {
global $ver;
$marquee_opt = get_option('marquee_options');
$myscrollingText=trim($marquee_opt['msg']);
$in=($marquee_opt['in']!="") ? $marquee_opt['in'] : "0==0";
$cookie_marquee = (isset($_COOKIE['Marquee'])) ? $_COOKIE['Marquee'] : "";
	if($myscrollingText!="" && $marquee_opt['show'] && $cookie_marquee=="") {
		if (eval("return ".$in.";")) {
			echo '
			<!-- Marquee version '.$ver.' -->
			<script type="text/javascript" src="'.get_bloginfo('wpurl').'/wp-content/plugins/marquee/scroller.js"></script>
			<link rel="stylesheet" type="text/css" href="'.get_bloginfo('wpurl').'/wp-content/plugins/marquee/marquee.css"/>
			<!-- end Marquee -->
			';
		}
	}
}

function Marquee_admin() {
global $level_for_admin, $ver, $wp_roles;

$marquee_opt = get_option('marquee_options');
$level_for_admin = ($marquee_opt['level_for_admin']!="") ? $marquee_opt['level_for_admin'] : $level_for_admin;

if(!current_user_can('level_'.$level_for_admin)) {
	echo '<div class="wrap"><h2>' . __("No Access for you!",marquee) .'</h2></div>';
	return;
}

//si aucune option on installe...
if (!$marquee_opt) {
	$marquee_opt = array (
		'level_for_admin' => $level_for_admin,
		'msg' => '',
		'in' => '',
		'where' => 'content',
		'size' => 9,
		'dir' => 'left',
		'height' => 18,
		'width' => '100%',
		'speed' => 90,
		'bg' => 'transparent',
		'color' => '#000',
		'over' => 1,
		'show' => 1,
	);
	ksort ($marquee_opt);
	add_option ('marquee_options', $marquee_opt);
}

if(isset($_POST['Submit'])) {
	$level_for_admin=($_POST['admin_user_level']!="") ? intval($_POST['admin_user_level']) : $level_for_admin;
	$marquee_opt['level_for_admin']=$level_for_admin;
	$marquee_opt['msg']= trim(stripslashes($_POST['msg']));
	$marquee_opt['where']= ($_POST['where']=="") ? "content" : $_POST['where'];
	$marquee_opt['size']= ($_POST['fontsize']=="") ? 9 : intval($_POST['fontsize']);
	$marquee_opt['dir']= $_POST['direction'];
	$marquee_opt['height']= ($_POST['height']=="") ? 18 : intval($_POST['height']);
	$marquee_opt['width']= ($_POST['width']=="") ? "100%" : $_POST['width'];

	if(strpos($marquee_opt['width'],"px")==false && strpos($marquee_opt['width'],"%")==false)
		$marquee_opt['width'].=(intval($marquee_opt['width'])<=100) ? "%" : "px";

	$marquee_opt['speed']= ($_POST['speed']=="") ? 90 : intval($_POST['speed']);
	$marquee_opt['bg']= ($_POST['bg']=="") ? "transparent" : $_POST['bg'];
	$marquee_opt['color']= ($_POST['color']=="") ? "#000" : $_POST['color'];
	$marquee_opt['over']= ($_POST['over']=="") ? "0" : "1";
	$marquee_opt['show']= ($_POST['show']=="") ? "0" : "1";
	
	$in_array = $_POST['elements'];
	if(is_array($in_array)) {
		$in="";
		foreach($in_array as $selectValue){
			if($selectValue=="") {
				$in="";
				break;
			}
			$in.=$selectValue." || ";
		}
		$in=substr($in,0,-4);
	}
	else $in="";
	$marquee_opt['in']=$in;

	ksort ($marquee_opt);
	update_option ('marquee_options', $marquee_opt);
}
$Marquee = trim(htmlspecialchars($marquee_opt['msg'],ENT_QUOTES));
$Marquee_over = ($marquee_opt['over']==0) ? '' : ' checked="true"';
$Marquee_show = ($marquee_opt['show']==0) ? '' : ' checked="true"';
$width=$marquee_opt['width'];
$Path=get_bloginfo('wpurl');
$in = explode(" || ", $marquee_opt['in']);
?>
<script type="text/javascript">
function previewSize() {
document.getElementById("msg").innerHTML=document.getElementById("original_text").value;
document.getElementById("msg").style.fontSize=parseInt(document.getElementById("fontsize").value)+"pt";
document.getElementById("msg").style.lineHeight=parseInt(document.getElementById("fontsize").value)+"pt";
document.getElementById("msg").style.background=document.getElementById("bg").value;
document.getElementById("msg").style.color=document.getElementById("color").value;
document.getElementById("msg").style.height=parseInt(document.getElementById("height").value)+"px";
document.getElementById("msg").style.width=document.getElementById("width").value;
}
</script>
<div class="wrap">
	<form method="post" action="">
	<table width="100%" class="editform" border="0">
		<tr>
			<td width="260"><h2><?php echo "Marquee v. ". $ver; ?></h2></td>
			<td valign="top"><div id="msg" style="width:<?php echo $width; ?>;overflow:hidden;border:1px solid #ccc"></div></td>
		</tr>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" 
			alt="" title="<?php _e('Enter here your marquee message, HTML tags are permit...',marquee); ?>"/><b><?php _e('Marquee content:',marquee); ?></b></td>
			<td><textarea onkeyup="previewSize()" id="original_text" name="msg" style="width:100%;height:44px;" rows="2"><?php echo $Marquee; ?></textarea></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>

		<?php if(current_user_can('level_10')) { ?>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" 
			alt="" title="<?php _e('Enter here the required user level to edit the Marquee',marquee); ?>"/><b><?php _e('Marquee management:',marquee); ?></b></td>
			<td>			
			<font color="red"><?php _e('<b>Who can administrate the Marquee:</b>',marquee);?></font>
			<select name="admin_user_level">
			<?php
			// Piece of code from "Role Manager" plugin for role and level part. Thanks to -> Thomas Schneider : http://www.im-web-gefunden.de/wordpress-plugins/role-manager/
			$array_admin=array();
		foreach($wp_roles->role_names as $roledex => $rolename) {
			$role = $wp_roles->get_role($roledex);
			$role_user_level = array_reduce(array_keys($role->capabilities), array('WP_User', 'level_reduction'), 0);
			if(!in_array($role_user_level, $array_admin)) {
				array_push($array_admin, $role_user_level);
				if($role_user_level >0) {
					$selected=($level_for_admin==$role_user_level) ? ' selected="true"' : '';
					echo '<option value="'.$role_user_level.'"'.$selected.'>'.$rolename." (".__("level",marquee) ." ". $role_user_level.')</option>
				';
				}
			}
		} ?>
			</select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<?php }; ?>

		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" alt="" 
			title="<?php _e('Define the places where the message will be shown (you can select several pages with the help of the Ctrl key. By default : Everywhere.',marquee); ?>"/><b><?php _e('On wich page the message will be shown:',marquee); ?></b></td>
			<td><select name="elements[]" multiple style="height:140px;">
			<option value=""<?php if (in_array("", $in)) echo ' selected="true"';?>><?php _e('Everywhere',marquee); ?></option>
			<option value="is_front_page()"<?php if (in_array("is_front_page()", $in)) echo ' selected="true"';?>><?php _e('On the homepage',marquee); ?></option>
			<option value="is_page()"<?php if (in_array("is_page()", $in)) echo ' selected="true"';?>><?php _e('On pages',marquee); ?></option>
			<option value="is_search()"<?php if (in_array("is_search()", $in)) echo ' selected="true"';?>><?php _e('On search page',marquee); ?></option>
			<option value="is_singular()"<?php if (in_array("is_singular()", $in)) echo ' selected="true"';?>><?php _e('On single pages',marquee); ?></option>
			<option value="is_archive()"<?php if (in_array("is_archive()", $in)) echo ' selected="true"';?>><?php _e('On archives pages',marquee); ?></option>
			<option value="is_category()"<?php if (in_array("is_category()", $in)) echo ' selected="true"';?>><?php _e('On categories page',marquee); ?></option>
			</select>
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" alt="" 
			title="<?php _e('Define the part of the page where the message will be shown (specify the id). By default',marquee); ?> content."/><b><?php _e('Where to display the message:',marquee); ?></b></td>
			<td><input type="text" name="where" size="10" value="<?php echo $marquee_opt['where'];?>"/></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" alt="" 
			title="<?php _e('Define the size of the font in pt. By default',marquee); ?> 9."/><b><?php _e('Font size:',marquee); ?></b></td>
			<td><input type="text" id="fontsize" name="fontsize" size="3" maxlength="3" onkeyup="previewSize();" 
			onfocus="previewSize();" onblur="if(this.value=='') this.value=9;previewSize();" value="<?php echo $marquee_opt['size'];?>"/></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" alt="" 
			title="<?php _e('Select the direction of the scrolling text.',marquee); ?>"/><b><?php _e('Direction:',marquee); ?></b></td>
			<td><select name="direction">
				<option value="left"<?php if($marquee_opt['dir']=="left") echo ' selected="true"';?>><?php _e('Left/Right',marquee); ?></option>
				<option value="right"<?php if($marquee_opt['dir']=="right") echo ' selected="true"';?>><?php _e('Right/Left',marquee); ?></option>
				<option value="up"<?php if($marquee_opt['dir']=="up") echo ' selected="true"';?>><?php _e('Top/Bottom',marquee); ?></option>
				<option value="down"<?php if($marquee_opt['dir']=="down") echo ' selected="true"';?>><?php _e('Bottom/Top',marquee); ?></option>
				<option value="fix"<?php if($marquee_opt['dir']=="fix") echo ' selected="true"';?>><?php _e('Fixed',marquee); ?></option>
			</select></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" alt="" 
			title="<?php _e('1 slow, 100 quick. By default',marquee); ?> 90."/><b><?php _e('Scrolling speed:',marquee); ?></b></td>
			<td><input type="text" name="speed" size="3" maxlength="3" value="<?php echo $marquee_opt['speed'];?>" onblur="if(this.value=='') this.value=90;"/></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" alt="" 
			title="<?php _e('Width in pixels or in percentage. By default',marquee); ?> 100%."/><b><?php _e('Width:',marquee); ?></b></td>
			<td><input type="text" id="width" name="width" size="4" maxlength="6" onkeyup="previewSize();" onfocus="previewSize();" 
			onblur="if(this.value=='') this.value='100%';previewSize();" value="<?php echo $marquee_opt['width'];?>"/></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" alt=""
			title="<?php _e('Height in pixels. By default',marquee); ?> 18."/><b><?php _e('Height:',marquee); ?></b></td>
			<td><input type="text" id="height" name="height" size="3" maxlength="3" onkeyup="previewSize();" onfocus="previewSize();" 
			onblur="if(this.value=='') this.value=18;previewSize();" value="<?php echo $marquee_opt['height']; ?>"/></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16"
			alt="" title="<?php _e('Possible value : transparent, color in format : #ffffff or url(image_url) and possibly a color in format',marquee); ?> #000000."/><b><?php _e('Background color and/or background image:',marquee); ?></b></td>
			<td><input type="text" id="bg" name="bg" style="width:100%;" onkeyup="previewSize();" onfocus="previewSize();" 
			onblur="if(this.value=='') this.value='transparent';previewSize();" value="<?php echo $marquee_opt['bg']; ?>"/></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" alt="" 
			title="<?php _e('In format',marquee); ?> #000000"/><b><?php _e('Text color:',marquee); ?></b></td>
			<td><input type="text" id="color" name="color" size="9" maxlength="7" onkeyup="previewSize();" onfocus="previewSize();" 
			onblur="if(this.value=='') this.value='#000';previewSize();" value="<?php echo $marquee_opt['color']; ?>"/></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" alt="" 
			title="<?php _e('Does the scrolling text have to stop on mouse over?',marquee); ?>"/><b><?php _e('Stop on mouse over:',marquee); ?></b></td>
			<td><input type="checkbox" name="over" value="1"<?php echo $Marquee_over; ?>/></td>
		</tr>
		<tr>
			<td colspan="2"><hr/></td>
		</tr>
		<tr>
			<td><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/info.png" style="float:right;" width="16" height="16" alt="" 
			title="<?php _e('Display scrolling text or not?',marquee); ?>"/><b><?php _e('Display text:',marquee); ?></b></td>
			<td><input type="checkbox" name="show" value="1"<?php echo $Marquee_show; ?>/></td>
		</tr>
	</table>
	<p class="submit"><input type="submit" name="Submit" value="<?php _e('Save',marquee); ?>"/></p>
</form>
</div>
<script type="text/javascript">
	previewSize();
</script>
<?php }

function Affiche_Marquee() {
global $level_for_admin;

$marquee_opt = get_option('marquee_options');
$Path=get_bloginfo('wpurl');

$level_for_admin = ($marquee_opt['level_for_admin']!="") ? $marquee_opt['level_for_admin'] : $level_for_admin;
$myscrollingText=trim(str_replace("\r\n"," ",$marquee_opt['msg']));
$direction=$marquee_opt['dir'];
$height = $marquee_opt['height'];
$width = $marquee_opt['width'];
$speed = $marquee_opt['speed'];
$bgcolor = "background:" . $marquee_opt['bg'];
$color   = $marquee_opt['color'];
$canstop = ($marquee_opt['over']==0) ? '' : ' jscroller2_mousemove';
$font_size=$marquee_opt['size'];
$Where=$marquee_opt['where'];
$attr=$height."px";
$in=($marquee_opt['in']!="") ? $marquee_opt['in'] : "0==0";

	if($myscrollingText!="" && $marquee_opt['show'] && $_COOKIE['Marquee']=="") {
		if (eval("return ".$in.";")) { ?>

		<div id="the_container" style="display:none;width:<?php echo $width;?>;margin:auto;">
		<?php if(current_user_can('level_'.$level_for_admin)) { ?>
			<div id="Edit_Marquee">
				<a href="<?php echo $Path;?>/wp-admin/themes.php?page=marquee/marquee.php"><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/configure.png" width="16" height="16" alt="" title="<?php _e('Click here to edit the message.',marquee); ?>"/></a>
			</div>
		<?php } ?>
			<div id="Hide_Marquee">
				<a href="#" style="position:absolute;" onclick="Hide_Marquee();return false;"><img src="<?php echo $Path;?>/wp-content/plugins/marquee/img/exit.png" width="16" height="16" alt="" title="<?php _e('Click here to hide the message.',marquee); ?>"/></a>
			</div>
			<div id="marquee_content" style="overflow:hidden;height:<?php echo $attr;?>;<?php echo $bgcolor;?>;color:<?php echo $color;?>">

			</div>
		</div>
		<script type="text/javascript">
		//<![CDATA[
		var insertO = document.getElementById("<?php echo $Where;?>");

		if(typeof window.addEventListener != 'undefined') {	//.. gecko, safari, konqueror and standard
		window.addEventListener('load', MarqueeLoaded, false);
		}
		else if(typeof document.addEventListener != 'undefined') {	//.. opera 7
		document.addEventListener('load', MarqueeLoaded, false);
		}
		else if(typeof window.attachEvent != 'undefined') {	//.. win/ie
		window.attachEvent('onload', MarqueeLoaded);
		}
		function MarqueeLoaded() {
			if(insertO) {
				var scrollers = document.getElementById('marquee_content');

				var 
				scroll_body = document.createElement('div'),
				scroll = document.createElement('div');

				scroll_body.style.width = '100%';
				scroll_body.style.height = '<?php echo $attr;?>';
				scroll.className = 'jscroller2_ignoreleave jscroller2_<?php echo $direction;?> jscroller2_speed-<?php echo $speed;?><?php echo $canstop;?>';
				scroll.style.fontSize = '<?php echo $font_size;?>pt';
				scroll.style.lineHeight = '<?php echo $attr;?>';
				scroll.style.margin = 0;
				scroll.innerHTML = '<?php echo str_replace(array("'","/"),array("\'","\\/"),convert_smilies($myscrollingText)); ?>';
				scroll_body.appendChild(scroll);
				scrollers.appendChild(scroll_body);

				insertO.insertBefore(document.getElementById("the_container"), insertO.firstChild);
				document.getElementById("the_container").style.display="";
				<?php if($direction!="fix") {
				echo 'ByRei_jScroller2.add(scroll,"'.$direction.'");
				ByRei_jScroller2.start();
				'; } ?>
			}
		}
		//]]>
		</script>
		<?php
		}
	}
}

function add_Marquee_link($links, $file) {
    if (strstr($file, 'marquee/marquee.php')) {
        $settings_link = '<a href="themes.php?page=marquee/marquee.php">'. __('Settings').'</a>';
		array_unshift( $links, $settings_link );
    }
    return $links;
}

function Marquee_Init() {
global $level_for_admin;
	$marquee_opt = get_option('marquee_options');
	$level_for_admin = ($marquee_opt['level_for_admin']!="") ? $marquee_opt['level_for_admin'] : $level_for_admin;
	add_theme_page('Marquee', 'Marquee', $level_for_admin, "marquee/".basename(__FILE__), 'Marquee_admin');
}
add_action('admin_menu', 'Marquee_Init');
add_action('wp_head', 'marquee_add_to_head');
add_action('wp_footer', 'Affiche_Marquee');
add_filter('plugin_action_links', 'add_Marquee_link', 10, 2);
?>