define(['jquery'], function($){
    function rateReview(url, reviewId, choice, event) {
    $.ajax({
        method: "POST",
        url: url,
        dataType: 'html',
        data: {
            'reviewId': reviewId,
            'choice': choice
        }
    }).done(function(){
        event.preventDefault();
    }).complete(function(){
        if (choice == 'helpful') {
            $('#review-rate-' + reviewId).text('You have found this helpful.');
            var helpful = parseInt($('.lof-helpful #helpful-' + reviewId).attr('data')) + 1;
            var total = parseInt($('.lof-helpful #total-' + reviewId).attr('data')) + 1;
            $('.lof-helpful #helpful-' + reviewId).text(helpful);
            $('.lof-helpful #total-' + reviewId).text(total);
        } else if (choice == 'unhelpful') {
            $('#review-rate-' + reviewId).text('You have found this unhelpful.');
            var total = parseInt($('.lof-helpful #total-' + reviewId).attr('data')) + 1;
            $('.lof-helpful #total-' + reviewId).text(total);
        }
    });
};

function reportReview(url, reviewId, type, event)
{
    $.ajax({
        method: "POST",
        url: url,
        dataType: 'html',
        data: {
            'reviewId': reviewId,
            'type': type
        }
    }).done(function(){
        event.preventDefault();
    }).complete(function(){
        $('#review-report-' + reviewId).text('You reported this review.');
    });
};

$(document).ready(function(){

    $(function () {
        $('[id^="action-helpful-"]').click(
            function(event){
                var url = $(this).attr('data');
                rateReview(url, this.value, 'helpful', event);
            });
    });

    $(function () {
        $('[id^="action-unhelpful-"]').click(
            function(event){
                var url = $(this).attr('data');
                rateReview(url, this.value, 'unhelpful', event);
            });
    });

    $(function () {
        $('[id^="action-report-"]').click(
            function(event){
                var id = this.id.replace('action-report-', '');
                var url = $(this).attr('data');
                reportReview(url, id, 'report', event);
            });
    });

    $(function () {
        $('[id^="review-link-"]').click(
            function(event){
                event.preventDefault();
                var id = this.id.replace('review-link-', '');
                $('.review-link-' + id).hide();
                $('.review-rest-' + id).show();
            });
    });
});
});