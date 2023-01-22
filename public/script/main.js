setTimeout(function () {
    $('.alert-success, .alert-info').alert('close');
}, 2500);

function disableButton() {
    var btn = document.getElementById('upload-media-btn');
    btn.disabled = true;
    btn.innerText = 'Качва се...'
}