<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">	
	<xsl:template match="/EntriesModule">
            <xsl:if test="count(entries/entry)">
		<div class="picture" style="width:100%">
			<xsl:for-each select="entries/entry">
                           <xsl:variable name="url" select="url"/>
                           <xsl:variable name="name" select="name"/>
                            <xsl:if test="(position() mod 2) = 1 ">
                                 <div style="float:left;width: 49%;overflow:hidden;height:70px;margin-bottom:5px;">
                                    <xsl:for-each select="fields/*">
                                            <xsl:if test="data/@icon">
                                                <a>
                                                    <xsl:attribute name="href">
                                                    <xsl:value-of select="$url" />
                                                    </xsl:attribute>
                                                <img style="width:100%;min-height:70px;">
                                                    <xsl:attribute name="src">
                                                        <xsl:value-of select="./data/@original" />
                                                    </xsl:attribute>
                                                    <xsl:attribute name="title">
                                                        <xsl:value-of select="$name"/>
                                                    </xsl:attribute>
                                                </img>
                                                </a>
                                            </xsl:if>
                                    </xsl:for-each>
                                 </div>
                            </xsl:if>
                            <xsl:if test="(position() mod 2) = 0 ">
                                 <div style="float:right;width: 49%;overflow:hidden;height:70px;margin-bottom:5px;">
                                    <xsl:for-each select="fields/*">
                                            <xsl:if test="data/@icon">
                                                <a>
                                                <xsl:attribute name="href">
                                                    <xsl:value-of select="$url" />
                                                </xsl:attribute>
                                               <img style="width:100%;min-height:70px;">
                                                    <xsl:attribute name="src">
                                                        <xsl:value-of select="./data/@original" />
                                                    </xsl:attribute>
                                                    <xsl:attribute name="title">
                                                        <xsl:value-of select="$name"/>
                                                    </xsl:attribute>
                                                </img>
                                                </a>
                                            </xsl:if>
                                    </xsl:for-each>
                                 </div>
                            </xsl:if>
			</xsl:for-each>
		</div>
            </xsl:if>
	</xsl:template>
</xsl:stylesheet>
