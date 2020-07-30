$(document).scroll(function() {
    var isScrolled = $(this).scrollTop() > $(".topBar").height();
    $(".topBar").toggleClass("scrolled", isScrolled);
})

function volumeToggle(button) {

    // CAmbia de mute a sonido cuando se hace click
    var muted = $(".previewVideo").prop("muted");
    $(".previewVideo").prop("muted", !muted);

    // CAmbia el icono cuando se presiona el muted o inmuted
    $(button).find("i").toggleClass("fa-volume-mute");
    $(button).find("i").toggleClass("fa-volume-up");

} // fin de la funcion volumeToggle

function previewEnded() {

    // alterna al onended del video
    $(".previewVideo").toggle();
    $(".previewImage").toggle();

} // fin de la funcion previewEnded

function goBack() {
    window.history.back(); // regresa a la pagina donde estaba antes
} // end the function goBack()

function startHideTimer() {
    var timeout = null;

    $(document).on("mousemove", function() {
        clearTimeout(timeout);
        $(".watchNav").fadeIn();

        timeout = setTimeout(function() {
            $(".watchNav").fadeOut();
        }, 2000)
    })
} // end the function startHideTimer()

function initVideo(videoId, username) {
    startHideTimer();
    setStartTime(videoId, username);
    updateProgressTimer(videoId, username);

} // end the function initVideo

function updateProgressTimer(videoId, username) {
    addDuration(videoId, username);

    var timer;

    $("video").on("playing", function(event) {
            window.clearInterval(timer);
            timer = window.setInterval(function() {
                updateProgress(videoId, username, event.target.currentTime);
            }, 3000)
        })
        .on("ended", function() {
            setFinished(videoId, username);
            window.clearInterval(timer);
        })
} // end the function updateProgressTimer()

function addDuration(videoId, username) {
    $.post("ajax/addDuration.php", { videoId: videoId, username: username }, function(data) {
        if (data !== null && data !== "") {
            alert(data);
        }
    })
} // end the funcion addDuration()

function updateProgress(videoId, username, progress) {
    $.post("ajax/updateDuration.php", { videoId: videoId, username: username, progress: progress }, function(data) {
        if (data !== null && data !== "") {
            alert(data);
        }
    })
} // end the function updateProgress()

function setFinished(videoId, username) {
    $.post("ajax/setFinished.php", { videoId: videoId, username: username }, function(data) {
        if (data !== null && data !== "") {
            alert(data);
        }
    })
} // end the function setFinished()

function setStartTime(videoId, username) {
    $.post("ajax/getProgress.php", { videoId: videoId, username: username }, function(data) {
        if (isNaN(data)) {
            alert(data);
            return;
        }

        $("video").on("canplay", function() {
            this.currentTime = data;
            $("video").off("canplay");
        })
    })

} // end the function setStartTime()

function restartVideo() {
    $("video")[0].currentTime = 0;
    $("video")[0].play();
    $(".upNext").fadeOut();
} // end the restartVideo()

function watchVideo(videoId) {
    window.location.href = "watch.php?id=" + videoId;
} // end the function watchVideo()

function showUpNext() {
    $(".upNext").fadeIn();
} // end the function showUpNext()