=== Verse Links ===
Contributors: BelieversResource
Tags: Bible, verse, passage, reference, link
Requires at least: 3.3.1
Tested up to: 3.3.1
Stable tag: 1.2

Finds all references to Bible verses within blog posts and links them to a reference site of your choice.

== Description ==

Verse Links is an easily extendable plugin that will find all references to Bible verses and passages within blog
posts and comments and automatically link them to a verse lookup site of your choice.  For example, Genesis 1:1-2 becomes 
<a href="http://www.believersresource.com/bible/genesis-1-1_2.html">Genesis 1:1-2</a>.  Common abbreviations and book
names variants also work, such as <a href="http://www.believersresource.com/bible/genesis-1-1.html">Gen 1:1</a>, 
<a href="http://www.believersresource.com/bible/1_timothy-2-3.html">First Timothy 2:3</a> and <a href="http://www.believersresource.com/bible/1_timothy-2-3.html">1 Tim 2:3</a>.

The sites included by default are:

*  <a href="http://www.believersresource.com/">BelieversResource.com</a>
*  <a href="http://www.bible.cc/">Bible.cc</a>
*  <a href="http://www.bible.cc/">Bible.com</a>
*  <a href="http://www.biblegateway.com/">BibleGateway.com</a>
*  <a href="http://www.biblestudytools.com/">BibleStudyTools.com</a>
*  <a href="http://www.blueletterbible.org/">BlueLetterBible.org</a>
*  <a href="http://www.godvine.com/">GodVine.com</a>
*  <a href="http://www.scripturetext.com/">ScriptureText.com</a>
*  <a href="http://www.scripturetext.com/">YouVersion.com</a>

To add additional sites you simply need to provide the url patterns for the site in the sites.php file.

In addition to linking the verses to reference sites you have the option to enable tooltips which will display the verse text when visitors mouse over the references
on your site.  You can adjust the appearance of these tooltips by editing the verselinks.css file.

For more information about this plugin and support, visit <a href="http://www.believersresource.com/downloads/verse-links-102.html">BelieversResource.com</a>.

== Installation ==

To Install:

1. Upload the `verselinks` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Select the reference site you wish to use in the Verse Links settings.
1. If you wish to alter the tooltip style, edit verselinks.css.

== Screenshots ==
1. The settings page.
2. An example tooltip.

== Changelog ==

= 1.2 - February 27, 2012 =

* Added support for tooltips
* Added "verseLink" CSS class name to links to enable styling.
* Added Bible.com as one of the default sites.

= 1.1 - February 24, 2012 =

* Added separate controls for linking blog posts and comments.
* Simplified the process of adding additional sites.
* Added YouVersion.com to the list of default sites.
* Fixed verse matching to not include leading and trailing spaces.