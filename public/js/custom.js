var seconds = $('#time-limit').val();
function secondPassed() {
    var minutes = Math.round((seconds - 30)/60);
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = '0' + remainingSeconds;
    }
    document.getElementById('countdown').innerHTML = minutes + ':' + remainingSeconds;
    if (seconds == 0) {
        clearInterval(countdownTimer);
        $('#finish-exam').click();
        alert('Exam time has expired, your examination will be submitted automatically');
        var url = document.URL;
        var id = url.substring(url.lastIndexOf('/') + 1);
        window.location.href = '/examination/' + id + '/result';
    } else {
        seconds--;
    }
}

var countdownTimer = setInterval('secondPassed()', 1000);
