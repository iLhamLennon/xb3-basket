/*--------------HOmepage1 Category Slider------------------*/
$(".homepage-category-sec").slick({
  slidesToShow: 2,
  slidesToScroll: 1,
  autoplay: true,
  swipeToSlide: true,
  infinite:true,
  variableWidth: true,
  autoplaySpeed: 2000,
  dots: false,
  arrows:false
});
/*--------------HOmepage1 Tranding Slider------------------*/
$('.homepage-tranding-bottom-wrap').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  infinite:true,
  swipeToSlide:true,
  autoplay: true,
  variableWidth: true,
  autoplaySpeed: 2000,
  dots: false,
  arrows:false
});
/*--------------HOmepage1 Official Partner Slider------------------*/
$('.offcial-partner-bottom').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  infinite:true,
  swipeToSlide:true,
  autoplay: true,
  variableWidth: true,
  autoplaySpeed: 2000,
  dots: false,
  arrows:false
});
/*--------------HOmepage2 Product Slider------------------*/
$('.prodcut-sec-slide').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  infinite:true,
  autoplay: true,
  autoplaySpeed: 2000,
  dots: true,
  arrows:false
});
/*--------------HOmepage2 Featured Slider------------------*/
$('.featured-description').slick({
  slidesToShow: 2,
  slidesToScroll: 1,
  swipeToSlide: true,
  infinite:true,
  autoplay: true,
  variableWidth: true,
  autoplaySpeed: 2000,
  speed:300,
  dots: false,
  arrows:false
});
/*--------------HOmepage2 Official Partner Slider------------------*/
$('.offcial-partner-home2-featured').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  swipeToSlide: true,
  infinite:true,
  autoplay: true,
  variableWidth: true,
  autoplaySpeed: 2000,
  dots: false,
  arrows:false
});
/*--------------HOmepage2 Inspiration Slider------------------*/
$('.home2-inspiration').slick({
  slidesToShow: 2,
  slidesToScroll: 1,
  infinite:true,
  swipeToSlide: true,
  autoplay: true,
  variableWidth: true,
  autoplaySpeed: 2000,
  dots: false,
  arrows:false
});
/*-------------------------------------  Wallet Page 38,35,21-------------------------------------*/
$('.wallet-payment-slider,.profile-pay-img-sec,.payment-method-checkoutpage-full ').slick({
  slidesToShow: 4.5,
  slidesToScroll: 1,
  swipeToSlide: true,
  infinite: false,
  autoplay: false,
  variableWidth: true,
  autoplaySpeed: 2000,
  speed: 300,
  dots: false,
  arrows:false
});

$('.pay-btn').on('click',function(){
  $(this).toggleClass('active');
});
/*------------------------------------- Single Prodcut Page Size -------------------------------------*/
$('.size-section-deatils').slick({
  slidesToShow: 3.5,
  slidesToScroll: 1,
  swipeToSlide: true,
  infinite: false,
  autoplay: false,
  variableWidth: true,
  autoplaySpeed: 2000,
  speed: 300,
  dots: false,
  arrows:false
});
/*------------------------------------- Single Prodcut Page Color -------------------------------------*/
$('.color-sec').slick({
  slidesToShow: 6.1,
  slidesToScroll: 1,
  swipeToSlide: true,
  infinite: false,
  autoplay: false,
  variableWidth: true,
  autoplaySpeed: 2000,
  speed: 300,
  dots: false,
  arrows:false
});
/*------------------------------------- Single Prodcut Page Image -------------------------------------*/
$('.image-video-sec-full').slick({
  slidesToShow: 3,
  slidesToScroll: 1,
  swipeToSlide: true,
  infinite: true,
  autoplay: true,
  variableWidth: true,
  autoplaySpeed: 2000,
  dots: false,
  arrows:false
});
/*------------------------------------- Onboarding Screen -------------------------------------*/
$(".skip_btn_1").click(function(){

  $("#first").removeClass("active");
  $(".first_slide").removeClass("active");  

  $("#second").addClass("active");
  $(".second_slide").addClass("active");

});

$(".skip_btn_2").click(function(){
 $("#second").removeClass("active");
 $(".second_slide").removeClass("active");

 $("#third").addClass("active");
 $(".third_slide").addClass("active");
});
/*------------------------------------- OTP Screen -------------------------------------*/
$('.opt-sec').on('click',function(){
  $(this).toggleClass('active');
}); 
/*------------------------------------- Clear Search Field  -------------------------------------*/
$(document).ready(function() {
  $(".close-btn").click(function(){
    $("#search-input").val(""); 
    console.log('button clicked')
  });
});
/*------------------------------------- Read More less-------------------------------------*/
$(document).on('click', '.read-more-btn-text', function(){

  if($(this).find('.readless').hasClass("readless")){
    $(this).parent().find('.read-more-text').hide();
    $(this).parent().find('.product2-readmore').removeClass('rotate-icon');
    $(".read-more-btn-text p").text('Read More').removeClass('readless');

  } else {

    $(this).parent().find('.read-more-text').css('display', 'inline-block');
    $(this).parent().find('.read_dots').hide();

    $(this).parent().find('.product2-readmore').addClass('rotate-icon');
    $(".read-more-btn-text p").text('Read Less').addClass('readless');
  }
});
/*------------------------------------- Payment Method Tabbar -------------------------------------*/
$('.insta_type_wrap div:first').addClass('active');
$('.hero-heading div:first').siblings("div").hide();
$('.hero-heading div:first').show();

