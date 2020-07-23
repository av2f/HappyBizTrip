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

// manage connect button
document.querySelectorAll('.btn-connect').forEach(connect => 
  connect.addEventListener('click', (e) => { 
    e.preventDefault()
    fetch(connect.getAttribute('href'), {
      method: 'POST',
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({'_token': connect.dataset.token})
    })
    .then(
      response => response.json()
    )
    .then(data => {
      if (data.success) {
        console.log('youpi ça a marché')
        // remove button
        connect.parentElement.remove()
        // display badge
        var btnId = connect.getAttribute('id')
        var badgeId = 'B_' + btnId.substring(btnId.indexOf('_', 1) + 1, btnId.length)
        if (document.getElementById(badgeId).style.display === 'none') {
            document.getElementById(badgeId).style.display = 'inline'
        }
        console.log(document.getElementById(badgeId).innerHTML)
      }
      else {
        console.log('merde ça a foiré')
      }
    })
    .catch(e => alert(e))
  })
)

// Manage Cancel button
document.querySelectorAll('.btn-cancel').forEach(cancel => 
  cancel.addEventListener('click', (e) => { 
    var btnId = cancel.getAttribute('id')
    console.log(btnId)
  })
)
