import moment from 'moment'
moment().format();
moment.locale('fr')

// load the picture and pseudo of the firt profile of list in header of discussion
$(document).ready(() => {
  changeHeaderDiscussion(document.querySelector('.media').getAttribute('id'))
  buildDiscussion(document.getElementById('discussion').dataset.discussions, document.getElementById('datapage').dataset.userid, true)
  document.getElementById('discussion').dataset.discussions = ''
})

// when select a new profile in the list, change header with avatar and pseudo selected
// and display discussion
document.querySelectorAll('.media').forEach(action =>
  action.addEventListener('click', (e) => {
    e.preventDefault()
    handleLoader(true)
    const userId = action.getAttribute('id').substring(action.getAttribute('id').indexOf('_', 1) + 1, action.getAttribute('id').length)
    changeHeaderDiscussion(action.getAttribute('id'))
    // retrieve discussion feed
    sendJsonRequest(document.getElementById('url_' + userId).getAttribute('href'), document.getElementById(action.getAttribute('id')).dataset.token, '', '')
    .then(response => {
      if (response.success) {
           // remove the content of div discussion
          const divDiscussion = document.getElementById('discussion')
          while(divDiscussion.firstChild) {
              divDiscussion.removeChild(divDiscussion.firstChild)
          }
          buildDiscussion (response.discussions, document.getElementById('datapage').dataset.userid, false)
          // reset textarea for new message
          document.getElementById('newMessage').value = ''
          handleLoader(false)
      } else {
          toastMsg('error', document.getElementById('msgerr').dataset.errone, '4000')
      }
    })
    .catch(e => toastMsg('error', document.getElementById('msgerr').dataset.errtwo, '4000'))  
  })
)

// erase the content of the textarea which contains new message
document.getElementById('btn-erase').addEventListener('click', (e) => {
  e.preventDefault()
  document.getElementById('newMessage').value = ''
})

// handle a new message
document.getElementById('btn-send').addEventListener('click', (e) => {
  e.preventDefault()
  handleLoader(true)
  const dialId = document.querySelector('.dial').getAttribute('id')
  const userId = dialId.substring(dialId.indexOf('_', 1) + 1, dialId.length)
  let newMessage = document.getElementById('newMessage').value.trim()
  sendJsonRequest(document.getElementById('btn-send').getAttribute('href'), document.getElementById('newMessage').dataset.token, newMessage, userId )
  .then(response => {
      if (response.success) {
           // remove the content of div discussion
          const divDiscussion = document.getElementById('discussion')
          while(divDiscussion.firstChild) {
              divDiscussion.removeChild(divDiscussion.firstChild)
          }
          buildDiscussion (response.discussions, document.getElementById('datapage').dataset.userid, false)
          // reset textarea for new message
          document.getElementById('newMessage').value = ''
          handleLoader(false)
      } else {
          toastMsg('error', document.getElementById('msgerr').dataset.errone, '4000')
      }
  })
  .catch(e => toastMsg('error', document.getElementById('msgerr').dataset.errtwo, '4000'))
})

/*
  Change picture and pseudo of header of discussion when
  - page is loaded
  - on click on profile in conversation list
*/
function changeHeaderDiscussion (mediaId) {
  const userId = mediaId.substring(mediaId.indexOf('_', 1) + 1, mediaId.length)
  document.getElementById('picture').setAttribute('src', document.getElementById('picture_' + userId).getAttribute('src'))
  document.querySelector('.dial').setAttribute('id', 'dial_' + userId)
  document.getElementById('dial_' + userId).innerHTML = document.getElementById('pseudo_' + userId).innerHTML
  document.getElementById('discussion').scrollTop = document.getElementById('discussion').scrollHeight
}

/*
  Build the html for the discussion feed
  input : Array of discussion (encode in json)
      userId = Id of the user connected
      boolParse : True = execute JSON.parse / false : do not execute
  Author : F. Parmentier
*/
function buildDiscussion (arrayDiscussion, userId, boolParse) {
  // setup lang for moment.js
  moment.locale(document.getElementById('datapage').dataset.locale)
  if (boolParse) {
    var discussions = JSON.parse(arrayDiscussion)
  } else {
    var discussions = arrayDiscussion
  }
  let numMsg = 1
  discussions.forEach(function (discussion) {
    let cardClass = 'card-receiver ml-auto mr-2'
    if (discussion.m_sender_id === userId) {
      cardClass = 'card-sender mr-auto'
    }
    const divCard = document.createElement('div')
    divCard.className = 'card ' + cardClass + ' mb-3'
    divCard.id = 'card_' + numMsg
    document.getElementById('discussion').appendChild(divCard) // Insertion du nouvel élément
    const divCardBody = document.createElement('div')
    divCardBody.className='card-body pb-1'
    divCardBody.id = 'cardbody_' + numMsg
    document.getElementById(divCard.id).appendChild(divCardBody)
    document.getElementById(divCardBody.id).insertAdjacentHTML('afterBegin', '<p class="text-right">' + moment(discussion.m_createdAt['date']).format('LLLL') + '</p>')
    document.getElementById(divCardBody.id).insertAdjacentHTML('beforeend', '<hr>')
    document.getElementById(divCardBody.id).insertAdjacentHTML('beforeend', '<p>' + discussion.m_message + '</p>')
    if (discussion.m_sender_id === userId) {
      let icon = 'fas fa-envelope'
      if (discussion.m_isReaded === true) {
        icon = 'fas fa-check'
      }
      document.getElementById(divCardBody.id).insertAdjacentHTML('beforeend', '<hr class="mb-1">')
      document.getElementById(divCardBody.id).insertAdjacentHTML('beforeend', '<p class="mt-1 mb-0 text-right icon-read"><i class="' + icon + '"></i></p>')
    }
    numMsg += numMsg
  })
  document.getElementById('discussion').scrollTop = document.getElementById('discussion').scrollHeight
}

function sendJsonRequest (href, theToken, newMessage, receiver) {
  return fetch(href, {
    method: 'POST',
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ '_token': theToken, 'message': newMessage, 'receiver': receiver })
  })
    .then(response => response.json())
    .then(data => { return data })
}

function handleLoader(activate) {
  if (activate) {
    document.querySelector('.load-spinner').setAttribute('id', 'loader')
    document.getElementById('spinner').style.display='block'
  } else {
    document.querySelector('.load-spinner').setAttribute('id', 'unload')
    document.getElementById('spinner').style.display='none'
  }
}

