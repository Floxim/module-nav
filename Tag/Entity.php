<?php
namespace Floxim\Nav\Tag;

class Entity extends \Floxim\Nav\Classifier\Entity
{
    public function isAvailableInSelectedBlock()
    {
        return $this->getFinder()->contentExists();
    }
}