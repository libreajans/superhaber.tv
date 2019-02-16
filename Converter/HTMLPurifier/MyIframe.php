<?php
	/**
	* HTMLPurifier dışı bir extend 
	* Iframe taglarına güvenli olmayan bir şekilde izin veriyor
	* Kaynak: http://htmlpurifier.org/phorum/read.php?3,4646,5791
	*/

	class HTMLPurifier_Filter_CustomIframesSupport extends HTMLPurifier_Filter
	{

		public $name = 'CustomIframesSupport';

		public function preFilter($html, $config, $context) 
		{
			$html = preg_replace('#<iframe([^>]+)>#i', '[[[custom-iframes-support $1]]]', $html);
			$html = preg_replace('#<\/iframe>#i', '', $html);
			return $html;
		}

		public function postFilter($html, $config, $context) 
		{
			$post_regex = '#\[\[\[custom-iframes-support ([^<]+?)\]\]\]#';
			$html = preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
			return $html;
		}

		protected function postFilterCallback($matches) 
		{
			return '<iframe'.$matches[1].'></iframe>';
		}
	}