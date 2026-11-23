<?php

defined('_JEXEC') or die;

$module = $displayData['module'];
$params = $displayData['params'];

if ($module->content === null || $module->content === '')
{
	return;
}

$moduleTag   = preg_replace('/[^a-z0-9:_-]/i', '', (string) $params->get('module_tag', 'div')) ?: 'div';
$moduleClass = trim($module->position . ' ' . (string) $params->get('moduleclass_sfx', ''));

?>
<<?php echo $moduleTag; ?> class="module <?php echo htmlspecialchars($moduleClass, ENT_QUOTES, 'UTF-8'); ?>">
	<div class="module_content">
		<?php echo $module->content; ?>
	</div> 
</<?php echo $moduleTag; ?>>