$('.insta_type_wrap a').click(function(){

  $('.insta_type_wrap a').removeClass('active');
  $(this).addClass('active');
  $('.hero-heading div:first').hide();
  $('.hero-heading div:first').siblings("div").hide();

  var activeTab = $(this).attr('href');
  $(activeTab).show();
  return false;
}); 
/*------------------------------------- Show Hide Password -------------------------------------*/
$("#eye").click(function() {
  $(this).toggleClass("fa-eye fa-eye-slash");
  input = $(this).parent().find("input");
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
$("#eye1").click(function() {
  $(this).toggleClass("fa-eye fa-eye-slash");
  input = $(this).parent().find("input");
  if (input.attr("type") == "password") {
    input.attr("type", "text");
  } else {
    input.attr("type", "password");
  }
});
/*------------------------------------- Favourite Hide Show -------------------------------------*/
$('.item-bookmark').on('click',function(){
  $(this).toggleClass('active');
}); 
/*------------------------------------- Incerment Decrement -------------------------------------*/
$('.add').on('click', function () {
  if ($(this).prev().val() < 100) {
    $(this).prev().val(+$(this).prev().val() + 1);
  }
});
$('.sub').on('click', function () {
  if ($(this).next().val() > 1) {
    if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
  }
});
/*-------------------------------------  Change Language,Change Currency-------------------------------------*/
var $radioButtons = $('#change-language-page input[type="radio"],#currency-sec input[type="radio"');
$radioButtons.click(function() {
  $radioButtons.each(function() {
    $(this).parent().toggleClass('language-sel', this.checked);
  });
});
/*-------------------------------------  Payment Mode-------------------------------------*/
$(".payment-mode .check-select-mode input[type='radio']").change(function(){
  var item=$(this);    
  if(item.is(":checked"))
  {
    window.location.href= item.data("target")
  }    
});
/*------------------------------------- Otp Section-------------------------------------*/
$('.otp-box').on('click',function(){
  $(this).addClass('active');
}); 
/*-------------------------------------  Range Slider-------------------------------------*/
$(function() {
  var rangePercent = $('range-slider').val();
  $('range-slider').on('change input', function() {
    rangePercent = $('range-slider').val();
    $('.value').text(rangePercent);
  });
});
/*-------------------------------------  Screen Changing-------------------------------------*/
$('.buy-now-btn a:last-child').hide();
$('.payment-mode-custom input:radio').change(function () {
  if ($('#payment-type1').is(":checked")) {
    $('.buy-now-btn a:first-child').show();
    $('.buy-now-btn a:last-child').hide();
  } else {  
    $('.buy-now-btn a:first-child').hide();
    $('.buy-now-btn a:last-child').show();
  }
});
/*-------------------------------------  Sticky Header-------------------------------------*/
$(window).scroll(function(){
  if ($(window).scrollTop() >= 20) {
    $('#top-navbar').addClass('fixed');
  }
  else {
    $('#top-navbar').removeClass('fixed');
  }
});
/*------------------------------------- Preloader-------------------------------------*/
$(window).on("load" , function () {
  $('.loader').fadeOut();
  $('.loader-mask').delay(350).fadeOut('slow');
});

/*------------------------------------- Custom -------------------------------------*/

function btnEffect(x,y) {
    var effect = $('<div class="btn-effect">')
    effect.css({
        'position': 'absolute',
        'left': 'calc('+x+'px - 1rem)',
        'top': 'calc('+y+'px - 1rem)',
        'background': 'white',
        'height': '2rem',
        'width': '2rem',
        'border-radius': '100%',
        'opacity': '.05'
    })
    $('body').append(effect)
    setTimeout(function() {
        $('.btn-effect').remove()
    }, 300)
}

$('button').on('click', function(e) {
  btnEffect(e.pageX,e.pageY)
})

let API = 'api-v1'
let key
let notif, hour, greeting
let home_id = 0,away_id = 0,home_name = null,away_name = null
let chart_result = 2

async function footy(e,league,value,action) {
	e.preventDefault()

  let formData = new FormData()

  formData.append('league', league)
  formData.append('stat', value)
    
  await fetch(action, {
    method: 'POST',
    body: formData
  })
}

function search(frmstanding, val) {
  if (val.length >= 3) {
    let c = frmstanding.find('.accordion-item.standing').length

    for (let y=0;y<c;y++) {
      if (!frmstanding.find('.accordion-item.standing:eq('+y+') h6 span').text().toLowerCase().includes(val)) {
        frmstanding.find('.accordion-item.standing:eq('+y+')').addClass('d-none')
      }
    }
  }
  else {
    frmstanding.find('.accordion-item.standing').removeClass('d-none')
  }
}

async function match(formstanding,nation,name,flag,league,id,team) {
  if (home_id<1) {
    home_id = id
    home_name = team
    if (formstanding.find('input[type="search"]').length > 0) {
      formstanding.find('input[type="search"]').val('')
      formstanding.find('.accordion-item.standing').removeClass('d-none')
      formstanding.find('input[type="search"]').focus()
    }
  }
  else if (away_id<1) {
    away_id = id
    away_name = team
    $('.load').css('display','block')
    await fetch('services/'+API+'.php?key='+key+'&act=match&league='+league+'&home='+home_id+'&away='+away_id, {
      method: 'GET'
    })
    .then(function(response) {
      if (response.status != 200) {
        $('.load').css('display','none')
      }
      return response.json()
    })
    .then(result => {
      if (result.status) {
        var istanding = $('.page.standing')
        var frmstanding = istanding.contents()
        frmstanding.find('.accordion-item.standing h6 .flag').removeClass('text-warning')
        frmstanding.find('.accordion-item.standing h6 span').removeClass('text-warning')
        frmstanding.find('.accordion-item.standing h6 small').removeClass('text-warning')
        frmstanding.find('.accordion-item.standing h6 .flag').addClass('text-white')
        frmstanding.find('.accordion-item.standing h6 span').addClass('text-white')
        frmstanding.find('.accordion-item.standing h6 small').addClass('text-white')
        frmstanding.find('.page.match').attr('src','match.html')
        frmstanding.find('.page.match').css('display','block')
        frmstanding.find('.page.match').css('animation','loadin')
        frmstanding.find('.page.match').css('animation-duration','300ms')
        var imatch = frmstanding.find('.page.match')
        imatch.on('load', function() {
          frmstanding.find('.top-navbar.standing').addClass('d-none')
          var frmmatch = imatch.contents()
          frmmatch.find('.top-navbar_full i').click(function(e) {
            setTimeout(function() {
              home_id = 0
              away_id = 0
              chart_result = 2
              frmstanding.find('.top-navbar.standing').removeClass('d-none')
              frmstanding.find('.page.match').css('display','none')
            }, 300)
          })
          frmmatch.find('.top-navbar_full small').focus()
          frmmatch.find('.top-navbar_full small').text(name)
          frmmatch.find('.top-navbar_full small span').remove()
          frmmatch.find('.top-navbar_full small i').remove()
          frmmatch.find('.top-navbar_full small').append('<span class="flag-icon"></span>')
          frmmatch.find('.top-navbar_full small span').css("background-image","url('data:image/png;base64,"+flag+"')")
          frmmatch.find('.top-navbar_full small').append('<i class="fas fa-globe text-white"></i>')
          frmmatch.find('.top-navbar_full small i').click(function(e) {
            frmstanding.find('.top-navbar.standing').removeClass('d-none')
            frmstanding.find('.page.match').css('display','none')
            $('.top-navbar.country').removeClass('d-none')
            $('.page.standing').css('display','none')
          })
          frmmatch.find('.top-navbar_full small span.flag-stat').remove()
          if (result.value[2].length > 0) {
            frmmatch.find('.top-navbar_full small').append('<span class="flag-stat"></span>')
            frmmatch.find('.top-navbar_full small span').on('click', function(e) {
              navigator.clipboard.writeText('https://footystats.org/'+nation+'/'+result.value[2]+'/predictions')
              $('body').append('<label class="popup">URL Copied</label>')
              setTimeout(function() {
                $('.popup').remove()
              }, 1500)
            })
          }
          frmmatch.find('.accordion-item.match:eq(0) ul li:eq(0)').text(home_name)
          frmmatch.find('.accordion-item.match:eq(1) ul li:eq(0)').text(away_name)

          frmmatch.find('.accordion-item.match:eq(0) ul li:eq(0)').addClass(result.value[3]?'fw-bold text-danger':'text-white')
          frmmatch.find('.accordion-item.match:eq(1) ul li:eq(0)').addClass(result.value[3]?'fw-bold text-danger':'text-white')

          frmmatch.find('.accordion-item.match:eq(2) button').remove()
          frmmatch.find('.accordion-item.match:eq(2)').append('<button type="reset" class="btn btn-dark w-100 py-2 my-2"></button>')
          frmmatch.find('.accordion-item.match button').click(function(e) {
            if (chart_result>1) {
              chart_result = 1
              checkout(frmmatch,1)
              getchart(frmmatch,1)
              frmmatch.find('.accordion-item.match button').text('Full Time')
            }
            else {
              chart_result = 2
              checkout(frmmatch,2)
              getchart(frmmatch,2)
              frmmatch.find('.accordion-item.match button').text('Half Time')
            }
          })
          frmmatch.find('.accordion-item.match button').focus()
          setchart(result.value[0],result.value[1],frmmatch)
        })
        formstanding.find('input[type="search"]').val('')
        formstanding.find('.accordion-item.standing').removeClass('d-none')
        $('.load').css('display','none')
      }
    })
  }
}

async function standing(index,nation,league,name,flag) {
  $('.load').css('display','block')
  await fetch('services/'+API+'.php?key='+key+'&act=standing&league='+league, {
    method: 'GET'
  })
  .then(function(response) {
    if (response.status != 200) {
      $('.load').css('display','none')
    }
    return response.json()
  })
  .then(result => {
    if (result.status && result.value[0].length>0) {
      $('.page.standing').attr('src','standing.html')
      $('.page.standing').css('display','block')
      $('.page.standing').css('animation','loadin')
      $('.page.standing').css('animation-duration','300ms')
      var istanding = $('.page.standing')
      istanding.on('load', function() {
        $('.top-navbar.country').addClass('d-none')
        var frmstanding = istanding.contents()
        frmstanding.find('.top-navbar_full i').click(function(e) {
          setTimeout(function() {
            $('.top-navbar.country').removeClass('d-none')
            $('.page.standing').css('display','none')
          }, 300)
        })
        frmstanding.find('.top-navbar_full small').text(name)
        frmstanding.find('.top-navbar_full small span').remove()
        frmstanding.find('.top-navbar_full small').append('<span class="flag-icon"></span>')
        frmstanding.find('.top-navbar_full small span').css("background-image","url('data:image/png;base64,"+flag+"')")
        if (result.value[1].length > 0) {
          frmstanding.find('.top-navbar_full small').append('<span class="flag-stat"></span>')
        }
        const list = frmstanding.find('.accordion.standing')
        list.empty()
        if (result.value[0].length > 30) {
          list.append('<input type="search" name="search" placeholder="Search name..." class="btn p-2 text-start bg-white" autofocus>')
          frmstanding.find('input[type="search"]').on('input', function(e) {
            search(frmstanding, e.target.value)
          })
        }
        result.value[0].forEach((rows,index) => {
          const row = Object.values(rows)
          list.append('<div class="accordion-item standing border-0 rounded-0 menu mt-12 bg-transparent">'
            +'<h6 class="accordion-header m-0 py-3 px-2 d-flex align-items-center">'
            +'<div class="flag me-2 lh-1 text-center col-1 text-white">'+(index+1)+'.</div>'
            +'<span data-id="'+row[1]+'" data-name="'+row[2]+'" class="lh-1 my-0 col-9 text-white">'+row[2]+'</span>'
            +'<small class="ms-auto lh-1 col-2 text-center text-white">[ '+row[3]+' ]</small>'
            +'</h6>'
            +'<div class="faq-bottom-border mt-0"></div>'
            +'</div>')
          frmstanding.find('.accordion-item.standing:eq('+index+') h6 span').on('click', function(e) {
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 .flag').removeClass('text-white')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 span').removeClass('text-white')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 small').removeClass('text-white')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 .flag').addClass('text-warning')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 span').addClass('text-warning')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 small').addClass('text-warning')
            match(frmstanding,nation,name,flag,league,e.target.dataset.id,e.target.dataset.name)
          })
        })
        /*
        list.append('<div class="accordion-item standing border-0 rounded-0 menu mt-12 bg-transparent">'
        +'<form method="post" action="services/'+API+'.php?act=footy" class="accordion-header m-0 py-3 px-2 d-flex align-items-center" novalidate>'
        +'<input type="text" name="stat" placeholder="URL Footy Stats" class="btn p-2 me-2 text-start bg-white" value="'+result.value[1]+'">'
        +'<button type="submit" class="btn ms-auto btn-dark w-25 py-2 my-2">Save</button>'
        +'</form>'
        +'</div>')
        frmstanding.find('.accordion-item.standing form').on('submit', function(e) {
          footy(e,league,$(this).find('input').val(),$(this).attr('action'))
        })
        */
        home_id = 0
        away_id = 0
        home_name = null
        away_name = null
      })
      $('.load').css('display','none')
    }
    else {
      $(".accordion-item.country ul li:eq("+index+")").remove()
      $('.load').css('display','none')
    }
  })
}

