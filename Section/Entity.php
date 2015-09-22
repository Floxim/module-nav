<?php
namespace Floxim\Nav\Section;

use Floxim\Floxim\System\Fx as fx;

class Entity extends \Floxim\Main\Page\Entity
{
    public function getFormFields() 
    {
        
        $ff = parent::getFormFields();
        if (in_array($this['type'], array('floxim.nav.page_alias', 'floxim.nav.external_link'))) {
            $ff->findRemove('id', array('url', 'title', 'description', 'h1', 'meta', 'full_text'));
        }
        return $ff;
    }
    
    public function getAvailParentsFinder($ib = null)
    {
        $f = parent::getAvailParentsFinder($ib);
        if (!$f) {
            return;
        }
        if (!$ib) {
            $ib = fx::data('infoblock', $this['infoblock_id']);
        }
        
        $f->whereOr(
            array('infoblock_id', $ib['id']),
            array('id', $ib['page_id'])
        );
        return $f;
    }
    
    public function getFormFieldParentId($field = null)
    {
        $ib = fx::data('infoblock', $this['infoblock_id']);
        if (!$ib['params']['submenu'] || $ib['params']['submenu'] == 'none') {
            return;
        }
        return parent::getFormFieldParentId($field);
    }
}