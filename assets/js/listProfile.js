// function to handle list of profile
// Author : F. Parmentier

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

// handle connect button
document.querySelectorAll('.btn-action').forEach(action => 
  action.addEventListener('click', (e) => { 
    e.preventDefault()
    var clickAction = action.getAttribute('id').substring(0,2)
    var userId = action.getAttribute('id').substring(action.getAttribute('id').indexOf('_', 1) + 1, action.getAttribute('id').length)
    var badgeId = 'B_' + userId
    // handle action on click
    switch (clickAction) {
      case 'CO':  // click on connect button
        sendJsonRequest(action.getAttribute('href'), action.dataset.token, 'CO')
        .then(response => {
          if (response.success) {
            // display badge
            badge(badgeId, 'none', 'inline',document.getElementById('badgeText').dataset.connection)
            // change type and string of action for button
            action.setAttribute('id', action.getAttribute('id').replace('CO', 'CA'))
            action.innerHTML = document.getElementById('btnText').dataset.cancel
          }
          else {
            console.log('foireux')
          }
        })
        .catch(e => alert(e))
        break
      case 'CA':  // Cancel
        sendJsonRequest(action.getAttribute('href'), action.dataset.token, 'CA')
        .then(response => {
          if (response.success) {
            // reset & hide badge
            badge(badgeId, 'inline', 'none', '')
            // change type and string of action for button
            action.setAttribute('id', action.getAttribute('id').replace('CA', 'CO'))
            action.innerHTML = document.getElementById('btnText').dataset.connect
          }
          else {
            console.log('foireux')
          }
        })
        .catch(e => alert(e))
        break
      case "AC":  // Accept
        sendJsonRequest(action.getAttribute('href'), action.dataset.token, 'AC')
        .then(response => {
          if (response.success) {
            // reset & hide badge
            badge(badgeId, 'inline', 'none', '')
            // change type and string of action for button
            action.setAttribute('id', action.getAttribute('id').replace('AC', 'ME'))
            action.setAttribute('href', '#')
            action.innerHTML = document.getElementById('btnText').dataset.message
            // change id and item of dropdown-item : DE and delete.relation
           var dropdown_item = document.getElementById('RE_' + userId)
           dropdown_item.setAttribute('id', dropdown_item.getAttribute('id').replace('RE', 'DE'))
           dropdown_item.innerHTML = document.getElementById('dropItemText').dataset.delrelation
          }
          else {
            console.log('foireux')
          }
        })
        .catch(e => alert(e))
        break
        case "UN":  // Unblock
        sendJsonRequest(action.getAttribute('href'), action.dataset.token, 'UN')
        .then(response => {
          if (response.success) {
            // change type and string of action for button
            action.setAttribute('id', action.getAttribute('id').replace('UN', 'CO'))
            action.innerHTML = document.getElementById('btnText').dataset.connect
          }
          else {
            console.log('foireux')
          }
        })
        .catch(e => alert(e))
        break

      default:
        break
    }
  })
)
