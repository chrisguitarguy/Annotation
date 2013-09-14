<?php

require __DIR__ . '/../vendor/autoload.php';

class Annotation
{
    private $args;
    private $ctx;

    public function __construct(array $args, $ctx)
    {
        $this->args = $args;
        $this->ctx = $ctx;
    }

    public function __toString()
    {
        return sprintf(
            'Annotation(%s, %d)',
            isset($this->args['place']) ? $this->args['place'] : 'UNKNOWN',
            $this->ctx->getContext()
        );
    }
}

/**
 * @Annot(place="class",)
 */
class ToCheck
{
    /**
     * @Annot(place="property", again=true, test=null, nope=false)
     */
    public $prop;

    /**
     * @Annot(place="property")
     */
    public $prop_again;

    /**
     * @Annot(place="method")
     */
    public function chk()
    {
        
    }

    /**
     * @Annot(place="method")
     */
    public function chkAgain()
    {
        
    }
}

/**
 * @Annot(place="function")
 */
function a_function()
{
    
}

$reader = new \Chrisguitarguy\Annotation\DefaultReader();
$reader->getCollection()->add('Annot', 'Annotation');

echo 'Reading Property', PHP_EOL;
foreach ($reader->readProperty('ToCheck', 'prop') as $a) {
    echo $a, PHP_EOL;
}

echo '--', PHP_EOL, PHP_EOL;

echo 'Reading ALL properties', PHP_EOL;
foreach ($reader->readProperties('ToCheck') as $name => $annotations) {
    echo 'Properies for ', $name, PHP_EOL;
    foreach ($annotations as $a) {
        echo $a, PHP_EOL;
    }
}

echo '--', PHP_EOL, PHP_EOL;

echo 'Reading method', PHP_EOL;
foreach ($reader->readMethod('ToCheck', 'chk') as $a) {
    echo $a, PHP_EOL;
}

echo '--', PHP_EOL, PHP_EOL;

echo 'Reading all methods', PHP_EOL;
foreach ($reader->readMethods('ToCheck') as $meth => $annotations) {
    echo 'Annotations for ', $meth, PHP_EOL;
    foreach ($annotations as $a) {
        echo $a, PHP_EOL;
    }
}

echo '--', PHP_EOL, PHP_EOL;

echo 'Reading function', PHP_EOL;
foreach ($reader->readFunction('a_function') as $a) {
    echo $a, PHP_EOL;
}

echo '--', PHP_EOL, PHP_EOL;

echo 'Reading class', PHP_EOL, PHP_EOL;
foreach ($reader->readClass('ToCheck') as $a) {
    echo $a, PHP_EOL;
}
