     <li id="about_author" class="block green">
        <div class="title"><a href="#111" class="link"><h1>{$aLang.block_stream}</h1></a><a href="#" class="close_block"><img src="/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content block stream">
            <ul class="gradient block-nav">
               <li><a href="#" id="block_stream_topic" onclick="lsBlockStream.toggle(this,'topic_stream'); return false;">{$aLang.block_stream_topics}</a></li>
               <li class="active"><a href="#" id="block_stream_comment" onclick="lsBlockStream.toggle(this,'comment_stream'); return false;">{$aLang.block_stream_comments}</a></li>
            </ul>
        
            <div class="block-content">
                {literal}
                    <script language="JavaScript" type="text/javascript">
                    var lsBlockStream;
                    window.addEvent('domready', function() { 
                        lsBlockStream=new lsBlockLoaderClass();
                    });
                    </script>
                {/literal}					
                
                {$sStreamComments}
            </div>
            <div class="block_bottom"></div>
        </div>
     </li>
{*
<div class="block stream">

				<div class="tl"><div class="tr"></div></div>
				<div class="cl"><div class="cr">
					
					<h1>{$aLang.block_stream}</h1>
					
					<ul class="block-nav">						
						<li><strong></strong><a href="#" id="block_stream_topic" onclick="lsBlockStream.toggle(this,'topic_stream'); return false;">{$aLang.block_stream_topics}</a></li>
						<li class="active"><a href="#" id="block_stream_comment" onclick="lsBlockStream.toggle(this,'comment_stream'); return false;">{$aLang.block_stream_comments}</a><em></em></li>
					</ul>					
					
				<div class="block-content">
					{literal}
						<script language="JavaScript" type="text/javascript">
						var lsBlockStream;
						window.addEvent('domready', function() { 
							lsBlockStream=new lsBlockLoaderClass();
						});
						</script>
					{/literal}					
					
					{$sStreamComments}

					</div>
				</div></div>
				<div class="bl"><div class="br"></div></div>
			</div>
*}
