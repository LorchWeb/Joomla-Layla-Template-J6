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
$headerTag   = preg_replace('/[^a-z0-9:_-]/i', '', (string) $params->get('header_tag', 'h3')) ?: 'h3';
$headerClass = htmlspecialchars((string) $params->get('header_class', ''), ENT_QUOTES, 'UTF-8');

?>
<<?php echo $moduleTag; ?> class="module <?php echo htmlspecialchars($moduleClass, ENT_QUOTES, 'UTF-8'); ?>">
	<div class="module_surround">
		<?php if ($module->showtitle) : ?>
			<?php
				$title = preg_split('/\s+/', trim((string) $module->title), 2);
				$title_part1 = htmlspecialchars($title[0] ?? '', ENT_QUOTES, 'UTF-8');
				$title_part2 = htmlspecialchars($title[1] ?? '', ENT_QUOTES, 'UTF-8');
			?>
		<div class="module_header">
			<<?php echo $headerTag; ?> class="mod_standard <?php echo $headerClass; ?>"><span><?php echo trim($title_part1 . ' ' . $title_part2); ?></span></<?php echo $headerTag; ?>>
		</div>
		<?php endif; ?>
		<div class="module_content">
		<?php echo $module->content; ?>
		</div> 
	</div>
</<?php echo $moduleTag; ?>>
