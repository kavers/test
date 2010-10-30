/* ������������ ������ ������ ���������� (���������� jQuery) */
var pUsercatsCatalog = null; //������ ��� ���������� ������ ������������ �������� � �������� ��������� �������������
var pUsercatsCelebrity = null;
var pUsercatsExpert = null;

function pluginUsercats() {};

pluginUsercats.prototype = {
	blockId: "",
	dataBlockId: "",
	activeCatClass: "",
	userCatChange: function(aObject) {
		var curCat = jQuery("#plugin_usercats_usercats").find("."+this.activeCatClass + " a").attr("name");
		jQuery("#plugin_usercats_blogcats_"+curCat).css("display", "none");
		jQuery("#plugin_usercats_usercats").children("."+this.activeCatClass).removeClass(this.activeCatClass);
		jQuery(aObject).parent().addClass(this.activeCatClass);
		var newCat = jQuery(aObject).attr("name");
		jQuery("#plugin_usercats_blogcats_"+newCat).css("display", "block");
	},
	
	userCatChangeInBlock: function(aObject) {
		var pPlugin = this;
		var curCat = jQuery(this.blockId).find("."+this.activeCatClass + " a").attr("name");
		var newCat = jQuery(aObject).attr("name");
		jQuery.ajax({
				"url": "/usercats/users",
				"data": { 'user_cat': newCat, 'security_ls_key': LIVESTREET_SECURITY_KEY },
				"dataType": "json",
				"error": function() {
					msgErrorBox.alert('Error!', 'Can\'t load users. Please, try later.');
				},
				"success": function(data, textStatus) {
					jQuery(pPlugin.dataBlockId).empty().append(data.sToggleText);
					jQuery(pPlugin.blockId).find("."+pPlugin.activeCatClass).removeClass(pPlugin.activeCatClass);
					jQuery(aObject).parent().addClass(pPlugin.activeCatClass);
					jQuery(pPlugin.dataBlockId + ' ul').hoverAccordion({
						activateitem: '1',
						speed: 'fast'
					});
				}
			}
		);
	},
	
	topicCatChangeInBlock: function(aObject) {
		var pPlugin = this;
		var curCat = jQuery(this.blockId).find("."+this.activeCatClass + " a").attr("name");
		var newCat = jQuery(aObject).attr("name");
		jQuery.ajax({
				"url": "/usercats/topics",
				"data": { 'user_cat': newCat, 'security_ls_key': LIVESTREET_SECURITY_KEY },
				"dataType": "json",
				"error": function() {
					msgErrorBox.alert('Error!', 'Can\'t load topics. Please, try later.');
				},
				"success": function(data, textStatus) {
					jQuery(pPlugin.dataBlockId).empty().append(data.sToggleText);
					jQuery(pPlugin.blockId).find("."+pPlugin.activeCatClass).removeClass(pPlugin.activeCatClass);
					jQuery(aObject).parent().addClass(pPlugin.activeCatClass);
					jQuery(pPlugin.dataBlockId + ' ul').hoverAccordion({
						activateitem: '1',
						speed: 'fast'
					});
				}
			}
		);
	}
};

//�������������� ����
jQuery(document).ready( function() {
	//���� �������� ��������� �������������
	if(jQuery("#plugin_usercats_usercats").length > 0) {
		pUsercatsCatalog = new pluginUsercats();
		
		/*
		������ � ����������
		*/
		pUsercatsCatalog.blockId = "#plugin_usercats_usercats";
		pUsercatsCatalog.activeCatClass = jQuery(pUsercatsCatalog.blockId).children(":first").attr("class");
		jQuery(pUsercatsCatalog.blockId + " a").click( function(){
			pUsercatsCatalog.userCatChange(this);
			return false;
		});
	}
	//���� �������������
	if(jQuery("#celebrity").length > 0) {
		pUsercatsCelebrity = new pluginUsercats();
		pUsercatsCelebrity.blockId = "#celebrity";
		pUsercatsCelebrity.dataBlockId = "#b15";
		/*
		������ � ����������
		*/
		pUsercatsCelebrity.activeCatClass = jQuery(pUsercatsCelebrity.blockId + " .gradient").children(":first").attr("class");
		jQuery(pUsercatsCelebrity.blockId + " .gradient a[name!='']").click( function(){
			pUsercatsCelebrity.userCatChangeInBlock(this);
			return false;
		});
	}
	
	//���� ���������
	if(jQuery("#blog_experts").length > 0) {
		pUsercatsExpert = new pluginUsercats();
		pUsercatsExpert.blockId = "#blog_experts";
		pUsercatsExpert.dataBlockId = "#b11";
		/*
		������ � ����������
		*/
		pUsercatsExpert.activeCatClass = jQuery(pUsercatsCelebrity.blockId + " .gradient").children(":first").attr("class");
		jQuery(pUsercatsExpert.blockId + " .gradient a[name!='']").click( function(){
			pUsercatsExpert.topicCatChangeInBlock(this);
			return false;
		});
	}
});