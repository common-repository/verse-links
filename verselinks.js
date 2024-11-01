function parseVerseLinks() {
    var elems = getElementsByClassName('verseLink');
    for (i = 0; i < elems.length; i++) {
        var el = elems[i];
        var parts = el.className.replace('verseLink ', '');
        el.onmouseover = new Function("showVerse(this,'" + parts + "');");
        el.onmouseout = new Function("hideVerse(this,'" + parts + "');");
    }
}

function hideVerse(el, parts) {
    var verseText = document.getElementById("verse_" + parts);
    if (verseText != null) verseText.style.display = 'none';
}

function styleVerse(el, verseText) {
    var pos = findPos(el);
    verseText.style.display = 'block';
    var x = pos[0];
    var y = pos[1] - verseText.offsetHeight;
    

    
    if (y<0) y=0;
    if (x > screen.width) x = screen.width - verseText.offsetWidth;

    verseText.style.top = y + 'px';
    verseText.style.left = x + 'px';
}

function showVerse(el, parts) {
    if (document.getElementById("verse_" + parts) == null) {
        var div = document.createElement('div');
        div.style.display = 'none';
        div.id = "verse_" + parts;
        div.className = 'vlText';
        document.body.appendChild(div);
    }
    var verseText = document.getElementById("verse_" + parts);
    if (verseText.innerHTML == '') {
        var url = "http://www.believersresource.com/verselinks/passage.aspx?parts=" + parts
        var isIE = window.XDomainRequest ? true : false;
        if (isIE)
        {
          req = new window.XDomainRequest();
          req.onload = function() { verseText.innerHTML = req.responseText; styleVerse(el, verseText); }
          req.open("GET", url, true);
          req.send();
        } else {
          req = new XMLHttpRequest();
          req.onreadystatechange = function() {
              if (req.readyState == 4 && req.status == 200) {
                  verseText.innerHTML = req.responseText;
                  styleVerse(el, verseText);
              }
          }
          req.open("GET", url, true);
          req.send();
        }
    } else {
        styleVerse(el, verseText);
    }
    
}

function findPos(obj) {
    var curleft = curtop = 0;
    if (obj.offsetParent) {
        curleft = obj.offsetLeft
        curtop = obj.offsetTop
        while (obj = obj.offsetParent) {
            curleft += obj.offsetLeft
            curtop += obj.offsetTop
        }
    }
    return [curleft, curtop];
}

function getElementsByClassName(className) {
    if (typeof (document.getElementsByClassName) == 'function') return document.getElementsByClassName(className);
    var a = [];
    var re = new RegExp('(^| )' + className + '( |$)');
    var els = document.getElementsByTagName("*");
    for (var i = 0, j = els.length; i < j; i++)
        if (re.test(els[i].className)) a.push(els[i]);
    return a;
}


window.addEventListener ? 
window.addEventListener("load",parseVerseLinks,false) : 
window.attachEvent && window.attachEvent("onload",parseVerseLinks);
