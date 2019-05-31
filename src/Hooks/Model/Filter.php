<?php
declare( strict_types=1 );

namespace HookAnnotations\Hooks\Model;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("METHOD")
 *
 */
class Filter extends Hook {
}