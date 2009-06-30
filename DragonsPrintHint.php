<?php
/*
Plugin Name: Dragons Print-Hint
Plugin URI: http://www.kroni.de/?p=766
Description: Einblenden eines Hinweis-Textes beim Ausdrucken.
Version: 0.3.3
Author: Roy Kronester
Author URI: http://www.kronester.com
*/

/*  Copyright 2009  Roy Kronester  (email : Roy@Kronester.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* ----------------------------------------------------------------------------------------------------
   Constants
   ---------------------------------------------------------------------------------------------------- */

define("FDRAG_PHI_FOLDER"		,WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));
define("FDRAG_PHI_CSS_PATH"		,FDRAG_PHI_FOLDER. 'css/');
define("FDRAG_PHI_I18N_PATH"	,FDRAG_PHI_FOLDER. 'i18n/');
define("FDRAG_PHI_I18N_RELPATH"	,str_replace(ABSPATH, "", WP_PLUGIN_DIR.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'i18n/'));
define("FDRAG_PHI_I18N_PLGPATH" ,str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'i18n/');
define("FDRAG_PHI_WP_BLOCK_URL" ,get_bloginfo("wpurl").'/');

define("FDRAG_PHI_REMOVECSS"	,'fdrag_phi_removecss');
define("FDRAG_PHI_HINTTEXT"     ,'fdrag_phi_hinttext');

/* ----------------------------------------------------------------------------------------------------
   Global Variables (define explicit as GLOBAL)
   ---------------------------------------------------------------------------------------------------- */
  
global $fdrag_phi_hinttext;
global $fdrag_phi_removecss;

$fdrag_phi_hinttext  = '';
$fdrag_phi_removecss = '';

/* ----------------------------------------------------------------------------------------------------
   Options initialising on activation
   ---------------------------------------------------------------------------------------------------- */

register_activation_hook(__FILE__, 'fdrag_phi_GetVariables');

/* ----------------------------------------------------------------------------------------------------
   Functions
   ---------------------------------------------------------------------------------------------------- */

function fdrag_phi_Header()
{
	global $fdrag_phi_removecss;
	
	echo '<link rel="stylesheet" type="text/css" href="'.FDRAG_PHI_CSS_PATH.'fdrag_phi_print.css" media="print">';
	echo '<link rel="stylesheet" type="text/css" href="'.FDRAG_PHI_CSS_PATH.'fdrag_phi_screen.css" media="screen">';

	$opt_removecss = FDRAG_PHI_REMOVECSS;

	if (get_option($opt_removecss))
	{
		$fdrag_phi_removecss 	= get_option($opt_removecss);
		
		if (strlen($fdrag_phi_removecss)>2)
		{
		   echo '<style type="text/css" media="print">'. $fdrag_phi_removecss .'{display:none;}</style>';	
		}
	}
	else
	{
		add_option($opt_removecss,'',__('Entfernt CSS-Blöcke (Kommagetrennte Liste)'),'no');
	}
}

function fdrag_phi_ImportStyleSheet()
{
	$StyleSheet = FDRAG_PHI_CSS_PATH . 'fdrag_phi_AdminPrintHint.css';
	
    wp_register_style('DragonHintPrintStyle', $StyleSheet);
    wp_enqueue_style( 'DragonHintPrintStyle');
}

function fdrag_phi_menu() 
{
  	add_submenu_page('options-general.php', 'Dragons PrintHint', 'Dragons Print-Hint', 2, __FILE__, fdrag_phi_dashboard); 	
}

function fdrag_phi_dashboard() 
{
	global $fdrag_phi_hinttext;
	
	echo "<h2>Dragon's Print-Hints</h2>";
	
	// Variablen verarbeiten 
	
	fdrag_phi_GetVariables();
	
	// Submit-Buttons verarbeiten
	
	fdrag_phi_ProcessSubmits();
	
	// Maske aufbauen
	
  	fdrag_phi_Div_Eingabe();
	
	// Variablen sichern
	
	fdrag_phi_SaveVariables();

	// Donation einsetzen
	
	fdrag_phi_donate();
}
	
// ------------------------------------------------------------------------------------------------------------------
// ------------------------------------------------------------------------------------------------------------------

function fdrag_phi_donate()
{
	echo '<div class="fdrag_phi_donate">
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="6387192">
		<input type="image" src="https://www.paypal.com/de_DE/DE/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen – mit PayPal.">
		<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1">
		</form>
		<div>
		';
}

function fdrag_phi_ProcessSubmits()
{
	global $fdrag_phi_hinttext;
	global $fdrag_phi_removecss;
	
	// ---------------------------------------------------------------------------------------------------------------------------------
	// Verarbeitung der Daten
	// ---------------------------------------------------------------------------------------------------------------------------------
		
	if ($_POST) 
	{
		if ($_POST['btn_savehint'])
		{	
			$fdrag_phi_hinttext = htmlspecialchars($_POST['HintText']);
			$fdrag_phi_removecss= htmlspecialchars($_POST['RemoveCssWhilePrinting']);
		}
	}
}
	
