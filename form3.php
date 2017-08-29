<?php   // form2
 ?>
 
 <div class="container">
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
<h2>Patient Enquiries, Quotes + Bookings</h2>

 <div class="row">
        <div class="col s12 m6">
          <div class="card blue-grey darken-1">
            <div class="card-content white-text">
              <span class="card-title">Patient Enquiries, Quotations & Reservations</span>
              
<p>Please fill out the contact form below in order to receive a personalised quote within 24 hours.  Following review of your enquiry, the Local Client Consultant working specifically on you chosen procedure will be in contact to discuss the intriacies of the procedure and the medical staff overseeing it.
Our ladies have many years of experience assisting people from all walks of life, from right across the World, finally realise their dreams of comsmetic procedures without breaking the bank.  Extremely knowledgable in their specialist surgeries, they will be able to clarify any questions you may have.</p>
          
            </div>
            <div class="card-action">
              <a href="callme.php">Hate Forms? Click here to arrange a phone consultation!</a>
              
            </div>
          </div>
        </div>
      </div>

<h2>Contact Information</h2>

    <label for="fname">First Name</label>
    <input type="text" id="fname" name="firstname" placeholder="Your name..">

    <label for="lname">Last Name</label>
    <input type="text" id="lname" name="lastname" placeholder="Your last name..">

    <label for="email">Email Address</label>
    <input type="email" id="email" name="email" placeholder="Email Address..">

    <label for="country">Country</label>
    <select id="country" name="country">
      <option value="australia">Australia</option>
      <option value="nz">New Zealand</option>
       <option value="canada">Canada</option>
      <option value="usa">USA</option>
      <option value="uk">UK</option>
      <option value="other">Other</option>
    </select>

<h2>Medical Procedural Info</h2>

<input type="radio" name="procedure" placeholder="Type of procedure Required..">
<input type="radio" name="procedure" value="cosmetic" checked>Cosmetic<br>
<input type="radio" name="procedure" value="dental">Dental<br>
<input type="radio" name="procedure" value="other"> Other

<label for="smoke">Do You Smoke?</label>
<input type="radio" name="no" value="No">
<input type="radio" name="some" value="Occasionally (not daily)">
<input type="radio" name="yes" value="Yes (daily habit)">

<label for="otherMeds">Please detail any medical conditions and/or medications you are currently taking</label>
<textarea id="otherMeds" name="otherMeds" placeholder="Other medical conditions and prescriptions..." style="height:200px"></textarea>


<label for="destination">Destination of Choice</label>
    <select id="destination" name="destination">
      <option value="thailand1">Thailand1</option>
      <option value="thailand2">thailand2</option>
      <option value="other">Other</option>
    </select>


<h2>Your Holiday Plans</h2>

<<input type="date" name="traveldates" placeholder="Preferred Date of Travel..." min="2017-08-30">

<label for="location">Location of Choice</label>
    <select id="location" name="location">
      <option value="thailand1">Thailand1</option>
      <option value="thailand2">thailand2</option>
      <option value="other">Other</option>
    </select>

<h4>Additional Travellers</h4>

    <label for="family">Please list the number of accompanying travellers (if any) - including whether children or adults (for flight pricing and ticketing )</label>
    <textarea id="family" name="others" placeholder="Additional Traveller Information" style="height:200px"></textarea>




    <label for="subject">Specific Enquiry / Questions (if any...)</label>
    <textarea id="subject" name="subject" placeholder="Specific Questions..." style="height:200px"></textarea>




    <input type="submit" value="Submit">

  </form>
</div> 