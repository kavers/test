/* ������������ ������ ������ ���������� (���������� jQuery) */
var pBlogosphere = null; //������ ��� ���������� ������ ����������

//�������������� ����
jQuery(document).ready( function() {
	if(jQuery("#blogosphere").length > 0) {
		pBlogosphere = new pluginBlogosphere(pluginBlogosphereConfig);
		
		/*������ � ����������, �������� ������� ������. ����������� �������� ������
		���������� ��������������� � ����� � id="pluginBlogosphereFilters"
		*/
		pBlogosphere.activeFilterClass = jQuery("#blogosphere #pluginBlogosphereFilters").children(":first").attr("class");
		//���������� ������� click ��� ��������.
		jQuery("#blogosphere #pluginBlogosphereFilters a").click( function(){
			pBlogosphere.filterChange(this);
		});
		
		//�������� ������ �������� � ����
		pBlogosphere.itemTemplate = jQuery("#blogosphere .blogs_cloud .blogs_items .item:first");
		pBlogosphere.itemTemplate.css("display", "block");
		//������� ����
		jQuery("#blogosphere .blogs_cloud .blogs_items").empty();
		
		//������� ����� �������
		pBlogosphere.prepareScroller();
		
		//������� ����������� �� ������ ����������� ���������
		jQuery("#blogosphere .blog_line .prev").mousedown( function(){
			pBlogosphere.startSmoothScrolling("left");
		});
		
		jQuery("#blogosphere .blog_line .next").mousedown( function(){
			pBlogosphere.startSmoothScrolling("right");
		});
		
		//������� ���������� �� �������� �� ������� �������� � ������/�����
		jQuery("#blogosphere .time_scroll .left a").click( function(){
			pBlogosphere.hardScrolling("begin");
		});
		
		jQuery("#blogosphere .time_scroll .right a").click( function(){
			pBlogosphere.hardScrolling("end");
		});
		
		//������� ���������� �� ������
		jQuery("#blogosphere .time_scroll .act").mousedown( function(event) {
			pBlogosphere.startScrolling(event);
		});
		
		jQuery(document).mouseup( function(event) {
			pBlogosphere.stopScrolling(event);
		});
		
		jQuery("#blogosphere .time_scroll .act").mouseout( function(event) {
			pBlogosphere.stopScrolling(event);
		});
		
		jQuery("#blogosphere .time_scroll .act").mousemove( function(event) {
			pBlogosphere.scrolling(event);
		});
		
		//��������� ������ ������ �������
		pBlogosphere.reloadTopics();
	}
});