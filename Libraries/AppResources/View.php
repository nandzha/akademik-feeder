<?php 
namespace Libraries\AppResources;

class View {

	/**
	 * Template Engine
	 * @var Object
	 */	
	protected $templateEngine, $pathView;


	/**
	 * Contstructor
	 * @param ViewDriverInterface $viewDriver
	 */
	public function __construct(ViewDriverInterface $viewDriver, $path) {
		$viewDriver->setViewPath($path);
		$this->pathView = $viewDriver->getViewPath();
		$this->templateEngine = $viewDriver->loadView();
	}

	/**
	 * Render view to user
	 * @param  string $view 
	 * @param  array $data 
	 * @return null       
	 */
	public function render($view, $data = []) {
		echo $this->templateEngine->render($view, $data);
	}

	public function getViewPath(){
		return $this->pathView;
	}
}