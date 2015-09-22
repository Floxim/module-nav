<?php
namespace Floxim\Nav\ExternalLink;

use Floxim\Floxim\System\Fx as fx;

class Entity extends \Floxim\Nav\Section\Entity 
{
    
    public function _getUrl() 
    {
        $url = $this['external_url'];
        $url = trim($url);
        if (!preg_match("~^(http|https|ftp)://~i", $url) && !preg_match("~^mailto:~i", $url)) {
            $url = 'http://'.$url;
        }
        return $url;
    }
    
    public function getForcedEditableFields()
    {
        $forced = parent::getForcedEditableFields();
        array_unshift($forced, 'external_url');
        return $forced;
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