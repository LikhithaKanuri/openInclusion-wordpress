<?php


/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

class PHPUnit_TestListener {
    /**
     * An error occurred.
     *
     * @param  object
     * @param  object
     * @access public
     * @abstract
     */
    function addError(&$test, &$t) { /*abstract */ }

    /**
     * A failure occurred.
     *
     * @param  object
     * @param  object
     * @access public
     * @abstract
     */
    function addFailure(&$test, &$t) { /*abstract */ }

    /**
     * A test ended.
     *
     * @param  object
     * @access public
     * @abstract
     */
    function endTest(&$test) { /*abstract */ }

    /**
     * A test started.
     *
     * @param  object
     * @access public
     * @abstract
     */
    function startTest(&$test) { /*abstract */ }
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>
