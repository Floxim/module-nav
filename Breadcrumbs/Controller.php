<?php
namespace Floxim\Nav\Breadcrumbs;

use Floxim\Floxim\System\Fx as fx;

class Controller extends \Floxim\Floxim\Controller\Widget
{
    public function doShow()
    {
        $path = fx::env()->getPath()->copy();
        $this->assign('items', $path);
    }
}