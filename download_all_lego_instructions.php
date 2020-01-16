<?php
//----------------------------------------------------------------------------------------
//  Download All LEGO Instructions
//----------------------------------------------------------------------------------------
//  Platform: CentOS7 + PHP5
//  Written by Pacess HO
//  Copyrights Pacess Studio, 2020.  All rights reserved.
//----------------------------------------------------------------------------------------

//  Global variables
$_themeURL = "https://productregistration.services.lego.com/api/v1/Content/themes?culture=##__CULTURE__##";
$_productURL = "https://productregistration.services.lego.com/api/v1/Content/productsbytheme?themeId=##__THEME_ID__##&culture=##__CULTURE__##";
$_boxURL = "https://productregistration.services.lego.com/api/v1/registrations/validateProductNumber?Culture=##__CULTURE__##&ProductNumber=##__PRODUCT__##";

$_cultureArray = array(
	"zh-HK", "ja-JP", "en-US",
 	"zh-TW", "da-DK", "ko-KR", "pt-BR",

 	"cs-CZ", "de-DE", "fr-BE", "nl-NL", "en-CA", "en-GB", "gl-ES", "es-MX", "nl-BE",
 	"fr-CA", "el-FR", "it-IT", "hu-HU", "nn-NO", "pl-PL", "ro-RO", "sv-SE", "tr-TR",
 	"el-GR", "ru-RU", "uk-UA",
);

