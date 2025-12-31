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
let criteria,minute,range

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

async function matchs(frmfixture,flag,name,league,home,away,home_name,away_name,nba) {
  $('.load').css('display','block')
  await fetch('services/'+API+'.php?key='+key+'&act=match&league='+league+'&home='+home+'&away='+away+'&nba='+nba+'&qhome=0&qaway=0&minute=0', {
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
      criteria = result.value[2]
      frmfixture.find('.page.match').attr('src','match.html')
      frmfixture.find('.page.match').css('display','block')
      frmfixture.find('.page.match').css('animation','loadin')
      frmfixture.find('.page.match').css('animation-duration','300ms')
      var imatch = frmfixture.find('.page.match')
      imatch.on('load', function() {
        $('header.country').addClass('d-none')
        frmfixture.find('header').addClass('d-none')
        var frmmatch = imatch.contents()
        frmmatch.find('.top-navbar_full small').focus()
        frmmatch.find('.top-navbar_full small').text(name)
        frmmatch.find('.top-navbar_full i').click(function(e) {
          setTimeout(function() {
            home_id = 0
            away_id = 0
            chart_result = 2
            frmfixture.find('.page.match').css('display','none')
            frmfixture.find('header').removeClass('d-none')
          }, 300)
        })
        frmmatch.find('.top-navbar_full small span').remove()
        frmmatch.find('.top-navbar_full small').append('<span class="flag-icon"></span>')
        frmmatch.find('.top-navbar_full small span').css("background-image","url('data:image/png;base64,"+flag+"')")
        frmmatch.find('.accordion-item.match:eq(0) ul li:eq(0)').text(home_name)
        frmmatch.find('.accordion-item.match:eq(1) ul li:eq(0)').text(away_name)
        frmmatch.find('.accordion-item.match:eq(0) ul li:eq(0)').addClass(result.value[3]?'fw-bold text-danger':'text-white')
        frmmatch.find('.accordion-item.match:eq(1) ul li:eq(0)').addClass(result.value[3]?'fw-bold text-danger':'text-white')
        frmmatch.find('.accordion-item.match:eq(3) button').remove()
        frmmatch.find('.accordion-item.match:eq(3)').append('<button type="reset" class="btn btn-dark col-12 py-2 my-2"></button>')
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
      $('.load').css('display','none')
    }
  })
}

function match(formstanding,name,flag,league,id,team,nba) {
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

    $('.score').removeClass('d-none')
    $('.score input').val('')
    $('.score select').val('')
    $('.score button').focus()

    m_formstanding = formstanding,m_name = name,m_flag = flag,m_league = league,m_nba = nba
  }
}

async function calc(qhome,qaway,minutes) {
    $('.score').addClass('d-none')
    $('.load').css('display','block')
    await fetch('services/'+API+'.php?key='+key+'&act=match&league='+m_league+'&home='+home_id+'&away='+away_id+'&nba='+m_nba+'&qhome='+qhome+'&qaway='+qaway+'&minute='+minutes, {
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
        criteria = result.value[2]
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
          frmmatch.find('.top-navbar_full small').text(m_name)
          frmmatch.find('.top-navbar_full small span').remove()
          frmmatch.find('.top-navbar_full small i').remove()
          frmmatch.find('.top-navbar_full small').append('<span class="flag-icon"></span>')
          frmmatch.find('.top-navbar_full small span').css("background-image","url('data:image/png;base64,"+m_flag+"')")
          frmmatch.find('.top-navbar_full small').append('<i class="fas fa-globe text-white"></i>')
          frmmatch.find('.top-navbar_full small i').click(function(e) {
            frmstanding.find('.top-navbar.standing').removeClass('d-none')
            frmstanding.find('.page.match').css('display','none')
            $('.top-navbar.country').removeClass('d-none')
            $('.page.standing').css('display','none')
            $('.home-navigation-menu').removeClass('d-none')
          })

          frmmatch.find('.accordion-item.match:eq(0) ul li:eq(0)').text(home_name)
          frmmatch.find('.accordion-item.match:eq(1) ul li:eq(0)').text(away_name)

          frmmatch.find('.accordion-item.match:eq(0) ul li:eq(0)').addClass(result.value[3]?'fw-bold text-danger':'text-white')
          frmmatch.find('.accordion-item.match:eq(1) ul li:eq(0)').addClass(result.value[3]?'fw-bold text-danger':'text-white')

          frmmatch.find('.accordion-item.match:eq(3) button').remove()
          frmmatch.find('.accordion-item.match:eq(3)').append('<button type="reset" class="btn btn-dark col-12 py-2 my-2"></button>')
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
        m_formstanding.find('input[type="search"]').val('')
        m_formstanding.find('.accordion-item.standing').removeClass('d-none')
        $('.load').css('display','none')
      }
    })
}

