<?php
declare( strict_types=1 );

namespace WpHookAnnotations\Hooks\Model;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 *
 */
class Shortcode extends Hook {
}