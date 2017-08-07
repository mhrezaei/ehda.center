/**
 * Created by Yasna-PC1 on 26/07/2017.
 */

window.optionsStatuses = {
    // If sounds should be played or not
    playSound: false,
};

window.timeouts = {
    // Allowed time to view "More Info" page before VTach
    moreInfo: 3 * 1000,
    // Allowed time to view "Exams" page before VTach
    exams: 3 * 1000,
    // Allowed time to stay in "VTach" status before case die
    VTack: 50 * 1000,
    // Allowed time to stay in "Treatments" page before inject FIO2
    needToFIO2: 10 * 1000,
};

// Styles using in js calculations
window.styleConstants = {
    // Default line height of texts
    line_height: 1.42857143,
};