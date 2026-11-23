<?php
/**
 * J51_LayerSlideshow
 * Version     : 1.1
 * Created by  : Joomla51
 * Email       : info@joomla51.com
 * URL         : www.joomla51.com
 * License GPLv2.0 - http://www.gnu.org/licenses/gpl-2.0.html
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

$document = Factory::getApplication()->getDocument();
$wa       = $document->getWebAssetManager();

if (!$wa->assetExists('style', 'mod_j51layerslideshow.base')) {
    $wa->registerStyle('mod_j51layerslideshow.base', 'media/mod_j51layerslideshow/css/style.css');
}

if (!$wa->assetExists('style', 'mod_j51layerslideshow.tiny-slider')) {
    if (file_exists(JPATH_ROOT . '/media/j51_assets/css/tiny-slider.min.css')) {
        $wa->registerStyle('mod_j51layerslideshow.tiny-slider', 'media/j51_assets/css/tiny-slider.min.css');
    } else {
        $wa->registerStyle('mod_j51layerslideshow.tiny-slider', 'media/mod_j51layerslideshow/css/tiny-slider.min.css');
    }
}

if (!$wa->assetExists('script', 'mod_j51layerslideshow.tiny-slider')) {
    if (file_exists(JPATH_ROOT . '/media/j51_assets/js/tiny-slider.min.js')) {
        $wa->registerScript('mod_j51layerslideshow.tiny-slider', 'media/j51_assets/js/tiny-slider.min.js');
    } else {
        $wa->registerScript('mod_j51layerslideshow.tiny-slider', 'media/mod_j51layerslideshow/js/tiny-slider.min.js');
    }
}

if (!$wa->assetExists('script', 'mod_j51layerslideshow.imagesloaded')) {
    if (file_exists(JPATH_ROOT . '/media/j51_assets/js/imagesloaded.pkgd.min.js')) {
        $wa->registerScript('mod_j51layerslideshow.imagesloaded', 'media/j51_assets/js/imagesloaded.pkgd.min.js');
    } else {
        $wa->registerScript('mod_j51layerslideshow.imagesloaded', 'media/mod_j51layerslideshow/js/imagesloaded.pkgd.min.js');
    }
}

$wa->useStyle('mod_j51layerslideshow.base');
$wa->useStyle('mod_j51layerslideshow.tiny-slider');
$wa->useScript('mod_j51layerslideshow.tiny-slider');
$wa->useScript('mod_j51layerslideshow.imagesloaded');

$wa->addInlineScript('
    document.addEventListener("DOMContentLoaded", function() {
        var slider = tns({
            container: "#layerslideshow'.$j51_moduleid.'",
            mode: "gallery",
            items: 1,
            slideBy: "page",
            controls: true,
            autoplay: '.$autoplay.',
            autoplayTimeout: '.$autoplaySpeed.',
            speed: '.$speed.',
            controlsContainer: "#layerslideshow'.$j51_moduleid.'-controls",
            autoplayButton: "#layerslideshow'.$j51_moduleid.'-play",
            navContainer: "#layerslideshow'.$j51_moduleid.'-nav",
        });
    });
'); 

if($j51_header_overlap)  {
    $wa->addInlineStyle('
        @media only screen and (min-width: 768px) {
            #container_header,
            .sticky-wrapper {
                position: absolute;
            }
            .layerslideshow'.$j51_moduleid.' .info {
                opacity: 0;
                transition: opacity .2s ease;
            }
        }
    ');
    $wa->addInlineScript('
        document.addEventListener("DOMContentLoaded", function() {
			var elem = document.querySelector("#layerslideshow'.$j51_moduleid.'");
			imagesLoaded( elem, function() {
				var header = document.getElementById("container_header");
            var nextArrow = document.getElementById("layerslideshow-next-'.(int) $j51_moduleid.'");
            var prevArrow = document.getElementById("layerslideshow-prev-'.(int) $j51_moduleid.'");

            if (!header) {
                return;
            }

				var headerHeight = header.clientHeight;

            if (nextArrow) nextArrow.style.marginTop = ((headerHeight * .5) - 20)+"px"; 
            if (prevArrow) prevArrow.style.marginTop = ((headerHeight * .5) - 20)+"px";
            var ele = document.querySelectorAll(".layerslideshow'.$j51_moduleid.' .info");
            var i;
            for (i = 0; i < ele.length; i++) {
                ele[i].style.paddingTop = headerHeight+"px";
                ele[i].style.opacity = "1";
            } 
			});
		});
    ');
};

$wa->addInlineStyle('
    .layerslideshow'.$j51_moduleid.' .layerslideshow-title {
        color:' . $j51_title_color . ';
    }
    .layerslideshow'.$j51_moduleid.' .layerslideshow-caption {
        color:' . $j51_text_color . ';
    }
    .layerslideshow' . $j51_moduleid . ' .img-fill {
        max-height: ' . $j51_max_height . 'px;
    }
    .layerslideshow' . $j51_moduleid . ' .info-wrapper,
    .layerslideshow .NextArrow,
    .layerslideshow .PrevArrow {
        background-color: ' . $j51_text_bg_color . ';
    }
    .layerslideshow' . $j51_moduleid . ' .active .layerslideshow-title,
    .layerslideshow' . $j51_moduleid . ' .active .layerslideshow-caption {
        animation-duration: ' . $j51_anim_speed . 'ms;
    }
    @media only screen and (max-width: ' . $j51_title_breakpoint . 'px) {
        .layerslideshow'.$j51_moduleid.' .layerslideshow-title {display: none;}
    }
    @media only screen and (max-width: ' . $j51_caption_breakpoint . 'px) {
        .layerslideshow'.$j51_moduleid.' .layerslideshow-caption {display: none;}
    }
    @media only screen and (max-width: ' . min($j51_title_breakpoint, $j51_caption_breakpoint) . 'px) {
        .layerslideshow'.$j51_moduleid.' .info-wrapper{display: none;}
    }
');

if($j51_overflow_hidden) {
    $wa->addInlineStyle('
        .layerslideshow-title-container,
        .layerslideshow-caption-container {
            overflow: hidden;
        }
    ');
};

echo '<div class="layerslideshow layerslideshow'.$j51_moduleid.'">';
    echo '<div id="layerslideshow'.$j51_moduleid.'">';
    if(!empty($j51slideimages)) {
        foreach ($j51slideimages as $item) {
            if (strpos($item->j51_slidelink, 'vimeo.com') !== false || strpos($item->j51_slidelink, 'youtube.com') !== false) {
                $slidetype = 'item-video';
            } else {
                $slidetype = 'item';
            };
            echo '<div class="'.$slidetype.' item-align-'.$item->j51_slidetextalign.'">';
            echo '<div class="img-fill">';
            echo '<img src="' . htmlspecialchars((string) $item->j51_slideimage, ENT_QUOTES, 'UTF-8') . '" alt="' . htmlspecialchars((string) $item->j51_slidetitle, ENT_QUOTES, 'UTF-8') . '">';
            if (!empty($item->j51_slidetitle) || !empty($item->j51_slidecaption)) {
                echo '<div class="layerslideshow'.$j51_moduleid.'__info info wrapper960">';
                    echo '<div class="info-wrapper" style="max-width: ' . $item->j51_slidetextwidth . 'px;">';
                    if (!empty($item->j51_slidetitle)) {
                        echo '<div class="layerslideshow-title-container">';
                        echo '<'.$j51_title_tag.' class="layerslideshow-title ';
                        if ($item->j51_override_anim) { echo $item->j51_title_anim; } else { echo $j51_title_anim_default; };
                        echo '" style="animation-delay: ' . $j51_title_delay . 'ms">';
                        echo $item->j51_slidetitle;
                        echo '</'.$j51_title_tag.'>';
                        echo '</div>';
                    }
                    if (!empty($item->j51_slidecaption)) {
                        echo '<div class="layerslideshow-caption-container">';
                        echo '<'.$j51_text_tag.' class="layerslideshow-caption ';
                        if ($item->j51_override_anim) { echo $item->j51_caption_anim; } else { echo $j51_caption_anim_default; };
                        echo '" style="animation-delay: ' . $j51_caption_delay . 'ms">';
                        echo $item->j51_slidecaption;
                        echo '</'.$j51_text_tag.'>';
                        echo '</div>';
                    }
                    echo '</div>';
                echo '</div>';
            }
            if (strpos($item->j51_slidelink, 'vimeo.com') !== false || strpos($item->j51_slidelink, 'youtube.com') !== false) {
                echo '<a class="owl-video" href="' . htmlspecialchars((string) $item->j51_slidelink, ENT_QUOTES, 'UTF-8') . '"></a>';
            }
            if (!empty($item->j51_slidelink)) {
                echo '<a href="' . htmlspecialchars((string) $item->j51_slidelink, ENT_QUOTES, 'UTF-8') . '" class="slidelink" aria-label="' . htmlspecialchars((string) $item->j51_slidetitle, ENT_QUOTES, 'UTF-8') . '"';
                if ($item->j51_linknewtab) {
                    echo ' target="_blank" rel="noopener noreferrer"';
                }
                echo '></a>';
            }
            echo '</div></div>';
        } 
    }
    echo '</div>';

    echo '<div id="layerslideshow'.$j51_moduleid.'-controls" class="layerslideshow-controls">';

    echo '<a id="layerslideshow-prev-' . (int) $j51_moduleid . '" class="layerslideshow-prev" href="#" role="button" aria-label="' . Text::_('JPREV') . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M5.41 11H21a1 1 0 0 1 0 2H5.41l5.3 5.3a1 1 0 0 1-1.42 1.4l-7-7a1 1 0 0 1 0-1.4l7-7a1 1 0 0 1 1.42 1.4L5.4 11z"/></svg></a><a id="layerslideshow-next-' . (int) $j51_moduleid . '" class="layerslideshow-next" href="#" role="button" aria-label="' . Text::_('JNEXT') . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M18.59 13H3a1 1 0 0 1 0-2h15.59l-5.3-5.3a1 1 0 1 1 1.42-1.4l7 7a1 1 0 0 1 0 1.4l-7 7a1 1 0 0 1-1.42-1.4l5.3-5.3z"/></svg></a>';
    
    echo '<div id="layerslideshow'.$j51_moduleid.'-play" style="display:none;"></div>';
    echo '<div class="j51-nav-dots" id="layerslideshow'.$j51_moduleid.'-nav">';
        if(!empty($j51slideimages)) {
            foreach ($j51slideimages as $item) {
                echo '<span role="button" class="j51-nav-dot"><span></span></span>'; 
            }
        } 
    echo '</div>';
    echo '</div>';
echo '</div>';

?>
