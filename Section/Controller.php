<?php
namespace Floxim\Nav\Section;

use Floxim\Floxim\System\Fx as fx;
use Floxim\Floxim\System;

class Controller extends \Floxim\Main\Page\Controller
{

    public function doListInfoblock()
    {
        $c_page = fx::env('page');
        if ($c_page) {
            $c_page_id = $c_page->get('id');
            $path = $c_page->getParentIds();
            $path [] = $c_page_id;
        } else {
            $path = array();
        }
        $submenu_type = $this->getParam('submenu');
        switch ($submenu_type) {
            case 'none':
                break;
            case 'active':
                $this->setParam('parent_id', $path);
                break;
            case 'all':
            default:
                $this->setParam('parent_id', false);
                break;
        }
        if ($submenu_type !== 'none') {
            $this->onItemsReady(function ($e) {
                foreach ($e['items'] as $item) {
                    $e['controller']->acceptContent(array(
                        'title'     => fx::alang('Add subsection', 'component_section'),
                        'parent_id' => $item['id']
                    ), $item);
                }
            });
        }
        $res = parent::doListInfoblock();
        return $res;
    }

    public function doList()
    {
        $this->onItemsReady(function ($e) {
            $ctr = $e['controller'];
            $items = $e['items'];
            $extra_ib_ids = (array) $ctr->getParam('extra_infoblocks', array());
            $extra_ibs = fx::data('infoblock')->where('id', $extra_ib_ids, 'in')->all();
            foreach ($extra_ibs as $extra_ib) {
                $extra_q = $extra_ib
                                ->initController()
                                ->getFinder()
                                ->where('infoblock_id', $extra_ib['id']);
                $extra_items = $extra_q->all();
                $items->concat($extra_items);
            }
            if (!$ctr->getParam('is_fake')) {
                $items->unique();
            }
            if (count($items) === 0) {
                //return;
            }
            $parent_ids = array_unique($items->getValues('parent_id'));
            if (count($parent_ids) < 2) {
                $e['items']->addFilter('parent_id', $ctr->getParentId());
                return;
            }
            $e['items'] = fx::tree($items, 'submenu', $ctr->getParam('extra_root_ids', array()));
        });
        return parent::doList();
    }
    
    protected function getFakeItems($count = 4) {
        $items = parent::getFakeItems(4);
        $items[1]['is_active'] = true;
        $submenu = $this->getParam('submenu');
        if ($submenu === 'none') {
            return $items;
        }
        if ($submenu === 'active') {
            $items[1]['submenu'] = parent::getFakeItems(rand(2, 4));
            return $items;
        }
        if ($submenu === 'all') {
            foreach ($items as $i) {
                $count = rand(0, 4);
                $i['submenu'] = $count === 0 ? fx::collection() : parent::getFakeItems($count);
            }
            return $items;
        }
    }

    protected function addSubmenuItems($e)
    {
        $items = $e['items'];
        $submenu_type = $this->getParam('submenu');
        if ($submenu_type === 'none') {
            return;
        }
        $finder = fx::content($this->getComponent()->get('keyword'));
        switch ($submenu_type) {
            case 'all':
                $finder->descendantsOf($items);
                break;
            case 'active':
                $path = fx::env('page')->getPath();
                $finder->where('parent_id', $path->getValues('id'));
                break;
        }
        $items->concat($finder->all());
    }

    public function doListSelected()
    {
        $this->onItemsReady(function ($e) {
            $e['controller']->setParam('extra_root_ids', $e['items']->getValues('id'));
        });
        $this->onItemsReady(array($this, 'addSubmenuItems'));
        return parent::doListSelected();
    }

    public function doListFiltered()
    {
        $this->onItemsReady(array($this, 'addSubmenuItems'));
        return parent::doListFiltered();
    }

    public function doListSubmenu()
    {
        $source = $this->getParam('source_infoblock_id');
        $path = fx::env('page')->getPath();
        if (count($path) < 2) {
            return;
        }
        if (isset($path[1])) {
            $this->listen('query_ready', function ($e) use ($path, $source) {
                $q = $e['query'];
                $q->where('parent_id', $path[1]->get('id'))->where('infoblock_id', $source);
            });
        }
        return $this->doList();
    }

    public function doBreadcrumbs()
    {
        $entity_page = fx::env('page');
        
        $entity_page['active'] = true;
        if ($this->getParam('header_only')) {
            $pages = new System\Collection(array($entity_page));
        } else {
            $pages = $entity_page->getPath();
        }
        return array('items' => $pages);
    }

    /**
     * Return allow parent pages for current component
     *
     * @return fx_collection
     */
    protected function getAllowedParents()
    {
        /**
         * Retrieve pages object
         */
        $pages = fx::data('section')->where('site_id', fx::env('site_id'))->all();
        $additional_parent_ids = array_diff($pages->getValues('parent_id'), $pages->getValues('id'));
        $additional_parent_ids = array_unique($additional_parent_ids);
        $pages_add = fx::data('content')->where('id', $additional_parent_ids)->all();

        return $pages_add->concat($pages);
    }
}