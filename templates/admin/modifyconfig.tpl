{adminheader}
<div class="z-admin-content-pagetitle">
    {icon type="config" size="small"}
    <h3>{gt text='Settings'}</h3>
</div>

<form class="z-form" action="{modurl modname='Feeds' type='admin' func='updateconfig'}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
        <fieldset>
            <legend>{gt text='General Settings'}</legend>
            <div class="z-formrow">
                <label for="feeds_enablecategorization">{gt text='Enable categorization'}</label>
                <input id="feeds_enablecategorization" type="checkbox" name="enablecategorization"{if $modvars.Feeds.enablecategorization} checked="checked"{/if} />
            </div>
            <!--[*
            <div class="z-formrow">
                <label for="feeds_bold">{gt text='Display item names in bold'}</label>
                <input id="feeds_bold" name="bold" type="checkbox" value="1"{if $modvars.Feeds.bold eq 1} checked="checked"{/if} />
            </div>
            *]-->
            <div class="z-formrow">
                <label for="feeds_openinnewwindow">{gt text='Open links in new browser window'}</label>
                <input id="feeds_openinnewwindow" name="openinnewwindow" type="checkbox" value="1"{if $modvars.Feeds.openinnewwindow eq 1} checked="checked"{/if} />
            </div>
            <div class="z-formrow">
                <label for="feeds_itemsperpage">{gt text='Items per page'}</label>
                <input id="feeds_itemsperpage" type="text" name="itemsperpage" size="3" value="{$modvars.Feeds.itemsperpage|safetext}" />
            </div>
            <div class="z-formrow">
                <label for="feeds_multifeedlimit">{gt text='Maximum items to add per feed'}</label>
                <input id="feeds_multifeedlimit" type="text" name="multifeedlimit" size="3" value="{$modvars.Feeds.multifeedlimit|safetext}" />
                <em class="z-formnote z-sub">{gt text='This is a per feed limit that applies if multiple feeds are used combined. For example in the category view function. (0 to add all)'}</em>
            </div>
        </fieldset>
        <fieldset>
            <legend>{gt text='Cache Settings'}</legend>
            <div class="z-formrow">
                <label for="feeds_cachedirectory">{gt text='Cache directory for feeds'}</label>
                <input id="feeds_cachedirectory" type="text" name="cachedirectory" size="30" value="{$modvars.Feeds.cachedirectory|safetext}" />
                <em class="z-formnote z-sub">{gt text="Relative to [%s]" tag1=$tempdir}</em>
            </div>
            <div class="z-formrow">
                <label for="feeds_cacheinterval">{gt text='Cache interval for feeds'}</label>
                <input id="feeds_cacheinterval" type="text" name="cacheinterval" size="3" value="{$modvars.Feeds.cacheinterval|safetext}" />
                <em class="z-formnote z-sub">{gt text='In seconds (3600 = 1 hour)'}</em>
            </div>
        </fieldset>
        <fieldset>
            <legend>{gt text='Cron Job Settings'}</legend>
            <div class="z-formrow">
                <label for="feeds_usingcronjob">{gt text='Using a Cron Job to update the cache'}</label>
                <input id="feeds_usingcronjob" name="usingcronjob" type="checkbox" value="1"{if $modvars.Feeds.usingcronjob eq 1} checked="checked"{/if} />
                <em class="z-formnote z-sub">{gt text='See instructions'}</em>
            </div>
            <div class="z-formrow">
                <label>{gt text='Link for using a Cron Job'}</label>
                <span><a href="{modurl fqurl=true forcelongurl=true modname='Feeds' type='user' func='updatecache' key=$modvars.Feeds.key}">{modurl fqurl=true forcelongurl=true modname='Feeds' type='user' func='updatecache' key=$modvars.Feeds.key}</a></span>
                <em class="z-formnote z-sub">{gt text='This link should not be shortened by shorturls, otherwise the parameter "key" is not submitted.'}</em>
            </div>
        </fieldset>
        <div class="z-buttons z-formbuttons">
            {button src="button_ok.png" set="icons/extrasmall" __alt="Save" __title="Save" __text="Save"}
            <a href="{modurl modname="Feeds" type="admin" func='view'}" title="{gt text="Cancel"}">{img modname='core' src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </div>
</form>
{adminfooter}
