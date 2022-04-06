/* ---------------------------------------------
 common scripts
 --------------------------------------------- */
;(function ($) {
    'use strict'; // use strict to start

    /* === Stickit === */

    (function () {
        $("[data-sticky_column]").stickit({
            scope: StickScope.Parent,
            top: 0
        });
    }());


    /* === Back To Top === */

    (function () {
        $(' a.back-to-top').click(function () {
            $('html, body').animate({scrollTop: 0}, 800);
            return false;
        });
    }());


    /* === Search === */

    (function () {
        $('.top-search a').on('click', function (e) {
            e.preventDefault();
            $('.show-search').slideToggle('fast');
            $('.top-search a').toggleClass('sactive');
        });
    }());

})(jQuery);


function addUserName(event) {
    const id = event.target.id.split('-')[1];
    const item = document.getElementById(`comment-login-${id}`);
    const userName = item.innerText;

    const newComment = document.getElementById('new-comment');
    if (!newComment.innerText.includes(userName)) {
        newComment.innerText = `${userName}, ${newComment.innerText}`
    }

    focus(newComment);
}