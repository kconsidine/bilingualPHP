<?php
/*
	// Setup Languages
	$lang_config = [
		"root_path" => ROOTDIR,				// path to where root of site is
		"lang_path" => "includes/lang/", 	// path relative to root where languages files are
		"languages" => [					// available '.json' language translation files
			"default" => "default",
			"spanish" => "spanish"
		]
	];

	Set accessible language terms based on json langauge data files.

	Return die instead of false because the integration of this class is critical for rendering.
*/
class Language {

	protected $lang_config = [];

	protected $lang_data = [];

	protected $language = [];

	public function __construct($config)
	{
		$this->lang_config = $config;
		if (!$this->checkConfig()) {
			die("ERROR: LANGUAGE: Missing Config");
		}
	}

	public function get($term_code) {
		if (empty($this->lang_data)) {
			die("ERROR: LANGUAGE: Get W/O Set");
		}

		if (array_key_exists($term_code, $this->language)) {
			return $this->language[$term_code];
		} else {
			// Term not found, but output term code.
			return $term_code;
		}
	}

	public function set($code = "default")
	{
		if (!array_key_exists($code, $this->lang_config['languages'])) {
			die("ERROR: LANGUAGE: Lang '".$code."' Doesn't Exist");
		}

		if (!$this->lang_data = $this->getSetLangData($code)) {
			die("ERROR: LANGUAGE: Set Lang Data Error");
		}

		$local_language = [];
		foreach ($this->lang_data as $i => $language_set) {
			if (is_array($language_set)) {
				foreach ($language_set as $k => $language_result) {
					$local_language[$i."/".$k] = $language_result;
				}
			} else {
				$local_language[$i] = $language_set;
			}
		}

		$this->language = $local_language;
	}

	private function getSetLangData($code)
	{
		if ($data = file_get_contents($this->lang_config['root_path']."/".$this->lang_config['lang_path'].$this->lang_config['languages'][$code].".json")) {
			return json_decode($data, TRUE);
		} else {
			return false;
		}
	}

	private function checkConfig()
	{
		return true;
		/*
		if (
			!empty($this->$auth_config) && 
			array_key_exists('cookie_name', $this->$auth_config)
		) {
			return true;
		}

		return false;
		*/
	}

}