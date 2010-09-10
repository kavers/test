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
* ��������������� ������� ��� ������ � ���������
*/

class PluginLib_ModulePlugin extends Module {
	public function Init() { }
	
	/**
	* ��������� ����������� ������� � ������� �� ��� �����
	* 
	* @param	string		�������� �������
	* @return	bool		��������� ��������
	*/
	static public function IsPluginAvailable($sPluginName) {
		$oEngine = Engine::getInstance();
		$aPlugin = $oEngine->GetPlugins();
		$bPluginAvailable = isset($aPlugin[strtolower($sPluginName)]);
		
		return $bPluginAvailable;
	}
}

?>