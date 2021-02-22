$(document).ready(function () {
   $("main").load("list.html", function () {
      $.getScript("list.js", function () {});      
   });
});