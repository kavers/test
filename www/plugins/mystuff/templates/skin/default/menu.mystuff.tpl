<ul class="menu">
    <li class="active">
        <a href="{router page='mine'}">{$aLang.my_stuff}</a>
        <ul class="sub-menu">
            <li {if !$sMenu}class="active"{/if}>
                <div><a href="{router page='mine'}">{$aLang.my_stuff_all}</a></div>
            </li>
            
            <li {if $sMenu == 'new'}class="active"{/if}>
                <div><a href="{router page='mine'}new/">{$aLang.my_stuff_new}</a> {if $myStuffNewComments}+{$myStuffNewComments}{/if}</div>
            </li>
        </ul>
    </li>
</ul>