async function league(index,nation,country,flag) {
  $('.load').css('display','block')
  await fetch('services/'+API+'.php?act=league&country='+country, {
    method: 'GET'
  })
  .then(function(response) {
    if (response.status != 200) {
      $('.load').css('display','none')
    }
    return response.json()
  })
  .then(result => {
    if (result.status) {
      $('.top-navbar_full label.country').text('League')
      $('.accordion-item.country ul').empty()
      $('.accordion-item.country h6 i').removeClass('fa-chevron-down')
      $('.accordion-item.country h6 i').removeClass('fa-chevron-right')
      $('.accordion-item.country h6 i').addClass('fa-chevron-right')
      $('.accordion-item.country:eq('+index+') h6 i').removeClass('fa-chevron-right')
      $('.accordion-item.country:eq('+index+') h6 i').addClass('fa-chevron-down')
      const list = $('.accordion-item.country:eq('+index+') ul')
      result.value[0].forEach((rows,liga) => {
        const row = Object.values(rows)
        list.append('<li data-id="'+row[0]+'" class="d-flex">'+row[1]+(!rows[1]?'':'<span class="material-symbols-outlined ms-auto text-white">arrows_outward</span>')+'</li>')
        $(".accordion-item.country:eq("+index+") ul li:eq("+liga+")").on('click', function(e) {
          standing(liga,nation,row[0],row[1],flag)
        })
      })
      $('.load').css('display','none')
    }
  })
}

