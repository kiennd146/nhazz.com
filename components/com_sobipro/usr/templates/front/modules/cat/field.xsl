<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">	
	<xsl:template match="/CatModule">
            <xsl:variable name="sid">
                <xsl:value-of select="sid" />
            </xsl:variable>
            <xsl:if test="nid">
                <xsl:variable name="nid">
                <xsl:value-of select="nid" />
                </xsl:variable>
            </xsl:if>
		<ul>
			<xsl:for-each select="fieloptions/fieloption">
                                 <li>
                                     <a>
                                         <xsl:attribute name="href">
                                                    <xsl:value-of select="@alt" />
                                            </xsl:attribute>                                    
                                     <xsl:value-of select="." />
                                     </a>
                                 </li>
			</xsl:for-each>
		</ul>
	</xsl:template>
</xsl:stylesheet>
