<?php

/**
 * An api that allows developers to fetch the definitions of words on-demand.
 * 
 * @package     WillSkates's Components
 * @subpackage  Dictionary
 * @author      Will Skates <will.skates@ntlworld.com>
 * @copyright   2012 William Skates.
 * @license     Can be seen in "LISCENCE" file, located in the root directory of this repo.
 * @link        http://github.com/WillSkates/Dictionary
 * @version     0.0.1 alpha
 */

use WillSkates\Dictionary\Word;
use WillSkates\Dictionary\Sources\API;

/**
 * Test that the methods required to define words and 
 * manipulate word data work and appropriately handles all errors that occur.
 * 
 * @package     WillSkates's Components
 * @subpackage  Dictionary
 * @author      Will Skates <will.skates@ntlworld.com>
 * @copyright   2012 William Skates.
 * @license     Can be seen in "LISCENCE" file, located in the root directory of this repo.
 * @link        http://github.com/WillSkates/Dictionary
 * @version     0.0.1 alpha
 */
class WordTest extends PHPUnit_Framework_TestCase
{

	protected static $api;

	public function testCreation()
	{
		$word = new Word(array(
			'word' => 'hello'
		), new API(DICTIONARY_API_LOCATION));
	}

	protected function make($word)
	{

		if ( self::$api === null ) {
			self::$api = new API(DICTIONARY_API_LOCATION);
		}

		return new Word(array(
			'word' => $word
		), self::$api);
	}

	public function testDefinition()
	{

		$words = require __DIR__ . '/_words.php';

		foreach ( $words as $word => $details) {

			$word = $this->make($word);
			$definition = $word->getDefinition();

			$this->assertEquals(
				$details['def'],
				$definition[0]
			);

		}

	}

	public function testTranslation()
	{

		$words = require __DIR__ . '/_words.php';
		//Note : is there a way to solve this unicode problem??
		$langs = array('de', 'fr'/*, 'ar'*/);

		foreach ( $words as $word => $details) {

			$word = $this->make($word);

			foreach ( $langs as $lang ) {

				$trans = $word->translate($lang);

				$translations = array();

				foreach ($trans as $t) {
					$translations[] = $t['translation'];
				}

				$this->assertTrue(
					in_array($details[$lang], $translations),
					print_r(array_merge($trans, array('lang'=>$lang)), true)
				);

			}

		}
	}

	public function testGetFirstDefinition()
	{

		$defs = require __DIR__ . '/_words.php';
		$words = array_keys($defs);

		$theWord = reset($words);

		$word = $this->make($theWord);

		$def = $word->getFirstDefinition();

		$this->assertEquals($defs[$theWord]['def'], $def);

		$def = $word->__toString();
		$this->assertEquals($defs[$theWord]['def'], $def);

	}

	public function testGetFirstTranslation()
	{
		$defs = require __DIR__ . '/_words.php';
		$words = array_keys($defs);

		$theWord = reset($words);

		$word = $this->make($theWord);

		$def = $word->getFirstTranslation('de');

		$this->assertEquals($defs[$theWord]['de'], $def);
	}

}