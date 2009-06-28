=== Plugin Name ===
Contributors: Roy Kronester
Donate link: http://www.kronester.com
Tags: print, comment, license
Requires at least: 2.7.1
Tested up to: 2.8
Stable tag: 0.3

The plugin prints a hint text

== Description ==

There are several reasons to print some hints on your posts. These hints will never been seen on the screen, but you want it to show on paper.
You can customize the hint text within the settings of the administration panel.

The plugin hooks into *the_content* filter. The *the_excerpt* filter hook is used to remove the hint from displaying on search result page.

You can use any html tag that can be placed within `<p> ... </p>` (a paragraph).

Here is an example (just in german) that demonstrate the usage ob html tags within the hint text.
Tip: The [...] are not part of the hint.

[begin comment]
<center><b>Achtung</b> - Urheberrechtshinweis! <br>
Da dieses Blog unter einer Creative Commons Lizenz steht, 
stellt das Ausdrucken für Sie kein Problem dar. <br>
Bei Webseiten die nicht unter einer solchen oder ähnlichen Lizenz stehen, 
kann das Ausdrucken zu einer Urheberrechtsverletzung führen.</center>
[end comment]

In addition you can set now some (comma delimited) CSS classes / idtags to hide while printing.
If you do this, a <style>-Element is inserted to the header of every page, defining the defined list to be display:none;

== Installation ==

1. Unzip `DragonsPrintHint.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Customize the hint text in admin panel settings menu
4. Verify your text within the print preview of your browser

== Frequently Asked Questions ==

= What kind of CSS classes / ids can i hide? =

You can insert all CSS classes / ids into the input box. These comma separated list
will be inserted in a style tag with display:none;
Be aware that doing some experiments without using your brain can hide the whole page while printing!

== Screenshots ==

1. Customize your hint text
2. Sample text shown in print preview

== Changelog ==

= 0.2 =
* Remove hint text in search results. 
* therefore there is no hint if you print the results!

= 0.3 =
* Changed the file structure (css moved to subfolder)
* Added functionality for hiding CSS classes / ids while printing


== Arbitrary section ==



