<?php
/*
Plugin Name: Dragons Print-Hint
Plugin URI: http://www.kroni.de/?p=766
Description: Einblenden eines Hinweis-Textes beim Ausdrucken.
Version: 0.3.1
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

$fdrag_phi_hinttext  = 'Hello world!';
$fdrag_phi_removecss = '';

function fdrag_phi_Header()
{
	global $fdrag_phi_removecss;
	
	$StylePath = get_bloginfo("wpurl") . '/wp-content/plugins/DragonsPrintHint/css/';

	echo '<link rel="stylesheet" type="text/css" href="'.$StylePath.'fdrag_phi_print.css" media="print">';
	echo '<link rel="stylesheet" type="text/css" href="'.$StylePath.'fdrag_phi_screen.css" media="screen">';

	$opt_removecss = "fdrag_phi_removecss";

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
		add_option($opt_removecss,'','Entfernt CSS-Blöcke (Kommagetrennte Liste)','no');
	}
}

function fdrag_phi_ImportStyleSheet()
{
	$StyleSheet = WP_PLUGIN_URL . '/DragonsPrintHint/css/fdrag_phi_AdminPrintHint.css';
	
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

	$opt_hinttext  = "fdrag_phi_hinttext";
	$opt_removecss = "fdrag_phi_removecss";

	if (get_option($opt_removecss))
	{
		$fdrag_phi_removecss = get_option($opt_removecss);
	}
	else
	{
		add_option($opt_removecss,'','Entfernt CSS-Blöcke (Kommagetrennte Liste)','no');
	}
	
	if (get_option($opt_hinttext))
	{
		$fdrag_phi_hinttext = get_option($opt_hinttext);
	}
	else
	{
		add_option($opt_hinttext,'','Hinweis-Text für Druckausgabe (DragonsPrintHint)','no');
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
	
	$opt_hinttext  = "fdrag_phi_hinttext";
	$opt_removecss = "fdrag_phi_removecss"; 

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
				<ul type="none" id="fdrag_phi_Input_Col2">
				    <li>
						<label     for="HintText">Hint - Text:</label>
						<textarea name="HintText"             type="text" id="hinttext"  cols="80" rows="5" class="regular-text code">' . $fdrag_phi_hinttext . '</textarea></li>
					<li>
						<label     for="RemoveCssWhilePrinting">Hide CSS elements while printing:</label>
						<textarea name="RemoveCssWhilePrinting" type="text" id="removecss" cols="80" rows="5" class="regular-text code">' . $fdrag_phi_removecss . '</textarea></li>
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


remove_action("admin_print_styles", fdrag_phi_ImportStyleSheet);
remove_action("wp_head"	          , fdrag_phi_Header);
remove_action('admin_menu'        , fdrag_phi_menu  );

add_action('admin_menu' ,'fdrag_phi_menu'  );
add_action("admin_print_styles", fdrag_phi_ImportStyleSheet    );
add_action('wp_head'	       , fdrag_phi_Header           ,10);

add_filter('the_content','fdrag_phi_PrintHintFilter',1);
add_filter('the_excerpt','fdrag_phi_RemovePrintHint',1);

?>