$('.nagivation-menu-wrap ul li').on('click', function(e) {
  $('.top-navbar.country').removeClass('d-none')
  $('.page.standing').css('display','none')
  $('.page.match').css('display','none')
  if($('.nagivation-menu-wrap ul li').index($(this)) < 1) {
    $('.page.feature').css('display','none')
  }
  else {
    feature()    
  }
})

async function country() {
  $('.load').css('display','block')
  await fetch('services/'+API+'.php?act=country', {
    method: 'GET'
  })
  .then(function(response) {
    if (response.status != 200) {
      $('.load').css('display','none')
    }
    return response.json()
  })
  .then(result => {
    if (result.status) {
      $('.top-navbar_full label.country').text('Country')
      const list = $('.accordion.country')
      list.empty()
      result.value[0].forEach((rows,index) => {
        const row = Object.values(rows)
        list.append('<div class="accordion-item country border-0 rounded-0 menu mt-12 bg-transparent">'
          +'<h6 data-id="'+row[0]+'" data-flag="'+row[2]+'" class="accordion-header m-0 py-3 px-2 d-flex align-items-center">'
          +'<div class="flag country me-2"></div>'
          +'<span class="lh-1 my-0 text-white">'+row[1]+'</span>'
          +'<i class="fas fa-chevron-right my-0 text-white ms-auto"></i>'
          +'</h6>'
          +'<div class="faq-bottom-border mt-0"></div>'
          +'<ul></ul>'
          +'</div>')
        $(".accordion-item.country:eq("+index+") h6").on('click', function(e) {
          if ($(".accordion-item.country:eq("+index+")").has('i.fa-chevron-down').length<1) {
            league(index,String(row[1]).toLowerCase().replace(/\s/g,'-'),e.target.dataset.id,e.target.dataset.flag)
          }
          else {
            $('.top-navbar_full label.country').text('Country')
            $(".accordion-item.country:eq("+index+") h6 i").removeClass('fa-chevron-down')
            $(".accordion-item.country:eq("+index+") h6 i").addClass('fa-chevron-right')
            $(".accordion-item.country:eq("+index+") ul").empty()
          }
        })
        $(".flag.country").eq(index).css("background-image","url('data:image/png;base64,"+row[2]+"')")
      })
      key = Object.values(result.value[1])[1]
      $('.load').css('display','none')
    }
  })
}

