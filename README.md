Annotation
==========

[![Build Status](https://travis-ci.org/chrisguitarguy/Annotation.png)](https://travis-ci.org/chrisguitarguy/Annotation)

A little PHP library for parsing PHP docblock "annotations"

Example:

```php
<?php
/**
 * This is a docblock.
 *
 * @ThisIsAnAnnotation(argument=2)
 */
 class AClass
 {
    // ...
 }
 ```

Goals
~~~~~

* Self-contained
* No nested annotation parsing
* Annotations that are not "registered" are ignored
