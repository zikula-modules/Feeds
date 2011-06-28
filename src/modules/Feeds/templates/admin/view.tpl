{ajaxheader modname='Feeds' filename='feeds.js' nobehaviour=true noscriptaculous=true}
{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="view" size="small"}
    <h3>{gt text='View feeds'}</h3>
</div>

{if $enablecategorization and $numproperties gt 0}
<form action="{modurl modname='Feeds' type='admin' func='view'}" method="post" enctype="application/x-www-form-urlencoded">
    <div id="feeds_multicategory_filter">
        <label for="feeds_property">{gt text='Category'}</label>
        {gt text='Choose a category' assign='lblDef'}
        {nocache}
            {if $numproperties gt 1}
                {html_options id='feeds_property' name='feeds_property' options=$properties selected=$property}
            {else}
                <input type="hidden" id="feeds_property" name="feeds_property" value="{$property}" />
            {/if}
            <div id="feeds_category_selectors">
            {foreach from=$catregistry key='prop' item='cat'}
                {assign var=propref value=$prop|string_format:'feeds_%s_category'}
                {if $property eq $prop}
                    {assign var='selectedValue' value=$category}
                {else}
                    {assign var='selectedValue' value=0}
                {/if}
                <noscript>
                    <div class="property_selector_noscript"><label for="{$propref}">{$prop}</label>:</div>
                </noscript>
                {selector_category category=$cat name=$propref selectedValue=$selectedValue allValue=0 allText=$lblDef editLink=false}
            {/foreach}
            </div>
        {/nocache}
        <input name="submit" type="submit" value="{gt text='Filter'}" />
        <input name="clear" type="submit" value="{gt text='Reset'}" />
    </div>
</form>
{/if}

<table class="z-datatable">
    <thead>
        <tr>
            <th>{gt text='Name'}</th>
            <th>{gt text='URL'}</th>
            {if $enablecategorization}
            <th>{gt text='Category'}</th>
            {/if}
            <th>{gt text='Actions'}</th>
        </tr>
    </thead>
    <tbody>
    {section name='feedsitems' loop=$feedsitems}    
        <tr class="{cycle values='z-odd,z-even'}">
            <td>{$feedsitems[feedsitems].name|safetext}</td>
            <td>{$feedsitems[feedsitems].url|safetext}</td>
            {if $enablecategorization}
            <td>
                {assignedcategorieslist item=$feedsitems[feedsitems]}
            </td>
            {/if}
            <td>
            {assign var='options' value=$feedsitems[feedsitems].options}
            {section name='options' loop=$options}
                <a href="{$options[options].url|safetext}">{img modname='core' set='icons/extrasmall' src=$options[options].image title=$options[options].title alt=$options[options].title}</a>
            {/section}
            </td>
        </tr>
    {sectionelse}
        <tr class="z-datatableempty">
            <td colspan="{if $modvars.Feeds.enablecategorization}4{else}3{/if}">
                {gt text='No Feeds found.'}
            </td>
        </tr>
     {/section}
     </tbody>
</table>

{pager rowcount=$pager.numitems limit=$pager.itemsperpage posvar='startnum' shift=1}
{adminfooter}
