<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="*|text()|@*|comment()|processing-instruction()|node()" priority="-4">
    <xsl:copy>
        <xsl:apply-templates  select="@*"/>
        <xsl:apply-templates />
    </xsl:copy>
</xsl:template>
<xsl:template match="/" priority="-4">
    <xsl:apply-templates />
</xsl:template>

</xsl:stylesheet>