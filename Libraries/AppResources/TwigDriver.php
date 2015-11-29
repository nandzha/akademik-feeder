<?php
namespace Libraries\AppResources;
use Resources;
use Twig_Loader_Filesystem;
use Twig_Environment;
use Twig_Extensions_Extension_Intl;
/**
 * Twig driver
 */
class TwigDriver implements ViewDriverInterface {

	/**
	 * @var Twig_Environment
	 */
	protected $twig;

	/**
	 * @var string
	 */
	protected $viewPath;

	/**
	 * Constructor
	 */
	public function __construct() {
		Resources\Import::composer();
		$this->args = Resources\Config::twig();
		// $this->setViewPath();
		// $this->initiate();
	}


	/**
	 * Initiate twig library, kita bisa menambahkan beberapa
	 * configurasi twig disini
	 * @return null
	 */
	protected function initiate() {
		$twigLoader = new Twig_Loader_FileSystem($this->viewPath);
		$twigLoader->addPath(VIEWS, 'app');
		$this->twig = new Twig_Environment($twigLoader, $this->args['default']);

		$this->twig->addExtension(new AppExtension());
        // $this->twig->addExtension(new Twig_Extensions_Extension_Intl());

    	if ($this->args['default']['debug'])
    		$this->twig->addExtension(new \Twig_Extension_Debug());
	}


	/**
	 * Implement to load template engine seperti twig, blade, atau yang lainya
	 * @return Twig_Environment
	 */
	public function loadView() {
		$this->initiate();
		return $this->twig;
	}

	/**
	 * Set view path
	 * @param string $path
	 */
	public function setViewPath($path) {
		$this->viewPath = $path;
	}


	/**
	 * Get view path
	 * @return string
	 */
	public function getViewPath() {
		return $this->viewPath;
	}
}
