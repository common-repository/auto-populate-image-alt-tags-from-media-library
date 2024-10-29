<?php
/*
Plugin Name: Auto-populate Image ALT Tags from Media Library
Description: Automatically populates Alt and Title tags for images using the values set in the Media Library. 
Version: 1.1
License: GPLv2
Author: Taylor Callsen
*/

function image_alt_ml_insert_img_tags($content) {
	// only execute filter when viewing page or post
	if (!is_single() && !is_page()) return $content;

	// compile post attachment image paths for use in looking up image id
	$attachmentImages = get_attached_media('image', get_the_ID() );

	$content = preg_replace_callback('/<img[^>]+>/i',function ($matches) use ($attachmentImages) {
		
		// parse image tag into proper xml node object
		$doc = simplexml_import_dom(DOMDocument::loadHTML($matches[0]));
		$imageElem = $doc->xpath("//img")[0];
        
        // pull image src attribute
        $imageAttributes = $imageElem->attributes();
        $imageSrc = $imageAttributes->src;
        
        // look up image id from image source (comparing against list of post attachments)
        //	note: if id lookup fails, will abort script and return base HTML
		$imageID;
		foreach ($attachmentImages as $image) {
			if ($imageSrc == $image->guid) $imageID = $image->ID;
		}
		if (!isset($imageID)) {
			//echo "\n" . 'imageID NOT FOUND: ' . $imageSrc;
			return $matches[0];
		}
		
		// retreive attachment alt and title tags from attachment
		$imageAltTag = get_post_meta($imageID, '_wp_attachment_image_alt', true);
		$imageTitleTag = get_the_title($imageID);

		// add attachment alt and title tags to imgElem if not already present
		//	(i.e. specified in current post where attachment is made)
		if ($imageAltTag != ''){
			if (!isset($imageAttributes->alt)) $imageElem->addAttribute('alt',trim($imageAltTag));
			else if ($imageAttributes->alt == '') $imageAttributes->alt = trim($imageAltTag);
		}
		if ($imageTitleTag != ''){
			if (!isset($imageAttributes->title)) $imageElem->addAttribute('title',trim($imageTitleTag));
			else if ($imageAttributes->title == '') $imageAttributes->title = trim($imageTitleTag);
		}
		
		// render $imageElem back to HTML with new attributes included (must strip out xml header)
		$imageHTML = str_replace("<?xml version=\"1.0\"?>\n", '', $imageElem->asXML());

		// return updated image HTML back into post content
        return $imageHTML;

    },$content); 

	return $content;
}
add_filter('the_content', 'image_alt_ml_insert_img_tags');