$.fn.extend({
  init_drawseats: function(matrix, selected) { // Draw Seats for Transportation
    let container = $("<div>").addClass("draw-seats");
    $(this).find("input.selected").keydown(function () {
      return false;
    })
    $(this).append(container);
    container.drawseats(matrix, selected);
  }
});

$.fn.extend({
  drawseats: function(matrix, selected, callback) { // Draw Seats for Transportation
    if($(this).hasClass("draw-seats")){
      $(this).empty();
      if(matrix.length){
        let car = $("<div>").addClass("car");
        let counter = 1;
        selected = selected.map((x)=>parseInt(x));
        $.each(matrix, function (index, row) {
          let rowdiv = $("<div>").addClass("row");
          let cls = 12 / row.length;
          for(let i in row){
            let col = $("<div>").addClass(`col s${cls}`);
            if(row[i]){
              let seat = $("<div>").addClass(`seat seat-${counter}`).text(`${counter}`).click(function () {$(this).select_seat(selected, callback);});
              if(selected.indexOf(counter) != -1)seat[0].status = "booked";
              else seat[0].status = "available";
              seat[0].seat_number = counter;
              seat.addClass(function(){return this.status;});
              col.append(seat);
              counter++;
            }
            rowdiv.append(col);
          }
          car.append(rowdiv);
        })
        $(this).append(car);
      }else{
        if(!selected){
          $(this).siblings(".seats-title").html($(this).parent().data("name"));
        }else{
          $(this).siblings(".seats-title").html("You selected seat");
          $(this).append($("<div>").addClass("seat-number").html(selected));
        }
        $(this).append($("<img>").attr({src:`${site.backend}images/no-seat.png`, width:"100%"}).addClass("empty-seat"));
      }
    }else{
      alert("You can't drawseats on this selected element");
    }
  }
});
$.fn.extend({
  select_seat: function(selected, callback) {
    let allow = ["available", "selected"];
    let status = $(this).prop("status");
    let number = $(this).prop("seat_number");
    let key = allow.indexOf(status);
    let container = $(this).closest(".seats-container");
    if( key != -1){
      key = 1 - key;
      $(this)[0].status = allow[key];
      $(this).closest(".car").find(".seat.selected").each(function() {
        $(this)[0].status = "available";
        $(this).removeClass("selected").addClass("available");
      })
      $(this).removeClass("available selected").addClass(allow[key]);
      if(key == 1){
        container.find("input.selected").focus().val(number)
        if(typeof(callback)==="function")callback(number);
      }else {
        container.find("input.selected").val("")
      }
    }
  }
});
