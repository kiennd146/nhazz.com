<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
<xsl:output method="xml" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" encoding="UTF-8" />

	<xsl:include href="../common/topmenu.xsl" />
	<xsl:include href="../common/manage.xsl" />
	<xsl:include href="../common/alphamenu.xsl" />

	<xsl:template match="/entry_details">
		<div class="SPDetails">
		    <div>
		      <xsl:apply-templates select="menu" />
		      <xsl:apply-templates select="alphaMenu" />
		    </div>
			<div style="clear:both;"/>

			<xsl:call-template name="manage" />

			<div class="SPDetailEntry">
				<xsl:variable name="section">
                                    <xsl:value-of select="section/@id" />
                                </xsl:variable>                           
                                <div class="SPauthor">
                                    <xsl:variable name="author"><xsl:value-of select="entry/author" /></xsl:variable>
                                    <a>
                                        <xsl:attribute name="href">
                                            <xsl:value-of select="php:function('TplFunctions::myAvatarLink', $author)" />
                                        </xsl:attribute>
                                        <xsl:value-of select="php:function( 'TplFunctions::myAvatarFunction', string( entry/author ) )" disable-output-escaping="yes" />
                                        <xsl:value-of select="php:function('SobiPro::User', $author, 'name')"/>
                                    </a> 
                                </div>
                                
                                <h1 class="SPTitle"><xsl:value-of select="php:function('ucfirst', string(entry/name))" /></h1>    
                                
                                <xsl:for-each select="entry/fields/*">
                                <xsl:if test="count(data/*) or string-length(data)">
                                <div>
                                    <xsl:attribute name="class">
                                    <xsl:value-of select="@css_class" />
                                    </xsl:attribute>    
                                    <xsl:if test="count(data/*) or string-length(data)">
                                        <xsl:if test="label/@show = 1">
                                            <legend class="nhazz-title"><xsl:value-of select="label" /><xsl:text>: </xsl:text></legend>
                                        </xsl:if>
                                    </xsl:if>

                                    <xsl:choose>
                                    <xsl:when test="count(data/*)">
                                        <div class="SPImage" id="SPGallery">
                                            <a class="lightbox">  
                                                    <xsl:attribute name="href">
                                                        <xsl:value-of select="data/@original"/>
                                                    </xsl:attribute>  
                                                    <xsl:copy-of select="data/*"/>
                                            </a>

                                        </div>
                                        
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <xsl:if test="string-length(data) and local-name()='field_miu_t'">
                                            <xsl:value-of select="data" disable-output-escaping="yes" />
                                        </xsl:if>
                                        <xsl:if test="string-length(data) and local-name()='field_gi'">
                                            <xsl:variable name="gia">
                                                <xsl:value-of select="data"/>
                                            </xsl:variable>
                                            <xsl:value-of select="php:function('number_format',$gia,0,',','.')" disable-output-escaping="yes" /> VNĐ
                                        </xsl:if>
                                    </xsl:otherwise>
                                    </xsl:choose>

                                    <xsl:if test="count(data/*) or string-length(data)">
                                    <xsl:if test="string-length(@suffix)">
                                        <xsl:text> </xsl:text>
                                        <xsl:value-of select="@suffix"/>
                                    </xsl:if>
                                    </xsl:if>
                                     
                                </div>
                                </xsl:if>
                                </xsl:for-each>
                                
                                <xsl:if test="count(entry/categories)">
                                <fieldset class="spEntryCats">
                                    <legend class="nhazz-title"><xsl:value-of select="php:function( 'SobiPro::Txt' , 'Category : ' )" /></legend><xsl:text> </xsl:text>
                                    <xsl:for-each select="entry/categories/category">
                                    <a>
                                        <xsl:attribute name="href">
                                        <xsl:value-of select="@url" />
                                        </xsl:attribute>
                                        <xsl:value-of select="." />
                                    </a>
                                    <xsl:if test="position() != last()">
                                    <xsl:text> | </xsl:text>
                                    </xsl:if>
                                    </xsl:for-each>
                                </fieldset>
                                </xsl:if>
                                <a class="dcs_comment_photos" href="#">
									<xsl:attribute name="photo_id"><xsl:value-of select="entry/@id"/></xsl:attribute>
				                    Đặt câu hỏi
				                </a>
                                
			</div>
			<div style="clear:both;"></div>
                        
                        
		</div>
                <div class="SPImageAuthor" style="width:100%">
                <div style="clear:both;"></div>
                        <xsl:value-of select="jcomments" disable-output-escaping="yes" />
                </div>
	</xsl:template>
</xsl:stylesheet>
