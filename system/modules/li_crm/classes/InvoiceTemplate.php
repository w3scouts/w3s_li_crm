<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * @copyright   Liplex Webprogrammierung und -design Christian Kolb 2011
 * @author      Christian Kolb <info@liplex.de>
 * @license     MIT (see /LICENSE.txt for further information)
 */

/**
 * Class InvoiceTemplate
 */
class InvoiceTemplate extends Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}

	public function getInvoiceTemplates(DataContainer $dc)
	{
		return $this->getTemplateGroup('invoice_');
	}
	
	public function moveHtaccessFile($path, DataContainer $dc) {
		$exportPath = '../'.$path.'/';
		$htaccess = '.htaccess';
		
		if (!file_exists($exportPath))
		{
			mkdir($exportPath, 0777, true);
		}
		
		$file = fopen($exportPath.$htaccess, 'w+');
		fwrite($file, 'deny from all');
		fclose($file);

		return $path;
	}
	
	public function updateDefaultTemplate($dc)
	{
		if ($this->Input->post('isDefaultTemplate'))
		{
			// Reset default template in all templates
			$this->Database->prepare("
				UPDATE tl_li_invoice_template
				SET isDefaultTemplate = 0
				WHERE NOT id = ?
			")->execute($dc->id);
		}
	}
	
	public function getDefaultTemplate() {
		$objTemplate = $this->Database->prepare("
			SELECT id
			FROM tl_li_invoice_template
			WHERE isDefaultTemplate = 1
		")->limit(1)->execute();
		
		if($objTemplate->id != '') {
			return $objTemplate->id;
		} else {
			return 0;
		}
	}
}
