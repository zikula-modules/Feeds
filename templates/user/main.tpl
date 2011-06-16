{if $enablecategorization and $properties}
    {modfunc modname='Feeds' func='view' prop=$properties.0}
{else} 
    {modfunc modname='Feeds' func='view'}
{/if} 