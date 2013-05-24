`<?php
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

use WillSkates\Dictionary\Sources\API;

/**
 * Test that the communication between the wrapper and the web service 
 * works and appropriately handles all errors that occur.
 * 
 * @package     WillSkates's Components
 * @subpackage  Dictionary
 * @author      Will Skates <will.skates@ntlworld.com>
 * @copyright   2012 William Skates.
 * @license     Can be seen in "LISCENCE" file, located in the root directory of this repo.
 * @link        http://github.com/WillSkates/Dictionary
 * @version     0.0.1 alpha
 */
class APITest extends PHPUnit_Framework_TestCase
{

	public function testCreation()
	{
		$api = new API(DICTIONARY_API_LOCATION);
	}

	protected function make()
	{
		return new API(DICTIONARY_API_LOCATION);
	}

	public function testWords()
	{

		$api = $this->make();
		$words = require __DIR__ . '/_words.php';
		$langs = array('de', 'fr'/*, 'ar'*/);

		foreach ( $words as $word => $details ) {

			$res = $api->get('word/' . $word);

			$this->assertFalse($res->error);

			$this->assertEquals(
				$details['def'],
				$res->result[0]['definition']
			);

			$res = $api->word($word);
			$def = $res->getDefinition();
			$def = reset($def);

			$this->assertEquals(
				$details['def'],
				$def
			);

			foreach ( $langs as $lang ) {

				$res = $api->word($word, $lang);
				$trans = $res->translate($lang);

				$translations = array();

				foreach ($trans as $t) {
					$translations[] = $t['translation'];
				}

				$this->assertTrue(
					in_array($details[$lang], $translations),
					print_r(array_merge($trans, array('lang'=>$lang, 'word'=>$word)), true)
				);

			}

		}

	}

	public function testRandom()
	{

		$api = $this->make();

		$current = 1;

		while ( $current < 1000 ) {

			$res = $api->get('random');
			$res->count = $current;

			$this->assertFalse($res->error, print_r($res, true));

			$res = $api->random();
			$res->count = $current;

			$this->assertFalse($res->error, print_r($res, true));

			$current++;

		}

	}

	/**
     * @expectedException \WillSkates\Dictionary\Exceptions\ConnectionProblemException
     */
	public function testConnectionProblemException()
	{
		$api = new API('https://dasiosdjwaionmioajdioawjdioawjiodjawiodwadawjdkoap.edhjauidhwuidhoahdiawh');
		$api->get('word/hello');
	}

	/**
     * @expectedException \WillSkates\Dictionary\Exceptions\FetchException
     */
	public function testFetchException()
	{
		$api = $this->make();
		$api->get('word/contrafabulationarycontraintinousgoo');
	}

	/**
     * @expectedException \WillSkates\Dictionary\Exceptions\TranslationFetchException
     */
	public function testTranslationFetchException()
	{	
		//translate an english word into english? ARE YOU INSANE???!!?!?!?!?!?
		$api = $this->make();
		$api->word('hello', 'en');
	}

}