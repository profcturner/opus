{* <link href="{$config.opus.url}/css/splashcss.css" rel="stylesheet" type="text/css" />*}

<script type="text/javascript" src='../../opus/javascript/jquery.js'/></script>
<script type="text/javascript" src="../../opus/javascript/jquery_login.js"></script>
		
	<script type="text/javascript">
		{literal}
		$(document).ready(function() 
		{
			$('#slideshow').cycle({
				fx:    'scrollLeft', 
			speed:  6300 
			});
		});
		{/literal}
	</script>
	
		<script type="text/javascript">
	{literal}	
		$(document).ready(function(){
			$('#slide').list_ticker({
				speed:5000,
						effect:'fade'
			})		
		})

		{/literal}
	</script>


  <div id="slideshow">
  <img src="{$config.opus.url}/images/mainslide.png" width="950" height="270" />
  <img src="{$config.opus.url}/images/studentMainSlide.png" width="950" height="270" />		
  		
  
  </div>



<div id="splashinfotop">

   <div id="splashbox">
      
              <p><img src="{$config.opus.url}/images/whatisopus.png" width="285" height="34" /></p>
              
              <p><img src="{$config.opus.url}/images/what_is_opus.jpg"  /></p>	
       
       	<p>OPUS is an online tool for handling all aspects of managing the work based learning and placement process, from helping students find a placement, 
       	recording all the information about the placement, through recording assessment outcomes.</p>
		
	
    </div>
    
  <div id="splashbox">
       
       <p><img src="{$config.opus.url}/images/whatitdelivers.png" width="285" height="34" /></p>
       
       <p><img src="{$config.opus.url}/images/deliver.jpg"  /></p>
       
       <p style="margin-bottom:2px";>OPUS .... :</p>
       	<ul style="padding-left:15px; margin-top:0px;">
       		<li>...</li>
			<li>...</li>
			<li>...</li>
			<li>...</li>
			<li>...</li>
		</ul>
		
       
      
  </div>
  
  <div id="splashbox">
  
      <p><img src="{$config.opus.url}/images/trails.png" width="285" height="34" /></p>
      
      <p><img src="{$config.opus.url}/images/trails.jpg"  /></p>
      <p>The user trails below will help you learn how to drive OPUS: </p>

  {*{include file='splashtrails.tpl'}*}

       
   </div>
	
 </div>

