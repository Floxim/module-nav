<?php
namespace Floxim\Nav\PageAlias;

use Floxim\Floxim\System\Fx as fx;

class Entity extends \Floxim\Nav\Section\Entity 
{
    public function getFormFields() 
    {
        $ff = parent::getFormFields();
        $ff->findRemove('id', array('url', 'title', 'description', 'h1'))->apply(function(&$f) {
            $f['tab'] = 0;
        });
        return $ff;
    }
    
    public function _getUrl() 
    {
        return $this['linked_page']['url'];
    }
    
    public function _getName() 
    {
        if (!empty($this->data['name'])) {
            return $this->data['name'];
        }
        return $this['linked_page']['name'];
    }
    
    public function getForcedEditableFields() 
    {
        $forced = parent::getForcedEditableFields();
        array_unshift($forced, 'linked_page_id');
        return $forced;
    }
    
    public function isActive() 
    {
        $linked = $this['linked_page'];
        if ($linked['url'] == '/') {
            return $linked['is_current'];
        }
        return $linked['is_active'];
    }
    
    public function hasPage() 
    {
        return false;
    }
    
    public function getDefaultPublishState() 
    {
        return true;
    }
    
    public function isAvailableInSelectedBlock() 
    {
        return false;
    }
}