async function feature() {
  $('.load').css('display','block')
  await fetch('services/'+API+'.php?act=feature', {
    method: 'GET'
  })
  .then(function(response) {
    if (response.status != 200) {
      $('.load').css('display','none')
    }
    return response.json()
  })
  .then(result => {
    if (result.status) {
      console.log(result.value)
      /*
      $('.page.standing').attr('src','standing.html')
      $('.page.standing').css('display','block')
      $('.page.standing').css('animation','loadin')
      $('.page.standing').css('animation-duration','300ms')
      var istanding = $('.page.standing')
      istanding.on('load', function() {
        $('.top-navbar.country').addClass('d-none')
        var frmstanding = istanding.contents()
        frmstanding.find('.top-navbar_full i').click(function(e) {
          setTimeout(function() {
            $('.top-navbar.country').removeClass('d-none')
            $('.page.standing').css('display','none')
          }, 300)
        })
        frmstanding.find('.top-navbar_full small').text(name)
        frmstanding.find('.top-navbar_full small span').remove()
        frmstanding.find('.top-navbar_full small').append('<span class="flag-icon"></span>')
        frmstanding.find('.top-navbar_full small span').css("background-image","url('https://apiv3.apifootball.com/badges/logo_country/"+flag+"')")
        if (result.value[1].length > 0) {
          frmstanding.find('.top-navbar_full small').append('<span class="flag-stat"></span>')
          frmstanding.find('.top-navbar_full small span.flag-stat').click(function(e) {
            window.open('https://footystats.org/'+nation+'/'+result.value[1]+'/predictions', '_blank')
          })
        }
        const list = frmstanding.find('.accordion.standing')
        list.empty()
        result.value[0].forEach((rows,index) => {
          const row = Object.values(rows)
          list.append('<div class="accordion-item standing border-0 rounded-0 menu mt-12 bg-transparent">'
            +'<h6 class="accordion-header m-0 py-3 px-2 d-flex align-items-center">'
            +'<div class="flag me-2 lh-1 text-center col-1 text-white">'+(index+1)+'.</div>'
            +'<span data-id="'+row[0]+'" data-name="'+row[1]+'" class="lh-1 my-0 col-9 text-white">'+row[1]+'</span>'
            +'<small class="ms-auto lh-1 col-2 text-center text-white">[ '+row[3]+' ]</small>'
            +'</h6>'
            +'<div class="faq-bottom-border mt-0"></div>'
            +'</div>')
          frmstanding.find('.accordion-item.standing:eq('+index+') h6 span').on('click', function(e) {
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 .flag').removeClass('text-white')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 span').removeClass('text-white')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 small').removeClass('text-white')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 .flag').addClass('text-warning')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 span').addClass('text-warning')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 small').addClass('text-warning')
            match(nation,name,flag,league,e.target.dataset.id,e.target.dataset.name)
          })
        })
        list.append('<div class="accordion-item standing border-0 rounded-0 menu mt-12 bg-transparent">'
        +'<form method="post" action="services/apifootball.php?act=footy" class="accordion-header m-0 py-3 px-2 d-flex align-items-center" novalidate>'
        +'<input type="text" name="stat" placeholder="URL Footy Stats" class="btn p-2 me-2 text-start bg-white" value="'+result.value[1]+'">'
        +'<button type="submit" class="btn ms-auto btn-dark w-25 py-2 my-2">Save</button>'
        +'</form>'
        +'</div>')
        frmstanding.find('.accordion-item.standing form').on('submit', function(e) {
          footy(e,league,$(this).find('input').val(),$(this).attr('action'))
        })
        home_id = 0
        away_id = 0
        home_name = null
        away_name = null
        window.scrollTo({ top: 0, left: 0 })
      })
    */
    }
    $('.load').css('display','none')
  })
}

