<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_categories
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="modulcontent">
	<select id="surf" class="ajaxSelect" name="surf" style="float:right;margin-top: 10px;">
		<option value="0">Tìm theo chủ đề</option>
		<?php foreach($categories as $category): ?>
		<option value="<?php echo $category->id ?>"><?php echo $category->title ?></option>
		<?php endforeach; ?>
	</select>
	
	<div id="mod_comment">
		
	</div>
</div>

<script>
window.addEvent('domready', function(){
	function getLatestDiscuss() {
		var catid = $('surf').get('value');
	   
		var myRequest = new Request.HTML ({
			url: 'index.php',
			onRequest: function(){
				$('mod_comment').set('text', 'loading...');
			},
			onComplete: function(response){
				$('mod_comment').empty().adopt(response);
			},
			data: {
				option: "com_vitabook",
				view: "vitabook",
				task: "getlatest",
				tmpl: "latest",
				format : "raw",
				catid: catid
			}
		}).send();
	}
	
	$('surf').addEvent('change', function(e){
		new Event(e).stop();
		getLatestDiscuss();
	});
	
	getLatestDiscuss();
});
</script>