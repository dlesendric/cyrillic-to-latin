<?php

namespace DLS;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}
/**
 * Class WP_CyrillicToLatin
 * @package DLS
 */
class WP_CyrillicToLatin
{

	private $version;


	public function __construct()
	{
		$this->version = get_bloginfo('version');
	}

	private $abs_path = ABSPATH.'/wp-content/languages/';
	/**
	 * Start the plugin
	 */
	public function run(){
		$this->router();
		load_plugin_textdomain( 'cyrillic-to-latin', FALSE, plugin_basename( __DIR__ ) . '/Resources/languages' );
	}


	/**
	 * Loads view from Resources/Views directory, don't use .php extension, it will add this for you
	 * @param string $view
	 * @param array $data
	 */
	protected function loadView($view, $data = array())
	{
		extract($data);
		require_once realpath(CTL_PATH.'Resources/Views/'.$view.'.php');
	}

	/**
	 * Switch state based on $_GET params
	 */
	protected function router(){
		if(array_key_exists('folder',$_GET)){
			$folder =  $_GET['folder'];
			$this->startPage($folder);
		}
		elseif(array_key_exists('convert', $_GET)){
			$file = urldecode($_GET['convert']);
			$this->convert($file);
		}
		elseif(array_key_exists('revert', $_GET)){
			$file = urldecode($_GET['revert']);
			$this->revertFile($file);
		}
		elseif(array_key_exists('reset', $_GET)){
			$file = urldecode($_GET['reset']);
			$this->reset($file);
		}
		else{
			$this->startPage();
		}
	}

	/**
	 * Default loading page
	 * @param string $folder
	 */
	protected function startPage($folder='')
	{
		$files = $this->getFileStructure($folder);
		$data = array(
			'languages'=>$files
		);
		$this->loadView('start',$data);
	}


	/**
	 * Converts .po files to latin serbian, saves automatically .mo extension
	 * Main function for converting cyrillic to latin
	 * Accepts file as path
	 *
	 * @param string $file
	 *
	 * @throws \Exception
	 */
	protected function convert($file)
	{
		$file = realpath($file);
		$this->createBackupFile($file);
		$backup_message = '<h3>Kreiran je backup</h3>';
		$backup_message.= '<p>'.str_replace('.po','.'.$this->getSuffix().'.po',$file).'</p>';
		$backup_message.= '<p>'.str_replace('.po','.'.$this->getSuffix().'.mo',$file).'</p>';
		$this->showMessage($backup_message, 'info');
		$poConverter = new PoConverter($file);
		try{
			$poConverter->convert();
			$moConverter = new MoConverter($file);
			$moConverter->convert();
			$this->showMessage('Uspešno konvertovano, osvežite stranicu da vidite promene.', 'success');
		}
		catch (\Exception $e){
			$this->showMessage($e->getMessage());
		}

		$this->startPage();
	}


	public function reset($file){
		try{
			unlink($file);
			$this->startPage();

		}catch (\Exception $e){
			$this->showMessage($e->getMessage());
		}
	}

	/**
	 * @return string
	 */
	private function getSuffix(){
		$suffix = str_replace('.', '_', $this->version).'_bak';
		return $suffix;
	}
	/**
	 * Creates backup of file which are preparing for converting
	 * Files are saved exp: _RS.po -> _RS.bak.po
	 * @param string $file
	 */
	private function createBackupFile($file)
	{
		if(!file_exists($file)){
			$this->startPage();
			return;
		}
		try{
			copy($file,str_replace('_RS.po','_RS.'.$this->getSuffix().'.po',$file));
			copy(str_replace('_RS.po','_RS.mo',$file),str_replace('_RS.po','_RS.'.$this->getSuffix().'.mo',$file));
		} catch (\Exception $e){
			$this->showMessage($e->getMessage());
		}
	}

	/**
	 * Revert backed up file to original state
	 * @param string $file
	 */
	private function revertFile($file){
		if(!file_exists($file)){
			$this->startPage();
			return;
		}
		try{
			$file = realpath($file);
			copy($file,str_replace('.'.$this->getSuffix(),'',$file));
			$moFile = str_replace('.po', '.mo', $file);
			$originalMo = str_replace('.'.$this->getSuffix().'.po', '.mo', $file);
			copy($moFile,$originalMo);
			unlink($file);
			unlink($moFile);
			$this->showMessage('Uspešno vraćeni fajlovi...', 'success');
		} catch (\Exception $e){
			$this->showMessage($e->getMessage());
		}
		$this->startPage();
	}

	/**
	 * Get file structure of ABSPATH./wp-content/languages/
	 * Returns only _RS.po and folder structure if someone using Loco translate etc.
	 * @param string $folder
	 *
	 * @return array
	 */
	protected function getFileStructure($folder = ''){
		$abs_path = realpath($this->abs_path.$folder);
		if(!file_exists($abs_path)){
			$this->showMessage('Wrong dir...');
			return array();
		}
		$directory = $abs_path;
		$scanned_directory = array_diff(scandir($directory), array('..', '.'));
		$files = array();
		foreach ($scanned_directory as $file){
			$item = array();
			$item['name'] = $file;
			$item['folder'] = $folder;
			if(is_dir($abs_path.DIRECTORY_SEPARATOR.$file)){
				$item['type'] = 'folder';
			}
			else{
				$item['type'] = 'file';
			}
			$item['path'] = ($item['type'] === 'folder') ? $abs_path.DIRECTORY_SEPARATOR.$file.DIRECTORY_SEPARATOR : $abs_path.DIRECTORY_SEPARATOR.$file;
			if((strpos($file, '_RS.po') == false) && $item['type'] != 'folder'){
				continue;
			}
			$dir = dirname($item['path']);
			$base = basename($file);
			$revert_path = $dir.DIRECTORY_SEPARATOR.str_replace('.po','.'.$this->getSuffix().'.po',$base);
			$item['revert'] = file_exists($revert_path);
			$item['revert_path'] = $revert_path;
			$files[] = $item;
			unset($item);
		}
		return $files;
	}

	/**
	 * Shows fancy wordpress notice, pass the message , or html to message
	 * @param string $message
	 * @param string $type
	 */
	protected function showMessage($message, $type = 'error') {
		echo '<div class="notice notice-'.$type.'">'.$message.'</div>';
	}


}