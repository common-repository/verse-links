<?php
/*
To add a new site, copy one of the $vl_sites lines below and customize it for the site you wish to add.

The parameters are:
  singleVerseUrl - The url pattern for looking up a single verse (ex Genesis 1:1)
  verseRangeUrl - The url pattern for looking up a multiverse passage (ex Genesis 1:1-2)
  chapterRangeUrl - The url pattern for looking up a passage that spans multiple chapters (ex Genesis 1:31-2:1)
  bookFind - An array of terms to match in the book name and replace
  bookReplace - An array of terms to replace the matches from bookFind with.  (ex. Use "_" and "-" to convert "1_john" to "1-john")

You can use the following variables within the url patterns: 
  [book], [chapter], [verse], [endchapter], [endverse]
*/

function init_vl_sites()
{
  global $vl_sites;
  $vl_sites["BelieversResource.com"] = array("singleVerseUrl"=>"http://www.believersresource.com/bible/[book]-[chapter]-[verse].html", "verseRangeUrl"=>"http://www.believersresource.com/bible/[book]-[chapter]-[verse]_[endverse].html", "chapterRangeUrl"=>"http://www.believersresource.com/bible/[book]-[chapter]-[verse]_[endchapter]-[endverse].html", "bookFind"=>array(),"bookReplace"=>array());
  $vl_sites["Bible.cc"] = array("singleVerseUrl"=>"http://www.bible.cc/[book]/[chapter]-[verse].htm", "verseRangeUrl"=>"", "chapterRangeUrl"=>"", "bookFind"=>array("song_of_solomon"),"bookReplace"=>array("songs"));
  $vl_sites["Bible.com"] = array("singleVerseUrl"=>"http://bibleresources.bible.com/passagesearchresults.php?passage1=[book]+[chapter]%3A[verse]", "verseRangeUrl"=>"http://bibleresources.bible.com/passagesearchresults.php?passage1=[book]+[chapter]%3A[verse]-[endverse]", "chapterRangeUrl"=>"http://bibleresources.bible.com/passagesearchresults.php?passage1=[book]+[chapter]%3A[verse]-[endchapter]%3A[endverse]", "bookFind"=>array("_"),"bookReplace"=>array("+"));
  $vl_sites["BibleGateway.com"] = array("singleVerseUrl"=>"http://www.biblegateway.com/passage/?search=[book]+[chapter]:[verse]", "verseRangeUrl"=>"http://www.biblegateway.com/passage/?search=[book]+[chapter]:[verse]-[endverse]", "chapterRangeUrl"=>"http://www.biblegateway.com/passage/?search=[book]+[chapter]:[verse]-[endchapter]:[endverse]", "bookFind"=>array("_"),"bookReplace"=>array("+"));
  $vl_sites["BibleStudyTools.com"] = array("singleVerseUrl"=>"http://www.biblestudytools.com/[book]/[chapter]-[verse].html", "verseRangeUrl"=>"http://www.biblestudytools.com/passage.aspx?q=[book]+[chapter]:[verse]-[endverse]", "chapterRangeUrl"=>"", "bookFind"=>array("_"),"bookReplace"=>array("-"));
  $vl_sites["BlueLetterBible.org"] = array("singleVerseUrl"=>"http://www.blueletterbible.org/Bible.cfm?b=[book]&c=[chapter]#comm/[verse]", "verseRangeUrl"=>"", "chapterRangeUrl"=>"", "bookFind"=>array("_"),"bookReplace"=>array("+"));
  $vl_sites["GodVine.com"] = array("singleVerseUrl"=>"http://www.godvine.com/bible/[book]/[chapter]-[verse]", "verseRangeUrl"=>"", "chapterRangeUrl"=>"", "bookFind"=>array("_"),"bookReplace"=>array("-"));
  $vl_sites["ScriptureText.com"] = array("singleVerseUrl"=>"http://www.scripturetext.com/[book]/[chapter]-[verse].htm", "verseRangeUrl"=>"", "chapterRangeUrl"=>"", "bookFind"=>array("song_of_solomon"),"bookReplace"=>array("songs"));
  $vl_sites["YouVersion.com"] = array("singleVerseUrl"=>"http://www.youversion.com/bible/niv/[book]/[chapter]/[verse]", "verseRangeUrl"=>"http://www.youversion.com/bible/niv/[book]/[chapter]/[verse]-[endverse]", "chapterRangeUrl"=>"", "bookFind"=>array("song_of_solomon"," "),"bookReplace"=>array("song",""));
}
?>