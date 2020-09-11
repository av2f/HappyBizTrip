// ***** manage interest choices *****
// initialize value of profile_listInterest
var fileAvatar
window.addEventListener('load', (event) => {
  // var listInterest = document.getElementById("profile_listInterest").value
  var arrayListInterest = []
  document.querySelectorAll('.control-interest').forEach(interest => {
    if (interest.checked) {
      var strInterest = interest.getAttribute('id')
      var interestId = strInterest.substring(strInterest.indexOf('_', 1) + 1, strInterest.length)
      arrayListInterest.push(interestId)
    }
  })
  document.getElementById('profile_listInterest').value = arrayListInterest.join(';')
})

// retrieve all interests and listen on click
document.querySelectorAll('.control-interest').forEach(interest =>
  interest.addEventListener('click', () => {
    var listInterest = document.getElementById('profile_listInterest').value
    // retrieve the id of interest
    var strInterest = interest.getAttribute('id')
    var interestId = strInterest.substring(strInterest.indexOf('_', 1) + 1, strInterest.length)
    // action following checked or not
    if (interest.checked /* && listInterest.indexOf(interestId) == -1 */) { // if checked
      listInterest = updateListInterest(listInterest, interestId, 'A')
    } else {
      listInterest = updateListInterest(listInterest, interestId, 'R')
    }
    document.getElementById('profile_listInterest').value = listInterest
  })
)
// ***** end of manage interest choices *****

// ***** Management of avatar modal window **********
var btnCancel = false // cancel button of modal avatar management

// load modal image when modal window is opened
$('#avatarProfileModal').on('show.bs.modal', () => {
  document.getElementById('imgModalAvatar').setAttribute('src', document.getElementById('imgAvatarProfile').getAttribute('src'))
})

document.getElementById('btnCancelAvatar').addEventListener('click', () => { btnCancel = true })
const fileInput = document.getElementById('uploadFile')

document.getElementById('btnDelAvatar').addEventListener('click', () => {
  // if delete image, replace it by default avatar image
  document.getElementById('imgModalAvatar').setAttribute('src', document.getElementById('pictures').dataset.imgdefaultavatar)
  fileInput.value = ''
})

// if click on change button
document.getElementById('btnChangeAvatar').addEventListener('click', () => {
  fileInput.click()
})

// *** manage upload new file ***
fileInput.addEventListener('change', (e) => {
  // if file choose
  fileAvatar = this.files[0]
  if (fileAvatar) {
    const reader = new FileReader()
    reader.addEventListener('load', () => {
      document.getElementById('imgModalAvatar').setAttribute('src', this.result)
    })
    reader.readAsDataURL(fileAvatar)
  }
})
$('#avatarProfileModal').on('hide.bs.modal', (e) => {
  if (!btnCancel) { // if not cancel button
    const imgModalAvatar = document.getElementById('imgModalAvatar').getAttribute('src')
    if (document.getElementById('imgAvatarProfile').getAttribute('src') !== document.getElementById('imgModalAvatar').getAttribute('src')) {
      // if image change
      handleLoader(true)
      const inputUpdateAvatar = document.getElementById('input-update-avatar') // token
      let form = new FormData()
      form.append('_token', inputUpdateAvatar.dataset.token)
      // if avatar deleted
      if (fileInput.value === '') {
        form.append('image', document.getElementById('pictures').dataset.useravatar)
        form.append('action', 'delete')
      } else {
        // else change with new image
        form.append('image', fileAvatar)
        form.append('action', 'update')
      }
      // Send new image by json to update - name of file = fileAvatar['name']
      fetch(inputUpdateAvatar.getAttribute('data-ref'), {
        method: 'POST',
        headers: {
          'X-Request-With': 'XMLHttpRequest',
          'enctype': 'multipart/form-data',
        },
        body: form
      })
        .then(function (response) {
          // retrieve the response in json
          return response.json()
        })
        .then(data => {
          handleLoader(false)
          if (!data.success) {
            var msgError = ''
            switch (data.error) {
              case '2':
                msgError = document.getElementById('msgerr').dataset.errone
                break
              case '3':
                msgError = document.getElementById('msgerr').dataset.errtwo
                break
              case '4':
                msgError = document.getElementById('msgerr').dataset.errthree
                break
              default:
                msgError = document.getElementById('msgerr').dataset.errone
                break
            }
            toastMsg('error', msgError, '4000')
          } else {
            // success
            if (fileInput.value === '') {
              document.getElementById('imgAvatarProfile').setAttribute('src', document.getElementById('pictures').dataset.imgdefaultavatar)
              document.getElementById('imgAvatarNav').setAttribute('src', document.getElementById('pictures').dataset.imgdefaultavatar)
            } else {
              // Replace image of avatar in profile form
              document.getElementById('imgAvatarProfile').setAttribute('src', imgModalAvatar)
              document.getElementById('imgAvatarNav').setAttribute('src', imgModalAvatar)
            }
          }
        })
        .catch(e => toastMsg('error', document.getElementById('msgerr').dataset.errone, '4000'))
    }
  } else {
    btnCancel = false
  }
})
// ***** end of management of avatar modal window **********

$('#deleteProfileModal').on('hidden.bs.modal', () => {
  $('#btn-profile-delete').focus(() => {
    // position at top of the page
    $('html,body').animate({ scrollTop: 0 }, 300)
  })
})
// *** end manage upload new file ***

/*  update the list of interest checked
    if action = R, remove the id from the list
    if action = A, add the if in the list

    arrayListInterest : list of id cheched in an array
    idToSearch : id to add or delete
    action : R=Remove, A=Add

    return the list updated
    Author : Frederic Parmentier
*/
function updateListInterest (listInterest, idToSearch, action) {
  var arrayListInterest = []
  if (listInterest.length > 0) {
    arrayListInterest = listInterest.split(';')
    var found = false
    var ind = -1
    while (!found && ind < arrayListInterest.length) {
      ind++
      if (arrayListInterest[ind] === idToSearch) {
        arrayListInterest.splice(ind, 1)
        found = true
      }
    }
  }
  if (action === 'A') { // add element
    arrayListInterest.push(idToSearch)
  }
  if (arrayListInterest.length === 1 && action === 'A') {
    return idToSearch
  } else {
    return arrayListInterest.join(';')
  }
}

/*
  Activate or deactivate the load spinner
*/
function handleLoader (activate) {
  if (activate) {
    document.querySelector('.load-spinner').setAttribute('id', 'loader')
    document.getElementById('spinner').style.display = 'block'
  } else {
    document.querySelector('.load-spinner').setAttribute('id', 'unload')
    document.getElementById('spinner').style.display = 'none'
  }
}
