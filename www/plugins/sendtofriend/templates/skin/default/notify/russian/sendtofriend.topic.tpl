Пользователь <a href="{$oUserFrom->getUserWebPath()}">{$oUserFrom->getLogin()}</a> предлагает Вам ознакомиться с топиком <a href="{router page='blog'}{$oTopic->getId()}.html">{$oTopic->getTitle()}</a> в  блоге <b>«{$oBlog->getTitle()|escape:'html'}»</b>
<br />
Так же он написал Вам:
<blockquote>{$sMessage}</blockquote>
<br />
<br />
С уважением, администрация сайта <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>