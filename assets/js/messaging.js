import moment from 'moment';
moment().format();
moment.locale('fr')

// load the picture and pseudo of the firt profile of list in header of discussion
$(document).ready( () => {
  changeHeaderDiscussion(document.querySelector('.media').getAttribute('id'))
  buildDiscussion(document.getElementById('discussion').dataset.discussions, document.getElementById('datapage').dataset.userid)
})

document.querySelectorAll('.media').forEach(action =>
  action.addEventListener('click', (e) => {
      e.preventDefault()
      changeHeaderDiscussion(action.getAttribute('id'))
      //$('discussion').load(document.URL +  ' discussion') // reload div discussion à tester
  })
)

// erase the content of the textarea which contains new message
document.getElementById('btn-erase').addEventListener('click', (e) => {
  e.preventDefault()
  document.getElementById('newMessage').value = ''
})

/*
  Change picture and pseudo of header of discussion when
  - page is loaded
  - on click on profile in conversation list
*/
function changeHeaderDiscussion(mediaId) {
  let userId = mediaId.substring(mediaId.indexOf('_', 1) + 1, mediaId.length)
  document.getElementById('picture').setAttribute('src', document.getElementById('picture_' + userId).getAttribute('src'))
  document.getElementById('pseudo').innerHTML =  document.getElementById('pseudo_' + userId).innerHTML
  document.getElementById('discussion').scrollTop = document.getElementById('discussion').scrollHeight
}

 /*
  Build the html for the discussion feed
  input : Array of discussion (encode in json)
      userId = Id of the user connected
  Author : F. Parmentier
*/
function buildDiscussion (arrayDiscussion, userId) {
  // setup lang for moment.js
  moment.locale(document.getElementById('datapage').dataset.locale)
  var discussions = JSON.parse(arrayDiscussion)
  let numMsg=1
  discussions.forEach(function(discussion) {
    let cardClass = 'card-receiver ml-auto mr-2'
    if (discussion.m_sender_id === userId) {
        cardClass = 'card-sender mr-auto'
    }
    let divCard = document.createElement('div')
    divCard.className = 'card ' + cardClass + ' mb-3'
    divCard.id = 'card_' + numMsg
    document.getElementById("discussion").appendChild(divCard) // Insertion du nouvel élément
    let divCardBody = document.createElement('div')
    divCardBody.className='card-body pb-1'
    divCardBody.id = 'cardbody_' + numMsg
    document.getElementById(divCard.id).appendChild(divCardBody)
    document.getElementById(divCardBody.id).insertAdjacentHTML("afterBegin", '<p class="text-right">' + moment(discussion.m_createdAt['date']).format('LLLL') + '</p>')
    document.getElementById(divCardBody.id).insertAdjacentHTML("beforeend", '<hr>')
    document.getElementById(divCardBody.id).insertAdjacentHTML("beforeend", '<p>' + discussion.m_message + '</p>')
    if (discussion.m_sender_id === userId) {
        let icon = 'fas fa-envelope'
        if (discussion.m_isReaded == true) {
            icon = 'fas fa-check'
        }
        document.getElementById(divCardBody.id).insertAdjacentHTML("beforeend", '<hr class="mb-1">')
        document.getElementById(divCardBody.id).insertAdjacentHTML("beforeend", '<p class="mt-1 mb-0 text-right icon-read"><i class="' + icon + '"></i></p>')
    }
    numMsg += numMsg
  })
}