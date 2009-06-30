=== Plugin Name ===
Contributors: Roy Kronester
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=6387192
Tags: print, comment, license, hint, copyright, prettyfier
Requires at least: 2.7.1
Text Domain: dragons-printhint
Tested up to: 2.8
Stable tag: 0.3.3

The plugin prints a hint text

== Description ==

There are several reasons to print some hints on your posts. These hints will never been seen on the screen, but you want it to show on paper.
 
= Feature-List: =
 
* Definition of a hint text, only appearing on printouts.
  You can use any html tag that can be placed within `<p> ... </p>` to format your hints.
* Comfortable definition of hint text via admin settings panel.
* Hiding user defined areas of posts / pages - see Example 3 in Arbitrary section
* Definition of CSS classes (.classname) in a comma separated list. These classes don't appear on printouts.
* Definition of CSS IDs (#idname) in a comma separated list. These IDs don't appear on printouts.
* Multilanguage Support: English, Deutsch already implemented

The plugin hooks into *the_content* filter. The *the_excerpt* filter hook is used to remove the hint from displaying on search result page.


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

= 0.3.1 =

CSS subfolder was not deployed with 0.3. Generating new version with css subfolder.

= 0.3.2 =

* FIXED: Remove PrintHint on RSS-Feeds (Excerpt/Content)
* FIXED: Directory structure was wrong in systems which was case sensitive
* NEW:   Insert base support for I18N 

= 0.3.3 =

* NEW:   Insert Multilanguage support: English, Deutsch
* FIXED: Display Excerpt with own filter function allows the 55 words output now
* IMPROVED: More standardized Constants for path definitions 
* IMPROVED: Added init action for initializing and initialize option on activation

== Arbitrary section ==

= Example 1: Hint-Text =
`<center><b>Achtung</b> - Urheberrechtshinweis! <br>
Da dieses Blog unter einer Creative Commons Lizenz steht, 
stellt das Ausdrucken für Sie kein Problem dar. <br>
Bei Webseiten die nicht unter einer solchen oder ähnlichen Lizenz stehen, 
kann das Ausdrucken zu einer Urheberrechtsverletzung führen.</center>`

= Example 2: CSS Class/ID hiding =
`#header, #top, #footer, #bottom, #sidebar,
.commentheader, #commentform`

= Example 3: Hide some portion of a post / page while printing =
Within the post define an area with a userdefined and no more referenced ID.
This ID don't have to be defined within any CSS stylesheet. It's just for Dragons Print-Hint.

`.. some content of your post
<div id="RemoveOnPrint"> ... here is some content of the post ... </div>
.. some more content of your post`

If you define this ID (#RemoveOnPrint) in the "hide css blocks" list (remember: comma separate more then one value)
you can see the post with all content but while printing the div tag with id="RemoveOnPrint" ist hidden.