let mainchartH,mainchartA
let chartH,chartA
let goalscoredH_HT = [],goalconcedH_HT = [],goalscoredHp_HT = [],goalconcedHp_HT = [],min_goalscoredHp_HT = [],min_goalconcedHp_HT = []
let goalscoredH_FT = [],goalconcedH_FT = [],goalscoredHp_FT = [],goalconcedHp_FT = [],min_goalscoredHp_FT = [],min_goalconcedHp_FT = []
let goalscoredA_HT = [],goalconcedA_HT = [],goalscoredAp_HT = [],goalconcedAp_HT = [],min_goalscoredAp_HT = [],min_goalconcedAp_HT = []
let goalscoredA_FT = [],goalconcedA_FT = [],goalscoredAp_FT = [],goalconcedAp_FT = [],min_goalscoredAp_FT = [],min_goalconcedAp_FT = []

function setchart(home,away,frmmatch) {
  goalscoredH_HT = [],goalconcedH_HT = [],goalscoredHp_HT = [],goalconcedHp_HT = []
  goalscoredH_FT = [],goalconcedH_FT = [],goalscoredHp_FT = [],goalconcedHp_FT = []
  goalscoredA_HT = [],goalconcedA_HT = [],goalscoredAp_HT = [],goalconcedAp_HT = []
  goalscoredA_FT = [],goalconcedA_FT = [],goalscoredAp_FT = [],goalconcedAp_FT = []
  
  let team

  home.forEach(row => {
    team = Object.values(row)

    goalscoredH_HT.push(team[0])
    goalconcedH_HT.push(0-team[1])
    goalscoredH_FT.push(team[2])
    goalconcedH_FT.push(0-team[3])
    goalscoredHp_HT.push(team[4])
    goalconcedHp_HT.push(team[5])
    goalscoredHp_FT.push(team[6])
    goalconcedHp_FT.push(team[7])
    min_goalscoredHp_HT.push(team[4])
    min_goalconcedHp_HT.push(team[5])
    min_goalscoredHp_FT.push(team[6])
    min_goalconcedHp_FT.push(team[7])
  })

  away.forEach(row => {
    team = Object.values(row)

    goalscoredA_HT.push(team[0])
    goalconcedA_HT.push(0-team[1])
    goalscoredA_FT.push(team[2])
    goalconcedA_FT.push(0-team[3])
    goalscoredAp_HT.push(team[4])
    goalconcedAp_HT.push(team[5])
    goalscoredAp_FT.push(team[6])
    goalconcedAp_FT.push(team[7])
    min_goalscoredAp_HT.push(team[4])
    min_goalconcedAp_HT.push(team[5])
    min_goalscoredAp_FT.push(team[6])
    min_goalconcedAp_FT.push(team[7])
  })

  mainchartH = frmmatch.find('#charthome')
  mainchartA = frmmatch.find('#chartaway')

  const existingChartH = Chart.getChart(mainchartH) 
  const existingChartA = Chart.getChart(mainchartA) 

  if (existingChartH) {
    existingChartH.destroy()
  }
  if (existingChartA) {
    existingChartA.destroy()
  }

  frmmatch.find('.accordion-item.match button').text('Half Time')
  checkout(frmmatch,2)
  getchart(frmmatch,2)
}

