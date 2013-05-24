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

use WillSkates\Dictionary\Dictionary;
use WillSkates\Dictionary\Sources\API;

/**
 * Test that the methods required to define words
 * work and appropriately handles all errors that occur.
 * 
 * @package     WillSkates's Components
 * @subpackage  Dictionary
 * @author      Will Skates <will.skates@ntlworld.com>
 * @copyright   2012 William Skates.
 * @license     Can be seen in "LISCENCE" file, located in the root directory of this repo.
 * @link        http://github.com/WillSkates/Dictionary
 * @version     0.0.1 alpha
 */

class DictionaryTest extends PHPUnit_Framework_TestCase
{

	public function testCreation()
	{
		$dict = new Dictionary(new API(DICTIONARY_API_LOCATION));
		$dict = new Dictionary();
	}

	protected function make()
	{
		return new Dictionary(new API(DICTIONARY_API_LOCATION));
	}

	public function testWords()
	{

		$dictionary = $this->make();
		$words = require __DIR__ . '/_words.php';
		$langs = array('de', 'fr'/*, 'ar'*/);

		foreach ( $words as $word => $details ) {

			$res = $dictionary->word($word);
			$def = $res->getDefinition();
			$def = reset($def);

			$this->assertEquals(
				$details['def'],
				$def
			);

			foreach ( $langs as $lang ) {

				$res = $dictionary->word($word, $lang);
				$translate = $res->translate($lang);
			}

		}

	}

	public function testRandom()
	{


		$dictionary = $this->make();

		$current = 1;

		while ( $current < 1000 ) {

			$res = $dictionary->random();
			$res->count = $current;

			$this->assertFalse($res->error, print_r($res, true));

			$current++;

		}

	}

}