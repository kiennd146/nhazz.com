<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
<xsl:output method="xml" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" encoding="UTF-8" />
	<xsl:include href="../common/topmenu.xsl" />
	<xsl:include href="../common/catchooser.xsl" />

	<xsl:template match="/entry_form">
		<div class="SPEntryEdit editImage">
		    <div>
		      <xsl:apply-templates select="menu" />
                        <h1 class="nhazz-title">Upload ảnh</h1>
                        <div>
                            <select id="projectList">
                                
                            </select>
                        </div>
                        <script type="text/javascript">
                        jQuery(document).ready(function($){
                            var target = jQuery('.tvtproject').parent();
                            jQuery('.tvtproject').css({'display' : 'none'});
                            jQuery("#projectList").appendTo(target);
                            jQuery('.tvtproject').attr('readonly', 'readonly');
                            var value = jQuery('.tvtproject').val();
                            jQuery.ajax({
                                        type: "POST",
                                        url: "index.php",
                                        data: { 
                                        option : "com_tvtproject", 
                                        view : "tvtproject", 
                                        task : "createDrop",
                                        format : "ajax",
                                        },
                                        dataType: "json",
                                        success: function(request){
                                            jQuery("#projectList").html(request);
                                            jQuery("#projectList").val(value);
                                        }
                             });
                            
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
                                                jQuery("#projectList").html(request.html);
                                                jQuery("#editProject").hide();
                                            }
                                            jQuery("#projectList").val(0);
                                        }
                                 });
                              jQuery("#projectName").val("");
                            });
                            
                            jQuery('#projectList').live('change',function(){
                                var value = jQuery(this).val();
                                if(value == 'create') {
                                    jQuery("#editProject").show();
                                } else {
                                    jQuery(".tvtproject").val(value);
                                }
                                
                            });
                            
                            jQuery('a.close,.bClose').live('click',function(){
                                jQuery("#editProject").hide();
                                jQuery("#projectList").val(0);
                                
                            });
                            
                            
                        })
                        </script>  
                      
		    </div>
			<div style="clear:both;"/>
			<div class="spFormRowOdd" >  
                        <div class="spFormRowLeft">
                            <label for="SPCatChooserSl">
                            <xsl:value-of select="php:function( 'SobiPro::Txt' , 'TP.CAT_BOX' )" />      
                            </label>
                        </div>          
                        <div class="spFormRowRight">
                            <xsl:value-of 
                            select="php:function( 'TplFunctions::CCSelectList' , entry/categories )"  
                            disable-output-escaping="yes" 
                            />
                        </div>
                        </div>
			
			<div style="clear:both;"/>
			<div>
				<xsl:for-each select="entry/fields/*">
					<xsl:if test="( name() != 'save_button' ) and ( name() != 'cancel_button' )">
						<xsl:variable name="fieldId">
							<xsl:value-of select="name(.)" />
						</xsl:variable>
						<div id="{$fieldId}Container">
							<xsl:attribute name="class">
								<xsl:choose>
									<xsl:when test="position() mod 2">spFormRowEven</xsl:when>
									<xsl:otherwise>spFormRowOdd</xsl:otherwise>
								</xsl:choose>
							</xsl:attribute>
							<xsl:if test="string-length( fee )">
								<div class="spFormPaymentInfo">
									<input name="{$fieldId}Payment" id="{$fieldId}Payment" value="" type="checkbox" class="SPPaymentBox" onclick="SP_ActivatePayment( this )"/>
									<label for="{$fieldId}Payment">
										<xsl:value-of select="fee_msg"></xsl:value-of><br/>
									</label>
									<div style="margin-left:20px;">
										<xsl:value-of select="php:function( 'SobiPro::Txt', 'TP.PAYMENT_ADD' )" />
									</div>
								</div>
							</xsl:if>
							<div class="spFormRowLeft">
								<label for="{$fieldId}">
									<xsl:choose>
										<xsl:when test="string-length( description )">
											<xsl:variable name="desc">
												<xsl:value-of select="description" />
											</xsl:variable>
											<xsl:variable name="label">
												<xsl:value-of select="label" />
											</xsl:variable>
											<xsl:value-of select="php:function( 'SobiPro::Tooltip', $desc, $label )" disable-output-escaping="yes"/>
										</xsl:when>
										<xsl:otherwise>
											<xsl:value-of select="label"/>
										</xsl:otherwise>
									</xsl:choose>
								</label>
							</div>
							<div class="spFormRowRight">
								<xsl:choose>
									<xsl:when test="data/@escaped">
										<xsl:value-of select="data" disable-output-escaping="yes"/>
									</xsl:when>
									<xsl:otherwise>
										<xsl:copy-of select="data/*" />
									</xsl:otherwise>
								</xsl:choose>
								<xsl:text> </xsl:text><xsl:value-of select="@suffix"/>
							</div>
						</div>
					</xsl:if>
				</xsl:for-each>
			</div>
                        <div class='infoUpload'>
                            <table class="infoUploadTable">
                                <thead>
                                    <th>Ảnh nên upload</th>
                                    <th>Ảnh không nên upload</th>
                                </thead>
                                <tr>
                                    <td> Ảnh thuộc về dân cư</td>
                                    <td> Ảnh thương mại hoặc không gian văn phòng</td>
                                </tr>
                                <tr>
                                    <td> Ảnh lớn ( Rộng 500px hoặc lớn hơn )</td>
                                    <td> Ảnh nhỏ</td>
                                </tr>
                                <tr>
                                    <td> Ảnh sau khi đã xử lý </td>
                                    <td> Ảnh ban đầu, chưa chỉnh sửa </td>
                                </tr>
                                <tr>
                                    <td> Ảnh có chất lượng tốt</td>
                                    <td> Ảnh có chất lượng kém</td>
                                </tr>
                            </table>
                            
                        </div>
			<div class="spFormRowFooter">
				<div>
					<xsl:copy-of select="entry/fields/cancel_button/data/*" />
					<xsl:copy-of select="entry/fields/save_button/data/*" />
				</div>
			</div>
			<br/>
			<div style="clear:both;"/>
                        <style>
                            #editProject {
                                position:fixed;  
                                _position:absolute; /* hack for internet explorer 6 */  
                                height:150px;  
                                width:300px;  
                                background:#FFFFFF;  
                                left: 450px;
                                top: 150px;
                                z-index:100; 
                                margin-left: 15px;  

                                /* additional features, can be omitted */
                                border:2px solid #EEEEEE;      
                                padding:15px;  
                                font-size:15px;  
                                -moz-box-shadow: 0 0 5px #EEEEEE;
                                -webkit-box-shadow: 0 0 5px #EEEEEE;
                                box-shadow: 0 0 5px #333;
                                display:none;
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
                                <h2 class="nhazz-title">Tạo mới dự án</h2>
                                <input type="text" name="projectName" id="projectName"/>
                                <input type="button" value="Tạo mới" id="createProject"/>
                                <input type="hidden" name="projectId" value="" id="projectId"/>
                                <input type="button" value="Hủy" class="bClose" id="noChange"/>
                            </div>
                            <a class="close" href="#">Thoát</a>    
                        </div>
		</div>
                
	</xsl:template>
</xsl:stylesheet>
