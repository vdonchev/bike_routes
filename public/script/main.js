setTimeout(function () {
    $('.alert-success.alert-notification, .alert-info.alert-notification').alert('close');
}, 2500);

function disableButton() {
    var btn = document.getElementById('upload-media-btn');
    btn.disabled = true;
    btn.innerText = 'Качва се...'
}