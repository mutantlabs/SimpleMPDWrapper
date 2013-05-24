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

namespace WillSkates\Dictionary;

/**
 * A class to provide extra functionality to describe or 
 * translate words that haven't been translated.
 * 
 * @package     WillSkates's Components
 * @subpackage  Dictionary
 * @author      Will Skates <will.skates@ntlworld.com>
 * @copyright   2012 William Skates.
 * @license     Can be seen in "LISCENCE" file, located in the root directory of this repo.
 * @link        http://github.com/WillSkates/Dictionary
 * @version     0.0.1 alpha
 */
class Word extends \stdClass
{

	/**
	 * The definition(s) of this word, if they have
	 * already been fetched from the api
	 * 
	 * @var Array
	 */
	protected $definition;

	/**
	 * A list of the translations of this word
	 * organized as a multi dimensional array.
	 * e.g. en => array()
	 * 
	 * @var array.
	 */
	protected $translations;

	/**
	 * An object that helps establish the link between
	 * this object and the dictionary data.
	 * 
	 * @var Sources\ISource
	 */
	protected $dataSource;

	/**
	 * Create a word object with some data in it.
	 * 
	 * @param array $data The data regarding this word.
	 */
	public function __construct(array $data, Sources\ISource $dSource)
	{

		$this->dataSource = $dSource;

		$this->translations = array();
		$this->definition = array();

		foreach ( $data as $key => $value ) {

			$this->$key = $value;

		}
	}

	/**
	 * Retrieve the definition of this word as a string.
	 * 
	 * @return String the definition of this word.
	 */
	public function getDefinition()
	{

		if ( empty($this->definition) ) {
			$word = $this->dataSource->word($this->word);
			$this->definition = $word->definition;

			unset($word);
		}

		
		return $this->definition;

	}

	/**
	 * Get the translation of this word in a particular language.
	 * 
	 * @param  String $lang The 2 character iso code for the 
	 *                      language to translate into ('en').
	 * 
	 * @return array This word translated into another language.
	 */
	public function translate($lang)
	{

		if ( isset($this->translations[$lang]) ) {
			return $this->translations[$lang];
		}

		$this->translations[$lang] = array();

		$translated = $this->dataSource->word($this->word, $lang);

		if ( isset($translated->translations[$lang]) ) {

			$this->translations[$lang] = $translated->translations[$lang];

		} else {
			//@TODO throw exception.
		}

		unset($translated);

		return $this->translations[$lang];

	}

	/**
	 * Get the first definition in the list of definitions for this word.
	 * 
	 * @return Mixed Either the first definition of this word or false if no definitions
	 *               were available.
	 */
	public function getFirstDefinition()
	{
		$definitions = $this->getDefinition();
		if ( isset($definitions[0]) ) {
			return $definitions[0];
		} else {
			return false;
		}
	}

	/**
	 * Get the first definition of this word in 
	 * a given language.
	 * 
	 * @param  String $lang The 2 character iso code for the 
	 *                      language to translate into ('en').
	 * 
	 * @return array The translation information.
	 */
	public function getFirstTranslation($lang)
	{
		$translation = $this->translate($lang);
		$translation = reset($translation);
		return $translation['translation'];
	}

	/**
	 * When this object is echoed or casted as a 
	 * string, return it's definition.
	 *
	 * @return String The definition of this word.
	 */
	public function __toString()
	{
		return (String)$this->getFirstDefinition();
	}

}