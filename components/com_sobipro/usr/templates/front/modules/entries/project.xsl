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
        <xsl:variable name="useridstr">
            <xsl:value-of select="useridstr"/>
        </xsl:variable>
        <xsl:variable name="fid">
            <xsl:value-of select="fid"/>
        </xsl:variable>
        <xsl:if test="profiles">
          <select class="ajaxSelect" id="sprf" name="sprf" style="float:right;margin-top: 10px;width:115px;display:block;">
              <option value="all" style="display:block">
                  <xsl:text>Tìm theo chủ đề </xsl:text>
              </option>
              <xsl:for-each select="profiles/profile">
                  <option style="display:block">
                      <xsl:attribute name="value">
                          <xsl:value-of select="."/>
                      </xsl:attribute>
                      <xsl:value-of select="."/>
                  </option>
             </xsl:for-each>
          </select>  
        </xsl:if>
	<div class="project" id="project">
            <xsl:if test="$d &lt;=3">
                <xsl:for-each select="entries/entry">
                     <xsl:if test="position() &lt;= 1">
                          <div class="leftdiv">
                               <div class="mainimg">
                                   <a> 
                                       <xsl:attribute name="href">
                                           <xsl:value-of select="@linkpro"/>
                                       </xsl:attribute>
                                    <img> <xsl:attribute name="src" >
                                               <!--xsl:value-of select="fields/field_hnh_nh/data/@original" /-->
                                               <xsl:value-of select="@imgcache" />
                                               
                                           </xsl:attribute>
                                           <xsl:if test="$limit=0">
                                                      <xsl:attribute name="title">
                                                          <xsl:value-of select="@business" />
                                                      </xsl:attribute>
                                           </xsl:if>
                                    </img>
                                   </a>
                                </div>
                                <div class="lcontent">
                                     <div class="imgtitle">
                                         <div class="avatar" style="height:32px;width:32px;float:left;">
                                             <a>
                                                 <xsl:attribute name="href">
                                                     <xsl:value-of select="@title"/>
                                                 </xsl:attribute>
                                                 <img style="width:100%">
                                                     <xsl:attribute name="src">
                                                         <xsl:value-of select="@avatar"/>
                                                     </xsl:attribute>
                                                 </img>
                                             </a>
                                         </div>
                                         <div class="titlecontent">
                                                 <a>
                                                        <xsl:attribute name="href">
                                                            <xsl:value-of select="@title"/>
                                                        </xsl:attribute>
                                                        <xsl:variable name="user">
                                                            <xsl:value-of select="@name"/>
                                                        </xsl:variable>
                                                        <b>
                                                            <xsl:value-of select="php:function('ucfirst',$user)"/>
                                                        </b>
                                                </a>
                                         </div>
                                     </div>
                                     <div class="desprice" >
                                            <xsl:variable name="descr">
                                                <xsl:value-of select="@business" />
                                            </xsl:variable>
                                           <xsl:variable name="pureText">
                                                <xsl:call-template name="removeHtmlTags">
                                                    <xsl:with-param name="html" select="$descr" />
                                                </xsl:call-template>
                                           </xsl:variable>
                                            <xsl:variable name="upcase">
                                                <xsl:value-of select="concat(translate(substring($pureText,1,1), 'abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'),substring($pureText,2))" />
                                            </xsl:variable>
                                            <xsl:if test="$limit>0 and $upcase!=''">
                                           <div class="description" style="overflow:hidden;margin-top: 5px;">
                                            <xsl:value-of disable-output-escaping="yes" select="substring($upcase,1,$limit)" />
                                                <b>...</b>
                                           </div>
                                           </xsl:if>
                                           <div class="author" style="display:none"><xsl:value-of select="php:function( 'SobiPro::Txt', 'CREAT_BY' )"/>: 
                                            
                                         </div>
                                    </div>
                                </div>
                         </div>
                      </xsl:if>
                      <xsl:if test="position() &gt; 1">
                         <div class="rightdiv">
                                <div class="limg">
                                    <div class="subimg">
                                        <a> 
                                       <xsl:attribute name="href">
                                           <xsl:value-of select="@linkpro"/>
                                       </xsl:attribute>
                                        <img> 
                                            <xsl:attribute name="src" >
                                               <!--xsl:value-of select="fields/field_hnh_nh/data/@original" /-->
                                               <xsl:value-of select="@imgcache" />
                                           </xsl:attribute>
                                           <xsl:if test="$limit=0">
                                                      <xsl:attribute name="title">
                                                          <xsl:value-of select="@business" />
                                                      </xsl:attribute>
                                           </xsl:if>
                                        </img>
                                        </a>
                                    </div>
                                </div>
                                <div class="rcontent">
                                    <div class="imgtitle">
                                         <div class="avatar" style="height:32px;width:32px;float:left;">
                                             <a>
                                                 <xsl:attribute name="href">
                                                     <xsl:value-of select="@title"/>
                                                 </xsl:attribute>
                                                 <img style="width:100%">
                                                     <xsl:attribute name="src">
                                                         <xsl:value-of select="@avatar"/>
                                                     </xsl:attribute>
                                                 </img>
                                             </a>
                                         </div>
                                         <div class="titlecontent">
                                                 <a>
                                                        <xsl:attribute name="href">
                                                            <xsl:value-of select="@title"/>
                                                        </xsl:attribute>
                                                        <xsl:variable name="user">
                                                            <xsl:value-of select="@name"/>
                                                        </xsl:variable>
                                                        <b>
                                                            <xsl:value-of select="php:function('ucfirst',$user)"/>
                                                        </b>
                                                </a>
                                         </div>
                                     </div>
                                    <div class="desprice">
                                        <xsl:variable name="descr"><xsl:value-of select="@business" /></xsl:variable>
                                        <xsl:variable name="pureText">
                                                <xsl:call-template name="removeHtmlTags">
                                                    <xsl:with-param name="html" select="$descr" />
                                                </xsl:call-template>
                                           </xsl:variable>
                                           <xsl:variable name="upcase">
                                                <xsl:value-of select="concat(translate(substring($pureText,1,1), 'abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'),substring($pureText,2))" />
                                            </xsl:variable>
                                        <xsl:if test="$limit>0 and $upcase!=''">
                                            <xsl:value-of disable-output-escaping="yes" select="substring($upcase,1,$limit)" />
                                            <b>...</b>
                                        </xsl:if>
                                        <div class="author" style="display:none"><xsl:value-of select="php:function( 'SobiPro::Txt', 'CREAT_BY' )"/>: 
                                            
                                        </div>
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
                                        <a> 
                                       <xsl:attribute name="href">
                                           <xsl:value-of select="@linkpro"/>
                                       </xsl:attribute>
                                         <img> 
                                              <xsl:attribute name="src" >
                                                <xsl:value-of select="fields/field_hnh_nh/data/@original" />
                                              </xsl:attribute>
                                              <xsl:if test="$limit=0">
                                                   <xsl:attribute name="title">
                                                          <xsl:value-of select="@business" />
                                                   </xsl:attribute>
                                              </xsl:if>
                                         </img>
                                        </a>
                                    </div>
                               </div>
                               <div class="rcontent">
                                    <div class="imgtitle">
                                         <div class="avatar" style="height:32px;width:32px;float:left;">
                                             <a>
                                                 <xsl:attribute name="href">
                                                     <xsl:value-of select="@title"/>
                                                 </xsl:attribute>
                                                 <img style="width:100%">
                                                     <xsl:attribute name="src">
                                                         <xsl:value-of select="@avatar"/>
                                                     </xsl:attribute>
                                                 </img>
                                             </a>
                                         </div>
                                         <div class="titlecontent">
                                                 <a>
                                                        <xsl:attribute name="href">
                                                            <xsl:value-of select="@title"/>
                                                        </xsl:attribute>
                                                        <xsl:variable name="user">
                                                            <xsl:value-of select="@name"/>
                                                        </xsl:variable>
                                                        <b>
                                                            <xsl:value-of select="php:function('ucfirst',$user)"/>
                                                        </b>
                                                </a>
                                         </div>
                                     </div>
                                    <div class="desprice">
                                         <xsl:variable name="descr"><xsl:value-of select="@business" /></xsl:variable>
                                         <xsl:variable name="pureText">
                                                <xsl:call-template name="removeHtmlTags">
                                                    <xsl:with-param name="html" select="$descr" />
                                                </xsl:call-template>
                                           </xsl:variable>
                                           <xsl:variable name="upcase">
                                                <xsl:value-of select="concat(translate(substring($pureText,1,1), 'abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'),substring($pureText,2))" />
                                            </xsl:variable>
                                         <xsl:if test="$limit>0 and $upcase!=''">
                                            <xsl:value-of disable-output-escaping="yes" select="substring($upcase,1,$limit)" />
                                            <b>...</b>
                                         </xsl:if>
                                         <div class="author" style="display:none"><xsl:value-of select="php:function( 'SobiPro::Txt', 'CREAT_BY' )"/>: 
                                            
                                         </div>
                                    </div>
                               </div>
                          </div>
                     </xsl:if>
                     <xsl:if test="(position() mod 2) = 0">
                          <div class="rdiv">
                               <div class="limg">
                                    <div class="subimg">
                                        <a> 
                                       <xsl:attribute name="href">
                                           <xsl:value-of select="@linkpro"/>
                                       </xsl:attribute>
                                         <img> 
                                              <xsl:attribute name="src" >
                                                <xsl:value-of select="fields/field_hnh_nh/data/@original" />
                                              </xsl:attribute>
                                              <xsl:if test="$limit=0">
                                                   <xsl:attribute name="title">
                                                          <xsl:value-of select="@business" />
                                                   </xsl:attribute>
                                              </xsl:if>
                                         </img>
                                        </a>
                                    </div>
                               </div>
                               <div class="rcontent">
                                    <div class="imgtitle">
                                         <div class="avatar" style="height:32px;width:32px;float:left;">
                                             <a>
                                                 <xsl:attribute name="href">
                                                     <xsl:value-of select="@title"/>
                                                 </xsl:attribute>
                                                 <img style="width:100%">
                                                     <xsl:attribute name="src">
                                                         <xsl:value-of select="@avatar"/>
                                                     </xsl:attribute>
                                                 </img>
                                             </a>
                                         </div>
                                         <div class="titlecontent">
                                                 <a>
                                                        <xsl:attribute name="href">
                                                            <xsl:value-of select="@title"/>
                                                        </xsl:attribute>
                                                        <xsl:variable name="user">
                                                            <xsl:value-of select="@name"/>
                                                        </xsl:variable>
                                                        <b>
                                                            <xsl:value-of select="php:function('ucfirst',$user)"/>
                                                        </b>
                                                </a>
                                         </div>
                                     </div>
                                    <div class="desprice">
                                         <xsl:variable name="descr"><xsl:value-of select="@business" /></xsl:variable>
                                         <xsl:variable name="pureText">
                                                <xsl:call-template name="removeHtmlTags">
                                                    <xsl:with-param name="html" select="$descr" />
                                                </xsl:call-template>
                                           </xsl:variable>
                                           <xsl:variable name="upcase">
                                                <xsl:value-of select="concat(translate(substring($pureText,1,1), 'abcdefghijklmnopqrstuvwxyz', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'),substring($pureText,2))" />
                                            </xsl:variable>
                                         <xsl:if test="$limit>0 and $upcase!=''">
                                            <xsl:value-of disable-output-escaping="yes" select="substring($upcase,1,$limit)" />
                                            <b>...</b>
                                         </xsl:if>
                                         <div class="author" style="display:none"><xsl:value-of select="php:function( 'SobiPro::Txt', 'CREAT_BY' )"/>: 
                                            
                                         </div>
                                    </div>
                               </div>
                          </div>
                     </xsl:if>
		</xsl:for-each>
            </xsl:if>
	</div>
        <form name="project" id="project" method="post" action="#">
         <input type="hidden" name="option" value ="com_tvtma1080" />
         <input type="hidden" name="view" value="tvtmaprofile" />
         <input type="hidden" name="task" value="getTVTMAProfile" />
         <input type="hidden" name="format" value="ajax" />
         <input type="hidden" name="entrieslimit" id="entrieslimit">
             <xsl:attribute name="value">
                 <xsl:value-of select="$d"/>
             </xsl:attribute>
         </input>
         <input type="hidden" name="section_id" id="section_id">
             <xsl:attribute name="value">
                 <xsl:value-of select="$sectionid"/>
             </xsl:attribute>
         </input>
         <input type="hidden" name="limitdesc" id="limitdesc">
             <xsl:attribute name="value">
                 <xsl:value-of select="$limit"/>
             </xsl:attribute>
         </input>
         <input type="hidden" name="useridstr" id="useridstr">
             <xsl:attribute name="value">
                 <xsl:value-of select="$useridstr"/>
             </xsl:attribute>
         </input>
         <input type="hidden" name="fid" id="fid">
             <xsl:attribute name="value">
                 <xsl:value-of select="$fid"/>
             </xsl:attribute>
         </input>   
        </form>
        <script>
        window.addEvent('domready', function(){
        $('sprf').addEvent('change', function(e){
                var profile_type = $('sprf').get('value');
                new Event(e).stop();
                var myRequest = new Request.HTML ({
                        url: 'index.php',
                        onRequest: function(){
                            $('project').set('text', 'loading...');
                        },
                        onComplete: function(response){
                            $('project').empty().adopt(response);
                        },
                        data: {
                            option: "com_tvtma1080",
                            view: "tvtmaprofile",
                            task: "getTVTMAProfile",
                            format : "ajax",
                            profile_type: profile_type,
                            entrieslimit: $("entrieslimit").value,
                            section_id : $("section_id").value,
                            limit : $("limitdesc").value,
                            useridstr : $("useridstr").value,
                            fid : $("fid").value,
                        }
                }).send();
        });
});
</script>
	</xsl:template>
</xsl:stylesheet>
