<?php

use Floxim\Floxim\System\Fx as fx;

$source_ibs = fx::data('infoblock')
    ->getContentInfoblocks('floxim.nav.section')
    ->find('site_id', fx::env('site')->get('id'))
    ->getValues('name', 'id');
return array(
    'actions' => array(
        '*list*' => array(
            'icon' => 'Nav',
            //'name' => 'Меню',
            'defaults' => array(
            	'!is_pass_through' => true,
                '!limit' => 0,
                '!create_record_ib' => false,
                '!sorting' => 'manual',
                '!sorting_dir' => 'asc',
                '!pagination' => false,
                //'!extra_infoblocks' => null,
                '!submenu' => 'none'
            ),
            'settings' => array(
                'extra_infoblocks' => array(
                    'name' => 'extra_infoblocks',
                    'label' => 'Добавить ссылки из другого блока',
                    'type' => 'livesearch',
                    'is_multiple' => true, 
                    'ajax_preload' => true,
                    //'params' => array(
                    'content_type' => 'infoblock',
                    'conditions' => array(
                        'controller' => array(
                            fx::component('floxim.main.page')->getAllVariants()->getValues(function($ch) {
                                return $ch['keyword'];
                            }),
                            'IN'
                        ),
                        'id' => array(
                            $this->getParam('infoblock_id'),
                            '!='
                        ),
                        'site_id' => fx::env('site_id'),
                        'action' => 'list_infoblock'
                    )
                    //),
                )
            ),
        ),
        'list_infoblock' => array(
            'name' => 'Меню',
            'default_scope' => function() {
                $ds = fx::env('site')->get('index_page_id').'-descendants-';
                return $ds;
            }
        ),
        'breadcrumbs' => array(
            'icon' => 'Nav',
            'icon_extra' => 'bre',
            'name' => fx::alang('Breadcrumbs', 'component_section'),
            'settings' => array(
                'header_only' => array(
                    'name' => 'header_only',
                    'type' => 'checkbox',
                    'label' => fx::alang('Show only header?', 'component_section'),
                )
            )
        ),
    )
);