������������ <a href="{$oUserComment->getUserWebPath()}">{$oUserComment->getLogin()}</a> ������� ����� ����������� � ������ ����������� � ������ <b>�{$oTopic->getTitle()|escape:'html'}�</b>, ��������� ��� ����� ������� �� <a href="{$oTopic->getUrl()}#comment{$oComment->getId()}">���� ������</a><br>							
{if $oConfig->GetValue('sys.mail.include_comment')}
	����� ���������: <i>{$oComment->getText()}</i>				
{/if}				
<br><br>
� ���������, ������������� ����� <a href="{cfg name='path.root.web'}">{cfg name='view.name'}</a>