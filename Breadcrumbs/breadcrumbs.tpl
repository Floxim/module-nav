<div 
    fx:b="breadcrumbs"
    fx:styled
    fx:template="breadcrumbs" 
    fx:of="show">
    
    {@show_breadcrumbs default="true" type="checkbox" label="Показать хлебные крошки?" /}
    
    {if count($items) > 1 && $show_breadcrumbs}
        <span fx:e="item" fx:each="range(0, count($items) - 2) as $n">
            <a fx:with="$items[$n]" href="{$url}" fx:e="link">{$name}</a>
            <span fx:e="separator">{%sep} / {/%}</span>
        </span>
    {/if}
    
    <div fx:with="$items.last()">
        {apply floxim.ui.header:header with $header = $h1 ? $h1 : $name, $level = 1 /}
    </div>
</div>