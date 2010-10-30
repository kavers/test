/* ������������ ������ ������ communitycats (���������� jQuery) */
var pCommunityCatalog = null; //������ ��� ���������� ������ ������������ �������� � �������� ���������

function pluginCommunitycats() {};

pluginCommunitycats.prototype = {
	blockId: "",
	dataBlockId: "",
	activeCatClass: "",
	
	blogCatChangeInBlock: function(aObject) {
		var pPlugin = this;
		var curCat = jQuery(this.blockId).find("."+this.activeCatClass + " a").attr("name");
		var newCat = jQuery(aObject).attr("name");
		jQuery.ajax({
				"url": "/communitycats/blogs",
				"data": { 'blog_cat': newCat, 'security_ls_key': LIVESTREET_SECURITY_KEY },
				"dataType": "json",
				"error": function() {
					msgErrorBox.alert('Error!', 'Can\'t load blogs. Please, try later.');
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
	//���� ���������
	if(jQuery("#community").length > 0) {
		pCommunityCatalog = new pluginCommunitycats();
		pCommunityCatalog.blockId = "#community";
		pCommunityCatalog.dataBlockId = "#b13";
		/*
		������ � ����������
		*/
		pCommunityCatalog.activeCatClass = jQuery(pCommunityCatalog.blockId + " .gradient").children(":first").attr("class");
		jQuery(pCommunityCatalog.blockId + " .gradient a[name!='']").click( function(){
			pCommunityCatalog.blogCatChangeInBlock(this);
			return false;
		});
	}
});