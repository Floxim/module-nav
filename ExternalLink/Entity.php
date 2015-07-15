<?php
namespace Floxim\Nav\ExternalLink;

use Floxim\Floxim\System\Fx as fx;

class Entity extends \Floxim\Nav\Section\Entity 
{
    public function getFormFields() {
        $ff = parent::getFormFields();
        $ff->findRemove('id', array('url', 'title', 'description', 'h1'))->apply(function(&$f) {
            $f['tab'] = 0;
        });
        return $ff;
    }
    
    public function _getUrl() {
        return $this['external_url'];
    }
    
    public function getForcedEditableFields(){
        $forced = parent::getForcedEditableFields();
        array_unshift($forced, 'external_url');
        return $forced;
    }
}