<?php
/**
 *
 * Package :- Quick_Action_Attribute_Values_or_Labels
 * Edition :- community
 * Developed By :- CueBlocks.com
 * 
 */
class CueBlocks_Cueattributevalue_Adminhtml_CueattributevaluedeleteController extends Mage_Adminhtml_Controller_Action
{
	protected function _initAction()
	{
		$this->_forward('edit');
		return $this;
	}   
	public function indexAction()
	{	
		$this->_initAction();       
		$this->renderLayout();
	}
	public function editAction()
	{
		$cueattributevalueId     = $this->getRequest()->getParam('id');
		if ($cueattributevalueId == 0) 
		{
			$this->loadLayout();
			$this->_setActiveMenu('cueattributevalue/items');
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item Manager'), Mage::helper('adminhtml')->__('Item Manager'));
			$this->_addBreadcrumb(Mage::helper('adminhtml')->__('Item News'), Mage::helper('adminhtml')->__('Item News'));
			$this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
			$this->_addContent($this->getLayout()->createBlock('cueattributevalue/adminhtml_cueattributevaluedelete_edit'))
			->_addLeft($this->getLayout()->createBlock('cueattributevalue/adminhtml_cueattributevaluedelete_edit_tabs'));
			$this->renderLayout();
		} 
		else 
		{
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cueattributevalue')->__('Item does not exist'));
			$this->_redirect('*/*/');
		}
	}
	public function newAction()
	{
		$this->_forward('edit');
	}
	public function deleteAction()
	{
		if($this->getRequest()->getParam('value_id') !=''  && $this->getRequest()->getParam('option_id') !='') 
		{
			try 
			{
				$value_id=$this->getRequest()->getParam('value_id');
				$write = Mage::getSingleton('core/resource')->getConnection('core_write');
				$optionvalue =  Mage::getModel('eav/entity_attribute_option')->getCollection()->join('attribute_option_value','(attribute_option_value.option_id = main_table.option_id) and attribute_option_value.value_id In ('.$value_id.')');
				foreach($optionvalue as $option)
				{
					$valuedel=$option['value_id'];
					$optiondel=$option['option_id'];
					if($option['store_id']=='0')
					{	$val=$option['value'];
						$eavattributeoptionObj = Mage::getModel('eav/entity_attribute_option');
						$eavattributeoptionObj->setId($optiondel)
						->delete();
						Mage::getSingleton('core/session')->setDelSessionVariable($this->getRequest()->getparams());
					}
					else
					{	
						$write->delete('eav_attribute_option_value', $write->quoteInto('value_id=?', $valuedel));
						Mage::getSingleton('core/session')->setDelSessionVariable($this->getRequest()->getparams());
					}
				}
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Label was successfully deleted.'));
				$this->_redirect('*/*/');
			} 
			catch (Exception $e) 
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				$this->_redirect('*/*/');
			}
		}
		else
		{
			Mage::getSingleton('core/session')->setDelSessionVariable($this->getRequest()->getparams());
			$this->_redirect('*/*/');
		}
	}
	
}
