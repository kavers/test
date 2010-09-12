<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright � 2008 Mzhelskiy Maxim
*
*--------------------------------------------------------
*
*   Official site: www.livestreet.ru
*   Contact e-mail: rus.engine@gmail.com
*
*   GNU General Public License, version 2:
*   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
---------------------------------------------------------
*/

/**
* ����������� �����
*/
class PluginPrevnextbutton_HookShowTopic extends Hook {

	public function RegisterHook() {
		//��� ��� �������������� id ����������� � ���������� ������� 
		$this->AddHook('topic_show', 'ChangePrevNextTopicsIds');
	}
	
	/**
	* ������� �������������� id ����������� � ���������� �������, ����������, ���
	* ��� ����� �� ���� �� �����, ��� � �������.
	* 
	* @param	array	array(oTopic)
	*/
	public function ChangePrevNextTopicsIds($data) {
		if(!isset($data['oTopic'])) {
			return;
		}
		
		$oTopicCurrrent = $data['oTopic'];
		
		$aTopicsIdFromCurrentBlog = $this->Topic_GetTopicsByBlogId($oTopicCurrrent->getBlogId());
		
		$iPrevId = $iNextId = false;
		if($aTopicsIdFromCurrentBlog) {
			$iCurrKey = array_search($oTopicCurrrent->getId(), $aTopicsIdFromCurrentBlog);
			//��-��������� ������ ����������� �� ���� ���������� �� ��������, ������� ��������� ���������.
			$iNextId = $iCurrKey > 0 ? $aTopicsIdFromCurrentBlog[$iCurrKey - 1] : false;
			$iPrevId = $iCurrKey < count($aTopicsIdFromCurrentBlog) - 1 ? $aTopicsIdFromCurrentBlog[$iCurrKey + 1] : false;
		}
		
		//������������� ���������� �������������
		$this->Viewer_Assign('prev', $iPrevId);
		$this->Viewer_Assign('next', $iNextId);
	}
}
?>