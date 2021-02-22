$(document).ready(function () {
   $('.modal').modal();
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