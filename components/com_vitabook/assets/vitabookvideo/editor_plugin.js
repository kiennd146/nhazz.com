/*
		GNU General Public License version 2 or later; see LICENSE.txt
 @author      JoomVita - http://www.joomvita.com
*/
(function(){tinymce.create("tinymce.plugins.VitabookVideoPlugin",{init:function(a,c){this.editor=a;var b="index.php?option=com_vitabook&task=editor.showVitabookVideo";null===$("vitabookMessageForm")&&(b="../"+b);a.addCommand("mceVitabookVideo",function(){a.windowManager.open({file:b,width:400,height:150,inline:1},{plugin_url:c})});a.addButton("vitabookvideo",{title:"Vitabook Video",cmd:"mceVitabookVideo",image:c+"/img/vitabookvideo.png"})},getInfo:function(){return{longname:"Vitabook Video Embed Plugin",
author:"JoomVita",authorurl:"http://www.joomvita.com",infourl:"http://www.joomvita.com",version:"1.0"}}});tinymce.PluginManager.add("vitabookvideo",tinymce.plugins.VitabookVideoPlugin)})();
