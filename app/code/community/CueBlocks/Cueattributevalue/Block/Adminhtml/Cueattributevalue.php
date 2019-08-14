<?php
/**
 *
 * Package :- Quick_Action_Attribute_Values_or_Labels
 * Edition :- community
 * Developed By :- CueBlocks.com
 * 
 */
class CueBlocks_Cueattributevalue_Block_Adminhtml_Cueattributevalue extends Mage_Adminhtml_Block_Widget_Grid_Container
{
	public function __construct()
	{
		$this->_controller = 'adminhtml_cueattributevalue';
		$this->_blockGroup = 'cueattributevalue';
		$this->_headerText = Mage::helper('cueattributevalue')->__('Attribute Manager');
		$this->_addButtonLabel = Mage::helper('cueattributevalue')->__('Add Attribute Options');
		parent::__construct();
	}
}
