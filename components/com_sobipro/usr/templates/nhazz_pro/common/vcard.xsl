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
                <div class="SPImageList" style="height:180px;overflow:hidden;width:100%;">
                    <a>
                        <xsl:attribute name="href">
                            <xsl:value-of select="url" />
                        </xsl:attribute>
                    <img style="width:100%">
                        <!--xsl:attribute name="src">
                                    <xsl:value-of select="fields/field_hnh_nh/data/@original" disable-output-escaping="yes"/>
                        </xsl:attribute-->
                        <xsl:attribute name="src"><xsl:value-of select="imgcache_list_product"/></xsl:attribute>
                    </img>
                    </a>
                </div>
                <div class="SPauthor" style="height:45px;width:100%;padding:0;float:left">
                    <xsl:variable name="name">
                        <xsl:value-of select="name"/>
                    </xsl:variable>
                    <xsl:value-of select="php:function('ucfirst',$name)" />
                                    <xsl:variable name="author"><xsl:value-of select="author" /></xsl:variable>
                                    <xsl:variable name="price"><xsl:value-of select="fields/field_gi/data" /></xsl:variable>
                                    <xsl:variable name="web"><xsl:value-of select="fields/field_website/data" /></xsl:variable>
                                    <xsl:if test="$web !=''">
                                            <a>
                                                <xsl:attribute name="href">
                                                    <xsl:value-of select="fields/field_website/data"/>   
                                                </xsl:attribute>
                                                <p style="text-align:left;">
                                                    Website
                                                </p>
                                            </a>
                                    </xsl:if>
                                    <div class="detail" style="">
                                    <a style="float:right;font-weight:bold;">
                                        <xsl:attribute name="href">
					<xsl:value-of select="url" />
                                        </xsl:attribute>
                                        >>
                                    </a>
                                    <xsl:if test="$price !=''">
                                        <xsl:variable name="gia">
                                            <xsl:value-of select="fields/field_gi/data"/>
                                        </xsl:variable>
                                        <p style="text-align:left; color:#3D8901;">
                                           Giá: <xsl:value-of select="php:function('number_format',$gia,0,',','.')"/> VNĐ
                                        </p>
                                    </xsl:if>
                                    </div>
                </div>
		<div style="clear:both;"/>
	</xsl:template>
</xsl:stylesheet>
