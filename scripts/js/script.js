/* =============================================================================
                          unix_timestamp_to_date()
============================================================================= */


https://stackoverflow.com/questions/847185/convert-a-unix-timestamp-to-time-in-javascript
function unix_timestamp_to_date(unix_timestamp){
  const x      = new Date(unix_timestamp * 1000);
  const days   = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  const months = ['Janurary','February','March','April','May','June','July','August','September','October','November','December'];
  const day    = days[x.getDay()];
  const month  = months[x.getMonth()];
  const year   = x.getFullYear();
  const date   = x.getDate();
  // const hour   = x.getHours();
  // const min    = x.getMinutes();
  // const sec    = x.getSeconds();

  const time = `${day}, ${month}  ${date},  ${year}`;
  return time;
}


/* =============================================================================
                                load_data()
============================================================================= */


function load_data(number_of_items_to_load) {
  //console.log("load_data() executed.");

  //////////////////////////////////////////////////////////////////////////////
  //
  //  Give the $.ajax() sufficient time to run before making the next call.
  //  This is done by initially setting prevent_ajax_call to false.
  //  Then just before the $.ajax() method, we set prevent_ajax_call = true;
  //  Finally, just before the end of the success method we can reset prevent_ajax_call = false;
  //
  //////////////////////////////////////////////////////////////////////////////


  if (! prevent_ajax_call) {
    const POST_DATA   = { number_of_items_to_load, offset };
    prevent_ajax_call = true;


    $.ajax({
      url:      "scripts/php/process.php",
      type:     "POST",
      data:      POST_DATA,
      dataType: "json",

      success: function(data) {
        for (var i = 0; i < data.rows.length; i++) {
          //Increment offset here, so that the next time we make a request it picks up where we left off.
          offset++;

          const row  = data.rows[i];
          const html = `<article><h2>Blog #${row.id}:</h2><h6>${ unix_timestamp_to_date(parseInt(row.date)) }</h6>${row.content}</article>`;
          $('#main').append(html);
        }

        prevent_ajax_call = false;

        //Stop $.ajax() from executing when data.rows has a length of 0.
        //load_data() will still execute on scroll, but nothing will happen.
        if(data.rows.length == 0){
          prevent_ajax_call = true;
          console.log("data.rows.length == 0. There are no more items to load from the database.");
        }
      } //End of success:
    }); //End of: $.ajax({ ... })
  }//End of: if (! prevent_ajax_call) { ... }
}//End of: function load_data(number_of_items_to_load){ ... }


/* =============================================================================
                              variables
============================================================================= */


let offset                  = 0;  //Set default offset to 0;
let number_of_items_to_load = 1;  //Set default number of items to load to 1;
let prevent_ajax_call       = false;


/* =============================================================================

============================================================================= */


//Initially load 5 items.
$(function() { load_data(5);  });


//When the user scrolls, load additional data/rows/blogs
$(window).scroll(
  function(){
    //If the user is near the bottom of the page (within 100px), then load more data.
    if ( $(window).scrollTop() >= $(document).height() - $(window).height() - 100 ) {
      load_data(1); //When scrolling, just load one item at a time.
    }
  }
);
