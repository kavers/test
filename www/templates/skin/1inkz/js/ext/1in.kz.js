jQuery.noConflict();
jQuery(document).ready(function($){
			 
function Palitra (event,name){

		    $('#circle').css({top: event.clientY+document.documentElement.scrollTop-15, left: event.clientX-15});
            $('#cur_name').html(name);
            $('#rating_picker').hide();
            $('#circle').hide();

       	}

    $(document).ready(function(){

		$('#about_music .gradient a').click(function(){
          $('#about_music .gradient .first3').removeClass('first3');
          $('#about_music .block_content > ul').hide();
          $('#about_music .block_content > ul:first').show();
          $(this).parent().addClass('first3');
   		  $('#' + $(this).attr('name')).show();
        });
		$('#about_dream .gradient a').click(function(){
          $('#about_dream .gradient .first3').removeClass('first3');
          $('#about_dream .block_content > ul').hide();
          $('#about_dream .block_content > ul:first').show();
          $(this).parent().addClass('first3');
   		  $('#' + $(this).attr('name')).show();
        });

        $('.point_photo').hover(function() {
           var coord = $(this).offset();
           $("<div class='point_bphoto' style='top:"+coord.top+"px; left:"+coord.left+"px'><img src="+ $(this).attr('rel')+"></div>").appendTo("body");
		}, function() {
           $(".point_bphoto").remove();
		});

        $('#cur_name').click(function(){
		    $('#rating_picker').show();
		    $('#circle').show();
            $('#rating_title').html($('#cur_name').html());
        });

		$("#rating_picker").hover(function() {
		}, function() {
            $('#rating_picker').hide();
            $('#circle').hide();
		});

		$("#circle").hover(function() {
            $('#rating_picker').show();
            $('#circle').show();
		}, function() {
		});

		$("#mycarousel6 li img").hover(function() {
			$('.wp').addClass('book_opacity');
            $('.other_wp').show();
		}, function() {
		});


		$(".wp_left").hover(function() {
            $('.other_wp').hide();
			$('.wp').removeClass('book_opacity');
		}, function() {
		});
		$(".wp_right").hover(function() {
            $('.other_wp').hide();
			$('.wp').removeClass('book_opacity');
		}, function() {
		});
		$(".add_icons").hover(function() {
            $('.other_wp').hide();
			$('.wp').removeClass('book_opacity');
		}, function() {
		});
		$(".title").hover(function() {
            $('.other_wp').hide();
			$('.wp').removeClass('book_opacity');
		}, function() {
		});



        $(".slider").easySlider({
            prevText: '<img src="'+DIR_STATIC_SKIN+'/img/left_arrow.gif" width="18" height="18" alt="prev" title="prev" />',
            nextText: '<img src="'+DIR_STATIC_SKIN+'/img/right_arrow.gif" width="18" height="18" alt="next" title="next" />',
            auto: false,
            continuous: true
        });

        $('.rating').corner();
        $('.theme_rating').corner();
        $('.cur_rating').corner();

		$('.theme > a').each(function (i) {
			$(this).html($(this).html().slice(0, 45) + '...');
		});

		$('.table_list > li > a.item_title').each(function (i) {
			$(this).html($(this).html().slice(0, 29) + '...');
		});

		$('.other_wp > li > a.item_title').each(function (i) {
			$(this).html($(this).html().slice(0, 26) + '...');
		});


        $('.title h1').FontEffect({
            outline:true,
            outlineColor1:"#E9E9E9",
            outlineColor2:"#FFF",
            outlineWeight:1
        })

		$('#tools input:checkbox').click(function(){
		$('#' + $(this).attr('name')).css('display', $(this).attr('checked') ? 'block' : 'none');
		});


        $('.more_menu').click(
            function(e){
				$('.search_menu_ul').hide();
				$('.search_menu > a').removeClass('open');

                var visible = $(this).hasClass('open');
                $('.more_menu').removeClass('open').next(".all_menu").hide();
                if (!visible)
                    $(this).addClass('open').next(".all_menu").show();

                e.stopPropagation();
                $(document).one('click', function(){
                    $('.more_menu').removeClass('open').next(".all_menu").hide();
                });
                return false;
        });

        $('.more_menu2').click(
            function(e){
				$('.search_menu_ul').hide();
				$('.search_menu > a').removeClass('open');

                var visible = $(this).hasClass('open');
                $('.more_menu2').removeClass('open').next(".all_menu").hide();
                if (!visible)
                    $(this).addClass('open').next(".all_menu").show();

                e.stopPropagation();
                $(document).one('click', function(){
                    $('.more_menu2').removeClass('open').next(".all_menu").hide();
                });
				$('.all_menu.message').bind('click', function(){return false});
                return false;
        });


        $('#search .search_menu').click(
            function(e){
				$(document).click();
				$('.search_menu > a').addClass('open');
                $('.search_menu_ul').show();
                $('.search_menu_ul a').click(function(e){
                    $('#search .search_menu').children('a').html($(this).html());
                    $('.search_menu_ul').hide();
					$('.search_menu > a').removeClass('open');
                    return false;
                });

                $(document).one('click', function(){
                    $('.search_menu_ul').removeClass('open').hide();
                });

                return false;
        });



     $('.tools_button').toggle (
            function(e){
        //    $(this).attr('src', DIR_STATIC_SKIN + '/img/plus.gif');
            $('#tools').hide();
         },
             function(e){
        //    $(this).attr('src', DIR_STATIC_SKIN + '/img/minus.gif');
            $('#tools').show();
         });

     $('#comments_list .close_block2').toggle (
            function(e){
            $('#comments_list .close_block2 img').attr('src', DIR_STATIC_SKIN + '/img/minus2.gif');
            $('#comments_list .block_content').hide();
            $(this).parent().css({background: 'URL('+DIR_STATIC_SKIN+'/img/title_fon4.png)'});
            $(this).parent().addClass('white');
			$('#comments_list .title').find('.JQFEText').next().hide();

         },
             function(e){
            $('#comments_list .close_block2 img').attr('src', DIR_STATIC_SKIN + '/img/minus.gif');
            $('#comments_list .block_content').show();
            $(this).parent().css({background: 'URL('+DIR_STATIC_SKIN+'/img/title_fon3.png)'});
            $(this).parent().removeClass('white');
			$('#comments_list .title').find('.JQFEText').next().show();
         });


     $('#add_comments .close_block2').toggle (
            function(e){
            $('#add_comments .block_content').hide();
            $('#add_comments').css({background: 'URL('+DIR_STATIC_SKIN+'/img/title_fon4.png)'});
            $('#add_comments').css({height: '41px'});
         },
             function(e){
            $('#add_comments .block_content').show();
            $('#add_comments').css({background: 'URL('+DIR_STATIC_SKIN+'/img/add_comments.png)'});
            $('#add_comments').css({height: '66px'});
         });

     $('#add_comments2 .close_block2').toggle (
            function(e){
            $('#add_comments2 .close_block2 img').attr('src', DIR_STATIC_SKIN + '/img/minus2.gif');
            $('#add_comments2 .block_content').hide();
            $(this).parent().css({background: 'URL('+DIR_STATIC_SKIN+'/img/title_fon4.png)'});
            $(this).parent().addClass('white');
			$('#add_comments2 .title').find('.JQFEText').next().hide();

         },
             function(e){
            $('#add_comments2 .close_block2 img').attr('src', DIR_STATIC_SKIN + '/img/minus.gif');
            $('#add_comments2 .block_content').show();
            $(this).parent().css({background: 'URL('+DIR_STATIC_SKIN+'/img/title_fon3.png)'});
            $(this).parent().removeClass('white');
			$('#add_comments2 .title').find('.JQFEText').next().show();
         });


        $('#b1 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });

        $('#b2 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });

        $('#b3 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b4 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b5 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b6 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b7 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b8 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b9 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b10 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b11 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b12 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b13 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b14 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b15 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });
        $('#b16 ul').hoverAccordion({
        activateitem: '1',
        speed: 'fast'
        });


    });
    /**
 * We use the initCallback callback
 * to assign functionality to the controls
 */
function mycarousel_initCallback(carousel) {
    $('.jcarousel-control a').bind('click', function() {
        carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
        return false;
    });

    $('.jcarousel-scroll select').bind('change', function() {
        carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
        return false;
    });

    $('#mycarousel-next').bind('click', function() {
        carousel.next();
        return false;
    });

    $('#mycarousel-prev').bind('click', function() {
        carousel.prev();
        return false;
    });
};

function mycarousel_initCallback2(carousel) {
    $('.jcarousel-control2 a').bind('click', function() {
        carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
        return false;
    });

    $('.jcarousel-scroll2 select').bind('change', function() {
        carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
        return false;
    });

    $('#mycarousel-next2').bind('click', function() {
        carousel.next();
        return false;
    });

    $('#mycarousel-prev2').bind('click', function() {
        carousel.prev();
        return false;
    });
};
function mycarousel_initCallback3(carousel) {
    $('.jcarousel-control3 a').bind('click', function() {
        carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
        return false;
    });

    $('.jcarousel-scroll3 select').bind('change', function() {
        carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
        return false;
    });

    $('#mycarousel-next3').bind('click', function() {
        carousel.next();
        return false;
    });

    $('#mycarousel-prev3').bind('click', function() {
        carousel.prev();
        return false;
    });
};
function mycarousel_initCallback4(carousel) {
    $('.jcarousel-control4 a').bind('click', function() {
        carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
        return false;
    });

    $('.jcarousel-scroll4 select').bind('change', function() {
        carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
        return false;
    });

    $('#mycarousel-next4').bind('click', function() {
        carousel.next();
        return false;
    });

    $('#mycarousel-prev4').bind('click', function() {
        carousel.prev();
        return false;
    });
};
function mycarousel_initCallback5(carousel) {
    $('.jcarousel-control5 a').bind('click', function() {
        carousel.scroll(jQuery.jcarousel.intval(jQuery(this).text()));
        return false;
    });

    $('.jcarousel-scroll5 select').bind('change', function() {
        carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
        return false;
    });

    $('#mycarousel-next5').bind('click', function() {
        carousel.next();
        return false;
    });

    $('#mycarousel-prev5').bind('click', function() {
        carousel.prev();
        return false;
    });
};

function mycarousel_initCallback6(carousel) {
    $('.jcarousel-scroll6 select').bind('change', function() {
        carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
        return false;
    });

    $('#mycarousel-next6').bind('click', function() {
        carousel.next();
		if ($('#mycarousel6 li').length == carousel.last){
			$(this).hide();
		}
		$('#mycarousel-prev6').show();
        return false;
    });

    $('#mycarousel-prev6').bind('click', function() {
        carousel.prev();
		if (carousel.first == 1){
			$(this).hide();
		}
		$('#mycarousel-next6').show();
        return false;
    });
};

function mycarousel_initCallback7(carousel) {
    $('.jcarousel-scroll7 select').bind('change', function() {
        carousel.options.scroll = jQuery.jcarousel.intval(this.options[this.selectedIndex].value);
        return false;
    });

    $('#mycarousel-next7').bind('click', function() {
        carousel.next();
		if ($('#mycarousel7 li').length == carousel.last){
			$(this).hide();
		}
		$('#mycarousel-prev7').show();
        return false;
    });

    $('#mycarousel-prev7').bind('click', function() {
        carousel.prev();
		if (carousel.first == 1){
			$(this).hide();
		}
		$('#mycarousel-next7').show();
        return false;
    });
};


// Ride the carousel...
$(document).ready(function() {
    $("#mycarousel").jcarousel({
        scroll: 1,
        initCallback: mycarousel_initCallback,
        buttonNextHTML: null,
        buttonPrevHTML: null
    });
        $("#mycarousel2").jcarousel({
        scroll: 1,
        initCallback: mycarousel_initCallback2,
        buttonNextHTML: null,
        buttonPrevHTML: null
    });
        $("#mycarousel3").jcarousel({
        scroll: 1,
        initCallback: mycarousel_initCallback3,
        buttonNextHTML: null,
        buttonPrevHTML: null
    });
        $("#mycarousel4").jcarousel({
        scroll: 1,
        initCallback: mycarousel_initCallback4,
        buttonNextHTML: null,
        buttonPrevHTML: null
    });
        $("#mycarousel5").jcarousel({
        scroll: 1,
        initCallback: mycarousel_initCallback5,
        buttonNextHTML: null,
        buttonPrevHTML: null
    });
        $("#mycarousel6").jcarousel({
        scroll: 1,
        initCallback: mycarousel_initCallback6,
        buttonNextHTML: null,
        buttonPrevHTML: null
    });
        $("#mycarousel7").jcarousel({
        scroll: 1,
        initCallback: mycarousel_initCallback7,
        buttonNextHTML: null,
        buttonPrevHTML: null
    });

});
});