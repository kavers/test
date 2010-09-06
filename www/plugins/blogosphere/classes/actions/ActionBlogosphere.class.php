<?php
/*-------------------------------------------------------
*
*   LiveStreet Engine Social Networking
*   Copyright © 2008 Mzhelskiy Maxim
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


class PluginBlogosphere_ActionBlogosphere extends ActionPlugin {
	
	public function Init() {
		$this->SetDefaultEvent('output');
	}
	
	protected function RegisterEvent() {
		$this->AddEvent('output','ajaxGetTopics');
	}
	
	/**
	 * Конвертирует входной массив в json
	 *
	 * @return json
	 */
	protected function prepareData($aResult) {
		foreach($aResult as $key=>$val) {
			$aResMy[]=array(
				'author' => array(
					'name' => $val->getUser()->getUserLogin(),
					'avatarUrl' => $val->getUser()->getUserProfileAvatar(),
					'profileUrl' => $val->getUser()->getUserWebPath(),
				),
				'title' => $val->getTopicTitle(),
				'url' => $val->getUrl(),
				'date' => strtotime($val->getTopicDateAdd()),
				'rating' => (float)$val->getTopicRating(),
				'viewCount' => (int)$val->getCountRead()
			);
		}
		return $aResMy;
	}
	
	/**
	 * Выводит список топиков
	 *
	 */
	protected function ajaxGetTopics() {
		$this->Viewer_SetResponseAjax('json');
		
		$aDates = PluginBlogosphere::getTimePeriod();
		
		//загоняем данные в фильтр
		$aFilter=array(
			'date_begin' => strftime('%Y-%m-%d %H:%M:00',$aDates['timeStart']),
			'date_end' => strftime('%Y-%m-%d %H:%M:00',$aDates['timeEnd'])
		);
		
		//Получаем список топиков
		$aResult=$this->Topic_GetTopicsForBlogosphereByFilter($aFilter);
		$this->Viewer_AssignAjax('topics', $aResult['collection'] ? $this->prepareData($aResult['collection']) : '');
	}
}