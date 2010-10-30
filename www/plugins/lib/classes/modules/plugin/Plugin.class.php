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
	
	/**
	* ��������� ������������� ������� ��� ����
	* 
	* @param	string		��� �������
	* @param	string		��� ����
	* @return	bool
	*/
	static function IsDBObjectExist($sTable, $sField = null) {
		$oEngine = Engine::getInstance();
		$bAlreadyInstall = false;
		if($sTable && $sField) {
			$bAlreadyInstall = $oEngine->Database_GetConnect()->query('SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
			WHERE COLUMN_NAME="'. $sField .'" AND TABLE_SCHEMA="'.Config::Get('db.params.dbname').'"
			AND TABLE_NAME = "'. $sTable .'";');
		} elseif($sTable) {
			$bAlreadyInstall = $oEngine->Database_GetConnect()->query('SELECT * FROM INFORMATION_SCHEMA.TABLES 
			WHERE TABLE_SCHEMA="'.Config::Get('db.params.dbname').'"
			AND TABLE_NAME = "'. $sTable .'";');
		}
		return $bAlreadyInstall;
	}
}

?>