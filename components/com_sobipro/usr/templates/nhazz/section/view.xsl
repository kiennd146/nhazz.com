<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:php="http://php.net/xsl">
<xsl:output method="xml" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" encoding="UTF-8"/>

<xsl:include href="../common/topmenu.xsl" />
<xsl:include href="../common/alphamenu.xsl" />
<xsl:include href="../common/category.xsl" />
<xsl:include href="../common/entries.xsl" />
<xsl:include href="../common/navigation.xsl" />

<xsl:variable name="subcatsNumber">
	<xsl:value-of select="/section/number_of_subcats" />
</xsl:variable>

<xsl:template match="/section">
	<xsl:variable name="rssUrl">{"sid":"<xsl:value-of select="id"/>","sptpl":"feeds.rss","out":"raw"}</xsl:variable>
	<xsl:variable name="sectionName"><xsl:value-of select="name"/></xsl:variable>
	<xsl:value-of select="php:function( 'SobiPro::AlternateLink', $rssUrl, 'application/atom+xml', $sectionName )" />
	<div class="SPListing">
	    <div>
	      <xsl:apply-templates select="menu" />
	      <xsl:apply-templates select="alphaMenu" />
	    </div>
		<div style="clear:both;"/>
		<xsl:call-template name="entriesLoop" />
		<xsl:apply-templates select="navigation" />
		<div style="clear:both;"></div>
	</div>
</xsl:template>
</xsl:stylesheet>
