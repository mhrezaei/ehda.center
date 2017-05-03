/**
 * Created by Yasna-PC1 on 03/05/2017.
 */

function Timer() {

    this.remainingTime = -1;
    var thisTimer = this;
    /**
     *
     * @param time in seconds
     */
    thisTimer.setTime = function (time) {
        thisTimer.remainingTime = time;
        return thisTimer;
    };

    thisTimer.getTime = function () {
        if (thisTimer.remainingTime > 0) {
            return thisTimer.remainingTime;
        } else {
            return false;
        }
    };

    thisTimer.decreaseTime = function () {
        if (thisTimer.remainingTime > 0) {
            thisTimer.remainingTime--;
            return true;
        } else {
            return false;
        }
    };

    thisTimer.stop = function () {
        clearInterval(thisTimer.runningInterval);
        delete thisTimer.runningInterval;
    };

    thisTimer.force = function () {
        thisTimer.setTime(-1);
        thisTimer.stop();
    };

    thisTimer.delay = function (callback, time) {
        if (isDefined(callback) &&
            (typeof callback == 'function') &&
            isDefined(time) &&
            ($.isNumeric(time))) {

            time = Math.floor(time);

            thisTimer.setTime(time);

            // TODO: shoud check if this timer is running or not?
            thisTimer.stop();

            thisTimer.runningInterval = setInterval(function () {
                if (thisTimer.decreaseTime()) {
                    console.warn((thisTimer.getTime() + 1) + ' seconds remaining...');
                } else {
                    callback();
                    thisTimer.stop();
                }
            }, 1000);

        }
    };

    return thisTimer;
}