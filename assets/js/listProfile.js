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
    var clickAction = action.getAttribute('id').substring(0, 2)
    var userId = action.getAttribute('id').substring(action.getAttribute('id').indexOf('_', 1) + 1, action.getAttribute('id').length)
    var badgeId = 'B_' + userId
    // handle action on click
    switch (clickAction) {
      case 'CO': // click on connect button
        sendJsonRequest(action.getAttribute('href'), action.dataset.token, 'CO')
          .then(response => {
            if (response.success) {
            // display badge
              badge(badgeId, 'none', 'inline', document.getElementById('badgeText').dataset.connection)
              // change type and string of action for button
              action.setAttribute('id', action.getAttribute('id').replace('CO', 'CA'))
              action.innerHTML = document.getElementById('btnText').dataset.cancel
            } else {
              toastMsg('error', document.getElementById('msgerr').dataset.errone, '4000')
            }
          })
          .catch(e => toastMsg('error', document.getElementById('msgerr').dataset.errtwo, '4000'))
        break
      case 'CA': // Cancel
        sendJsonRequest(action.getAttribute('href'), action.dataset.token, 'CA')
          .then(response => {
            if (response.success) {
            // reset & hide badge
              badge(badgeId, 'inline', 'none', '')
              // change type and string of action for button
              action.setAttribute('id', action.getAttribute('id').replace('CA', 'CO'))
              action.innerHTML = document.getElementById('btnText').dataset.connect
            } else {
              toastMsg('error', document.getElementById('msgerr').dataset.errone, '4000')
            }
          })
          .catch(e => toastMsg('error', document.getElementById('msgerr').dataset.errtwo, '4000'))
        break
      case 'AC': // Accept
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
              var dropdownItem = document.getElementById('RE_' + userId)
              dropdownItem.setAttribute('id', dropdownItem.getAttribute('id').replace('RE', 'DE'))
              dropdownItem.innerHTML = document.getElementById('dropItemText').dataset.delrelation
            } else {
              toastMsg('error', document.getElementById('msgerr').dataset.errone, '4000')
            }
          })
          .catch(e => toastMsg('error', document.getElementById('msgerr').dataset.errtwo, '4000'))
        break
      case 'UN': // Unblock
        sendJsonRequest(action.getAttribute('href'), action.dataset.token, 'UN')
          .then(response => {
            if (response.success) {
            // change type and string of action for button
              action.setAttribute('id', action.getAttribute('id').replace('UN', 'CO'))
              action.innerHTML = document.getElementById('btnText').dataset.connect
            } else {
              toastMsg('error', document.getElementById('msgerr').dataset.errone, '4000')
            }
          })
          .catch(e => toastMsg('error', document.getElementById('msgerr').dataset.errtwo, '4000'))
        break

      default:
        break
    }
  })
)

// handle dropdown-item
document.querySelectorAll('.dropdown-item-action').forEach(item =>
  item.addEventListener('click', (e) => {
    e.preventDefault()
    var clickItem = item.getAttribute('id').substring(0, 2)
    var userId = item.getAttribute('id').substring(item.getAttribute('id').indexOf('_', 1) + 1, item.getAttribute('id').length)
    var badgeId = 'B_' + userId
    switch (clickItem) {
      case 'BL': // Block user
        sendJsonRequest(item.getAttribute('href'), item.dataset.token, 'BL')
          .then(response => {
            if (response.success) {
            // reset and hide badge
              badge(badgeId, 'inline', 'none', '')
              // change type and string of action for button
              var suffixAction = 'ME'
              if (document.getElementById('AC_' + userId)) {
                suffixAction = 'AC'
              }
              var btnAction = document.getElementById(suffixAction + '_' + userId)
              btnAction.setAttribute('id', btnAction.getAttribute('id').replace(suffixAction, 'UN'))
              if (suffixAction === 'ME') {
              // set the right URL
                btnAction.setAttribute('href', document.getElementById('btnText').dataset.url)
                btnAction.setAttribute('href', btnAction.getAttribute('href').replace('resultId', userId))
              }
              btnAction.innerHTML = document.getElementById('btnText').dataset.unblock
              // hide action item
              if (document.getElementById('BG_' + userId).style.display === 'inline') {
                document.getElementById('BG_' + userId).style.display = 'none'
              }
            } else {
              toastMsg('error', document.getElementById('msgerr').dataset.errone, '4000')
            }
          })
          .catch(e => toastMsg('error', document.getElementById('msgerr').dataset.errtwo, '4000'))
        break
      case 'DE': // Delete the relation
        sendJsonRequest(item.getAttribute('href'), item.dataset.token, 'DE')
          .then(response => {
            if (response.success) {
            // reset and hide badge
              badge(badgeId, 'inline', 'none', '')
              // change type and string of action for button
              var btnAction = document.getElementById('ME' + '_' + userId)
              btnAction.setAttribute('id', btnAction.getAttribute('id').replace('ME', 'CO'))
              // set the right URL
              btnAction.setAttribute('href', document.getElementById('btnText').dataset.url)
              btnAction.setAttribute('href', btnAction.getAttribute('href').replace('resultId', userId))
              btnAction.innerHTML = document.getElementById('btnText').dataset.connect
              // hide action item
              if (document.getElementById('BG_' + userId).style.display === 'inline') {
                document.getElementById('BG_' + userId).style.display = 'none'
              }
            } else {
              toastMsg('error', document.getElementById('msgerr').dataset.errone, '4000')
            }
          })
          .catch(e => toastMsg('error', document.getElementById('msgerr').dataset.errtwo, '4000'))
        break
      case 'RE': // relation refused
        sendJsonRequest(item.getAttribute('href'), item.dataset.token, 'RE')
          .then(response => {
            if (response.success) {
            // reset and hide badge
              badge(badgeId, 'inline', 'none', '')
              // change type and string of action for button
              var btnAction = document.getElementById('AC' + '_' + userId)
              btnAction.setAttribute('id', btnAction.getAttribute('id').replace('AC', 'CO'))
              btnAction.innerHTML = document.getElementById('btnText').dataset.connect
              // hide action item
              if (document.getElementById('BG_' + userId).style.display === 'inline') {
                document.getElementById('BG_' + userId).style.display = 'none'
              }
            } else {
              toastMsg('error', document.getElementById('msgerr').dataset.errone, '4000')
            }
          })
          .catch(e => toastMsg('error', document.getElementById('msgerr').dataset.errtwo, '4000'))
        break
      default:
        break
    }
  })
)

/*
  Manage json request with promise
  href  : url to send request
  theToken  : token
  action  :
    CO = Connect
    CA = Cancel
    AC = Accept
*/

function sendJsonRequest (href, theToken, action) {
  return fetch(href, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ '_token': theToken, 'action': action })
  })
    .then(response => response.json())
    .then(data => { return data })
}

/*
  Display or hide the badge
  badgeId : id of the badge to change state
  displayScr  : value of original style display
  displayDest : value of style display after change
  textBadge : Text for badge
*/
function badge (badgeId, displaySrc, displayDest, textBadge) {
  if (document.getElementById(badgeId).style.display === displaySrc) {
    document.getElementById(badgeId).innerHTML = textBadge
    document.getElementById(badgeId).style.display = displayDest
  }
}