async function standing(index,league,name,nba,flag) {
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
    if (result.status && result.value.length>0) {
      $('.home-navigation-menu').addClass('d-none')
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
            $('.home-navigation-menu').removeClass('d-none')
            $('.top-navbar.country').removeClass('d-none')
            $('.page.standing').css('display','none')
          }, 300)
        })
        frmstanding.find('.top-navbar_full small').text(name)
        frmstanding.find('.top-navbar_full small span').remove()
        frmstanding.find('.top-navbar_full small').append('<span class="flag-icon"></span>')
        frmstanding.find('.top-navbar_full small span').css("background-image","url('data:image/png;base64,"+flag+"')")
        const list = frmstanding.find('.accordion.standing')
        list.empty()
        if (result.value.length > 50) {
          list.append('<input type="search" name="search" placeholder="Search name..." class="btn p-2 text-start bg-white" autofocus>')
          frmstanding.find('input[type="search"]').on('input', function(e) {
            search(frmstanding, e.target.value)
          })
        }
        result.value.forEach((rows,index) => {
          const row = Object.values(rows)
          list.append('<div class="accordion-item standing border-0 rounded-0 menu mt-12 bg-transparent">'
            +'<h6 class="accordion-header m-0 py-3 px-2 d-flex align-items-center">'
            +'<div class="flag me-2 lh-1 text-center col-1 text-white">'+(index+1)+'.</div>'
            +'<span data-id="'+row[1]+'" data-name="'+row[2]+'" class="lh-1 my-0 col-9 text-white">'+row[2]+'</span>'
            +'<small class="ms-auto lh-1 col-2 text-center text-white">[ '+row[3]+' ]</small>'
            +'</h6>'
            +'<div class="faq-bottom-border mt-0"></div>'
            +'</div>')
          frmstanding.find('.accordion-item.standing:eq('+index+') h6 span').on('click', async function(e) {
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 .flag').removeClass('text-white')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 span').removeClass('text-white')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 small').removeClass('text-white')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 .flag').addClass('text-warning')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 span').addClass('text-warning')
            frmstanding.find('.accordion-item.standing:eq('+index+') h6 small').addClass('text-warning')
            await match(frmstanding,name,flag,league,e.target.dataset.id,e.target.dataset.name,nba)
          })
        })
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

async function league(index,country,flag) {
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
      result.value.forEach((rows,liga) => {
        const row = Object.values(rows)
        list.append('<li data-id="'+row[0]+'" class="d-flex">'+row[1]+(!rows[1]?'':'<span class="material-symbols-outlined ms-auto text-white">arrows_outward</span>')+'</li>')
        $(".accordion-item.country:eq("+index+") ul li:eq("+liga+")").on('click', async function(e) {
          await standing(liga,row[0],row[1],row[3],flag)
        })
      })
      $('.load').css('display','none')
    }
  })
}

let m_formstanding = null,m_name = null,m_flag = null,m_league = null,m_nba = null

