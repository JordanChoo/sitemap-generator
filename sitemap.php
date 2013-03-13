<?php

/**
* 
*/
class GenericSitemap
{

	// function __construct($config)
	// {
	// 	# code...
	// }

	function sitemap($config)
	{
		$sitemap_folder = $config['sitemap_folder'];
		$sitemap_name = $config['sitemap_name'];
		$url = $config['params']['loc'];
		// Check if the sitemap folder exists
		if(!is_dir($sitemap_folder))
		{
			// Create a sitemap folder
			mkdir($sitemap_folder) or die('Unable to create sitemap folder: '.$sitemap_folder);
		}
		$sitemap_name = $this->current_sitemap($sitemap_name, $sitemap_folder);
		// Check if the sitemap exists
		if(!file_exists($sitemap_folder.'/'.$sitemap_name.".xml"))
		{
			// Create the sitemap
			$this->create_sitemap($sitemap_folder.'/'.$sitemap_name);
		}
		// Check the # of nodes in the sitemap 
		$url = $sitemap_folder.'/'.$sitemap_name.'.xml';
		$xml = simplexml_load_file($url);
		$node_count =  $xml->count();
		// Count the nodes
		if($node_count >= 50000)
		{
			$sitemap_name = $this->increment_sitemap($config['sitemap_name'], $sitemap_folder);
			// // Create the sitemap
			$this->create_sitemap($sitemap_folder.'/'.$sitemap_name) or die("Unable to create the sitemap");
		}
		// Append the date to the sitemap
		$this->append_sitemap($sitemap_folder.'/'.$sitemap_name, $config);
	}

	// Create sitemap function
	private function create_sitemap($sitemap_name)
	{
		$sitemap_name .= ".xml";
		// Create the sitemap
		$open_map = fopen($sitemap_name, "w");
		if(!$open_map) 
		{
			// if there was an error
			return false;
		}
		// Close the file
		fclose($open_map );
		// Create the "empty" sitemap
		$create_xml = new SimpleXMLElement('<urlset></urlset>');
		$create_xml->addAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
		$create_xml->asXML($sitemap_name);
		// Show that it was success
		return true;
	}


	private function current_sitemap($sitemap_name, $sitemap_folder, $offset = 0)
	{
		// count the number of sitemaps in the folder with the same name
		$folder_data = opendir($sitemap_folder);
		// create an empty array
		$sitemap_folder_content = array();
		while (false !== ($entry = readdir($folder_data))) {
	        $sitemap_folder_content[] =  $entry;
	    }
		foreach ($sitemap_folder_content as $sitemap) 
		{
			$result = strpos($sitemap, $sitemap_name);
			if($result !== false)
			{
				$offset++;	
			} 
		}
		if($offset == 0)
		{
			$offset++;
		}

		return $sitemap_name."_".$offset;
	}


	private function increment_sitemap($sitemap_name, $sitemap_folder, $offset = 1)
	{
		$current = $this->current_sitemap($sitemap_name, $sitemap_folder);

		preg_match('/(.+)'.'_'.'([0-9]+)$/', $current, $match);
		$sitemap_name =  isset($match[2]) ? $match[1].'_'.($match[2] + 1) : $sitemap_name.'_'.$offset;

		return $sitemap_name;
	}

	// Make writing the actual file a new function
		// Config should be the default array
	public function append_sitemap($sitemap_name, $config)
	{
		$sitemap_name .= ".xml";
		//Load the XML file
		$xml = simplexml_load_file($sitemap_name);
		//Create a node
		$url = $xml->addChild($config['type']);
		// //Set the location of the URL
		foreach ($config['params'] as $param => $value) 
		{
			$url->addChild($param, $value);
		}

		//Save the XML
		$xml->asXML($sitemap_name);
		return true;
	}
}

/**
*<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
*	<sitemap>
*		<loc>http://www.pornhub.com/sitemap_core.xml</loc>
*	</sitemap>
*</sitemapindex>
*/
class CoreSitemap extends GenericSitemap
{

	function __construct($sitemap_folder, $sitemap_name)
	{
		$this->sitemap_folder = $sitemap_folder;
		$this->sitemap_name = $sitemap_name;
		$this->sitemap_uri = $sitemap_folder."/".$sitemap_name;

	}

	// Create sitemap function
	public function create_sitemap()
	{

		$this->sitemap_uri .= ".xml";
		// Create the sitemap
		$open_map = fopen($this->sitemap_uri, "w");
		if(!$open_map) 
		{
			// if there was an error
			return false;
		}
		// Close the file
		fclose($open_map );
		// Create the "empty" sitemap
		$create_xml = new SimpleXMLElement('<sitemapindex></sitemapindex>');
		$create_xml->addAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
		$create_xml->asXML($this->sitemap_uri);
		// Show that it was success
		return true;
	}
}

/**
* 
*/
class VideoSitemap extends GenericSitemap
{
	
	function __construct($argument)
	{
		# code...
	}
}
/**
* 
*/
class ImageSitemap extends GenericSitemap
{
	function __construct($argument)
	{
		# code...
	}
}


?>