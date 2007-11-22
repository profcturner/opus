

var HAconfig = new HTMLArea.Config();


HAconfig.toolbar = [
['formatblock', 'space',
 'bold', 'italic', 'underline', 'separator',
 'copy', 'cut', 'paste', 'space', 'undo', 'redo'],

[ "justifyleft", "justifycenter", "justifyright", "justifyfull", "separator",
  "orderedlist", "unorderedlist", "outdent", "indent", "separator",
  "inserthorizontalrule", "createlink", "htmlmode", "separator",
  "popupeditor" , "separator", "removeformat", "killword" ]
];


	HAconfig.formatblock = {
		"&mdash; format &mdash;"  : "",
		"Heading": "h4",
		"Normal"   : "p",
		"Address"  : "address",
		"Formatted": "pre"
	};
	

var editor = null;
function initEditor() {
  editor = new HTMLArea("HAEditor", HAconfig);
//  editor = new HTMLArea("HAEditor");

  // comment the following two lines to see how customization works
  editor.generate();
}

