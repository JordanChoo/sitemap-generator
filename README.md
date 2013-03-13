# Sitemap Library

This library allows the user to create a scalable generic, video and image based sitemaps.

When creating a sitemap object it accepts three inputs which are 

```php
$sitemap = new Sitemap('FolderOfSitemap', 'NameOfSitemap', 'TypeOfSitemap')
```
NOTE: For the type of sitemap it accepts only three types which are:
- 'generic' // Default value if none given
- 'video'
- 'image'

##Generic Sitemap

To start generating a generic sitemap you can use the following code:

```php

	$SitemapNode = new Sitemap('SitemapFolder', 'NameOfSitemap');

	$config = array(
			'type'   => 'url',
			'params' => array(
					'loc'      => 'http://example.com',
					'freq'     => 'daily',
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

Additionally, it will automatically create a core sitemap called "core.xml" in the folder that you specified earlier which in this case would be "SitemapFolder", the contents would be:

```xml
<?xml version="1.0"?>
	<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
		<sitemap>
			<loc>SitemapFolder/NameOfSitemap_1.xml</loc>
		</sitemap>
	</sitemapindex>
```
As a note whenever a new sitemap is created the core.xml file will be automatically updated with the new entry of the sitemap.

## Images in sitemap

If you'd like to build a sitemap with image files in it, you would do the following:

```php

	$SitemapNode = new Sitemap('SitemapFolder', 'NameOfSitemap', 'image');

	$config = array(
			'type'   => 'url',
			'params' => array(
					'loc'   => 'http://example.com/super-awesome-blog-post',
					'image' => array(
							'loc' => 'http://www.example.com/super-awesome-blog-post-image.jpg',
						),
				),
		);

	$SitemapNode->add_node($config);
```

Just like the default sitemap a core sitemap file is automatically created in the sitemap folder that you specified to allow for auto sitemap scaling. The results would be the creation of the following sitemap (assuming that one hasn't been created previously). 

```xml
	<?xml version="1.0"?>
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
			<url>
				<loc>http://example.com/how-to-have-fun</loc>
				<image:image>
					<image:loc>http://www.example.com/1.jpg</image:loc>
				</image:image></url>
		</urlset>

```


## Videos in sitemap