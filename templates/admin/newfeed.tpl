{gt text='Create a Feed' assign='templatetitle'}

{include file='admin/menu.htm'}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='filenew.gif' set='icons/large' alt=$templatetitle}</div>

    <h2>{$templatetitle}</h2>

    <form class="z-form" action="{modurl modname='Feeds' type='admin' func='create'}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <fieldset>
                <legend>{gt text='New Feed'}</legend>
                <input type="hidden" name="authid" value="{insert name='generateauthkey' module='Feeds'}" />
                <div class="z-formrow">
                    <label for="feeds_feedname">{gt text='Name'}</label>
                    <input id="feeds_feedname" name="feed[name]" type="text" size="100" maxlength="100" />
                </div>
                <div class="z-formrow">
                    <label for="feeds_urltitle">{gt text='PermaLink URL title'}</label>
                    <input id="feeds_urltitle" name="feed[urltitle]" type="text" size="32" maxlength="255" />
                    <em class="z-formnote">{gt text='(Blank = auto-generate)'}</em>
                </div>
                {if $enablecategorization}
                <div class="z-formrow">
                    <label>{gt text='Category'}</label>
                    {gt text='Choose a category' assign='lblDef'}
                    {nocache}
                    {foreach from=$categories key='property' item='category'}
                    <div class="z-formnote">{selector_category category=$category name="feed[__CATEGORIES__][$property]" field='id' selectedValue='0' defaultValue='0' defaultText=$lblDef}</div>
                    {/foreach}
                    {/nocache}
                </div>
                {/if}
                <div class="z-formrow">
                    <label for="feeds_url">{gt text='URL'}</label>
                    <input id="feeds_url" name="feed[url]" type="text" size="50" maxlength="240" />
                </div>
            </fieldset>

            {modcallhooks hookobject='item' hookaction='new' module='Feeds'}

            <div class="z-formbuttons">
                {button src='button_ok.gif' set='icons/small' __alt='Create' __title='Create'}
                <a href="{modurl modname='Feeds' type='admin' func='view'}">{img modname='core' src='button_cancel.gif' set='icons/small'  __alt='Cancel' __title='Cancel'}</a>
            </div>
        </div>
    </form>
</div>
