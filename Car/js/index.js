$(document).ready(function () {
   $(".table-section").load("table.html", function () {
      $.getScript("./js/table.js", function () {});
   });
});