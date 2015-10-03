<?php
namespace Libraries\AppResources;

/**
 * View Driver Interface
 */
interface ViewDriverInterface {

	/**
	 * Implement to load template engine seperti twig, blade, atau yang lainya	
	 * @return mixed Sebuah object template engine
	 */
	public function loadView();


	/**
	 * Set view path tempat dimana file view disimpan
	 * @param string $path 
	 */
	public function setViewPath($path);


	/**
	 * Get view path
	 * @return string
	 */
	public function getViewPath();
}