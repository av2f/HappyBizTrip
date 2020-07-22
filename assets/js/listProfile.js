// position of footer for screen >=768 px
$(function () {
  if ($(window).width() >= 768) {
    var bht = $('body').height()
    var wht = $(window).height()
    if (bht < wht) {
      $('footer').css('position', 'absolute')
    }
  }
})
