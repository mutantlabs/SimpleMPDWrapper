#Dictionary API
A set of classes that allows a developer to be able to fetch definitions for words and terms.

NOTE:
This library requires composer. You can find out more over at http://getcomposer.org. There is also a windows installer: https://github.com/johnstevenson/composer-setup or if you are using a unix system (MAC, Linux etc. ) you can find some instructions here: http://getcomposer.org/doc/00-intro.md

Clone or download the contents of this repository onto your local machine, setup your project as you normally would and add the following to the "require" section of your composer.json file:
	
	"willskates/dictionaryapi": "dev-master"

After you have done this, run the command:

	composer install

into your command line from within the specified folder.

##From then on you may use the dictionary like this:

	$dictionary = new WillSkates\Dictionary();

	try {
		$word = $dictionary->word('hello');

		echo $word->getDefinition(); // array(0=>'A greeting ...');
		echo $word->getFirstDefinition() // 'A greeting ...';
		echo $word->getTranslation('fr'); //Bonjour.

		echo $dictionary->word('hello'); // 'A greeting ...' (uses __toString()).
		echo $dictionary->word('hello', 'en'); // 'A greeting ...' (uses __toString()).

		echo $dictionary->word('hello')->translate('de')[0]['translation']; //Hallo :D

	} catch ( WillSkates\Dictionary\Exceptions\FetchException $e ) {
		echo "Oh no, we couldnt get any information for 'hello'";
	} catch ( WillSkates\Dictionary\Exceptions\ConnectionProblemException $e ) {
		echo "oh no the api is not available.";
	} catch ( WillSkates\Dictionary\Exceptions\TranslationFetchException $e ) {
		echo "Just.... dont try to translate an english word into english.";
	}

##Test Status
[![Build Status](https://secure.travis-ci.org/WillSkates/Dictionary-API-Wrapper.png?branch=master)](http://travis-ci.org/WillSkates/Dictionary-API-Wrapper)

###Next
	The plan is to release the ability to define and translate words first. After that's complete
	I will also work towards releasing methods which allow you to view all of the words that begin with a given letter.
	Those lists will also be paginated.

	####Note:
		
		This functionality is already in the api but not planned for this phase of the wrapper, You can achieve that by using http://dictionary.stuffby.ws/letter/a for example.
		Enjoy!