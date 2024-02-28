<?php

namespace App\Services;

use App\Contracts\LanguageInterface;
use App\Language;
use Barryvdh\TranslationManager\Models\Translation;

class LanguageService implements LanguageInterface 
{
	/**
	 * Object of Language class.
	 *
	 * @var $language 
	 */
	private $language;

	/**
	 * Create a new instance of LanguageService class.
	 *
	 * @return void
	 */

	public function __construct()
	{
		$this->language = new Language();
		$this->translation = new Translation ();
	}

	public function getAll()
	{
		return $this->language->all();
	}

	public function getLanguageByName($name)
	{
		return $this->language->where('language', $name)->first();
	}

	public function getLanguageByNative($name)
	{
		return $this->language->where('native', $name)->first();
	}

	public function getLanguageByCode($code)
	{
		return $this->language->where('code', $code)->first();
	}

	public function getGroupTranslation()
	{
		return $this->translation->where('group', 'angular')->where('value', '!=', NULL)->get();
	}

}