$('.score button').on('click', async function(e) {
  await calc(parseInt($('.score input:eq(0)').val()),parseInt($('.score input:eq(1)').val()),parseInt($('.score select').val()))
})

$('.nagivation-menu-wrap ul li').on('click', async function(e) {
  if($('.nagivation-menu-wrap ul li').index($(this)) < 1) {
    $('header.country').removeClass('d-none')
    $('.page.fixture').css('display','none')
  }
  else {
    await fixture()
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
        $(".accordion-item.country:eq("+index+") h6").on('click', async function(e) {
          if ($(".accordion-item.country:eq("+index+")").has('i.fa-chevron-down').length<1) {
            await league(index,e.target.dataset.id,e.target.dataset.flag)
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

async function fixture() {
  $('.load').css('display','block')
  await fetch('services/'+API+'.php?key='+key+'&act=fixture', {
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
      $('.page.fixture').attr('src','fixture.html')
      $('.page.fixture').css('display','block')
      $('.page.fixture').css('animation','loadin')
      $('.page.fixture').css('animation-duration','300ms')      
      var ifixture = $('.page.fixture')
      ifixture.on('load', function() {
        var frmfixture = ifixture.contents()
        const list = frmfixture.find('.accordion.fixture')
        $('header.country').addClass('d-none')
        frmfixture.find('header').removeClass('d-none')
        frmfixture.find('.page.match').css('display','none')
        frmfixture.find('.page.match').css('animation-duration','0ms')
        list.empty()
        let n_country = 0,country,league,dates,n_match = 0
        result.value.forEach(rows => {
          const row = Object.values(rows)
          if (country != row[1]) {
            country = row[1]
            league = row[4]
            dates = row[5]
            list.append('<div class="accordion-item fixture border-0 rounded-0 menu mt-4 bg-transparent">'
              +'<h6 class="accordion-header m-0 d-flex align-items-center">'
              +'<div class="flag fixture me-2"></div>'
              +'<span class="lh-1 my-0 text-white">'+row[1]+'</span>'
              +'</h6>'
              +'<div class="mt-2 px-1 pb-2 d-flex border-bottom border-dark">'
              +'<small class="text-white fw-bold">'+row[4]+'</small>'
              +'<small class="ms-auto text-white">'+row[5]+'</small>'
              +'</div>'
              +'<ul class="row m-0 p-0 py-1 border-bottom border-dark">'
              +'<li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-5 text-center text-white">'+row[8]+'</li><li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-2 text-center text-white">-</li><li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-5 text-center text-white">'+row[9]+'</li>'
              +'</ul>'
              +'</div>')
            frmfixture.find('.flag.fixture').eq(n_country).css("background-image","url('data:image/png;base64,"+row[2]+"')")
            n_country++
          }
          else if (league == row[4] && dates == row[5]) {
            list.append('<ul class="row m-0 p-0 py-1 border-bottom border-dark">'
              +'<li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-5 text-center text-white">'+row[8]+'</li><li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-2 text-center text-white">-</li><li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-5 text-center text-white">'+row[9]+'</li>'
              +'</ul>')
          }
          else if (league == row[4] && dates != row[5]) {
            dates = row[5]
            list.append('<div class="mt-2 px-1 pb-2 d-flex border-bottom border-dark">'
              +'<small class="text-white fw-bold"></small>'
              +'<small class="ms-auto text-white">'+row[5]+'</small>'
              +'</div>'
              +'<ul class="row m-0 p-0 py-1 border-bottom border-dark">'
              +'<li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-5 text-center text-white">'+row[8]+'</li><li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-2 text-center text-white">-</li><li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-5 text-center text-white">'+row[9]+'</li>'
              +'</ul>')
          }
          else if (league != row[4]) {
            league = row[4]
            list.append('<div class="mt-2 px-1 pb-2 d-flex border-bottom border-dark">'
              +'<small class="text-white fw-bold">'+row[4]+'</small>'
              +'<small class="ms-auto text-white">'+row[5]+'</small>'
              +'</div>'
              +'<ul class="row m-0 p-0 py-1 border-bottom border-dark">'
              +'<li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-5 text-center text-white">'+row[8]+'</li><li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-2 text-center text-white">-</li><li data-flag='+row[2]+' data-league='+row[3]+' data-name='+row[4]+' data-home='+row[6]+' data-away='+row[7]+' data-nhome='+row[8]+' data-naway='+row[9]+' data-nba='+row[10]+' class="py-1 col-5 text-center text-white">'+row[9]+'</li>'
              +'</ul>')
          }
          frmfixture.find('ul').eq(n_match).on('click', async function(e) {
            await matchs(frmfixture,e.target.dataset.flag,e.target.dataset.name,e.target.dataset.league,e.target.dataset.home,e.target.dataset.away,e.target.dataset.nhome,e.target.dataset.naway,e.target.dataset.nba)
          })
          n_match++
        })
      })
      $('.load').css('display','none')
    }
  })
}

let mainchartH,mainchartA
let chartH,chartA
let goalscoredH_HT = [],goalconcedH_HT = [],goalscoredH_FT = [],goalconcedH_FT = []
let goalscoredA_HT = [],goalconcedA_HT = [],goalscoredA_FT = [],goalconcedA_FT = []

function setchart(home,away,frmmatch) {
  goalscoredH_HT = [],goalconcedH_HT = []
  goalscoredH_FT = [],goalconcedH_FT = []
  goalscoredA_HT = [],goalconcedA_HT = []
  goalscoredA_FT = [],goalconcedA_FT = []
  
  let team

  home.forEach(row => {
    team = Object.values(row)

    goalscoredH_HT.push(team[0])
    goalconcedH_HT.push(0-team[1])
    goalscoredH_FT.push(team[2])
    goalconcedH_FT.push(0-team[3])
  })

  away.forEach(row => {
    team = Object.values(row)

    goalscoredA_HT.push(team[0])
    goalconcedA_HT.push(0-team[1])
    goalscoredA_FT.push(team[2])
    goalconcedA_FT.push(0-team[3])
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

async function checkout(frmmatch,time) {
  $('.checkout').css('display','none')

  frmmatch.find('#minutehome li').css('color','#523c01')
  frmmatch.find('#minutehome li label').css('color','#523c01')
  frmmatch.find('#minutehome li span').css('color','#523c01')
  frmmatch.find('#minuteaway li').css('color','#523c01')
  frmmatch.find('#minuteaway li label').css('color','#523c01')
  frmmatch.find('#minuteaway li span').css('color','#523c01')
  frmmatch.find('#scores li small').css('color','#000000')

  frmmatch.find('.accordion-item.match:eq(0) ul li:eq(1) small span:eq(0)').text('(0:0)')
  frmmatch.find('.accordion-item.match:eq(0) ul li:eq(1) small span:eq(1)').text('')
  frmmatch.find('#minutehome li:eq(0) label').text('0')
  frmmatch.find('#minutehome li:eq(0) span').text('0')
  frmmatch.find('#minutehome li:eq(1) label').text('0')
  frmmatch.find('#minutehome li:eq(1) span').text('0')
  frmmatch.find('#minutehome li:eq(2) label').text('0')
  frmmatch.find('#minutehome li:eq(2) span').text('0')
  frmmatch.find('#minutehome li:eq(3) label').text('0')
  frmmatch.find('#minutehome li:eq(3) span').text('0')
  frmmatch.find('.accordion-item.match:eq(1) ul li:eq(1) small span:eq(0)').text('(0:0)')
  frmmatch.find('.accordion-item.match:eq(1) ul li:eq(1) small span:eq(1)').text('')
  frmmatch.find('#minuteaway li:eq(0) label').text('0')
  frmmatch.find('#minuteaway li:eq(0) span').text('0')
  frmmatch.find('#minuteaway li:eq(1) label').text('0')
  frmmatch.find('#minuteaway li:eq(1) span').text('0')
  frmmatch.find('#minuteaway li:eq(2) label').text('0')
  frmmatch.find('#minuteaway li:eq(2) span').text('0')
  frmmatch.find('#minuteaway li:eq(3) label').text('0')
  frmmatch.find('#minuteaway li:eq(3) span').text('0')
  frmmatch.find('#scores li small').text('0')

  if (goalscoredH_HT.length > 4 && goalconcedH_HT.length > 4 && goalscoredA_HT.length > 4 && goalconcedA_HT.length > 4 && goalscoredH_FT.length > 4 && goalconcedH_FT.length > 4 && goalscoredA_FT.length > 4 && goalconcedA_FT.length > 4) {
    frmmatch.find('#scores li small').css('color','white')
    frmmatch.find('#scores li:eq(0) small:eq(0)').text('Min. ')
    frmmatch.find('#scores li:eq(1) small:eq(0)').text('Max. ')

    if (time == 1) {
      if (criteria[0][0][0] && criteria[0][0][3] || criteria[0][0][2] && criteria[0][0][1]) {
        money()
        frmmatch.find('#minutehome li:eq(0) label').text(criteria[3][7][0][0][0])
        frmmatch.find('#minutehome li:eq(1) label').text(criteria[3][7][0][0][1])
        frmmatch.find('#minutehome li:eq(2) label').text(criteria[3][7][0][0][2])
        frmmatch.find('#minutehome li:eq(3) label').text(criteria[3][7][0][0][3])

        frmmatch.find('.accordion-item.match:eq(0) ul li:eq(1) small span:eq(0)').text('('+criteria[1][2][2][0]+':'+criteria[1][2][2][1]+')')
        if (criteria[1][2][0] > 0) {
          frmmatch.find('.accordion-item.match:eq(0) ul li:eq(1) small span:eq(1)').text('more +'+criteria[1][2][0])
        }

        if (criteria[0][0][0] && criteria[0][0][3]) {
          frmmatch.find('#minutehome li').css('color','#ffc107')
          frmmatch.find('#minutehome li label').css('color','#ffc107')
          frmmatch.find('#minutehome li span').css('color','#ffc107')
        }

        money()
        frmmatch.find('#minuteaway li:eq(0) label').text(criteria[3][7][0][1][0])
        frmmatch.find('#minuteaway li:eq(1) label').text(criteria[3][7][0][1][1])
        frmmatch.find('#minuteaway li:eq(2) label').text(criteria[3][7][0][1][2])
        frmmatch.find('#minuteaway li:eq(3) label').text(criteria[3][7][0][1][3])

        frmmatch.find('.accordion-item.match:eq(1) ul li:eq(1) small span:eq(0)').text('('+criteria[1][2][2][0]+':'+criteria[1][2][2][1]+')')
        if (criteria[1][2][0] > 0) {
          frmmatch.find('.accordion-item.match:eq(1) ul li:eq(1) small span:eq(1)').text('more +'+criteria[1][2][0])
        }

        if (criteria[0][0][2] && criteria[0][0][1]) {
          frmmatch.find('#minuteaway li').css('color','#ffc107')
          frmmatch.find('#minuteaway li label').css('color','#ffc107')
          frmmatch.find('#minuteaway li span').css('color','#ffc107')
        }

        frmmatch.find('#scores li:eq(0) small:eq(1)').text(criteria[1][10][0][0])
        frmmatch.find('#scores li:eq(1) small:eq(1)').text(criteria[1][10][0][1])
        frmmatch.find('#scores li:eq(2) small').text(criteria[1][0][0])
        frmmatch.find('#scores li:eq(3) small').text(criteria[1][0][1])
        frmmatch.find('#scores li:eq(4) small').text(criteria[1][0][2])
        frmmatch.find('#scores li:eq(5) small').text(criteria[1][0][3])
      }

      frmmatch.find('#scores li:eq(6) small:eq(0)').text(criteria[1][9][0][0])
      frmmatch.find('#scores li:eq(6) small:eq(1)').text(' pts/mnt')
      frmmatch.find('#scores li:eq(7) small:eq(0)').text('Q2 : ')
      frmmatch.find('#scores li:eq(7) small:eq(1)').text(criteria[1][9][0][1])
    }
    else if (time == 2) {
      if (criteria[0][1][0] && criteria[0][1][3] || (criteria[0][1][2] && criteria[0][1][1])) {
        money()
        frmmatch.find('#minutehome li:eq(0) label').text(criteria[3][7][1][0][0])
        frmmatch.find('#minutehome li:eq(1) label').text(criteria[3][7][1][0][1])
        frmmatch.find('#minutehome li:eq(2) label').text(criteria[3][7][1][0][2])
        frmmatch.find('#minutehome li:eq(3) label').text(criteria[3][7][1][0][3])

        frmmatch.find('.accordion-item.match:eq(0) ul li:eq(1) small span:eq(0)').text('('+criteria[1][2][2][0]+':'+criteria[1][2][2][1]+')')
        if (criteria[1][2][1] > 0) {
          frmmatch.find('.accordion-item.match:eq(0) ul li:eq(1) small span:eq(1)').text('more +'+criteria[1][2][1])
        }

        if (criteria[0][1][0] && criteria[0][1][3]) {
          frmmatch.find('#minutehome li').css('color','#ffc107')
          frmmatch.find('#minutehome li label').css('color','#ffc107')
          frmmatch.find('#minutehome li span').css('color','#ffc107')
        }

        money()
        frmmatch.find('#minuteaway li:eq(0) label').text(criteria[3][7][1][1][0])
        frmmatch.find('#minuteaway li:eq(1) label').text(criteria[3][7][1][1][1])
        frmmatch.find('#minuteaway li:eq(2) label').text(criteria[3][7][1][1][2])
        frmmatch.find('#minuteaway li:eq(3) label').text(criteria[3][7][1][1][3])

        frmmatch.find('.accordion-item.match:eq(1) ul li:eq(1) small span:eq(0)').text('('+criteria[1][2][2][0]+':'+criteria[1][2][2][1]+')')
        if (criteria[1][2][1] > 0) {
          frmmatch.find('.accordion-item.match:eq(1) ul li:eq(1) small span:eq(1)').text('more +'+criteria[1][2][1])
        }

        if (criteria[0][1][2] && criteria[0][1][1]) {
          frmmatch.find('#minuteaway li').css('color','#ffc107')
          frmmatch.find('#minuteaway li label').css('color','#ffc107')
          frmmatch.find('#minuteaway li span').css('color','#ffc107')
        }

        frmmatch.find('#scores li:eq(0) small:eq(1)').text(criteria[1][10][1][0])
        frmmatch.find('#scores li:eq(1) small:eq(1)').text(criteria[1][10][1][1])
        frmmatch.find('#scores li:eq(2) small').text(criteria[1][1][0])
        frmmatch.find('#scores li:eq(3) small').text(criteria[1][1][1])
        frmmatch.find('#scores li:eq(4) small').text(criteria[1][1][2])
        frmmatch.find('#scores li:eq(5) small').text(criteria[1][1][3])
      }

      frmmatch.find('#scores li:eq(6) small:eq(0)').text(criteria[1][9][1][0])
      frmmatch.find('#scores li:eq(6) small:eq(1)').text(' pts/mnt')
      frmmatch.find('#scores li:eq(7) small:eq(0)').text('Total : ')
      frmmatch.find('#scores li:eq(7) small:eq(1)').text(criteria[1][9][1][1])
    }
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
