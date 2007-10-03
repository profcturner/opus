<attach event="onmouseover" handler="rollOver" />
<attach event="onmouseout" handler="rollOff" />
<script type="text/javascript">
function rollOver() {
  //change the colour
  element.className += (element.className?' ':'') + 'CSStoHighlight';
  //change display of child
  for( var x = 0; element.childNodes[x]; x++ ){
    if( element.childNodes[x].tagName == 'UL' ) { element.childNodes[x].className += (element.childNodes[x].className?' ':'') + 'CSStoShow'; }
    if( element.childNodes[x].tagName == 'A' ) { element.childNodes[x].className += (element.childNodes[x].className?' ':'') + 'CSStoHighLink'; }
  }
}

function rollOff() {
  //change the colour
  element.className = element.className.replace(/ ?CSStoHighlight$/,'');
  //change display of child
  for( var x = 0; element.childNodes[x]; x++ ){
    if( element.childNodes[x].tagName == 'UL' ) { element.childNodes[x].className = element.childNodes[x].className.replace(/ ?CSStoShow$/,''); }
    if( element.childNodes[x].tagName == 'A' ) { element.childNodes[x].className = element.childNodes[x].className.replace(/ ?CSStoHighLink$/,''); }
  }
}