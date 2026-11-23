<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

$titleAttr = $item->anchor_title ? ' title="' . htmlspecialchars($item->anchor_title, ENT_QUOTES, 'UTF-8') . '"' : '';
$anchorCss = trim((string) ($item->anchor_css ?? ''));
$linktype  = htmlspecialchars((string) $item->title, ENT_QUOTES, 'UTF-8');

if ($item->menu_icon) {
    if ($itemParams->get('menu_text', 1)) {
        $linktype = '<span class="' . htmlspecialchars($item->menu_icon, ENT_QUOTES, 'UTF-8') . '" aria-hidden="true"></span>' . htmlspecialchars((string) $item->title, ENT_QUOTES, 'UTF-8');
    } else {
        $linktype = '<span class="' . htmlspecialchars($item->menu_icon, ENT_QUOTES, 'UTF-8') . '" aria-hidden="true"></span><span class="visually-hidden">' . htmlspecialchars((string) $item->title, ENT_QUOTES, 'UTF-8') . '</span>';
    }
} elseif ($item->menu_image) {
    $imageAttributes = [];

    if (!empty($item->menu_image_css)) {
        $imageAttributes['class'] = $item->menu_image_css;
    }

    $linktype = HTMLHelper::_('image', $item->menu_image, $item->title, $imageAttributes);

    if ($itemParams->get('menu_text', 1)) {
        $linktype .= '<span class="image-title">' . htmlspecialchars((string) $item->title, ENT_QUOTES, 'UTF-8') . '</span>';
    }
}

if (strpos($item->title, 'menumod') === 0) {
?><jdoc:include type="modules" name="<?php echo $item->title; ?>" style="mod_standard"/>
<?php } else { ?>
<span class="mod-menu__heading heading<?php echo $anchorCss !== '' ? ' ' . htmlspecialchars($anchorCss, ENT_QUOTES, 'UTF-8') : ''; ?>"<?php echo $titleAttr; ?>><?php echo $linktype; ?>
<?php if ($item->parent) {
	echo '<span class="parent-indicator j51-inline-icon" aria-hidden="true"><svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"></path></svg></span>';
} ?>
</span>
<?php } ?>
