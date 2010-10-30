/* Прототип объекта блогосферы */
function pluginBlogosphere(pluginBlogosphereConfig) {
	this.topics = [];
	this.timeStart = pluginBlogosphereConfig.timeStart;
	this.timeEnd = pluginBlogosphereConfig.timeEnd;
	this.windowObject = jQuery("#blogosphere .blogs_cloud:first");
	this.fieldObject = jQuery("#blogosphere .blogs_cloud .blogs_items:first");
	this.timeLineObject = jQuery("#blogosphere .time_scroll:first");
	this.scrollerObject = jQuery("#blogosphere .time_scroll .act:first");
};

pluginBlogosphere.prototype = {
	//Класс вкладки активного фильтра. Считывается из первого потомка блока с классом pluginBlogosphereFilters
	activeFilterClass: "",
	//Окно для вывода топиков
	windowObject: null,
	//Пано с топиками
	fieldObject: null,
	//Лента времени
	timeLineObject: null,
	//Скроллер
	scrollerObject: null,
	//Тип текущего фильтра
	filterType: "",
	//Массив топиков
	topics: [],
	//Шаблон карточки топика
	itemTemplate: null,
	itemWidth: 270,
	itemHeight: 80,
	//Временные рамки (timestamp)
	timeStart: 0,
	timeEnd: 0,
	
	minRating: 0,
	minViews: 0,
	
	//Рaзличные коэффициенты для определения местоположения
	stepByTime: 0,
	stepByRating: 0,
	stepByViews: 0,
	
	scrollingFlag: false, //Для работы скроллера
	
	//Обработчик смены фильтра
	filterChange: function(aObject) {
		jQuery("#pluginBlogosphereFilters").children("."+this.activeFilterClass).removeClass(this.activeFilterClass);
		jQuery(aObject).parent().addClass(this.activeFilterClass);
		this.filterType = jQuery(aObject).attr("name");
		this.reloadTopics();
	},
	
	//Получаем и отображаем массив топиков.
	reloadTopics: function(filter) {
		this.fieldObject.empty();
		this.getTopics();
		this.hardScrolling('end');
	},
	
	//Выполняем запрос к эвенту
	getTopics: function() {
		var oBlogosphere = this;
		jQuery.ajax({
				"url": "blogosphere",
				"data": {"security_ls_key":pluginBlogosphereConfig.securityKey, "filterType": this.filterType},
				"dataType": "json",
				"error": function() {
					msgErrorBox.alert('Error!', 'Can\'t load topics to blogosphere. Please, try later.');
				},
				"success": function(data, textStatus) {
					oBlogosphere.drawTopics(data.topics);
				}
			}
		);
	},
	
	//Вывод топиков на пано
	drawTopics: function(topics) {
		this.topics = topics;
		this.stepsCalc();
		var oBlogosphere = this;
		jQuery.each(topics, function(key, topic){
			if((topic.date > oBlogosphere.timeStart) && (topic.date < oBlogosphere.timeEnd)) {
				var itemObject = oBlogosphere.itemTemplate.clone();
				itemObject.children("a.avatar").attr("href", topic.url);
				if(topic.author.avatarUrl) {
					itemObject.children(".avatar img")
						.attr("src", topic.author.avatarUrl)
						.attr("title", topic.title.substr(0, 70))
						.attr("alt", topic.author.name);
				}
				
				itemObject.children("a.theme")
					.attr("href", topic.url)
					.empty()
					.append(topic.title);
				
				itemObject.children("a.nickname")
					.attr("href", topic.author.profileUrl)
					.empty()
					.append(topic.author.name)
					.after(" " + topic.strDate);
				
				var mLeft = Math.ceil(oBlogosphere.stepByTime * (topic.date - oBlogosphere.timeStart));
				var mTop = oBlogosphere.windowObject.height() - oBlogosphere.itemHeight - Math.ceil(oBlogosphere.stepByViews * (topic.viewCount - oBlogosphere.minViews));
				var zIndex = Math.ceil(oBlogosphere.stepByRating * (topic.rating - oBlogosphere.minRating)) + 1;
				itemObject
					.css("margin-left", mLeft.toString() + "px")
					.css("margin-top", mTop.toString() + "px")
					.css("z-index", zIndex.toString())
					.hover(
						function() {
							jQuery(this).css(
								"z-index",
								function() {
									return parseInt(jQuery(this).css("z-index")) + 1000;
								}
							);
						},
						function() {
							jQuery(this).css(
								"z-index",
								function() {
									return parseInt(jQuery(this).css("z-index")) - 1000;
								}
							);
						}
					);
				oBlogosphere.fieldObject.append(itemObject);
				
			}
		});
	},
	
	//Вычисляем коэффициенты-мнодители для определения положения топиков на пано.
	stepsCalc: function() {
		if(this.topics.length > 0) {
			var minRating = this.topics[0].rating, maxRating = this.topics[0].rating; 
			var minViews = this.topics[0].viewCount, maxViews = this.topics[0].viewCount;
			/*Так как позиционирование зависит от рейтинга, просмоторов и даты, получаем их крайние значения для топиков*/
			jQuery.each(this.topics, function(key, topic) {
				if(minRating > topic.rating) minRating = topic.rating;
				if(maxRating < topic.rating) maxRating = topic.rating;
				if(minViews > topic.viewCount) minViews = topic.viewCount;
				if(maxViews < topic.viewCount) maxViews = topic.viewCount;
			});
			
			/*Получим отношение отступов*/
			//К времени
			if(this.timeEnd - this.timeStart) {
				this.stepByTime = this.calcFieldWidth() / (this.timeEnd - this.timeStart);
			} else {
				this.stepByTime = 0;
			}
			//К просмотрам
			if(maxViews - minViews) {
				this.stepByViews = (this.windowObject.height() - this.itemHeight) / (maxViews - minViews);
			} else {
				this.stepByViews = 0;
			}
			this.minViews = minViews;
			//К рейтингу
			if(maxRating - minRating) {
				this.stepByRating = 1000 / (maxRating - minRating); //Позиционирование по оси z, поэтому всего 1000.
			} else {
				this.stepByRating = 0;
			}
			this.minRating = minRating;
		}
	},
	
	//Плавная прокрутка вправо/лево
	startSmoothScrolling: function(direction) {
		switch(direction){
			case "right":
				//Влево скроллим пока правая часть пано не пересечёт правую часть окна
				this.fieldObject.animate({
					"marginLeft": this.leftScrollMargin()
				}, "slow");
				this.scrollerObject.animate({
					"left": this.leftScrollerMargin()
				}, "slow");
				break;
			case "left":
				this.fieldObject.animate({
					"marginLeft": this.rightScrollMargin()
				}, "slow");
				this.scrollerObject.animate({
					"left": this.rightScrollerMargin()
				}, "slow");
				break;
			default:
		}
	},
	
	//Жёсткая прокрутка (переход к определённому часу, началу/концу)
	hardScrolling: function(hour) {
		switch(hour){
			case "end":
				//Влево скроллим пока правая часть пано не пересечёт правую часть окна
				this.fieldObject.animate({
					"marginLeft": this.leftScrollMargin()
				}, "fast");
				this.scrollerObject.animate({
					"left": this.leftScrollerMargin()
				}, "fast");
				break;
			case "begin":
				this.fieldObject.animate({
					"marginLeft": this.rightScrollMargin() 
				}, "fast");
				this.scrollerObject.animate({
					"left": this.rightScrollerMargin() 
				}, "fast");
				break;
			default:
		}
	},
	
	//Прокрутка перетаскивание скролла
	startScrolling: function(event) {
		this.scrollingFlag = true;
	},
	
	stopScrolling: function(event) {
		this.fieldObject.stop();
		this.scrollerObject.stop();
		this.fixPointX = null;
		this.scrollingFlag = false;
	},
	
	fixPointX: null,
	scrolling: function(event) {
		if(this.scrollingFlag) {
			var timeLineOffset = this.timeLineObject.offset();
			var scrollerOffset = this.scrollerObject.offset();
			if(this.fixPointX == null) {
				this.fixPointX = event.pageX - scrollerOffset.left;
			}
			var mLeft = scrollerOffset.left - timeLineOffset.left;
			var newFixX = event.pageX - scrollerOffset.left;
			var step = newFixX - this.fixPointX;
			var newLeft = mLeft + step;
			if((newLeft >= 0) && (newLeft <= this.timeLineObject.width() - this.scrollerObject.width())) {
				this.scrollerObject.css("left", newLeft.toString() + "px");
				fieldLeftMargin = - Math.ceil(this.fieldObject.width() / this.timeLineObject.width() * newLeft);
				this.fieldObject.css("margin-left", fieldLeftMargin.toString() + "px");
			}
		}
	},
	
	leftScrollMargin: function() {
		return (this.windowObject.width() - this.fieldObject.width()).toString() + "px";
	},
	
	rightScrollMargin: function() {
		return "0px";
	},
	
	leftScrollerMargin: function() {
		return (this.timeLineObject.width() - this.scrollerObject.width()).toString() + "px";
	},
	
	rightScrollerMargin: function() {
		return "0px";
	},
	
	calcFieldWidth: function() {
		return this.fieldObject.width() - this.itemWidth;
	},
	
	prepareScroller: function() {
		var widthArrow = this.timeLineObject.children(".left").width();
		var widthTimeLine = this.timeLineObject.width() - widthArrow * 2;
		var widthScroller = Math.ceil(this.windowObject.width() / this.fieldObject.width() * widthTimeLine);
		var heightScroller = this.timeLineObject.height();
		this.scrollerObject
			.css("position", "absolute")
			.css("width", widthScroller)
			.css("height", heightScroller);
	}
};