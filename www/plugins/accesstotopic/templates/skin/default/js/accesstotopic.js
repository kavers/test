function pluginAccesstotopic(personalAccessLevelsKeys, personalAccessLevelsTitles, collectiveAccessLevelsKeys, collectiveAccessLevelsTitles, selectedAccessLevel) {
	this.accessLevelSelectObject = jQuery("select#accesstotopic:first");
	this.blogSelectObject = jQuery("select#blog_id:first");
	this.personalAccessLevelsKeys = personalAccessLevelsKeys;
	this.personalAccessLevelsTitles = personalAccessLevelsTitles;
	this.collectiveAccessLevelsKeys = collectiveAccessLevelsKeys;
	this.collectiveAccessLevelsTitles = collectiveAccessLevelsTitles;
	this.selectedAccessLevel = selectedAccessLevel;
}

var pAccesstotopic = null;

jQuery(document).ready( function() {
	if(jQuery("select#accesstotopic").length > 0 && jQuery("#blog_id").length > 0) {
		pAccesstotopic = new pluginAccesstotopic(personalAccessLevelsKeys, personalAccessLevelsTitles, collectiveAccessLevelsKeys, collectiveAccessLevelsTitles, selectedAccessLevel);
		
		jQuery("#blog_id").change( function() {
			pAccesstotopic.switchAccessSelect();
		});
		
		pAccesstotopic.switchAccessSelect();
	}
});

pluginAccesstotopic.prototype = {
	accessLevelSelectObject: null,
	personalAccessLevelsKeys: [], 
	personalAccessLevelsTitles: [],
	collectiveAccessLevelsKeys: [], 
	collectiveAccessLevelsTitles: [], 
	selectedAccessLevel: 0,
	
	switchAccessSelect: function() {
		if(this.blogSelectObject.val() == 0) {
			this.switchAccessSelectTo(this.personalAccessLevelsKeys, this.personalAccessLevelsTitles);
		} else {
			this.switchAccessSelectTo(this.collectiveAccessLevelsKeys, this.collectiveAccessLevelsTitles);
		}
	},
	
	switchAccessSelectTo: function(accessLevelsKeys, accessLevelsTitles) {
		var selectObject = this.accessLevelSelectObject;
		var selectedAccessLevel = this.selectedAccessLevel;
		//Чистим, что есть
		selectObject.empty();
		jQuery.each(accessLevelsKeys, function(index, key) {
			if(selectedAccessLevel == key) {
				selectObject.append('<option value="'+ key.toString() +'" selected>' + accessLevelsTitles[index] + '</option>');
			} else {
				selectObject.append('<option value="'+ key.toString() +'">' + accessLevelsTitles[index] + '</option>')
			}
		});
	}
}