function checkout(frmmatch,time) {
  $('.checkout').css('display','none')

  let min_gscscored_H = time > 1 ? min_goalscoredHp_FT : min_goalscoredHp_HT
  let min_gscconced_H = time > 1 ? min_goalconcedHp_FT : min_goalconcedHp_HT
  let min_gscscored_A = time > 1 ? min_goalscoredAp_FT : min_goalscoredAp_HT
  let min_gscconced_A = time > 1 ? min_goalconcedAp_FT : min_goalconcedAp_HT

  let gscscored_H = time > 1 ? goalscoredHp_FT : goalscoredHp_HT
  let gscconced_H = time > 1 ? goalconcedHp_FT : goalconcedHp_HT
  let gscscored_A = time > 1 ? goalscoredAp_FT : goalscoredAp_HT
  let gscconced_A = time > 1 ? goalconcedAp_FT : goalconcedAp_HT

  let minscoredH = min_gscscored_H.sort(function(a, b) {
    return a - b
  })
  let minscoredH_1 = 0,minscoredH_2 = 0,minscoredH_3 = 0
  minscoredH.forEach(row => {
    if (minscoredH_1 < 1) {
      minscoredH_1 = row
    }
    else if (row > minscoredH_1 && minscoredH_2 < 1) {
      minscoredH_2 = row
    }
    else if (row > minscoredH_2 && minscoredH_3 < 1) {
      minscoredH_3 = row
    }
  })

  let minconcedH = min_gscconced_H.sort(function(a, b) {
    return a - b
  })
  let minconcedH_1 = 0,minconcedH_2 = 0,minconcedH_3 = 0
  minconcedH.forEach(row => {
    if (minconcedH_1 < 1) {
      minconcedH_1 = row
    }
    else if (row > minconcedH_1 && minconcedH_2 < 1) {
      minconcedH_2 = row
    }
    else if (row > minconcedH_2 && minconcedH_3 < 1) {
      minconcedH_3 = row
    }
  })

  let minscoredA = min_gscscored_A.sort(function(a, b) {
    return a - b
  })
  let minscoredA_1 = 0,minscoredA_2 = 0,minscoredA_3 = 0
  minscoredA.forEach(row => {
    if (minscoredA_1 < 1) {
      minscoredA_1 = row
    }
    else if (row > minscoredA_1 && minscoredA_2 < 1) {
      minscoredA_2 = row
    }
    else if (row > minscoredA_2 && minscoredA_3 < 1) {
      minscoredA_3 = row
    }
  })

  let minconcedA = min_gscconced_A.sort(function(a, b) {
    return a - b
  })
  let minconcedA_1 = 0,minconcedA_2 = 0,minconcedA_3 = 0
  minconcedA.forEach(row => {
    if (minconcedA_1 < 1) {
      minconcedA_1 = row
    }
    else if (row > minconcedA_1 && minconcedA_2 < 1) {
      minconcedA_2 = row
    }
    else if (row > minconcedA_2 && minconcedA_3 < 1) {
      minconcedA_3 = row
    }
  })

  let xGscored_H1 = 0,xGscored_H2 = 0,xGscored_H3 = 0
  let xGconced_H1 = 0,xGconced_H2 = 0,xGconced_H3 = 0
  let xGscored_A1 = 0,xGscored_A2 = 0,xGscored_A3 = 0
  let xGconced_A1 = 0,xGconced_A2 = 0,xGconced_A3 = 0

  gscscored_H.forEach(row => {
    if (row >= minscoredH_3) {
      xGscored_H1++
      xGscored_H2++
      xGscored_H3++
    }
    else if (row >= minscoredH_2) {
      xGscored_H1++
      xGscored_H2++
    }
    else {
      xGscored_H1++
    }
  })
  gscconced_H.forEach(row => {
    if (row >= minconcedH_3) {
      xGconced_H1++
      xGconced_H2++
      xGconced_H3++
    }
    else if (row >= minconcedH_2) {
      xGconced_H1++
      xGconced_H2++
    }
    else {
      xGconced_H1++
    }
  })
  gscscored_A.forEach(row => {
    if (row >= minscoredA_3) {
      xGscored_A1++
      xGscored_A2++
      xGscored_A3++
    }
    else if (row >= minscoredA_2) {
      xGscored_A1++
      xGscored_A2++
    }
    else {
      xGscored_A1++
    }
  })
  gscconced_A.forEach(row => {
    if (row >= minconcedA_3) {
      xGconced_A1++
      xGconced_A2++
      xGconced_A3++
    }
    else if (row >= minconcedA_2) {
      xGconced_A1++
      xGconced_A2++
    }
    else {
      xGconced_A1++
    }
  })

  frmmatch.find('#minutehome li').css('color','black')
  frmmatch.find('#minuteaway li').css('color','black')

  if (gscscored_H.length > 4 && gscconced_H.length > 4 && gscscored_A.length > 4 && gscconced_A.length > 4) {
      gscscored_H.forEach((row,index) => {
        if (index > 3) {
          if (
            gscscored_H[index-1] <= row && gscconced_A[index-1] < gscconced_A[index]
          ) {
            money()
            frmmatch.find('#minutehome').empty()
            frmmatch.find('#minutehome').append('<li class="col-3 p-0">xB '+(((xGscored_H1+xGconced_A1)*20)/2)+'% : '+(parseInt(minscoredH_1)+parseInt(minconcedA_1))+'</li>')
            frmmatch.find('#minutehome').append('<li class="col-3 p-0">xB '+(((xGscored_H2+xGconced_A2)*20)/2)+'% : '+(parseInt(minscoredH_2)+parseInt(minconcedA_2))+'</li>')
            frmmatch.find('#minutehome').append('<li class="col-3 p-0">xB '+(((xGscored_H3+xGconced_A3)*20)/2)+'% : '+(parseInt(minscoredH_3)+parseInt(minconcedA_3))+'</li>')
            frmmatch.find('#minutehome li').css('color','#ffc107')
          }
        }
      })
      gscscored_A.forEach((row,index) => {
        if (index > 3) {
          if (
            gscscored_A[index-1] <= row && gscconced_H[index-1] < gscconced_H[index]
          ) {
            money()
            frmmatch.find('#minuteaway').empty()
            frmmatch.find('#minuteaway').append('<li class="col-3 p-0">xB '+(((xGscored_A1+xGconced_H1)*20)/2)+'% : '+(parseInt(minscoredA_1)+parseInt(minconcedH_1))+'</li>')
            frmmatch.find('#minuteaway').append('<li class="col-3 p-0">xB '+(((xGscored_A2+xGconced_H2)*20)/2)+'% : '+(parseInt(minscoredA_2)+parseInt(minconcedH_2))+'</li>')
            frmmatch.find('#minuteaway').append('<li class="col-3 p-0">xB '+(((xGscored_A3+xGconced_H3)*20)/2)+'% : '+(parseInt(minscoredA_3)+parseInt(minconcedH_3))+'</li>')
            frmmatch.find('#minuteaway li').css('color','#ffc107')
          }
        }
      })
    }
}

