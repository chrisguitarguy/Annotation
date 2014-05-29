<?php
/**
 * Annotation - Test Bootstrap
 *
 * @package     Chrisguitarguy\Annotation
 * @copyright   Christopher Davis <http://christopherdavis.me>
 * @license     http://opensource.org/licenses/MIT MIT
 */

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Chrisguitarguy\\AnnotationTest\\', __DIR__);

require __DIR__ . '/stubs.php';
