// Get modal post modal id
var modal = document.getElementById('post_modal');

// Get open modal button
var post_btn = document.getElementById('create_post');

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];


// When the user clicks the button, open the modal 
post_btn.onclick = function() {
  modal.style.display = "block";
  document.body.style.backgroundColor = '#68686831';
  

}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
  document.body.style.backgroundColor = 'white';
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
    document.body.style.backgroundColor = 'white';
  }
}

var modal2 = document.getElementById('feedback_modal');

// Get open modal button
var feedback_btn = document.getElementById('create_feedback');

// Get the <span> element that closes the modal
var span2 = document.getElementsByClassName("close")[1];


// When the user clicks the button, open the modal
feedback_btn.onclick = function() {

  modal2.style.display = "block";
  document.body.style.backgroundColor = '#68686831';
  

}

// When the user clicks on <span> (x), close the modal
span2.onclick = function() {
  modal2.style.display = "none";
  document.body.style.backgroundColor = 'white';
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal2) {
    modal2.style.display = "none";
    document.body.style.backgroundColor = 'white';
  }
}
function submit_like_form(event) {
  // alert('Form submitted!');

  event.preventDefault();
  // console.log(event.target.id);
  var form = event.target.value;
  // console.log(form);
  action = "update_vote_like";
                
  jQuery.ajax({
      url: wp_feedback_vote.ajax_url,
      type:'POST',
      data:'&action='+action+'&vote_id='+form,
      success:function(data)
      {
        document.getElementById('text'+event.target.id).textContent=data;
          console.log(data);
      }
  });

  
}

function submit_dislike_form(event){
  event.preventDefault();
  var form = event.target.value;

  action = "update_vote_dislike";
                
  jQuery.ajax({
      url: wp_feedback_vote.ajax_url,
      type:'POST',
      data:'&action='+action+'&vote_id='+form,
      success:function(data)
      {
        document.getElementById('text'+event.target.id).textContent=data;
          console.log(data);
      }
  });
}