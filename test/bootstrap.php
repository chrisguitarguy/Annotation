<?php
/**
 * Annotation - Test Bootstrap
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->add('Chrisguitarguy\\AnnotationTest', __DIR__);
