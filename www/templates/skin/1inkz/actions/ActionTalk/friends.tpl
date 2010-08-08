     <li id="about_author" class="block green">
        <div class="title"><a href="" class="link"><h1>{$aLang.block_friends}</h1></a><a href="" class="close_block"><img src="/img/minus.gif" width="18" height="18" alt="Свернуть блок" title="Свернуть блок"/></a></div>
        <div class="block_content block blogs">
            <div class="block-content">
            {if $aUsersFriend}
					{literal}
						<script language="JavaScript" type="text/javascript">
						function friendToogle(element) {
							login=element.getNext('a').get('text');
							to=$('talk_users')
								.getProperty('value')
									.split(',')
										.map(function(item,index){
											return item.trim();
										}).filter(function(item,index){
											return item.length>0;
										});
							$('talk_users').setProperty(
								'value', 
								(element.getProperty('checked'))
									? to.include(login).join(',')
									: to.erase(login).join(',')
							);							
						}
						window.addEvent('domready', function() { 
							// сканируем список друзей      
							var lsCheckList=$('friends')
												.getElements('input[type=checkbox]')
													.addEvents({
														'click': function(){
															return friendToogle(this);
														}
													});
							// toogle checkbox`а при клике на ссылку-логин
							$('friends').getElements('a').addEvents({
								'click': function() {
									checkbox=this.getPrevious('input[type=checkbox]');
									checkbox.setProperty('checked',!checkbox.getProperty('checked'));
									friendToogle(checkbox);
									return false;
								}
							});
							// выделить всех друзей
							$('friend_check_all').addEvents({
								'click': function(){
									lsCheckList.each(function(item,index){
										if(!item.getProperty('checked')) {
											item.setProperty('checked',true);
											friendToogle(item);
										}
									});
									return false;
								}
							});
							// снять выделение со всех друзей
							$('friend_uncheck_all').addEvents({
								'click': function(){
									lsCheckList.each(function(item,index){
										if(item.getProperty('checked')) {
											item.setProperty('checked',false);
											friendToogle(item);
										}
									});
									return false;
								}
							});							
						});
						</script>
					{/literal}
            		    <ul class="descriptions list" id="friends">
							{foreach from=$aUsersFriend item=oFriend}
								<li class="descr1"><input type="checkbox" name="friend[{$oFriend->getId()}]"/> <a href="#" class="stream-author">{$oFriend->getLogin()}</a></li>						
							{/foreach}
						</ul>
            {else}
                {$aLang.block_friends_empty}
            {/if}
            </div>
            <div class="right"><a href="#" id="friend_check_all">{$aLang.block_friends_check}</a> | <a href="#" id="friend_uncheck_all">{$aLang.block_friends_uncheck}</a></div>
            <div class="block_bottom"></div>
        </div>
     </li>