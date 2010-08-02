     <li id="about_author" class="block green">
        <div class="title"><a href="#111" class="link"><h1>{$aLang.block_blogs}</h1></a><a href="#" class="close_block"><img src="/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content block blogs">
        <ul class="gradient block-nav">
           <li class="active"><a href="#" id="block_blogs_top" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;">{$aLang.block_blogs_top}</a></li>
            {if $oUserCurrent}
                <li><a href="#" id="block_blogs_join" onclick="lsBlockBlogs.toggle(this,'blogs_join'); return false;">{$aLang.block_blogs_join}</a></li>
                <li><a href="#" id="block_blogs_self" onclick="lsBlockBlogs.toggle(this,'blogs_self'); return false;">{$aLang.block_blogs_self}</a></li>
            {/if}
        </ul>
        
        <div class="block-content">
        {literal}
            <script language="JavaScript" type="text/javascript">
            var lsBlockBlogs;
            window.addEvent('domready', function() {       
                lsBlockBlogs=new lsBlockLoaderClass();
            });
            </script>
        {/literal}
            {$sBlogsTop}
        </div>
        <div class="right"><a href="{router page='blogs'}">{$aLang.block_blogs_all}</a></div>
        <div class="block_bottom"></div>
        </div>
     </li>

{*			<div class="block blogs">
				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.block_blogs}</h1>
					
					<ul class="block-nav">
						<li class="active"><strong></strong><a href="#" id="block_blogs_top" onclick="lsBlockBlogs.toggle(this,'blogs_top'); return false;">{$aLang.block_blogs_top}</a>{if !$oUserCurrent}<em></em>{/if}</li>
						{if $oUserCurrent}
							<li><a href="#" id="block_blogs_join" onclick="lsBlockBlogs.toggle(this,'blogs_join'); return false;">{$aLang.block_blogs_join}</a></li>
							<li><a href="#" id="block_blogs_self" onclick="lsBlockBlogs.toggle(this,'blogs_self'); return false;">{$aLang.block_blogs_self}</a><em></em></li>
						{/if}
					</ul>
					
					<div class="block-content">
					{literal}
						<script language="JavaScript" type="text/javascript">
						var lsBlockBlogs;
						window.addEvent('domready', function() {       
							lsBlockBlogs=new lsBlockLoaderClass();
						});
						</script>
					{/literal}
					{$sBlogsTop}
					</div>
					
					<div class="right"><a href="{router page='blogs'}">{$aLang.block_blogs_all}</a></div>

					
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>
*}
