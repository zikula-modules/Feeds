{gt text='Delete Feed' assign='templatetitle'}

{include file='admin/menu.tpl'}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='editdelete.png' set='icons/large' alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>
    <p class="z-warningmsg">{gt text='Do you really want to delete this Feed?'}</p>

    <form class="z-form" action="{modurl modname='Feeds' type='admin' func='delete'}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name="csrftoken"}" />
            <input type="hidden" name="confirmation" value="1" />
            <input type="hidden" name="fid" value="{$fid|safetext}" />
            <div class="z-buttons z-formbuttons">
                {button src="button_ok.png" set="icons/extrasmall" __alt="Confirm deletion?" __title="Confirm deletion?" __text="Confirm deletion?"}
                <a href="{modurl modname="Feeds" type="admin" func='view'}" title="{gt text="Cancel"}">{img modname='core' src="button_cancel.png" set="icons/extrasmall" __alt="Cancel" __title="Cancel"} {gt text="Cancel"}</a>
        </div>
    </form>
</div>
