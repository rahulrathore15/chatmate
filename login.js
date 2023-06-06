const form = document.querySelector(".login form"),
continueBtn = form.querySelector(".button input"),
errorText = form.querySelector(".error-text");

form.onsubmit = (e)=>{ 
    e.preventDefault(); //preventing form from submitting
}

continueBtn.onclick = ()=>{
   //creating XML object
    let xhr = new XMLHttpRequest();

    //we will use post method because we need to send data 
    xhr.open("POST", "php/login.php", true);
    xhr.onload = ()=>{
      if(xhr.readyState === XMLHttpRequest.DONE){
          if(xhr.status === 200){
            //xhr.response gives us response of that passed URL
              let data = xhr.response;
              if(data === "success"){
                location.href = "users.php";
              }else{
                errorText.style.display = "block";
                errorText.textContent = data;
              }
          }
      }
    }
     //we have to send the form data through ajax to php
    //creating new formData object
    let formData = new FormData(form);
    
     //sending the form data to php
    xhr.send(formData);
}