# Sitemap Library

##Generic Sitemap

To start generating a generic sitemap you can use the following code:

```php

	$SitemapNode = new GenericSitemap('SitemapFolder', 'NameOfSitemap');

	$config = array(
			'type' => 'url',
			'params' => array(
					'loc' => 'http://example.com',
					'freq' => 'daily',
					'priority' => '0.5',
				),
		);

	$SitemapNode->add_node($config);

```

It will create the following XML in a folder callder "SitemapFolder" with the name of 'NameOfSitemap_1.xml'

```xml
<?xml version="1.0"?>
	<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
		<url>
			<loc>http://example.com</loc>
			<freq>daily</freq>
			<priority>0.5</priority>
		</url>
	</urlset>
```