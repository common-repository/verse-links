<?php

/*

Plugin Name: Verse Links
Plugin URI: http://www.believersresource.com/downloads/verse-links-102.html
Description: Automatically hyperlinks Bible verse references to websites such as BelieversResource.com, Bible.cc, BibleGateway.com and others.  Can be easily extended to include additional sites.
Version: 1.2
*/

/*
    Copyright 2012 BelieversResource.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

include_once (dirname (__FILE__) . '/sites.php');
include_once (dirname (__FILE__) . '/options.php');

$bookHash=array();
$bookUrlNames = array();
$vl_sites = array();

function get_verse_url($bookNumber, $chapterNumber, $verseNumber, $endChapterNumber, $endVerseNumber)
{
  if ($bookNumber==0) return "";
  global $vl_sites;
  global $bookUrlNames;

  $website=get_option('vl_preferred_website');
  if (strlen($website)<1) $website="BelieversResource.com"; //Provide default option
  $hash=$vl_sites[$website];
  $bookName=str_replace($hash["bookFind"], $hash["bookReplace"], $bookUrlNames[$bookNumber]);
  $result="";
  if ($endVerseNumber>0)
  {
    if (endChapterNumber>0) $result = $hash["chapterRangeUrl"]; else $result = $hash["verseRangeUrl"];
  } else {
    $result = $hash["singleVerseUrl"];
  }
  return str_replace(array("[book]","[chapter]","[verse]","[endchapter]","[endverse]"), array($bookName,$chapterNumber,$verseNumber,$endChapterNumber,$endVerseNumber), $result);
  break;
}

function vl_register_scripts() {
  wp_deregister_script( 'vlHover' );
  wp_register_script( 'vlHover', plugins_url('verselinks.js', __FILE__));
//  wp_register_script( 'vlHover', 'http://www.believersresource.com/verselinks/verselinks.js');
  wp_enqueue_script( 'vlHover' );
  wp_register_style('vlStyle', plugins_url('verselinks.css', __FILE__));
  wp_enqueue_style( 'vlStyle');
}


function link_verses($text)
{
  $vl_tooltip_trans=get_option('vl_tooltip_translation');
  if ($vl_tooltip_trans=="") $vl_tooltip_trans=1;
  populate_book_hash();
  populate_book_url_names();

  $pattern = "/([0-9Ii]{0,3}|Song of)[ ]{0,1}[A-Za-z.]{1,99} \\d{1,3}:\\d{1,3}[ ]{0,1}[-]{0,1}[ ]{0,1}\\d{0,3}/";
  preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);

  for ($i = count($matches[0]) - 1; $i>=0 ; $i--) {
    $match = $matches[0][$i][0];
    $idx = $matches[0][$i][1];

    $array = parse_verse($match);
    $url = get_verse_url($array[0], $array[1], $array[2], $array[3], $array[4]);

    if ($url!="")
    {
      if (substr($match,0,1)==" ") $idx++;  //Make sure the $idx isn't thrown off when it trims
      $match=trim($match);
      $before=substr($text, 0, $idx);
      $after=substr($text, $idx + strlen($match), strlen($text)-$idx-strlen($match));
      $closeAnchor=strpos($after, "</a");
      $openAnchor=strpos($after, "<a");

      $insertLink=false;
      if ($closeAnchor===false && $openAnchor===false) {
        $insertLink=true;
      } else if ($closeAnchor!==false && $openAnchor!==false && $closeAnchor>$openAnchor) {
        $insertLink=true;
      }

      if ($insertLink===true) {
        $text = $before . "<a href=\"" . $url . "\" class=\"verseLink";
        if (get_option('vl_enable_tooltips')!="false") $text .= " " . $array[0] . "_" . $array[1] . "_" . $array[2] . "_" . $array[3] . "_" . $array[4] . "_" . $vl_tooltip_trans;
        $text .= "\"";
        if (get_option('vl_new_window')==="true") $text .= " target=\"_blank\"";
        $text .= ">" . $match . "</a>" . $after;
      }
    }
  }

  return $text;
}


function parse_verse($text)
{
  global $bookHash;

  $firstSplit = split(":",$text); //separate chapter and verse
  $idx = strrpos($firstSplit[0], " ");
  $bookPart = str_replace(".","",substr($firstSplit[0],0,$idx));
  $bookPart=strtolower(trim($bookPart));
  $bookNumber = $bookHash[$bookPart];
  $chapterNumber=(int) trim(substr($firstSplit[0],$idx,strlen($firstSplit[0])-$idx));
  $verseNumber=0;
  $endChapterNumber=0;
  $endVerseNumber=0;

  $verseSplit = split("-",$firstSplit[1]);
  $verseNumber = (int) $verseSplit[0];
  if (count($verseSplit)>1)
  {
    $endSplit = split(":",$verseSplit[1]);
    if (count($endSplit)==1)
    {
      $endVerseNumber=(int) $endSplit[0];
    } else {
      $endChapterNumber=(int) $endSplit[0];
      $endVerseNumber=(int) $endSplit[1];
    }
  }

  return array($bookNumber, $chapterNumber, $verseNumber, $endChapterNumber, $endVerseNumber);
}


function add_book_to_hash($number, $names)
{
  global $bookHash;
  foreach ($names as &$name)
  {
    $bookHash[$name] = $number;
  }
}

function populate_book_hash()
{
  global $bookHash;
  if (count($bookHash)>0) return;
  init_vl_sites();
  add_book_to_hash(1, array("genesis","gen","ge","gn"));
  add_book_to_hash(2, array("exodus","exo","ex","exod"));
  add_book_to_hash(3, array("leviticus","lev","le","lv"));
  add_book_to_hash(4, array("numbers","lev","le","lv"));
  add_book_to_hash(5, array("deuteronomy","deut","dt","de","deu"));
  add_book_to_hash(6, array("joshua","josh","jos","jsh"));
  add_book_to_hash(7, array("judges","judg","jdg","jg","jdgs","jug"));
  add_book_to_hash(8, array("ruth","rth","ru","rut"));
  add_book_to_hash(9, array("1 samuel","1 sam","1 sa","1samuel","1s","i sa","1 sm","1sa","i sam","1sam","i samuel","1st samuel","first samuel"));
  add_book_to_hash(10, array("2 samuel","2 sam","2 sa","2s","ii sa","2 sm","2sa","ii sam","2sam","ii samuel","2samuel","2nd samuel","second samuel"));
  add_book_to_hash(11, array("1 kings","1 kgs","1 ki","1k","i kgs","1kgs","i ki","1ki","i kings","1kings","1st kgs","1st kings","first kings","first kgs","1kin"));
  add_book_to_hash(12, array("2 kings","2 kgs","2 ki","2k","ii kgs","2kgs","ii ki","2ki","ii kings","2kings","2nd kgs","2nd kings","second kings","second kgs","2kin"));
  add_book_to_hash(13, array("1 chronicles","1 chron","1 ch","i ch","1ch","1 chr","i chr","1chr","i chron","1chron","i chronicles","1chronicles","1st chronicles","first chronicles"));
  add_book_to_hash(14, array("2 chronicles","2 chron","2 ch","ii ch","2ch","ii chr","2chr","ii chron","2chron","ii chronicles","2chronicles","2nd chronicles","second chronicles"));
  add_book_to_hash(15, array("ezra","ezr"));
  add_book_to_hash(16, array("nehemiah,neh,ne"));
  add_book_to_hash(17, array("esther","esth","es","est"));
  add_book_to_hash(18, array("job","jb"));
  add_book_to_hash(19, array("psalms","pslm","ps","psalm","psa","psm","pss"));
  add_book_to_hash(20, array("proverbs","prov","pr","prv","pro"));
  add_book_to_hash(21, array("ecclesiastes","eccles","ec","ecc"));
  add_book_to_hash(22, array("song of solomon","song","so","song of songs","sos","son"));
  add_book_to_hash(23, array("isaiah","isa","is"));
  add_book_to_hash(24, array("jeremiah","jer","je","jr"));
  add_book_to_hash(25, array("lamentations","lam","la"));
  add_book_to_hash(26, array("ezekiel","ezek","eze","ezk"));
  add_book_to_hash(27, array("daniel","dan","da","dn"));
  add_book_to_hash(28, array("hosea","hos","ho"));
  add_book_to_hash(29, array("joel","joe","jl"));
  add_book_to_hash(30, array("amos","am","amo"));
  add_book_to_hash(31, array("obadiah","obad","ob","oba"));
  add_book_to_hash(32, array("jonah","jnh","jon"));
  add_book_to_hash(33, array("micah","mic"));
  add_book_to_hash(34, array("nahum,nah,na"));
  add_book_to_hash(35, array("habakkuk","hab","ha"));
  add_book_to_hash(36, array("zephaniah","zeph","zep","zp"));
  add_book_to_hash(37, array("haggai","hag","hg"));
  add_book_to_hash(38, array("zechariah","zech","zec","zc"));
  add_book_to_hash(39, array("malachi","mal","ml"));
  add_book_to_hash(40, array("matthew","matt","mt","mat"));
  add_book_to_hash(41, array("mark","mrk","mk","mr","mak"));
  add_book_to_hash(42, array("luke","luk","lk","lu"));
  add_book_to_hash(43, array("john","jn","jhn","joh"));
  add_book_to_hash(44, array("acts","ac","act"));
  add_book_to_hash(45, array("romans","rom","ro","rm"));
  add_book_to_hash(46, array("1 corinthians","1 cor","1 co","i co","1co","i cor","1cor","i corinthians","1corinthians","1st corinthians","first corinthians","1 corin"));
  add_book_to_hash(47, array("2 corinthians","2 cor","2 co","ii co","2co","ii cor","2cor","ii corinthians","2corinthians","2nd corinthians","second corinthians","2 corin"));
  add_book_to_hash(48, array("galatians","gal","ga"));
  add_book_to_hash(49, array("ephesians","ephes","eph"));
  add_book_to_hash(50, array("philippians","phil","php","phl"));
  add_book_to_hash(51, array("colossians","col"));
  add_book_to_hash(52, array("1 thessalonians","1 thess","1 th","i th","1th","i thes","1thes","i thess","1thess","i thessalonians","1thessalonians","1st thessalonians","first thessalonians","1ts"));
  add_book_to_hash(53, array("2 thessalonians","2 thess","2 th","ii th","2th","ii thes","2thes","ii thess","2thess","ii thessalonians","2thessalonians","2nd thessalonians","second thessalonians","2ts"));
  add_book_to_hash(54, array("1 timothy","1 tim","1 ti","i ti","1ti","i tim","1tim","i timothy","1timothy","1st timothy","first timothy"));
  add_book_to_hash(55, array("2 timothy","2 tim","2 ti","ii ti","2ti","ii tim","2tim","ii timothy","2timothy","2nd timothy","second timothy"));
  add_book_to_hash(56, array("titus","tit","ti"));
  add_book_to_hash(57, array("philemon","philem","phm","phlm"));
  add_book_to_hash(58, array("hebrews","heb"));
  add_book_to_hash(59, array("james","jas","jm"));
  add_book_to_hash(60, array("1 peter","1 pet","1 pe","i pe","1pe","i pet","1pet","i pt","1 pt","1pt","i peter","1peter","1st peter","first peter"));
  add_book_to_hash(61, array("2 peter","2 pet","2 pe","ii pe","2pe","ii pet","2pet","ii pt","2 pt","2pt","ii peter","2peter","2nd peter","second peter"));
  add_book_to_hash(62, array("1 john","1 jn","i jn","1jn","i jo","1jo","i joh","1joh","i jhn","1 jhn","1jhn","i john","1john","1st john","first john"));
  add_book_to_hash(63, array("2 john","2 jn","ii jn","2jn","ii jo","2jo","ii joh","2joh","ii jhn","2 jhn","2jhn","ii john","2john","2nd john","second john"));
  add_book_to_hash(64, array("3 john","3 jn","iii jn","3jn","iii jo","3jo","iii joh","3joh","iii jhn","3 jhn","3jhn","iii john","3john","3rd john","third john"));
  add_book_to_hash(65, array("jude","jud"));
  add_book_to_hash(66, array("revelation","rev","re"));
}

function populate_book_url_names()
{
  global $bookUrlNames;
  $bookUrlNames[1]='genesis';
  $bookUrlNames[2]='exodus';
  $bookUrlNames[3]='leviticus';
  $bookUrlNames[4]='numbers';
  $bookUrlNames[5]='deuteronomy';
  $bookUrlNames[6]='joshue';
  $bookUrlNames[7]='judges';
  $bookUrlNames[8]='ruth';
  $bookUrlNames[9]='1_samuel';
  $bookUrlNames[10]='2_samuel';
  $bookUrlNames[11]='1_kings';
  $bookUrlNames[12]='2_kings';
  $bookUrlNames[13]='1_chronicles';
  $bookUrlNames[14]='2_chronicles';
  $bookUrlNames[15]='ezra';
  $bookUrlNames[16]='nehemiah';
  $bookUrlNames[17]='ester';
  $bookUrlNames[18]='job';
  $bookUrlNames[19]='psalms';
  $bookUrlNames[20]='proverbs';
  $bookUrlNames[21]='ecclesiastes';
  $bookUrlNames[22]='song_of_solomon';
  $bookUrlNames[23]='isiah';
  $bookUrlNames[24]='jeremiah';
  $bookUrlNames[25]='lamentations';
  $bookUrlNames[26]='ezekiel';
  $bookUrlNames[27]='daniel';
  $bookUrlNames[28]='hosea';
  $bookUrlNames[29]='joel';
  $bookUrlNames[30]='amos';
  $bookUrlNames[31]='obadiah';
  $bookUrlNames[32]='jonah';
  $bookUrlNames[33]='micah';
  $bookUrlNames[34]='nahum';
  $bookUrlNames[35]='habakkuk';
  $bookUrlNames[36]='zephaniah';
  $bookUrlNames[37]='haggai';
  $bookUrlNames[38]='zechariah';
  $bookUrlNames[39]='malachi';
  $bookUrlNames[40]='matthew';
  $bookUrlNames[41]='mark';
  $bookUrlNames[42]='luke';
  $bookUrlNames[43]='john';
  $bookUrlNames[44]='acts';
  $bookUrlNames[45]='romans';
  $bookUrlNames[46]='1_corinthians';
  $bookUrlNames[47]='2_corinthians';
  $bookUrlNames[48]='galatians';
  $bookUrlNames[49]='ephesians';
  $bookUrlNames[50]='philippians';
  $bookUrlNames[51]='colossians';
  $bookUrlNames[52]='1_thessalonians';
  $bookUrlNames[53]='2_thessalonians';
  $bookUrlNames[54]='1_timothy';
  $bookUrlNames[55]='2_timothy';
  $bookUrlNames[56]='titus';
  $bookUrlNames[57]='philemon';
  $bookUrlNames[58]='hebrews';
  $bookUrlNames[59]='james';
  $bookUrlNames[60]='1_peter';
  $bookUrlNames[61]='2_peter';
  $bookUrlNames[62]='1_john';
  $bookUrlNames[63]='2_john';
  $bookUrlNames[64]='3_john';
  $bookUrlNames[65]='jude';
  $bookUrlNames[66]='revelation';
}

if (get_option('vl_link_posts')!="false") add_filter('the_content', 'link_verses');
if (get_option('vl_link_comments')!="false") add_filter('comment_text', 'link_verses');
if (get_option('vl_enable_tooltips')!="false") add_action('wp_enqueue_scripts', 'vl_register_scripts');

?>