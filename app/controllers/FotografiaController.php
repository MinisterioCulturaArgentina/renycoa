<?php
/**
 * Copyright (C) 2008 Marcelo Costanzi - www.dotdev.com.ar
 * 
 * This file is part of Sistema RENYCOA
 *
 * Sistema RENYCOA is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Sistema RENYCOA is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Sistema RENYCOA.  If not, see <http://www.gnu.org/licenses/>.
 * 
 */

class FotografiaController extends CustomController
{
	var $_parent;
	
	public function init()
    {       
    	parent::init();
    	
    	if ( $this->getRequest()->getParam('lote_id') || $this->getRequest()->getParam('objeto_id') || $this->getRequest()->getParam('yacimiento_id'))
        {
			if ($this->getRequest()->getParam('lote_id'))
			{
				$this->_parent = Lote::findByPK($this->getRequest()->getParam('lote_id'));
			}
			elseif ($this->getRequest()->getParam('objeto_id'))
			{ 
				$this->_parent = Objeto::findByPK($this->getRequest()->getParam('objeto_id'));
			}
			else
			{
				$this->_parent = Yacimiento::findByPK($this->getRequest()->getParam('yacimiento_id'));
			}
        }
        
    	$this->view->mode = 'edit';
		if ($this->getRequest()->getParam('mode') == 'view')
		{
			$this->view->mode = 'view';
		}
    }
    
	
	function indexAction()
    {
    	$this->_helper->layout->setLayout('iframe');
		$this->view->pageTitle = "Alta de Fotografías";
    	
    	$this->view->show_fotografias = false;
    	$this->view->fotografias = array();
    	
    	if (strpos($_SERVER['REQUEST_URI'], 'yacimiento_id'))
    	{
    		$this->view->parent_type = 'yacimiento';
    	}
    	else if (strpos($_SERVER['REQUEST_URI'], 'lote_id'))
    	{
    		$this->view->parent_type = 'lote';
    	}
    	else if (strpos($_SERVER['REQUEST_URI'], 'objeto_id'))
    	{
    		$this->view->parent_type = 'objeto';
    	}
    	
        if ( is_object($this->_parent) && $this->_parent->id )
        {
			$parent_class = get_class($this->_parent);
			$method = 'findAllFrom'.$parent_class;
			$this->view->show_fotografias = true;
			
			$fotografias = Fotografia::$method($this->_parent->id);
			$max_imgs = 2;
			
			$total_pages =intval(count($fotografias) / $max_imgs);

			if ( ($total_pages * $max_imgs) < count($fotografias) )
			{
				$total_pages++;
			}
			$current_page = 1;
			$start_index = 0;
			if ($this->getRequest()->getParam('page'))
			{
				$current_page = $this->getRequest()->getParam('page');
				$start_index = ($current_page * $max_imgs) - $max_imgs; 
			}
			
			$this->view->fotografias = $fotografias;
			$this->view->parent_type = strtolower($parent_class);
			$this->view->parent_id = $this->_parent->id;
			$this->view->img_folder = Zend_Registry::get('config')->img_folder;
			$this->view->total_pages = $total_pages;
			$this->view->max_imgs = $max_imgs;
			$this->view->current_page = $current_page;
			$this->view->start_index = $start_index;
			$this->view->last_index = count($fotografias) - 1;
        }
    }
    
	function editAction()
	{
		$this->_helper->layout->setLayout('iframe');
		$this->view->pageTitle = "Alta de Fotografías";
		
		if ( is_object($this->_parent) && $this->_parent->id )
		{
			$form = new FormFotografia();
			$this->view->parent_type = strtolower(get_class($this->_parent));
			$this->view->parent_id = $this->_parent->id;
			
			$fotografia = Fotografia::findByPK($this->getRequest()->getParam('id'));
			
			if ($this->_request->isPost())
			{
				$formData = $this->_request->getPost();
				if ($form->isValid($formData))
				{
					$fotografia = new Fotografia();
					$fotografia->descripcion = $form->getValue('descripcion');
					try
					{
						$fotografia->upload($form->getValue('fotografia'));
						
						$relation_type = get_class($this->_parent).'Fotografia';
						$relation = new $relation_type();
						$parent_id =  strtolower(get_class($this->_parent)).'_id';
						$relation->$parent_id = $this->_parent->id;
						$relation->fotografia_id = $fotografia->id;
						$relation->save();
						
						Zend_Registry::get('messagehandler')->add('INFO', 'La fotografía se incluyó correctamente.');
						return $this->_redirect(getControllerUrl('fotografia','index',array(strtolower(get_class($this->_parent)).'_id' => $this->_parent->id)));
					}
					catch (ErrorException $e)
					{
						Zend_Registry::get('messagehandler')->add('ERROR', 'Archivo de imagen no válido.');
						return $this->_redirect(getControllerUrl('fotografia','index',array(strtolower(get_class($this->_parent)).'_id' => $this->_parent->id)));
					}
				}
				else
				{
					$form->populate($formData);
				}
				
			}
			else
			{
				$form->populate((array)$fotografia);
				$form->populate(array(strtolower(get_class($this->_parent)).'_id' => $this->_parent->id));
			}
			$this->view->upload_max_filesize = ini_get('upload_max_filesize');
			$this->view->form = $form;
		}
		else
		{
			return $this->_redirect(getControllerUrl('fotografia','index'));
		}		
    }
    
	public function getAction() 
    { 
    	$this->_helper->layout->setLayout('ajax');

    	if ($this->getRequest()->getParam('id'))
    	{
    		$fotografia = Fotografia::findByPK($this->getRequest()->getParam('id'));
    	}
    	if (isset($fotografia) && is_object($fotografia))
    	{
    		$this->view->fotografia = $fotografia;
    		$this->view->img_folder = Zend_Registry::get('config')->img_folder;
    	}
    	else
    	{
    		$this->getHelper("ViewRenderer")->setNoRender();
    	}
    }
    
	public function deleteAction() 
    { 
    	$this->_helper->layout->setLayout('iframe');
    	$fotografia = Fotografia::findByPK($this->getRequest()->getParam('id'));
    	Fotografia::delete($fotografia);
    	Zend_Registry::get('messagehandler')->add('INFO', 'Se borró la fotografía.');
		return $this->_redirect(getControllerUrl('fotografia','index',array(strtolower(get_class($this->_parent)).'_id' => $this->_parent->id)));
    }
    
}