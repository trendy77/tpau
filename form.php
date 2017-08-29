<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

<?php include('header.php'); ?>

</head>
<body>
<header>
 
<?php include ('navi.php'); ?>
</header>
<main>
  <div class="parallax-container valign-wrapper">
     <div class="parallax"><img src="timg/1.jpg" alt="background img 1">
     </div>
     
   <div class="container card-title grey">
       	<h1 class="header center black-text"> Further Enquiries </h1>
  </div>
  
    </div>
      <div class="section">

          <?php include('form3.php'); ?>	

      </div> 
         <div class="section">
             <div class="parallax-container valign-wrapper">
     <div class="parallax"><img src="timg/sm22.jpeg" alt="background img 1"></div>
</div>

    <div class="container black no-padding">
        <div class="row">
           	<div class="col s12 m4">
    
         <?php include ('form2.php'); ?>	
   
           </div>
        </div> 
     </div>   


    <div class="row">
        <div class="col l4 s4">

         <?php include ('form.php'); ?>	
 
        </div>
       </div>
  <div class="parallax-container valign-wrapper">
      <div class="parallax"><img src="timg/sm5.jpeg" alt="background img 1"></div>
       </div> 
     
     

         <div class="container black">
 
             <?php include ('form2.php'); ?>	

        </div> 
  
 
</main>
<footer>

  <?php include('footer.php'); ?>
 
</footer>
	</div>
		</div> 
   <?php include_once('js.php'); ?>
</body>
</html>





  <div class="card-action">
		<div class="container">		
  <div class="row">
    <form class="col s2">
<div class="gravity-select-parent" style="width: 179px;">
<select name="input_1.2" id="input_18_1_2" aria-label="Name prefix" tabindex="2" style="width: 100% !important;">
         <option value=""></option><option value="Mr.">Mr.</option><option value="Mrs.">Mrs.</option><option value="Miss">Miss</option><option value="Ms.">Ms.</option><option value="Dr.">Dr.</option><option value="Prof.">Prof.</option><option value="Rev.">Rev.</option>
                      </select>
					  <div class="select-arrow" style="height: 38.7667px; width: 38.7667px; line-height: 38.7667px;">
					  </div>
				</div>
		<form class="col s10">
       <div class="input-field col s6">
          <input placeholder="Placeholder" id="first_name" type="text" class="validate">
          <label for="first_name">First Name</label>
        </div>
        <div class="input-field col s6">
          <input id="last_name" type="text" class="validate">
          <label for="last_name">Last Name</label>
        </div>
      </div>
      <div class="row">
        <div class="input-field col s12">
          <input id="contact_number" id="phone" type="text" class="validate">
          <label for="contact_number">Preferred Contact #</label>
        </div>
      </div>
         <div class="row">
        <div class="input-field col s12">
          <input id="email" type="email" class="validate">
          <label for="email">Email</label>
        </div>
      </div>
	    <div class="row">
        <div class="col s12">
          This is an inline input field:
          <div class="input-field inline">
            <input id="email" type="email" class="validate">
            <label for="email" data-error="wrong" data-success="right">Email</label>
          </div>
        </div>
      </div>
    </form>
  </div>