function fdrag_phi_GetVariables()
{
	global $current_user;
	global $fdrag_phi_hinttext;
	global $fdrag_phi_removecss;
	
  	get_currentuserinfo();

	if ($current_user->ID = '')
	{
		http_redirect("/wp-login.php", NULL, true, HTTP_REDIRECT_PERM);
	}

	$opt_hinttext  = FDRAG_PHI_HINTTEXT;
	$opt_removecss = FDRAG_PHI_REMOVECSS;

	if (get_option($opt_removecss))
	{
		$fdrag_phi_removecss = get_option($opt_removecss);
	}
	else
	{
		add_option($opt_removecss,'',__('Removes CSS blocks while printing (comma separated list)'),'no');
	}
	
	if (get_option($opt_hinttext))
	{
		$fdrag_phi_hinttext = get_option($opt_hinttext);
	}
	else
	{
		add_option($opt_hinttext,'',__('Hint-Text for Printout (DragonsPrintHint)'),'no');
	}
	
//	print 'A:'.$fdrag_phi_removecss;
}
	
function fdrag_phi_SaveVariables()
{
	global $current_user;
	global $fdrag_phi_hinttext;
	global $fdrag_phi_removecss;
	
  	get_currentuserinfo();

	if ($current_user->ID = '')
	{
		http_redirect("/wp-login.php", NULL, true, HTTP_REDIRECT_PERM);
	}
	
	$opt_hinttext  = FDRAG_PHI_HINTTEXT;
	$opt_removecss = FDRAG_PHI_REMOVECSS;

	update_option($opt_hinttext,$fdrag_phi_hinttext);
	update_option($opt_removecss,$fdrag_phi_removecss);
	
//	print 'B:'.$fdrag_phi_removecss;
}

function fdrag_phi_Div_Eingabe()
{
	global $fdrag_phi_hinttext;
	global $fdrag_phi_removecss;
		
	echo '
		<div class="fdrag_phi_Input">
			<form action="" method="post">
				<ul type="none" id="fdrag_phi_Input_Col">
				    <li>
						<label     for="HintText">' . __("Hint - Text:") . '</label>
						<textarea name="HintText" type="text" id="hinttext"  cols="80" rows="5" class="regular-text code">' . $fdrag_phi_hinttext . '</textarea></li>
					<li>
						<label     for="RemoveCssWhilePrinting">' . __("Hide CSS elements while printing:") . '</label>
						<textarea name="RemoveCssWhilePrinting" type="text" id="removecss" cols="80" rows="5" class="regular-text code">' . $fdrag_phi_removecss . '</textarea></li>
				</ul>
				
				<ul type="none" id="fdrag_phi_Input_Footer"><li><input type="submit" name="btn_savehint" id="btn_savehint" class="button-primary" value="' . __("Save") . '" /></li></ul>
			</form>
		</div>
	';
}

function fdrag_phi_PrintHintFilter($text)
{
	global $fdrag_phi_hinttext;
	
	fdrag_phi_GetVariables();
	
	$HintText = '<div class="fdrag_phi_JustPrint"><p>'.htmlspecialchars_decode($fdrag_phi_hinttext).'</p></div>';
	
	$HintText = $HintText;
	
	$text = $HintText . $text;
	return $text;
}

function fdrag_phi_RemovePrintHint($text)
{
	global $fdrag_phi_hinttext;
	
	fdrag_phi_GetVariables();
	
	$HintText = strip_tags(htmlspecialchars_decode($fdrag_phi_hinttext));
	$Replace  = '';
	
	if (preg_match('/.*$/', $HintText, $Ergebnis))
	{
		$text = substr($text,strpos($text,$Ergebnis[0])+strlen($Ergebnis[0]));
	}
				
	return $text;
}

function fdrag_phi_RemovePrintHint_Excerpt($text)
{
    $raw_excerpt = $text;
		
            $text = get_the_content('');

            $text = strip_shortcodes( $text );

 //           $text = apply_filters('the_content', $text);
 
            $text = str_replace(']]>', ']]&gt;', $text);
            $text = strip_tags($text);
            $excerpt_length = apply_filters('excerpt_length', 55);
            $words = explode(' ', $text, $excerpt_length + 1);
            if (count($words) > $excerpt_length) {
                    array_pop($words);
                    array_push($words, '[...]');
                    $text = implode(' ', $words);
    }
//    return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);					

	return $text;
}

function fdrag_phi_init()
{
	load_plugin_textdomain('dragons-printhint',FDRAG_PHI_I18N_RELPATH,FDRAG_PHI_I18N_PLGPATH);
	
	remove_action("admin_print_styles",'fdrag_phi_ImportStyleSheet'	);
	remove_action("wp_head"	          ,'fdrag_phi_Header'			);
	remove_action('admin_menu'        ,'fdrag_phi_menu'				);
	
	add_action('admin_menu' 		,'fdrag_phi_menu'  				);
	add_action("admin_print_styles"	,'fdrag_phi_ImportStyleSheet' 	);
	add_action('wp_head'	       	,'fdrag_phi_Header'           	);
	
	add_filter('the_content'		,'fdrag_phi_PrintHintFilter',1);
	add_filter('the_content_rss'	,'fdrag_phi_RemovePrintHint',1);
	
	add_filter('the_excerpt'		,'fdrag_phi_RemovePrintHint_Excerpt',1);	
	add_filter('the_excerpt_rss'	,'fdrag_phi_RemovePrintHint_Excerpt',1);
}

/* Install Filter and Actions */

add_action('init' ,'fdrag_phi_init');

?>