//----------------------------------------------------------------------------------------
//  Main program
foreach ($_cultureArray as $culture)  {

	//  Get theme list of culture
	echo("Getting theme list of $culture...");

	$filename = "Lego_".$culture.".json";
	$filePath = $filename;
	if (file_exists($filePath) == false)  {

		//  Download theme list JSON
		$searchArray = array("##__CULTURE__##");
		$replaceArray = array($culture);
		$url = str_replace($searchArray, $replaceArray, $_themeURL);
		$content = file_get_contents($url);
		file_put_contents($filename, $content);
	}  else  {

		//  Load saved theme list JSON
		$content = file_get_contents($filePath);
	}
	$themeJSON = json_decode($content, true);
	if ($themeJSON == null)  {
		echo("Invalid JSON!\n");
		continue;
	}

	// 	Theme list sample: [{
	// 		"Title": "LEGO Brickheadz",
	// 		"Description": "LEGO Brickheadz",
	// 		"ShortDescription": "",
	// 		"PrimaryImage": "https://www.lego.com/r/www/r/catalogs/-/media/catalogs/themes/franchises/theme%20cards%202018/2hy/brickheadz_starwars_2hy18_legodotcom_336x448.jpg?l.r=-1697245100",
	// 		"ThemeId": "8e9caf21-13a2-41cf-a789-06afdc32f80b",
	// 		"Language": "en-US",
	// 		"Logo": "https://www.lego.com/r/www/r/catalogs/-/media/catalogs/themes/2019%20franchise%20logos/brickheadz_short_srgb_2017.png?l.r=2086288498",
	// 		"BoxLogo": "https://www.lego.com/r/www/r/catalogs/-/media/catalogs/themes/box%20logos/brickheadz_box%20logo.jpg?l.r=-1259536881"
	// 	}]
	$count = count($themeJSON);
	echo("$count themes\n");
	foreach ($themeJSON as $theme)  {

		//  Get products list of theme in culture
		$themeID = $theme["ThemeId"];

		$title = $theme["Title"];
		echo("- Getting product list of theme '$title' in '$culture'...");
		$searchArray = array("®", "™", "\t", "  ", " ");
		$replaceArray = array("", "", "", "_", "_");
		$title = strtolower(str_replace($searchArray, $replaceArray, trim($title)));

		$filename = "Lego_".$title."_".$culture.".json";
		$filePath = $filename;
		if (file_exists($filePath) == false)  {

			//  Download product list JSON
			$searchArray = array("##__CULTURE__##", "##__THEME_ID__##");
			$replaceArray = array($culture, $themeID);
			$url = str_replace($searchArray, $replaceArray, $_productURL);
			$content = file_get_contents($url);
			file_put_contents($filename, $content);
		}  else  {

			//  Load saved product list JSON
			$content = file_get_contents($filePath);
		}

		$productJSON = json_decode($content, true);
		if ($productJSON == null)  {
			echo("Invalid JSON!\n");
			continue;
		}

		// 	Product list sample: [{
		// 		"Title": "2018 Dodge Challenger SRT Demon and 1970 Dodge Charger R/T",
		// 		"BrickCount": 478,
		// 		"ProductNumber": "75893",
		// 		"Description": "一起來製造這款樂高® Speed Champions超級賽車系列75893 2018 Dodge Challenger SRT Demon及 1970 Dodge Charger R/T，為新型汽車與經典汽車飊車對決作好準備﹗\r\n\r\n使用超級增壓器為經典道奇充電器(Dodge Charger)補充燃料，接著將賽車手們放在駕駛艙內，等待聖誕樹燈光改變，然後立刻全速奔馳！誰會在這個歷史性比賽中成為首位衝過黑白方格旗的賽車手呢？",
		// 		"PrimaryImage": "https://www.lego.com/r/www/r/catalogs/-/media/catalogs/products/speed%20champions/2018/75893/lego_75893_web_pri_1488.jpg?l.r=-641274733",
		// 		"InspirationalBuilds": [],
		// 		"BuildingInstructions": [
		// 			{
		// 				"Filename": "6267130.pdf",
		// 				"Url": "https://www.lego.com/biassets/bi/6267130.pdf",
		// 				"FileSize": 8457818,
		// 				"Thumbnail": "https://www.lego.com/biassets/biimg/6267130.png",
		// 				"Title": "BI 3004/64+4, 75893 1/2 V29",
		// 				"Description": "BI 3004/64+4, 75893 1/2 V29",
		// 				"Id": "6267130"
		// 			},
		// 			{
		// 				"Filename": "6267135.pdf",
		// 				"Url": "https://www.lego.com/biassets/bi/6267135.pdf",
		// 				"FileSize": 4528113,
		// 				"Thumbnail": "https://www.lego.com/biassets/biimg/6267135.png",
		// 				"Title": "BI 3004/56, 75893 2/2 V29",
		// 				"Description": "BI 3004/56, 75893 2/2 V29",
		// 				"Id": "6267135"
		// 			}
		// 		],
		// 		"InvariantId": "44d753cb-ac3c-4086-a8f2-3bcbf8510eb3",
		// 		"ThemeId": "01d34657-9aeb-4103-846d-cf885e3b5fc1",
		// 		"HideDBIXAvailability": false,
		// 		"Images": [
		// 			"https://www.lego.com/r/www/r/catalogs/-/media/catalogs/products/speed%20champions/2018/75893/lego_75893_web_sec01_1488.jpg?l.r=1381056331",
		// 			"https://www.lego.com/r/www/r/catalogs/-/media/catalogs/products/speed%20champions/2018/75893/lego_75893_web_sec02_1488.jpg?l.r=1661541360",
		// 			"https://www.lego.com/r/www/r/catalogs/-/media/catalogs/products/speed%20champions/2018/75893/lego_75893_web_sec03_1488.jpg?l.r=1078787393",
		// 			"https://www.lego.com/r/www/r/catalogs/-/media/catalogs/products/speed%20champions/2018/75893/lego_75893_web_sec04_1488.jpg?l.r=500071765",
		// 			"https://www.lego.com/r/www/r/catalogs/-/media/catalogs/products/speed%20champions/2018/75893/lego_75893_web_sec05_1488.jpg?l.r=-1481170048"
		// 		],
		// 		"Thumbnail": "https://www.lego.com/r/www/r/catalogs/-/media/catalogs/products/speed%20champions/2018/75893/lego_75893_web_pri_720.jpg?l.r=927460551"
		// 	}]

		$count = count($productJSON);
		echo("$count products\n");
		foreach ($productJSON as $productDictionary)  {

			$product = $productDictionary["ProductNumber"];

			$folder = "./".substr($product, 0, 1)."/";
			if (file_exists($folder) == false)  {
				mkdir($folder, 0777, true);
			}

			echo("-- Downloading #$product...");
			$filename = "Lego_".$product."_".$culture.".json";
			$filePath = $folder.$filename;
			if (file_exists($filePath) == false)  {

				//  Download product JSON data
				$searchArray = array("##__CULTURE__##", "##__PRODUCT__##");
				$replaceArray = array($culture, $product);
				$url = str_replace($searchArray, $replaceArray, $_boxURL);
				$content = file_get_contents($url);
				file_put_contents($filePath, $content);
			}  else  {

				//  Load saved JSON
				$content = file_get_contents($filePath);
			}

			$boxJSON = json_decode($content, true);
			if ($boxJSON == null)  {
				echo("Invalid JSON...\n");
				continue;
			}

			// 	{
			// 		"IsValid": true,
			// 		"ProductInfo": {
			// 			"Title": "Mickey Mouse",
			// 			"BrickCount": 109,
			// 			"ProductNumber": "41624",
			// 			"Description": "Build your own LEGO® BrickHeadz™ construction character featuring Mickey Mouse and bring some classic Disney magic into your life. Check out his decorated eyes, iconic ears, red shorts and a tail, then display the plucky little mouse on the baseplate and show him to all your friends!",
			// 			"BoxShot": {
			// 				"Url": "https://www.lego.com/r/www/r/catalogs/-/media/catalogs/products/brickheadz/2018/41624/lego_41624_box1_v29_1488.png?l.r=-1479428188",
			// 				"Height": 838,
			// 				"Width": 1488
			// 			},
			// 			"PrimaryImage": {
			// 				"Url": "https://www.lego.com/r/www/r/catalogs/-/media/catalogs/products/brickheadz/2018/41624/lego_41624_web_pri_1488.jpg?l.r=367483660",
			// 				"Height": 837,
			// 				"Width": 1488
			// 			},
			// 			"Images": [
			// 				{
			// 					"Url": "https://www.lego.com/r/www/r/catalogs/-/media/catalogs/products/brickheadz/2018/41624/lego_41624_web_sec01_1488.jpg?l.r=394642102",
			// 					"Height": 837,
			// 					"Width": 1488
			// 				}
			// 			],
			// 			"InspirationalBuilds": [],
			// 			"BuildingInstructions": [
			// 				{
			// 					"Filename": "6248865.pdf",
			// 					"Url": "https://www.lego.com/biassets/bi/6248865.pdf",
			// 					"FileSize": 4101135,
			// 					"Thumbnail": {
			// 						"Url": "https://lc-imageresizer-live-s.legocdn.com/resize?mode=landscape&size=medium&ratio=2&imageUrl=https://www.lego.com/biassets/biimg/6248865.png",
			// 						"Height": 0,
			// 						"Width": 0
			// 					},
			// 					"Title": "BI 3001, 44/65G, 41624 V39",
			// 					"Description": "BI 3001, 44/65G, 41624 V39"
			// 				}
			// 			],
			// 			"DigitalBuildingInstructionAvailable": false,
			// 			"Language": "en-US",
			// 			"InvariantId": "0ca41e5c-b89c-4d25-9fa9-6bbe26a67742",
			// 			"ThemeId": "8e9caf21-13a2-41cf-a789-06afdc32f80b",
			// 			"HideDBIXAvailability": false
			// 		}
			// 	}

			//  Download boxshot
			$url = $boxJSON["ProductInfo"]["BoxShot"]["Url"];
			if (strlen($url) > 10)  {

				$filename = "Lego_".$product.".jpg";
				$filePath = $folder.$filename;
				if (file_exists($filePath) == false)  {

					$content = file_get_contents($url);
					if (strlen($content) > 0)  {
						file_put_contents($filePath, $content);
					}
				}
			}

			//  Download PDF
			$i = 0;
			$instructionDictionary = $boxJSON["ProductInfo"]["BuildingInstructions"];
			foreach ($instructionDictionary as $dictionary)  {

				$i++;
				$url = $dictionary["Url"];
				if ($url == null)  {
					echo("No PDF...");
					continue;
				}

				//  Check if file already exists
				$filename = str_replace(" ", "_", $dictionary["Filename"]);
				if (strlen($filename) <= 0)  {
					echo("Invalid filename...");
					continue;
				}
				$filename = "Lego_".$product."_".$i."_".$filename;
				$filePath = $folder.$filename;
				if (file_exists($filePath) == true)  {
					echo("Skip...");
					continue;
				}

				//  Download PDF and save it to local
				$sourceFile = fopen($url, "rb");
				if ($sourceFile != null)  {

					$targetFile = fopen($filePath, "wb");
					while (!feof($sourceFile))  {

						$contents = fread($sourceFile, 1000*1000);
						fwrite($targetFile, $contents);
					}
					echo("$filename...");
				}
			}
			echo("\n");
		}
	}
	echo("\n");
}

?>