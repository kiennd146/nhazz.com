<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.mootools');
$baseurl = JURI::base();
$document = &JFactory::getDocument();
$document->addScript( $baseurl.'components/com_tvtproject/js/jquery.js' );
$document->addScript( $baseurl.'components/com_tvtproject/js/jquery.bpopup-0.7.0.min.js' );
$document->addCustomTag( '<script type="text/javascript">jQuery.noConflict();</script>' );
?>


<fieldset class="filters">
    <h1 class="nhazz-title">Quản lý dự án</h1>
    <input type="text" name="projectName" value ="" id="projectName" />
    <input type="button" name="createProject" value ="<?php echo JText::_('CREATE')?>" id="createProject" />
</fieldset>

<table class="category listProject">
    <thead>
    <th><?php echo JText::_('PROJECTNAME')?></th>
        <th><?php echo JText::_('EDIT')?></th>
        <th><?php echo JText::_('DELETE')?></th>
    </thead>
    <?php
    //var_dump($this->list);
    $lists = $this->list;
    //echo "<div class='listProject'>";
    
    foreach ($lists as $value) {
        echo "<tr  id='$value->id'>";
        //echo "<div id='$value->id'><a href='#'>".$value->name."</a>" . " -- <a href='' onclick='return false;' class='edit'>Sửa</a>  --  <a href='' onclick='return false;' class='delete'>Xóa</a><br/></div>";
        //echo "<td>" . "<a href='#'>".$value->name."</a>" .  "</td>";
        $model = $this->getModel();
        $SID = $model->getParams('request.projectId');
        $FieldNid = $model->getParams('request.fieldID');
        $link = JHTML::link(JRoute::_('index.php?option=com_sobipro&task=search.search&sp_search_for='. $value->id .'&' . $FieldNid . '&sid=' . $SID . '&spsearchphrase=exact'), $value->name);
        //$html .= "<td>" . "<a href='#'>".$value->name."</a>" .  "</td>";
        echo "<td>" . $link .  "</td>";
        echo "<td>" . "<a href='' onclick='return false;' class='edit'>Sửa</a>" .  "</td>";
        echo "<td>" . "<a href='' onclick='return false;' class='delete'>Xóa</a>" .  "</td>";
        echo "</tr>";
    }
    //echo "</div>";
    ?>
</table>

<script type="text/javascript">
jQuery(document).ready(function($){
    jQuery('#createProject').live('click',function(){
        jQuery.ajax({
                type: "POST",
                url: "index.php",
                data: { 
                option : "com_tvtproject", 
                view : "tvtproject", 
                task : "createProject",
                format : "",
                projectName : jQuery("#projectName").val()
                },
                dataType: "json",
                success: function(request){
                    alert(request.text);
                    if(request.key == 1) {
                        jQuery('.listProject').html(request.list);
                    }
                }
            });
        jQuery("#projectName").val("");
    });
    
    jQuery('a.delete').live('click',function(){
        var answer = confirm('Bạn có thực sự muốn xóa dự án này không ? ');
	if(answer == false) return false;
        jQuery.ajax({
                type: "POST",
                url: "index.php",
                data: { 
                option : "com_tvtproject", 
                view : "tvtproject", 
                task : "deleteProject",
                format : "",
                id : jQuery(this).parents('tr').attr("id")
                },
                dataType: "json",
                success: function(request){
                    alert(request.text);
                    if(request.key == 1) {
                        jQuery('.listProject').html(request.html);
                    }
                }
            });
        jQuery("#projectName").val("");
    });
    
    jQuery('a.edit').live('click',function(){
         var id = jQuery(this).parents('tr').attr("id");
         jQuery.ajax({
                type: "POST",
                url: "index.php",
                data: { 
                option : "com_tvtproject", 
                view : "tvtproject", 
                task : "getNameProject",
                format : "",
                id : jQuery(this).parents('tr').attr("id")
                },
                dataType: "json",
                success: function(request){
                    if(request.key == 1) {
                        jQuery("#projectEdit").val(request.text);
                        jQuery("#projectId").val(id);
                        jQuery('#editProject').bPopup({
                            modalClose: false,
                            opacity: 0.6,
                            positionStyle: 'fixed' //'fixed' or 'absolute'
                        });
                    } else {
                        alert("Lỗi");
                    }
                }
            });
         
    });
    
    jQuery('#changeName').live('click',function(){
         jQuery.ajax({
                type: "POST",
                url: "index.php",
                data: { 
                option : "com_tvtproject", 
                view : "tvtproject", 
                task : "changeNameProject",
                format : "",
                id : jQuery("#projectId").val(),
                projectName : jQuery("#projectEdit").val()
                },
                dataType: "json",
                success: function(request){
                    jQuery('.bClose').click();
                    if(request.key == 1) {
                        alert(request.text);
                        jQuery('.listProject').html(request.html);
                    } else {
                        alert("Lỗi");
                    }
                }
            });
         
    });
    
})
</script>
<style>
    #editProject {
        width: 200px;
        height: auto;
        background: white;
        border: 1px solid #d9d9d9;
        box-shadow: 3px 3px 3px #383838;
        display: none;
        vertical-align: middle;
    }
    #editProject .inner {
        margin: 30px;
    }
    #projectEdit {
        margin-bottom: 5px;
    }
    #projectName {
        margin-bottom: 10px;
    }
</style>
<div id="editProject">
    <div class="inner">
        <h1 class="nhazz-title">Đổi tên dự án</h1>
        <input type="text" name="projectName" id="projectEdit"/>
        <input type="button" value="Thay đổi" id="changeName"/>
        <input type="hidden" name="projectId" value="" id="projectId"/>
        <input type="button" value="Hủy" class="bClose" id="noChange"/>
    </div>
    
</div>
