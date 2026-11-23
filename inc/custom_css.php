<?php
defined('_JEXEC') or die('Restricted index access');

$customcss_source = $this->params->get('customcss_source', 'default');
$customcss_url = $this->params->get('customcss_url', '');
$customcss_name = $this->params->get('customcss_name', 'custom');

if ($this->params->get('custom_css')) {
	$wa->addInlineStyle($this->params->get('custom_css'));
}

if($this->params->get('customcss_sw') == "1") {
	if($this->params->get('customcss_source') == "default") {
		$wa->useStyle('template.custom');
	}
	if($customcss_source == "template") {
		$wa->registerAndUseStyle('template.custom.templatefolder', 'templates/' . $this->template . '/css/custom.css');
	}
	if($customcss_source == "media") {
		$wa->registerAndUseStyle('template.custom.mediafolder', 'media/templates/site/' . $this->template . '/css/custom.css');
	}
	if($customcss_source == "url" && $customcss_url !== '') {
		$wa->registerAndUseStyle('template.custom.url', $customcss_url);
	}
}

// Responsive Custom CSS
if($this->params->get('tabport_css') != "1") {
	$wa->addInlineStyle('@media only screen and (min-width: 768px) and (max-width: 959px) {'.$this->params->get('tabport_css').'}');
}
if($this->params->get('mobland_css') != "1") {
	$wa->addInlineStyle('@media only screen and ( max-width: 767px ) {'.$this->params->get('mobland_css').'}');
}
if($this->params->get('mobport_css') != "1") {
	$wa->addInlineStyle('@media only screen and (max-width: 440px) {'.$this->params->get('mobport_css').'}');
}
