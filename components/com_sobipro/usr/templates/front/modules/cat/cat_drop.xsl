<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">	
	<xsl:template match="/CatModule">
        <form name="boogie">
            <select name="surf" onChange="location=document.boogie.surf.options[document.boogie.surf.selectedIndex].value;" value="GO">
                <option>Chọn Metro</option>
			<xsl:for-each select="categories/category">
                                 <xsl:if test="count(subcategories/*) = 0">
                                    <option>
                                        <xsl:attribute name="value">
                                            <xsl:value-of select="@name" />
                                        </xsl:attribute>
                                                    <b><xsl:value-of select="name" /></b>
                                    </option>
				 </xsl:if>
			</xsl:for-each>
                            
                        </select>
                        </form>
	</xsl:template>
</xsl:stylesheet>
