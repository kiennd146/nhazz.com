<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : product.xsl
    Created on : July 30, 2012, 10:34 AM
    Author     : tuyenhung
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns:php="http://php.net/xsl">
    <xsl:output method="html"/>

    <!-- TODO customize transformation rules 
         syntax recommendation http://www.w3.org/TR/xslt 
    -->
    <xsl:template name="removeHtmlTags">
    <xsl:param name="html"/>
    <xsl:choose>
      <xsl:when test="contains($html, '&lt;')">
        <xsl:value-of select="substring-before($html, '&lt;')"/>
        <!-- Recurse through HTML -->
        <xsl:call-template name="removeHtmlTags">
          <xsl:with-param name="html" select="substring-after($html, '&gt;')"/>
        </xsl:call-template>
      </xsl:when>
      <xsl:otherwise>
        <xsl:value-of select="$html"/>
      </xsl:otherwise>
    </xsl:choose>
    </xsl:template>
    <xsl:template match="/EntriesModule">
	<xsl:variable name="limit">
            <xsl:value-of select="limit" />
        </xsl:variable>
        <xsl:variable name="d">
            <xsl:value-of select="entriesLimit"/>
        </xsl:variable>
        <xsl:variable name="sectionid">
            <xsl:value-of select="section"/>
        </xsl:variable>
	<input type="hidden" name="section_id" id="section_id">
             <xsl:attribute name="value">
                 <xsl:value-of select="$sectionid"/>
             </xsl:attribute>
        </input>
        <script type="text/javascript">
	jQuery(document).ready(function() {
	    var id = jQuery("#section_id").val();
	    jQuery.ajax({
	    type: "POST",
	    url: "index.php",
	    data: {
		option: "com_tvtma1080",
		view: "modtvtmasobiproentries",
		task: "getMegamenuProduct",
		format : "ajax",
		sectionProduct: id
            },
	    dataType: "",
	    success: function(request){
	    jQuery("#megamenu-product2").html(request);
	    ddmegamenu.docinit({
	    menuid:'megaanchorlink',
	    dur:500,
	    trigger: 'click',
	    easing:'easeInOutCirc'
	    });
	    }
	    });
	    
	});
	
        </script>
        
        <a id="megaanchorlink" href="#" style="float:right;margin-right:10px;" onclick="return false;" class="selectproduct" rel="megacontent">Tìm theo chủ đề</a>
        <div id="megamenu-product2">
	Menu
	</div>
	<div class="product">
            <xsl:if test="$d &lt;=3">
            <div style="float:left;width:49%">
                <xsl:for-each select="entries/entry">
                     <xsl:if test="position() mod 3 != 0">
                          <div class="leftdiv">
                               <div class="mainimg">
                                    <img> <xsl:attribute name="src" >
                                               <xsl:value-of select="fields/field_hnh_nh/data/@original" />
                                           </xsl:attribute>
                                           <xsl:if test="$limit=0">
                                                      <xsl:attribute name="title">
                                                          <xsl:value-of select="fields/field_miu_t/data" />
                                                      </xsl:attribute>
                                           </xsl:if>
                                    </img>
                                </div>
                                <div class="lcontent">
                                     <div class="imgtitle">
                                         <div class="titlecontent">
                                                 <a>
                                                        <xsl:attribute name="href">
                                                                <xsl:value-of select="url" />
                                                        </xsl:attribute>
                                                        <xsl:variable name="name">
                                                            <xsl:value-of select="name"/>
                                                        </xsl:variable>
                                                        <b><xsl:value-of select="php:function('ucfirst',$name)" /></b>
                                                 </a>
                                         </div>
                                     </div>
                                     <div class="desprice" >
                                            <xsl:variable name="price">
                                                <xsl:value-of select="fields/field_gi/data" />
                                            </xsl:variable>
                                            <xsl:variable name="descr">
                                                <xsl:value-of select="fields/field_miu_t/data" />
                                            </xsl:variable>
                                           <xsl:variable name="pureText">
                                                <xsl:call-template name="removeHtmlTags">
                                                    <xsl:with-param name="html" select="$descr" />
                                                </xsl:call-template>
                                           </xsl:variable>
                                            <xsl:variable name="upcase">
                                                <xsl:value-of select="concat(translate(substring($pureText,1,1), 'aăâbcdđeêfghijklmnoôơpqrstuưvwxyz', 'AĂÂBCDĐEÊFGHIJKLMNOÔƠPQRSTUƯVWXYZ'),substring($pureText,2))" />
                                            </xsl:variable>
                                            <xsl:if test="$limit>0 and $upcase!=''">
                                           <div class="description" style="overflow:hidden;">
                                            <xsl:value-of disable-output-escaping="yes" select="substring($upcase,1,$limit)" />
                                                <b>...</b>
                                           </div>
                                           </xsl:if>
                                            <div class="author"><xsl:value-of select="php:function( 'SobiPro::Txt', 'CREAT_BY' )"/>: 
                                                <a>
                                                        <xsl:attribute name="href">
                                                            <xsl:value-of select="@title"/>
                                                        </xsl:attribute>
                                                        <b>
                                                            <xsl:value-of select="@name"/>
                                                        </b>
                                                </a>
                                            </div>
                                            <xsl:if test="$price!=''">
												<xsl:variable name="gia">
													<xsl:value-of select="fields/field_gi/data"/>
												</xsl:variable>
                                            <div class="price">
                                                <xsl:value-of select="fields/field_gi/label"/>:
                                                <xsl:value-of select="php:function('number_format',$gia,0,',','.')"/> VNĐ
                                            </div>
                                            </xsl:if>
                                    </div>
                                </div>
                         </div>
                      
                       </xsl:if>
                      </xsl:for-each>
            </div>
                      <xsl:for-each select="entries/entry">
                      <xsl:if test="position() mod 3 = 0">
                         <div class="rightdiv">
                                <div class="limg">
                                    <div class="subimg">
                                        <img> 
                                            <xsl:attribute name="src" >
                                               <xsl:value-of select="fields/field_hnh_nh/data/@original" />
                                           </xsl:attribute>
                                           <xsl:if test="$limit=0">
                                                      <xsl:attribute name="title">
                                                          <xsl:value-of select="fields/field_miu_t/data" />
                                                      </xsl:attribute>
                                           </xsl:if>
                                        </img>
                                    </div>
                                </div>
                                <div class="rcontent">
                                    <div class="imgtitle">
                                        <div class="titlecontent">
                                                 <a>
                                            <xsl:attribute name="href">
                                                    <xsl:value-of select="url" />
                                            </xsl:attribute>
                                            <xsl:variable name="name">
                                                            <xsl:value-of select="name"/>
                                                        </xsl:variable>
                                                        <b><xsl:value-of select="php:function('ucfirst',$name)" /></b>
                                                </a>
                                        </div>
                                    </div>
                                    <div class="desprice">
                                        <xsl:variable name="price"><xsl:value-of select="fields/field_gi/data" /></xsl:variable>
                                        <xsl:variable name="descr"><xsl:value-of select="fields/field_miu_t/data" /></xsl:variable>
                                        <xsl:variable name="pureText">
                                                <xsl:call-template name="removeHtmlTags">
                                                    <xsl:with-param name="html" select="$descr" />
                                                </xsl:call-template>
                                           </xsl:variable>
                                           <xsl:variable name="upcase">
                                                <xsl:value-of select="concat(translate(substring($pureText,1,1), 'aăâbcdđeêfghijklmnoôơpqrstuưvwxyz', 'AĂÂBCDĐEÊFGHIJKLMNOÔƠPQRSTUƯVWXYZ'),substring($pureText,2))" />
                                            </xsl:variable>
                                        <xsl:if test="$limit>0 and $upcase!=''">
                                            <div class="descr" style="display:block;">
                                            <xsl:value-of disable-output-escaping="yes" select="substring($upcase,1,$limit)" />
                                            <b>...</b>
                                            </div>
                                        </xsl:if>
                                        <div class="author"><xsl:value-of select="php:function( 'SobiPro::Txt', 'CREAT_BY' )"/>: 
                                            <a>
                                                <xsl:attribute name="href">
                                                    <xsl:value-of select="@title"/>
                                                </xsl:attribute>
                                            <b>
                                                <xsl:value-of select="@name"/>
                                            </b>
                                            </a>
                                        </div>
                                        <xsl:if test="$price!=''">
											<xsl:variable name="gia">
												<xsl:value-of select="fields/field_gi/data"/>
											</xsl:variable>
                                            <div class="price">
                                                <xsl:value-of select="fields/field_gi/label"/>:
                                                <xsl:value-of select="php:function('number_format',$gia,0,',','.')"/> VNĐ
                                            </div>
                                        </xsl:if>
                                   </div>
                                </div>
                          </div>
                      </xsl:if>
                </xsl:for-each>
            </xsl:if>
            <xsl:if test="$d &gt;3">
                <xsl:for-each select="entries/entry">
                      <xsl:if test="(position() mod 2) = 1">
                          <div class="ldiv">
                               <div class="limg">
                                    <div class="subimg">
                                         <img> 
                                              <xsl:attribute name="src" >
                                                <xsl:value-of select="fields/field_hnh_nh/data/@original" />
                                              </xsl:attribute>
                                              <xsl:if test="$limit=0">
                                                   <xsl:attribute name="title">
                                                          <xsl:value-of select="fields/field_miu_t/data" />
                                                   </xsl:attribute>
                                              </xsl:if>
                                         </img>
                                    </div>
                               </div>
                               <div class="rcontent">
                                    <div class="imgtitle">
                                         <div class="titlecontent">
                                              <a>
                                                    <xsl:attribute name="href">
                                                        <xsl:value-of select="url" />
                                                    </xsl:attribute>
                                                    <xsl:variable name="name">
                                                            <xsl:value-of select="name"/>
                                                        </xsl:variable>
                                                        <b><xsl:value-of select="php:function('ucfirst',$name)" /></b>
                                              </a>
                                         </div>
                                    </div>
                                    <div class="desprice">
                                         <xsl:variable name="price"><xsl:value-of select="fields/field_gi/data" /></xsl:variable>
                                         <xsl:variable name="descr"><xsl:value-of select="fields/field_miu_t/data" /></xsl:variable>
                                         <xsl:variable name="pureText">
                                                <xsl:call-template name="removeHtmlTags">
                                                    <xsl:with-param name="html" select="$descr" />
                                                </xsl:call-template>
                                           </xsl:variable>
                                           <xsl:variable name="upcase">
                                                <xsl:value-of select="concat(translate(substring($pureText,1,1), 'aăâbcdđeêfghijklmnoôơpqrstuưvwxyz', 'AĂÂBCDĐEÊFGHIJKLMNOÔƠPQRSTUƯVWXYZ'),substring($pureText,2))" />
                                            </xsl:variable>
                                         <xsl:if test="$limit>0 and $upcase!=''">
                                            <xsl:value-of disable-output-escaping="yes" select="substring($upcase,1,$limit)" />
                                            <b>...</b>
                                         </xsl:if>
                                         <div class="author"><xsl:value-of select="php:function( 'SobiPro::Txt', 'CREAT_BY' )"/>: 
                                            <a>
                                                <xsl:attribute name="href">
                                                    <xsl:value-of select="@title"/>
                                                </xsl:attribute>
                                                <b>
                                                    <xsl:value-of select="@name"/>
                                                </b>
                                            </a>
                                         </div>
                                         <xsl:if test="$price!=''">
											<xsl:variable name="gia">
												<xsl:value-of select="fields/field_gi/data"/>
											</xsl:variable>
                                            <div class="price">
                                                 <xsl:value-of select="fields/field_gi/label"/>:
                                                 <xsl:value-of select="php:function('number_format',$gia,0,',','.')"/> VNĐ
                                            </div>
                                         </xsl:if>
                                    </div>
                               </div>
                          </div>
                     </xsl:if>
                     <xsl:if test="(position() mod 2) = 0">
                          <div class="rdiv">
                               <div class="limg">
                                    <div class="subimg">
                                         <img> 
                                              <xsl:attribute name="src" >
                                                <xsl:value-of select="fields/field_hnh_nh/data/@original" />
                                              </xsl:attribute>
                                              <xsl:if test="$limit=0">
                                                   <xsl:attribute name="title">
                                                          <xsl:value-of select="fields/field_miu_t/data" />
                                                   </xsl:attribute>
                                              </xsl:if>
                                         </img>
                                    </div>
                               </div>
                               <div class="rcontent">
                                    <div class="imgtitle">
                                         <div class="titlecontent">
                                              <a>
                                                 <xsl:attribute name="href">
                                                        <xsl:value-of select="url" />
                                                 </xsl:attribute>
                                                 <xsl:variable name="name">
                                                            <xsl:value-of select="name"/>
                                                        </xsl:variable>
                                                        <b><xsl:value-of select="php:function('ucfirst',$name)" /></b>
                                              </a>
                                         </div>
                                    </div>
                                    <div class="desprice">
                                         <xsl:variable name="price"><xsl:value-of select="fields/field_gi/data" /></xsl:variable>
                                         <xsl:variable name="descr"><xsl:value-of select="fields/field_miu_t/data" /></xsl:variable>
                                         <xsl:variable name="pureText">
                                                <xsl:call-template name="removeHtmlTags">
                                                    <xsl:with-param name="html" select="$descr" />
                                                </xsl:call-template>
                                           </xsl:variable>
                                           <xsl:variable name="upcase">
                                                <xsl:value-of select="concat(translate(substring($pureText,1,1), 'aăâbcdđeêfghijklmnoôơpqrstuưvwxyz', 'AĂÂBCDĐEÊFGHIJKLMNOÔƠPQRSTUƯVWXYZ'),substring($pureText,2))" />
                                            </xsl:variable>
                                         <xsl:if test="$limit>0 and $upcase!=''">
                                            <xsl:value-of disable-output-escaping="yes" select="substring($upcase,1,$limit)" />
                                            <b>...</b>
                                         </xsl:if>
                                         <div class="author"><xsl:value-of select="php:function( 'SobiPro::Txt', 'CREAT_BY' )"/>: 
                                            <a>
                                                <xsl:attribute name="href">
                                                    <xsl:value-of select="@title"/>
                                                </xsl:attribute>
                                                <b>
                                                    <xsl:value-of select="@name"/>
                                                </b>
                                            </a>
                                         </div>
                                         <xsl:if test="$price!=''">
											<xsl:variable name="gia">
												<xsl:value-of select="fields/field_gi/data"/>
											</xsl:variable>
                                            <div class="price">
                                                 <xsl:value-of select="fields/field_gi/label"/>:
                                                 <xsl:value-of select="php:function('number_format',$gia,0,',','.')"/> VNĐ
                                            </div>
                                         </xsl:if>
                                    </div>
                               </div>
                          </div>
                     </xsl:if>
		</xsl:for-each>
            </xsl:if>
	</div>
	</xsl:template>
</xsl:stylesheet>
