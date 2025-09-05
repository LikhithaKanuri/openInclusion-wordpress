<?php


/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

class PHPUnit_TestFailure {
    /**
     * @var    object
     * @access private
     */
    var $_failedTest;

    /**
     * @var    string
     * @access private
     */
    var $_thrownException;

    /**
     * Constructs a TestFailure with the given test and exception.
     *
     * @param  object
     * @param  string
     * @access public
     */
    function PHPUnit_TestFailure(&$failedTest, &$thrownException) {
        $this->_failedTest      = &$failedTest;
        $this->_thrownException = &$thrownException;
    }

    /**
     * Gets the failed test.
     *
     * @return object
     * @access public
     */
    function &failedTest() {
        return $this->_failedTest;
    }

    /**
     * Gets the thrown exception.
     *
     * @return object
     * @access public
     */
    function &thrownException() {
        return $this->_thrownException;
    }

    /**
     * Returns a short description of the failure.
     *
     * @return string
     * @access public
     */
    function toString() {
        return sprintf(
          "TestCase %s->%s() failed: %s\n",

          get_class($this->_failedTest),
          $this->_failedTest->getName(),
          $this->_thrownException
        );
    }
}

/*
 * Local variables:
 * tab-width: 4
 * c-basic-offset: 4
 * c-hanging-comment-ender-p: nil
 * End:
 */
?>
