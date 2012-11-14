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
                        <div style="clear:both;"/>
			<div class="SPDetailEntry">
                                <xsl:variable name="section">
                                <xsl:value-of select="section/@id" />
                                </xsl:variable>     
				<div class="SPImageList"  style="width:100%;margin-bottom:30px;">
                                    <div class="SPImage" id="SPGallery" >
                                        <a class="lightbox">  
                                                <xsl:attribute name="href">
                                                    <xsl:value-of select="//field_hnh_nh/data/@original"/>
                                                </xsl:attribute>  
                                                <xsl:copy-of select="//field_hnh_nh/data/*"/>
                                        </a>
                                    </div>
                                    <div class="SpAuthorArea" style="width:100%">
                                        <xsl:variable name="author"><xsl:value-of select="//entry/author" /></xsl:variable>
                                        <a>
                                            <xsl:attribute name="href">
                                                <xsl:value-of select="php:function('TplFunctions::myAvatarLink', $author)" />
                                            </xsl:attribute>
                                            <xsl:value-of select="php:function( 'TplFunctions::myAvatarFunction', $author )" disable-output-escaping="yes" />
                                        </a> 
                                        <a class="author-link">
                                        <xsl:attribute name="href">
                                            <xsl:value-of select="php:function('TplFunctions::myAvatarLink', $author)" />
                                        </xsl:attribute>
                                        <xsl:value-of select="php:function('SobiPro::User', $author, 'name')"/>
                                        </a> 
                                        <div class="SpEntryName">
                                            <xsl:value-of select="php:function('ucfirst',string( //entry/name ))"/>
                                        </div>
                                    </div>
                                    <div class='SpEntryContent'>
                                        <xsl:value-of select="//field_m_t/data" disable-output-escaping="yes" />
                                    </div>
                                    <div class='SpEntryLink'>
                                        <xsl:copy-of select="//field_website/data" disable-output-escaping="yes" />
                                    </div>

                                        
                                </div>
                                <div class="SPImageAuthor"  style="width:100%">
                                    
                                    <div style="clear:both;"></div>
                                    <xsl:value-of select="jcomments" disable-output-escaping="yes" />
                                </div>

			</div>
			<div style="clear:both;"></div>
                        
                        
		</div>
                <div style="clear:both;"></div>
                
	</xsl:template>
</xsl:stylesheet>
