<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

$title          = $item->anchor_title ? ' title="' . htmlspecialchars($item->anchor_title, ENT_QUOTES, 'UTF-8') . '"' : '';
$anchor_css     = trim((string) ($item->anchor_css ?? ''));
$rawTitle        = trim((string) $item->title);
$escapedTitle    = htmlspecialchars($rawTitle, ENT_QUOTES, 'UTF-8');
$separatorMarker = preg_replace('/[\s\-_–—.=*|]+/u', '', $rawTitle);
$hasTextLabel    = $rawTitle !== '' && $separatorMarker !== '';
$linktype        = $hasTextLabel ? $escapedTitle : '';

if ($item->menu_icon) {
    $linktype = '<span class="p-2 ' . htmlspecialchars($item->menu_icon, ENT_QUOTES, 'UTF-8') . '" aria-hidden="true"></span>';

    if ($hasTextLabel && $itemParams->get('menu_text', 0)) {
        $linktype .= '<span class="image-title">' . $escapedTitle . '</span>';
    } elseif ($hasTextLabel) {
        $linktype .= '<span class="visually-hidden">' . $escapedTitle . '</span>';
    }
} elseif ($item->menu_image) {
    $image_attributes = [];

    if ($item->menu_image_css) {
        $image_attributes['class'] = $item->menu_image_css;
    }

    $linktype = HTMLHelper::_('image', $item->menu_image, $item->title, $image_attributes);

    if ($hasTextLabel && $itemParams->get('menu_text', 0)) {
        $linktype .= '<span class="image-title">' . $escapedTitle . '</span>';
    }
} elseif (!$hasTextLabel) {
    $linktype = '<span class="mod-menu__separator-line" aria-hidden="true"></span>';
}

if (strpos($item->title, 'menumod') === 0) {
	?><jdoc:include type="modules" name="<?php echo $item->title; ?>"  style="mod_standard"/>
	<?php }
	else { ?>
	<span class="mod-menu__separator separator<?php echo $anchor_css !== '' ? ' ' . htmlspecialchars($anchor_css, ENT_QUOTES, 'UTF-8') : ''; ?><?php echo !$hasTextLabel && !$item->menu_icon && !$item->menu_image ? ' is-visual-separator' : ''; ?>"<?php echo $title; ?>><?php echo $linktype; ?>
	<?php if ($item->parent) {
		echo '<span class="parent-indicator j51-inline-icon" aria-hidden="true"><svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"></path></svg></span>';
	} ?>
	</span>
<?php } ?>


