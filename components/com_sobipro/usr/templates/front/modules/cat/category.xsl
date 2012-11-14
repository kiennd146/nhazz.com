<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">	
    <xsl:template name="show_subcat" match="/CatModule">
        <xsl:param name="idparent" />
  <xsl:for-each select="/CatModule/categories/category">
      <xsl:variable name="name">
                <xsl:value-of select="name"/>
      </xsl:variable>
      <xsl:if test="@id=$idparent and count(subcategories/*) &gt; 0">
        <ul>
            <li>
                <a>
                    <xsl:attribute name="href">
                        <xsl:value-of select="url" />
                    </xsl:attribute>
                    Tất cả <xsl:value-of select="translate($name,'AĂÂBCDĐEÊFGHIJKLMNOÔƠPQRSTUƯVWXYZ','aăâbcdđeêfghijklmnoôơpqrstuưvwxyz')"/>
                </a>
            </li>
          <xsl:for-each select="subcategories/subcategory">
             <li>
                 <a>
                    <xsl:attribute name="href">
                        <xsl:value-of select="@url" />
                    </xsl:attribute>
                    <xsl:value-of select="." />
                 </a>
                 <xsl:call-template name="show_subcat">
                    <xsl:with-param name="idparent" select="@id" />
                 </xsl:call-template>
             </li>
          </xsl:for-each>
        </ul>
      </xsl:if>
  </xsl:for-each>
</xsl:template>
	<xsl:template match="/CatModule">
            <script type="text/javascript">
            var mymenu=new drilldownmenu({
            menuid: 'drillmenu1',
            menuheight: 'auto',
            breadcrumbid: 'drillcrumb',
            persist: {enable: true, overrideselectedul: true}
            })
            </script>
            <script type="text/Javascript">
                function autoscroll(dk,ten) {
                var element = document.getElementById(ten);
                if (dk==1){
                element.style.overflow = "auto";
                } else {
                element.style.overflow = "hidden";
                }
                }
            </script>
            <xsl:variable name="sectionid">
            <xsl:value-of select="sid"/>
            </xsl:variable>
            <div id="drillmenu1" class="drillmenu" onmouseout="autoscroll('0','drillmenu1')" onmouseover="autoscroll('1','drillmenu1')">
		<ul>
                    <li>
                        <a>
                            <xsl:attribute name="href">
                                <xsl:value-of select="viewall"/>
                            </xsl:attribute>
                        Tất cả sản phẩm
                        </a>
                    </li>
			<xsl:for-each select="categories/category">
                            <xsl:variable name="name">
                                <xsl:value-of select="name"/>
                            </xsl:variable>
                                 <xsl:if test="@title=$sectionid">
                                    <li>
                                            <a>
                                                    <xsl:attribute name="href">
                                                            <xsl:value-of select="url" />
                                                    </xsl:attribute>
                                                    <xsl:value-of select="name" />
                                            </a>
                                            <xsl:if test="count(subcategories/*) &gt; 0">
                                                <ul>
                                                    <li>
                                                        <a>
                                                            <xsl:attribute name="href">
                                                                <xsl:value-of select="url" />
                                                            </xsl:attribute>
                                                            Tất cả <xsl:value-of select="translate($name,'AĂÂBCDĐEÊFGHIJKLMNOÔƠPQRSTUƯVWXYZ','aăâbcdđeêfghijklmnoôơpqrstuưvwxyz')"/>
                                                        </a>
                                                    </li>
                                                    <xsl:for-each select="subcategories/subcategory">
                                                        <li>
                                                            <a>
                                                                <xsl:attribute name="href">
                                                                        <xsl:value-of select="@url" />
                                                                </xsl:attribute>
                                                                <xsl:value-of select="." />
                                                            </a>
                                                            <xsl:call-template name="show_subcat">
                                                                <xsl:with-param name="idparent" select="@id" />
                                                            </xsl:call-template>
                                                        </li>
                                                    </xsl:for-each>
                                                </ul>
                                             </xsl:if>  
                                    </li>
                                </xsl:if>
			</xsl:for-each>
		</ul>
            </div>
	</xsl:template>
</xsl:stylesheet>
