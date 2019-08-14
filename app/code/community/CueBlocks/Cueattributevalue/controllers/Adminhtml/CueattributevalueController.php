<?php
/**
 *
 * Package :- Quick_Action_Attribute_Values_or_Labels
 * Edition :- community
 * Developed By :- CueBlocks.com
 * 
 */
class CueBlocks_Cueattributevalue_Adminhtml_CueattributevalueController extends Mage_Adminhtml_Controller_Action
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
			$this->_addContent($this->getLayout()->createBlock('cueattributevalue/adminhtml_cueattributevalue_edit'))
			->_addLeft($this->getLayout()->createBlock('cueattributevalue/adminhtml_cueattributevalue_edit_tabs'));
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
	public function saveAction()
	{
		if ( $this->getRequest()->getPost() ) 
		{
			try 
			{
				$write = Mage::getSingleton('core/resource')->getConnection('core_write');
				$eavattributeoptionObj = Mage::getModel('eav/entity_attribute_option');
				$eavattributeObj = Mage::getModel('eav/entity_attribute');
				$storeObj = Mage::getModel('core/store');
				

				$write->beginTransaction();
				$attribute_id=$this->getRequest()->getParam('attribute_id');
				
				$StoreDetails = Mage::getModel('core/store')->getCollection()->addFilter('`is_active`','1')->setLoadDefault(true)->load();

							
				foreach($StoreDetails as $store)
				{
					$optionvalue=$this->getRequest()->getParam('attribute_option_value_'.$store['store_id']);
					if($optionvalue!='')
					{ 
						$eavattoptioncollObj =  Mage::getModel('eav/entity_attribute_option')->getCollection()->join('attribute_option_value','(attribute_option_value.option_id = main_table.option_id) and main_table.attribute_id='.$attribute_id.' where attribute_option_value.store_id='.$store["store_id"].' and UPPER(value)="'.addslashes(strtoupper($optionvalue)).'"');
						//$eavattoptioncollObj->printlogquery(true);die;
						foreach($eavattoptioncollObj as $val)
						{
							if(!empty($val))
							{
								$optionvaluearr[]=array('option_id' => $val['option_id'],'value'=>$val['value'],'store_id' => $val['store_id']);
							}
						}
					}
				}
				if(!empty($optionvaluearr))
				{
					$StoreName = Mage::getModel('core/store')->getCollection()->addFilter('`is_active`','1')->addFilter('`store_id`',$optionvaluearr[0]['store_id'])->setLoadDefault(true)->load();
					foreach($StoreName as $name)
					{
						$sname=$name['name'];
					}
					
					Mage::getSingleton('core/session')->setTempSessionVariable($this->getRequest()->getParams());
					Mage::getSingleton('core/session')->addError(Mage::helper('core')->__('Attribute label '.$optionvaluearr[0]['value'].' already exists for '.$sname));
					Mage::getSingleton('core/session')->setCueattributevalueData(false);
					$this->_redirect('*/*/');
					return;
				}
				else
				{
					$dataatt = array('attribute_id' => $attribute_id,'sort_order'  => '0');					
					$write->insert('eav_attribute_option', $dataatt);
					$insertedId=$write->lastInsertId();
					foreach ($StoreDetails as $store) 
					    {
						$optionvalue=$this->getRequest()->getParam('attribute_option_value_'.$store['store_id']);
						if($this->getRequest()->getParam('attribute_option_value_'.$store['store_id']) != '')
						{
						    $data = array(
						        'option_id' => $insertedId,
						        'store_id'  => $store->getId(),
						        'value'     => stripslashes($optionvalue),
						    );
						    $write->insert('eav_attribute_option_value', $data);
						}
					    }
					$write->commit();
					Mage::getSingleton('core/session')->setTempSessionVariable();
					Mage::getSingleton('core/session')->addSuccess(Mage::helper('core')->__('Attribute label was sucessfully saved. '));
					Mage::getSingleton('core/session')->setCueattributevalueData(false);
					$this->_redirect('*/*/');
					return;
				}
			} 
			catch (Exception $e) 
			{
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
				Mage::getSingleton('adminhtml/session')->setCueattributevalueData($this->getRequest()->getPost());
				$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
				return;
			}
		}
		$this->_redirect('*/*/');
	}
}
