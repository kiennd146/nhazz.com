/*
		GNU General Public License version 2 or later; see LICENSE.txt
 @author      JoomVita - http://www.joomvita.com
*/
(function(){tinymce.create("tinymce.plugins.VitabookUploadPlugin",{init:function(a,c){var b="index.php?option=com_vitabook&task=editor.showVitabookUpload";null===$("vitabookMessageForm")&&(b="../"+b);a.addCommand("mceVitabookUpload",function(){a.windowManager.open({file:b,width:400,height:250,inline:1},{plugin_url:c})});a.addButton("vitabookupload",{title:"Vitabook Upload",cmd:"mceVitabookUpload",image:c+"/img/vitabookupload.png"})},getInfo:function(){return{longname:"Vitabook Image Upload Plugin",
author:"JoomVita",authorurl:"http://www.joomvita.com",infourl:"http://www.joomvita.com",version:"1.0"}}});tinymce.PluginManager.add("vitabookupload",tinymce.plugins.VitabookUploadPlugin)})();
