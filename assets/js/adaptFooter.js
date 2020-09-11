// position of footer for screen >=768 px
$(() => {
  if ($(window).width() >= 768) {
    const bht = $('body').height()
    const wht = $(window).height()
    if (bht < wht) {
      $('footer').css('position', 'absolute')
    }
  }
})
