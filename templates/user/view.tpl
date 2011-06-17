{sitename assign='sitename'}

{insert name='getstatusmsg'}

{if $action eq 'subcatslist'}
    <h3 class="feed-title">
        {gt text='Welcome to the Feeds of %s' tag1=$sitename}
    </h3>
    <div class="feed-list">
        {gt text='Choose a category to see the Feeds that belongs to it'}
    <ul>
      {foreach from=$categories item=category}
        {* get the category name avoiding E_ALL errors *}
        {array_field assign='categoryname' array=$category.display_name field=$lang}
        {if $categoryname eq ''}{assign var='categoryname' value=$category.name}{/if}

        {if $modvars.ZConfig.shorturls}
        <li><a href="{modurl modname='Feeds' type='user' func='view' prop=$property cat=$category.path|replace:$rootCat.path:''}" title="{$category.display_desc.$lang|safetext}">{$categoryname|safetext}</a></li>
        {else}
        <li><a href="{modurl modname='Feeds' type='user' func='view' prop=$property cat=$category.id}" title="{$category.display_desc.$lang|safetext}">{$categoryname|safetext}</a></li>
        {/if}
      {/foreach}
    </ul>
    </div>
{else}
    {if $modvars.Feeds.enablecategorization and $category}
    <h3 class="feed-title">
        {gt text='Category: %s' tag1=$category.display_name.$lang|safetext}
    </h3>
    <div class="feed-list">
        {gt text='The Feeds of this category are as follows:'}
        <ul>
            <li><a href="{modurl modname='Feeds' type='user' func='category' cat=$category.id}">{gt text='View all feeds'}</a></li>
    {else}
    <h3 class="feed-title">
        {gt text='Feeds list'}
    </h3>
    <div class="feed-list">
        {gt text='Available feeds'}
        <ul>
    {/if}
        {section name=item loop=$items}
            <li><a href="{modurl modname='Feeds' type='user' func='display' fid=$items[item].fid}">{$items[item].name|safetext}</a></li>
        {/section}
        </ul>
    </div>
    <div class="feed-bottom" >
        {pager rowcount=$pager.numitems limit=$pager.itemsperpage posvar=startnum shift=1}
    </div>
{/if}
