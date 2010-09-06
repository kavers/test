/* Обеспечиваем работу модуля блогосферы (используем jQuery) */
var pBlogosphere = null; //Объект для управления блоком блогосферы

//Инициализируем блок
jQuery(document).ready( function() {
	if(jQuery("#blogosphere").length > 0) {
		pBlogosphere = new pluginBlogosphere(pluginBlogosphereConfig);
		
		/*Работа с закладками, активной считаем первую. Фильтрующие закладки должны
		находиться непосредственно в блоке с id="pluginBlogosphereFilters"
		*/
		pBlogosphere.activeFilterClass = jQuery("#blogosphere #pluginBlogosphereFilters").children(":first").attr("class");
		//Подключаем событие click для фильтров.
		jQuery("#blogosphere #pluginBlogosphereFilters a").click( function(){
			pBlogosphere.filterChange(this);
		});
		
		//Получаем шаблон карточки с пано
		pBlogosphere.itemTemplate = jQuery("#blogosphere .blogs_cloud .blogs_items .item:first");
		pBlogosphere.itemTemplate.css("display", "block");
		//Очищаем пано
		jQuery("#blogosphere .blogs_cloud .blogs_items").empty();
		
		//Готовим ленту времени
		pBlogosphere.prepareScroller();
		
		//Цепляем обработчики на кнопки непрерывной прокрутки
		jQuery("#blogosphere .blog_line .prev").mousedown( function(){
			pBlogosphere.startSmoothScrolling("left");
		});
		
		jQuery("#blogosphere .blog_line .next").mousedown( function(){
			pBlogosphere.startSmoothScrolling("right");
		});
		
		//Цепляем обработчик на кликание по кнопкам перехода к началу/концу
		jQuery("#blogosphere .time_scroll .left a").click( function(){
			pBlogosphere.hardScrolling("begin");
		});
		
		jQuery("#blogosphere .time_scroll .right a").click( function(){
			pBlogosphere.hardScrolling("end");
		});
		
		//Цепляем обработчик на скролл
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
		
		//Загружаем первую порцию топиков
		pBlogosphere.reloadTopics();
	}
});