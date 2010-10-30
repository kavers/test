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

class PluginLib_ModuleLib extends Module {
	public function Init() {
	}
	
	/**
	* Проверяет существование ключа в многомерных массивах
	* Проверка не строгая.
	* 
	* @param	string|int		искомый ключ
	* @param	array			массив для проверки
	* @param	bool			если true, то функция вернёт полный путь до ключа в формате key1:key2:...:needle
	* @return	bool|string		результат
	*/
	static public function Multi_array_key_exists($needle, $aHaystack, $bPath = false) {
		if(!is_array($aHaystack)) {
			return false;
		}
		
		foreach($aHaystack as $key => $val) {
			if($key == $needle) {
				return $bPath ? $key : true;
			}
			if(is_array($val)) {
				if( $sResult = self::Multi_array_key_exists($needle, $val, $bPath)) {
					return $bPath ? $key . ':' . $sResult : true;
				}
			}
		}
		
		return false;
	}
	
	/**
	* Рекурсивная проверка существования последовательности ключей в 
	* многомертном массиве
	* 
	* @param	array		проверяемый массив
	* @param	array		путь
	* @return	bool		результат
	*/
	static public function CheckCategoryPath($aCats, $aPath) {
		if(!is_array($aCats) || !is_array($aPath)) {
			return false;
		}
		
		if(!($sCheckedKey = array_shift($aPath))) {
			return false;
		}
		
		if(array_key_exists($sCheckedKey, $aCats)) {
			if(count($aPath)) {
				return self::CheckCategoryPath($aCats[$sCheckedKey], $aPath);
			}
			
			return true;
		}
		
		return false;
	}
}
?>