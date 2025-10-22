class InactivityTracker {
    constructor(timeoutSeconds = 5) {
        this.timeoutSeconds = timeoutSeconds;
        this.timer = null;
        this.init();
    }

    init() {
        // Only track tab visibility changes
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                // Start timer when tab becomes hidden
                this.startTimer();
            } else {
                // Clear timer when tab becomes visible again
                if (this.timer) {
                    clearTimeout(this.timer);
                    this.timer = null;
                }
            }
        });
    }

    startTimer() {
        if (this.timer) {
            clearTimeout(this.timer);
        }

        this.timer = setTimeout(() => {
            this.redirectToLockScreen();
        }, this.timeoutSeconds * 1000);
    }

    redirectToLockScreen() {
        window.location.href = '/lock-screen';
    }
}

// Initialize the tracker when the page loads
document.addEventListener('DOMContentLoaded', () => {
    new InactivityTracker(900); // 15 minutes timeout
});
