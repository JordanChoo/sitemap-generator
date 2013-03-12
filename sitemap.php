<?php

/**
* 
*/
class Generic_Sitemap
{
	// Variables
		//Just the name of the folder
		public $sitemap_folder = ""; 
		//ONLY the name of the sitemap excluding the .xml extension
		public $sitemap_name = "";

	function __construct()
	{
		# code...
	}

	function sitemap()
	{
		// state the sitemap folder
		
		// Check if the sitemap folder exists
		if(!is_dir($sitemap_folder))
		{
			// Create a sitemap folder
			mkdir($sitemap_folder) or die('Unable to create sitemap folder: '.$sitemap_folder);
		}
		
		// Check for the name of the sitemap

		// Check if the sitemap exists
		if(!file_exists($sitemap_folder.'/'.$sitemap_name))
		{
			// Create the sitemap
			create_sitemap($sitemap_folder.'/'.$sitemap_name) or die("Unable to create the sitemap");
		}
		
		// Check the # of nodes in the sitemap 
		$xml = new SimpleXmlElement($variable_GOES_HERE);
		// Count the nodes
		$node_count = $xml->count();
		// Check if the node count is at/over the limit
		if($node_count <= 50000)
		{
			// count the number of sitemaps in the folder with the same name
			$sitemap_folder_content = readdir($sitemap_folder);
			$count = 0;
			foreach ($sitemap_folder_content as $sitemap) 
			{
				strpos($sitemap, $sitemap_name) or $count++;
			}
			if($count == 0)
			{
				$count++;
			}
			// Create a file that has the above calculated number appended to it with an _ as the seperator
				// i.e. videos_1.xml

			// The following two lines are courtesy of:
				// Of FuelPHP's Str::increment() function
			preg_match('/(.+)'.'_'.'([0-9]+)$/', $sitemap_name, $match);
			$sitemap_name =  isset($match[2]) ? $match[1].'_'.($match[2] + 1) : $sitemap_name.'_'.$count;
			// Create the sitemap
			create_sitemap($sitemap_folder.'/'.$sitemap_name) or die("Unable to create the sitemap");
		}
		// Append the date to the sitemap
		append_sitemap($sitemap_folder.'/'.$sitemap_name, $url, $config);
	}

	// Create sitemap function
	function create_sitemap($sitemap_name)
	{
		$sitemap_name .= ".xml";
		// Create the sitemap
		if(!fopen($sitemap_name, "w")) 
		{
			// if there was an error
			return false;
		}
		// Close the file
		fclose($sitemap_name);
		// Create the "empty" sitemap
		$create_xml = new SimpleXMLElement('<urlset></urlset>');
		$create_xml->addAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
		$create_xml->asXML($sitemap_name);
		// Show that it was success
		return true;
	}

	// Make writing the actual file a new function
		// Config should be the default array
	function append_sitemap($sitemap_name, $url, $confg)
	{
		/* CONFIG EXAMPLE

		$confg = array(
				'type' => "url",
				'params' => array(
						'loc' => 'http://example.com',
						'changefreq' => 'daily',
						'priority' => '0.9',
					),
			);

		*/

		$sitemap_name .= ".xml";
		//Load the XML file
		$xml = simplexml_load_file($sitemap_name);
		//Create a node
		$url = $xml->addChild($config['type']);
		//Set the location of the URL
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
* 
*/
class Core_Sitemap extends Sitemap
{
	
	function __construct($argument)
	{
		# code...
	}
}

/**
* 
*/
class Video_Sitemap extends Sitemap
{
	
	function __construct($argument)
	{
		# code...
	}
}
/**
* 
*/
class Image_Sitemap extends Sitemap
{
	function __construct($argument)
	{
		# code...
	}
}


?>