<?php

/**
* 
*/
class Sitemap
{

	function __construct($sitemap_folder, $sitemap_name, $sitemap_type = 'generic')
	{
		$this->sitemap_folder = $sitemap_folder;
		$this->sitemap_name = $sitemap_name;
		$this->sitemap_uri = $sitemap_folder."/".$sitemap_name;
		$this->sitemap_type = $sitemap_type;

		// Check if the sitemap folder exists
		if(!is_dir($this->sitemap_folder))
		{
			// Create a sitemap folder
			mkdir($this->sitemap_folder) or die('Unable to create sitemap folder: '.$this->sitemap_folder);
		}
		// Check if the core exists
		if(!file_exists($sitemap_folder."/core.xml"))
		{
			// Create the core
			$this->create_core($sitemap_folder);
		}
		$this->sitemap_uri = $this->sitemap_folder.'/'.$this->current_sitemap();
		// Check if the sitemap exists
		if(!file_exists($this->sitemap_uri.".xml"))
		{
			// Create the sitemap
			$this->create_sitemap($this->sitemap_uri);
		}
	}

	// Create sitemap function
	private function create_sitemap($sitemap_uri)
	{
		$sitemap_uri .= ".xml";
		// Create the sitemap
		$open_sitemap_folder = fopen($sitemap_uri, "w");
		if(!$open_sitemap_folder) 
		{
			// if there was an error
			return false;
		}
		// Close the file
		fclose($open_sitemap_folder);
		// Create the "empty" sitemap
		$create_xml = new SimpleXMLElement('<urlset></urlset>');
		$create_xml->addAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
		switch ($this->sitemap_type) {
			case 'image':
					
					$create_xml->addAttribute("xmlns:image", "http://www.google.com/schemas/sitemap-image/1.1", '');
					$create_xml->asXML($sitemap_uri);
					file_put_contents($sitemap_uri,preg_replace('/xmlns:xmlns=""\s?/', '',file_get_contents($sitemap_uri)));
				break;
			case "video":
					$create_xml->addAttribute("xmlns:video", "http://www.google.com/schemas/sitemap-video/1.1", '');
					$create_xml->asXML($sitemap_uri);
					file_put_contents($sitemap_uri,preg_replace('/xmlns:xmlns=""\s?/', '',file_get_contents($sitemap_uri)));
				break;
			default:
					$create_xml->asXML($sitemap_uri);
				break;
		}
		
		$core_config = array(
				'type' => 'sitemap',
				'params' => array(
						'loc' =>  $sitemap_uri,
					),
			);
		$this->add_core($core_config);
		// Show that it was success
		return true;
	}


	private function current_sitemap($offset = 0)
	{
		// count the number of sitemaps in the folder with the same name
		$folder_data = opendir($this->sitemap_folder);
		// create an empty array
		$sitemap_folder_content = array();
		while (false !== ($entry = readdir($folder_data))) {
	        $sitemap_folder_content[] =  $entry;
	    }
		foreach ($sitemap_folder_content as $sitemap) 
		{
			$result = strpos($sitemap, $this->sitemap_name);
			if($result !== false)
			{
				$offset++;	
			} 
		}
		if($offset == 0)
		{
			$offset++;
		}

		return $this->sitemap_name."_".$offset;
	}


	private function increment_sitemap($offset = 1)
	{
		$current = $this->current_sitemap();

		preg_match('/(.+)'.'_'.'([0-9]+)$/', $current, $match);
		return isset($match[2]) ? $match[1].'_'.($match[2] + 1) : $this->sitemap_name.'_'.$offset;
	}

	// Make writing the actual file a new function
		// Config should be the default array
	public function add_node($config)
	{
		// Check the # of nodes in the sitemap 
		$url = $this->sitemap_uri.'.xml';
		$xml = simplexml_load_file($url);
		$node_count =  $xml->count();
		// Count the nodes
		if($node_count >= 50000)
		{
			$this->sitemap_uri = $this->sitemap_folder.'/'.$this->increment_sitemap();
			// Create the sitemap
			$this->create_sitemap($this->sitemap_uri) or die("Unable to create the sitemap");
		}
		$this->sitemap_uri .= ".xml";
		//Load the XML file
		$xml = simplexml_load_file($this->sitemap_uri);
		//Create a node
		$node = $xml->addChild($config['type']);
		// //Set the location of the URL
		foreach ($config['params'] as $param => $value) 
		{
			$node->addChild($param, $value);
		}
		//Save the XML
		$xml->asXML($this->sitemap_uri);
		return true;
	}

	private function create_core($sitemap_folder)
	{
		$core_uri = $sitemap_folder."/core.xml";
		// Create the sitemap
		$open_map = fopen($core_uri, "w");
		if(!$open_map) 
		{
			// if there was an error
			return false;
		}
		// Close the file
		fclose($open_map);
		// Create the "empty" sitemap
		$create_xml = new SimpleXMLElement('<sitemapindex></sitemapindex>');
		$create_xml->addAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
		$create_xml->asXML($core_uri);
		// Show that it was success
		return true;
	}

	private function add_core($config)
	{
		// Check the # of nodes in the sitemap 
		$url = $this->sitemap_folder.'/core.xml';
		$xml = simplexml_load_file($url);
		//Create a node
		$node = $xml->addChild($config['type']);
		// //Set the location of the URL
		foreach ($config['params'] as $param => $value) 
		{
			$node->addChild($param, $value);
		}
		//Save the XML
		$xml->asXML($url);
		return true;
	}

}

?>