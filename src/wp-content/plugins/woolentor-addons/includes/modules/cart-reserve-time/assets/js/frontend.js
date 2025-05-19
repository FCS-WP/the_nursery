(function($){
    "use strict";
    function updateTimer(){
        let isAnyTimerRunning = false;

        $('.woolentor-timer').each(function(){
            const expiryTime = $(this).data('expires');
            const currentTime = Math.floor(Date.now() / 1000);
            const remainingTime = expiryTime - currentTime;
            
            if(remainingTime > 0){
                isAnyTimerRunning = true;

                const hours = Math.floor(remainingTime / 3600);
                const minutes = Math.floor((remainingTime % 3600) / 60);
                const seconds = remainingTime % 60;

                let timerText = '';
                
                // Add hours if exists
                if(hours > 0){
                    timerText += (hours < 10 ? '0' : '') + hours + ':';
                }
                
                // Add minutes and seconds
                timerText += (minutes < 10 ? '0' : '') + minutes + ':';
                timerText += (seconds < 10 ? '0' : '') + seconds;

                $(this).text(timerText);
            } else {
                $(this).text('00:00');
                isAnyTimerRunning = false;
            }
        });

        // Stop the interval if no timers are running
        if (!isAnyTimerRunning) {
            clearInterval(timerInterval);
            location.reload();
        }
    }

    // Update timer every second
    let timerInterval;
    if($('.woolentor-timer').length > 0){
        updateTimer();
        timerInterval = setInterval(updateTimer, 1000);
    }

})(jQuery);