<?php
// includes/schema_article.php
// Generates JSON-LD Schema for Articles and Breadcrumbs
// Assumes $pageTitle, $pageDesc, and $canonicalUrl (from header.php) are available.

$schemaHeadline = isset($pageTitle) ? $pageTitle : 'SQL Tutorial';
$schemaDesc = isset($pageDesc) ? $pageDesc : 'Learn SQL with our interactive compiler.';
$schemaUrl = isset($canonicalUrl) ? $canonicalUrl : 'https://sqlcompiler.shop' . $_SERVER['PHP_SELF'];

// Construct Breadcrumb Items
$breadcrumbs = [
    [
        "@type" => "ListItem",
        "position" => 1,
        "name" => "Home",
        "item" => "https://sqlcompiler.shop/"
    ],
    [
        "@type" => "ListItem",
        "position" => 2,
        "name" => "Learn SQL",
        "item" => "https://sqlcompiler.shop/learn/"
    ],
    [
        "@type" => "ListItem",
        "position" => 3,
        "name" => $schemaHeadline,
        "item" => $schemaUrl
    ]
];
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Article",
      "headline": "<?= htmlspecialchars($schemaHeadline) ?>",
      "description": "<?= htmlspecialchars($schemaDesc) ?>",
      "image": "https://sqlcompiler.shop/assets/og-image.jpg",
      "author": {
        "@type": "Organization",
        "name": "SQLCompiler Team",
        "url": "https://sqlcompiler.shop"
      },
      "publisher": {
        "@type": "Organization",
        "name": "SQLCompiler",
        "logo": {
          "@type": "ImageObject",
          "url": "https://sqlcompiler.shop/assets/logo.png"
        }
      },
      "datePublished": "2024-01-01",
      "dateModified": "<?= date('Y-m-d') ?>", 
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?= $schemaUrl ?>"
      }
    },
    {
      "@type": "BreadcrumbList",
      "itemListElement": <?= json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
    }
  ]
}
</script>
