$(document).ready(function () {
   $('.modal').modal();

   $.getJSON("car.json", function (response) {
         console.log(response);
         var template = $("car-template").html();
         var html = Mustache.render(template, response);
         $("tbody").html(html);
      }
   );

   $(".edit-data").click(function (e) { 
      e.preventDefault();
      $("#modal-title").html("Edit: " + $(this).parent().attr("data-dataset"))
      console.log("Edit");
   });
   $(".delete-data").click(function (e) { 
      e.preventDefault();
      console.log("Delete");
   });
   $(".refuel-data").click(function (e) { 
      e.preventDefault();
      console.log("Refuel");
   });
});