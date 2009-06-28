<?php
/*
Plugin Name: Dragons Print-Hint
Plugin URI: http://www.kroni.de
Description: Einblenden eines Hinweis-Textes beim Ausdrucken.
Version: 0.2
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

$fdrag_phi_hinttext = 'Hello world!';

function fdrag_phi_Header()
{
	$StylePath = get_bloginfo("wpurl") . '/wp-content/plugins/DragonsPrintHint/';

	echo '<link rel="stylesheet" type="text/css" href="'.$StylePath.'fdrag_phi_print.css" media="print">';
	echo '<link rel="stylesheet" type="text/css" href="'.$StylePath.'fdrag_phi_screen.css" media="screen">';
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
	
	// ---------------------------------------------------------------------------------------------------------------------------------
	// Verarbeitung der Daten
	// ---------------------------------------------------------------------------------------------------------------------------------
		
	if ($_POST) 
	{
		if ($_POST['btn_savehint'])
		{	
			$fdrag_phi_hinttext = htmlspecialchars($_POST['HintText']);
		}
	}
}

function fdrag_phi_ImportStyleSheet()
{
	$StyleSheet = WP_PLUGIN_URL . '/DragonsPrintHint/fdrag_AdminPrintHint.css';
	
    wp_register_style('DragonHintPrintStyle', $StyleSheet);
    wp_enqueue_style( 'DragonHintPrintStyle');
}
	
function fdrag_phi_GetVariables()
{
	global $current_user;
	global $fdrag_phi_hinttext;
	
  	get_currentuserinfo();

	if ($current_user->ID = '')
	{
		http_redirect("/wp-login.php", NULL, true, HTTP_REDIRECT_PERM);
	}

	$opt_hinttext  = "fdrag_phi_hinttext";

	if (get_option($opt_hinttext))
	{
		$fdrag_phi_hinttext = get_option($opt_hinttext);
	}
	else
	{
		add_option($opt_hinttext,'','Hinweis-Text für Druckausgabe (DragonsPrintHint)','no');
	}
	
//	print 'A:'.$fdrag_phi_hinttext;
}
	
function fdrag_phi_SaveVariables()
{
	global $current_user;
	global $fdrag_phi_hinttext;
	
  	get_currentuserinfo();

	if ($current_user->ID = '')
	{
		http_redirect("/wp-login.php", NULL, true, HTTP_REDIRECT_PERM);
	}
	
	$opt_hinttext = "fdrag_phi_hinttext";

	update_option($opt_hinttext,$fdrag_phi_hinttext);
	
//	print 'B:'.$fdrag_phi_hinttext;
}

function fdrag_phi_Div_Eingabe()
{
	global $fdrag_phi_hinttext;
	
	echo '
		<div class="fdrag_phi_Input">
			<form action="" method="post">
				
				<!-- linke Spalte: Feldbeschriftung -->
				<ul type="none" id="fdrag_phi_Input_Col1">
					<li>Hint - Text:</li>
				</ul>
				
				<!-- rechte Spalte: Eingabefelder -->
				<ul type="none" id="fdrag_phi_Input_Col2">
					<li><textarea name="HintText" type="text" id="twitter_Name" cols="80" rows="5" class="regular-text code">' . $fdrag_phi_hinttext . '</textarea></li>
				</ul>
				
				<!-- Submit-Button für Login-Daten -->
				<ul type="none" id="fdrag_phi_Input_Footer"><li><input type="submit" name="btn_savehint" id="btn_savehint" class="button-primary" value="Speichern" /></li></ul>
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

add_action('admin_menu' ,'fdrag_phi_menu'  );

remove_action("admin_print_styles", fdrag_phi_ImportStyleSheet);
add_action("admin_print_styles", fdrag_phi_ImportStyleSheet );

add_action('wp_head'	,'fdrag_phi_Header',1000);
add_filter('the_content','fdrag_phi_PrintHintFilter',1);
add_filter('the_excerpt','fdrag_phi_RemovePrintHint',1);

?>