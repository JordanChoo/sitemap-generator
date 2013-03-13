# Sitemap Library

##Generic Sitemap

To start generating a generic sitemap you can use the following code:

```php

	$SitemapNode = new GenericSitemap('sitemaps', 'sitemap');

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