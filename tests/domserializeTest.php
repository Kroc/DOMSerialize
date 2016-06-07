<?php
/**
 * Test suite for DOMSerialize
 *
 * @copyright   Copyright 2016, Kroc Camen, all rights reserved
 * @author      Kroc Camen <kroc@camendesign.com>
 * @license     BSD-2-Clause
 *
 *      NOTE:   the "phpunit.xml.dist" file in root defines the configuration of PHPUnit, including the autoloading
 *              of our project's PHP source files, which is why you don't see any `include` statements up here
 */

/* because programming errors in our testing code would be a very bad thing, we will force PHP to emit all errors
 * including simple warnings. since PHPUnit will catch PHP errors, a mistake in the test code will appear as if the
 * particular test failed due to the test code linting error. This isn't ideal, but it's better than a typo in the
 * test code causing a test to give wrong results */
error_reporting( E_ALL | E_STRICT );

//shorthand the namespace
use kroc\domserialize as domserialize;

/**
 *
 */
class DOMSerializeTest
        extends PHPUnit_Framework_TestCase
{
        private function roundTripTest ($serialized_xml, $expected_sxml = '')
        {
                $document = domserialize\DOMDocumentSerialize::deserialize ($serialized_xml);
                $result = $document->serialize();
                
                echo $result . "\n";
                
                return empty( $expected_sxml )
                        ? $serialized_xml == $result
                        : $result == $expected_sxml
                ;
        }
        
        /**
         * @test
         */
        public function conformsToExpectedBehaviour ()
        {
                $this->assertTrue(
                        $this->roundTripTest( 'text' )
                ,       'Single-word text node'
                );
                $this->assertTrue(
                        $this->roundTripTest( 'test text' )
                ,       'Multi-word text node'
                );
                $this->assertTrue(
                        $this->roundTripTest( ' whitespace ', 'whitespace' )
                ,       'Leading / trailing whitespace removed'
                );
                
                $this->assertTrue(
                        $this->roundTripTest( '<a>' )
                ,       'Simple element'
                );
                $this->assertTrue(
                        $this->roundTripTest( '<a><b><c>', '<a> <b> <c>' )
                ,       'Elements side by side (no whitespace)'
                );
                $this->assertTrue(
                        $this->roundTripTest( '<a> <b> <c>' )
                ,       'Whitespace between elements'
                );
                $this->assertTrue(
                        $this->roundTripTest( '<a> b <c> d <e>', '<a> b <c> d <e>' )
                ,       'Text between elements'
                );
        }
}

?>