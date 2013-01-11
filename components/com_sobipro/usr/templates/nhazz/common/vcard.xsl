<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
<xsl:output method="xml" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" encoding="UTF-8"/>

	<xsl:template name="vcard">
                <style>
                    #rightMain {
                    padding: 0;
                    background : #fafbf6;
                    }
                    #rightMain.noRight, .noLeft {
                        width : 810px;
                    }
                    body {
                    
                    }
                    
                </style>
                <div class="SPImageList" id="SPGallery">
                    <a class="lightbox">
                        <xsl:attribute name="href">
                                <xsl:value-of select="fields/field_hnh_nh/data/@original"/>
                        </xsl:attribute>
                        <xsl:attribute name="data">hidden<xsl:value-of select="id"/></xsl:attribute>
                        <xsl:copy-of select="fields/field_hnh_nh/data" disable-output-escaping="yes"/>
                    </a>
                    <div class="SPCommandList" style="">
                        <ul>

                            <li class='sp-comment-icon'>
                                <a>
                                    <xsl:attribute name="href">
                                            <xsl:value-of select="url" />#addcomments
                                    </xsl:attribute>
                                    Bình luận
                                </a>
                            </li>

                            <li class='sp-search-icon'>
                                <a>
                                    <xsl:attribute name="href">
                                            <xsl:value-of select="url" />
                                    </xsl:attribute>
                                    Xem chi tiêt
                                </a>
                            </li>

							<li class='sp-comment-icon'>
                                <a class="dcs_comment_photos" href="#">
									<xsl:attribute name="photo_id"><xsl:value-of select="id"/></xsl:attribute>
                                    Đặt câu hỏi
                                </a>
                            </li>
							
                        </ul>
                    </div>
                                    
                </div>
                <div class="SPImageAuthor">
                    <xsl:if test="edit_url">
			<span class="">
				<a class='edit'>
					<xsl:attribute name="href">
						<xsl:value-of select="edit_url" />
					</xsl:attribute>
					<xsl:value-of select="php:function( 'SobiPro::Txt', 'Edit Entry' )" />
				</a>
			</span>
                    </xsl:if>
                    <div class="SpAuthorArea">
                        <xsl:variable name="author"><xsl:value-of select="author" /></xsl:variable>
                        
                        <a>
                            <xsl:attribute name="href">
                                <xsl:value-of select="php:function('TplFunctions::myAvatarLink', $author)" />
                            </xsl:attribute>
                            <xsl:value-of select="php:function( 'TplFunctions::myAvatarFunction', string( author ) )" disable-output-escaping="yes" />
                        </a> 
                        
                        
                        <a class="author-link">
                        <xsl:attribute name="href">
                            <xsl:value-of select="php:function('TplFunctions::myAvatarLink', $author)" />
                        </xsl:attribute>
                        <xsl:value-of select="php:function('SobiPro::User', $author, 'name')"/>
                        </a> 
                        
                    </div>
                    <div class="SpEntryLink">
                        <xsl:copy-of select="fields/field_website/data"/>
                    </div>
                    <div class="SpEntryName">
                        <xsl:value-of select="php:function('ucfirst',string( name ))"/>
                    </div>
                    <div class="SpEntryContent">
                        <xsl:value-of select="php:function('TplFunctions::substring',string( fields/field_m_t/data ) , 300)"/>
                    </div>
                    
                    <div class="SpEntryControl">
                        <ul>
                            <li><a href="#">Email</a></li>
                            <li><a href="#">Nhúng trên blog</a></li>
                            <li><a href="#" class="fb-icon"></a></li>
                            <li><a href="#" class="twitter-icon"></a></li>
                            <li><a href="#" class="google-icon"></a></li>
                        </ul>
                    </div>
                    

                </div>
                

                <div style="display:none;">
                                    <xsl:attribute name="class">hidden<xsl:value-of select="id"/>  hiddenContent</xsl:attribute>
                                    <fieldset class="SPauthor">
                                    <legend class="nhazz-title">Thành viên đăng: </legend>
                                    <xsl:variable name="author"><xsl:value-of select="author" /></xsl:variable>
                                    <a>
                                        <xsl:attribute name="href">
                                            <xsl:value-of select="php:function('TplFunctions::myAvatarLink', $author)" />
                                        </xsl:attribute>
                                        <xsl:value-of select="php:function( 'TplFunctions::myAvatarFunction', string( author ) )" disable-output-escaping="yes" />
                                        <xsl:value-of select="php:function('SobiPro::User', $author, 'name')"/>
                                    </a> 
                                    </fieldset>
                 </div>

		<div style="clear:both;"/>
	</xsl:template>
</xsl:stylesheet>
