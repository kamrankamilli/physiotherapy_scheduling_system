$(function (){
    $(".prev").mouseenter(function(){
    $(".prev, .next").show();
    });
    $(".prev").mouseleave (function(){
    $(".prev, .next").hide();
    });
});