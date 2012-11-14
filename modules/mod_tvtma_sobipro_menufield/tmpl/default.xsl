<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">	
	<xsl:template match="/CatModule">
		<ul>
			<xsl:for-each select="categories/category">
                                 <xsl:if test="count(subcategories/*) &gt; 0">
                                    <li>
                                            <a>
                                                    <xsl:attribute name="href">
                                                            <xsl:value-of select="url" />
                                                    </xsl:attribute>
                                                    <b><xsl:value-of select="name" /></b>
                                            </a>	
                                            
                                    </li>
                                    <xsl:for-each select="subcategories/subcategory ">
                                            <li>
                                                        <a>
                                                                <xsl:attribute name="href">
                                                                        <xsl:value-of select="@url" />
                                                                </xsl:attribute>
                                                                <xsl:value-of select="." />
                                                                
                                                        </a>	
                                                        
                                            </li>
                                    </xsl:for-each>
                                    ---------------------------------------
				 </xsl:if>
			</xsl:for-each>
		</ul>
	</xsl:template>
</xsl:stylesheet>
