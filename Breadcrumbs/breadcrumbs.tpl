<div fx:b="breadcrumbs style_{$breadcrumbs_style}" fx:template="breadcrumbs" fx:of="show">
    {@breadcrumbs_style type="style" mask="breadcrumbs_style_*" /}
    {each $items.slice(0,-1)}
        <a href="{$url}" fx:e="link">{$name}</a>
        <span fx:e="separator">{%sep} / {/%}</span>
    {/each}
    {set $last_item = $items.last() /}
    {apply floxim.ui.header:header with $header = $last_item.name, $level = 1 /}
</div>