async function money() {
  await $('.checkout').css('display','block')
  setTimeout(function() {
    $('.checkout').css('display','none')
  }, 1000)
}

function getchart(frmmatch,time) {
  mainchartH = frmmatch.find('#charthome')
  mainchartA = frmmatch.find('#chartaway')

  const existingChartH = Chart.getChart(mainchartH) 
  const existingChartA = Chart.getChart(mainchartA) 

  if (existingChartH) {
    existingChartH.destroy()
  }
  if (existingChartA) {
    existingChartA.destroy()
  }

  const options = {
		elements: {
			point: {
				pointStyle: false
			}
		},
		scales: {
			x: {
				display: false,
				stacked: true
			},
			y: {
				display: false,
				min: -15,
				max: 15,
				step: 0.5,
				ticks: {
					beginAtZero: true
				}
			}
		},
		plugins: {
			legend: {
				display: false
			},
      tooltip: {
        enabled: false
      }
		}
  }

  chartH = new Chart(mainchartH, {
		data: {
		    labels: [1,2,3,4,5],
		    datasets: [
			    {
			    	type: 'bar',
			    	backgroundColor: '#007700',
			    	borderWidth: 0,
			    	data: time > 1 ? goalscoredH_FT : goalscoredH_HT
			    },
			    {
			    	type: 'bar',
			    	backgroundColor: '#770000',
			    	borderWidth: 0,
			    	data: time > 1 ? goalconcedA_FT : goalconcedA_HT
			    }
		    ]
		},
		options: options
	})  

  chartA = new Chart(mainchartA, {
		data: {
		    labels: [1,2,3,4,5],
		    datasets: [
			    {
			    	type: 'bar',
			    	backgroundColor: '#007700',
			    	borderWidth: 0,
			    	data: time > 1 ? goalscoredA_FT : goalscoredA_HT
			    },
			    {
			    	type: 'bar',
			    	backgroundColor: '#770000',
			    	borderWidth: 0,
			    	data: time > 1 ? goalconcedH_FT : goalconcedH_HT
			    }
		    ]
		},
		options: options
	})
}

$(document).ready(function() {
  country()
})

$(document).on('scroll', function(e) {
  var scrolledTo = window.scrollY + window.innerHeight
  if (document.body.scrollHeight == scrolledTo) {
    $('.customer').css('visibility','visible')
  }
  else {
    $('.customer').css('visibility','hidden')
  }
})

function attachSignin(element) {
    auth2.attachClickHandler(element, {},
        function(googleUser) {
              console.log(googleUser.getBasicProfile())
        }, function(error) {
        });
  }  

var startApp = function() {
    gapi.load('auth2', function() {
      auth2 = gapi.auth2.init({
        client_id: '347172264697-tnhlrnn3f5ntrq04m0a4jac8q75f22kk.apps.googleusercontent.com',
        cookiepolicy: 'single_host_origin',
        scope: 'profile email'
      });
      attachSignin(document.getElementById('oauth'));
    });
  };
