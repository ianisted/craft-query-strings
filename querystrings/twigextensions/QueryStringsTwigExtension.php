<?php

namespace Craft;

use Twig_Extension;
use Twig_Filter_Method;

class QueryStringsTwigExtension extends Twig_Extension {

	public function getName()
	{
		return 'Query Strings';
	}

	public function getFilters()
	{
		return array(
      'preserveQueryStrings' => new Twig_Filter_Method($this, 'preserveQueryStrings')
		);
	}

  public function getFunctions()
    {
        return array(
           // 'getQueryStrings' => new Twig_Function('getQueryStrings', 'getQueryStrings'),
           'getQueryStrings' => new \Twig_SimpleFunction('getQueryStrings', array($this, 'getQueryStrings'), array('is_safe' => array('html')))
        );
    }
  
  public function preserveQueryStrings($url)
  {
    if (substr(craft()->request->queryString, 0,2) == "p=") {
      $queries = explode("&", craft()->request->queryString, 2);
      $queryStrings = $queries[1];
    } else {
      $queryStrings = craft()->request->queryString;
    }

    if (substr($url,-1) != "?") {
      $return = $url . "?";
    } else {
      $return = $url;
    }

    $return = $return . $queryStrings;
    
    return TemplateHelper::getRaw($return);
  }


  public function getQueryStrings($lookForKey = false)
  {
      
    // Get the query string from Craft and break it apart for use in templates
  
    // Break query apart to remove page information if needed
    if (substr(craft()->request->queryString, 0,2) == "p=") {
      $queries = explode("&", craft()->request->queryString, 2);
    }

    // Break query apart to separate individual parts
    if (sizeof($queries) > 1) {
      $queries = explode("&", $queries[1]);
    } else {
      return false;
    }

    // Setup object array to return later
    $objectArray = array();

    // Loop over query parts and add them to the object
    foreach ($queries as $query) {
      $querySplit  = explode("=", $query);
      $queryObject = (object) ['key' => $querySplit[0],'value' => $querySplit[1]];
      if (($lookForKey != false && $querySplit[0] == $lookForKey) || ($lookForKey == false)) {
        array_push($objectArray,$queryObject);
      }
    }

    // Return objects
    $return = $objectArray;
    
    return  $return;
  }
  
  
}