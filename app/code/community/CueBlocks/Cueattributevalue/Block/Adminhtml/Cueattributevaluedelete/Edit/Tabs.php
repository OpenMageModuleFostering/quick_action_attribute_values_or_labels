<?php
/**
 *
 * Package :- Quick_Action_Attribute_Values_or_Labels
 * Edition :- community
 * Developed By :- CueBlocks.com
 * 
 */
class CueBlocks_Cueattributevalue_Block_Adminhtml_Cueattributevaluedelete_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('cueattributevalue_tabs');
		$this->setDestElementId('edit_form');
		$this->setTitle(Mage::helper('cueattributevalue')->__('Delete Attribute Label'));
	}
	protected function _beforeToHtml()
	{
		$this->addTab('form_section', array(
		'label'     => Mage::helper('cueattributevalue')->__('Attribute Information'),
		'title'     => Mage::helper('cueattributevalue')->__('Attribute Information'),
		));
		return parent::_beforeToHtml();
	}
}
