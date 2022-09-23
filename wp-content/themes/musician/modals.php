<div class="modal fade" id="pricingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button class="modal-close-btn"><i class="fa fa-times" style="color: white; font-size:30px"></i></button>
      <div class="modal-body mt-3">
        <div class="text-center" style="font-size: 36px">Musicians and Advertisers</div>
        <div class="d-flex justify-content-around mt-4 mb-3">
          <div>
            <span class="fw-bold">$10/mo. per account</span></br>
            <span class="fw-bold">unlimited Profiles</span>
          </div>
          <div>
            <span class="fw-bold">Advertisement for</span></br>
            <span class="fw-bold">10¢ per impression.</span>
          </div>
        </div>
        <div style="padding-left: 20px; padding-right: 20px">
          <p class="fw-light">
            Music Gigs is a service dedicated to giving musicians the exposure needed to keep their schedule full of
            premium paying gigs.
          </p>
          <p class="fw-light">
            We promote this website to event and venue managers, wedding planners, and nightclub owners in addition to
            those wanting to throw great private parties.
          </p>
          <p class="fw-light">
            Set up your Musician or Advertising account today so you'll be ready for some exciting times in the months
            ahead.
          </p>
          <p class="fw-light">
            The MusicGigs Team
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="aboutusModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button class="modal-close-btn"><i class="fa fa-times" style="color: white; font-size:30px"></i></button>
      <div class="modal-body mt-3">
        <div class="text-center" style="font-size: 36px">About Us</div>
        <div style="padding-left: 20px; padding-right: 20px">
          <p class="fw-light">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus id scelerisque mauris.
            Morbi ut mauris volutpat, egestas turpis nec, egestas felis.
            Sed eleifend nulla id nulla sagittis vehicula. Vestibulum accumsan nec turpis id lacinia. Ut gravida luctus
            ipsum et scelerisque.
            Donec in velit ut justo lobortis vehicula sit amet aliquet tortor.
            Donec pretium justo id libero mattis, non dignissim dui condimentum. In a mauris in metus pharetra congue.
            Praesent non nisl non risus suscipit dapibus. Pellentesque sagittis sagittis justo at ultrices. In eget arcu
            eu enim molestie blandit. Suspendisse aliquet est vel tincidunt commodo. Ut sit amet ligula scelerisque,
            pulvinar neque at, iaculis ipsum.
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="contactusModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button class="modal-close-btn"><i class="fa fa-times" style="color: white; font-size:30px"></i></button>
      <div class="modal-body mt-3">
        <div class="text-center" style="font-size: 36px">Contact Us</div>
        <div style="padding-left: 20px; padding-right: 20px">
          <form method="post" action="">
            <div class="mb-3">
              <label for="contact_email" class="form-label">Email address</label>
              <input type="email" class="form-control" id="contact_email" aria-describedby="emailHelp"
                placeholder="example@test.com">
              <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
              <label for="contact_comment" class="form-label">Comment</label>
              <textarea class="form-control" id="contact_comment" rows="4"
                placeholder="Place your comments here..."></textarea>
            </div>
            <div class="mt-4 mb-3">
              <button type="submit" class="btn btn-primary" style="width: 100%" value="contact">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="signinModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button class="modal-close-btn"><i class="fa fa-times" style="color: white; font-size:30px"></i></button>
      <div class="modal-body mt-3">
        <div class="text-center" style="font-size: 36px">Sign In</div>
        <div style="padding-left: 20px; padding-right: 20px">
          <div class="alert alert-warning alert-dismissible fade show" role="alert" id="signin_alert"
            style="display:none;">
          </div>
          <form method="post" action="">
            <div class="mb-3">
              <label for="useremail" class="form-label">Email address</label>
              <input type="email" class="form-control" id="useremail" name="useremail">
            </div>
            <div class="mb-3">
              <label for="userpass" class="form-label">Password</label>
              <input type="password" class="form-control" id="userpass" name="userpass">
            </div>
            <div class="ms-4 mb-3 form-check">
              <input type="checkbox" class="form-check-input" id="termspolicy">
              <label class="form-check-label" for="termspolicy">
                Agree to the <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.
              </label>
            </div>
            <div class="mt-4">
              <button type="button" class="btn btn-primary" style="width: 100%" name="submit" value="signin"
                id="signin_btn">Submit</button>
            </div>
            <p class="text-center mt-3">Doesn't have an account? <a href="javascript:void(0)"
                onclick="openSignupModal()">Sign Up</a></p>
            <div class="d-flex justify-content-center mt-4">
              <a href="#" class="ms-3 me-3"><i class="fa fa-facebook" aria-hidden="true"
                  style="font-size: 20px; color: white"></i></a>
              <a href="#" class="ms-3 me-3"><i class="fa fa-twitter" aria-hidden="true"
                  style="font-size: 20px; color: white"></i></a>
              <a href="#" class="ms-3 me-3"><i class="fa fa-google" aria-hidden="true"
                  style="font-size: 20px; color: white"></i></a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button class="modal-close-btn"><i class="fa fa-times" style="color: white; font-size:30px"></i></button>
      <div class="modal-body mt-3">
        <div class="text-center" style="font-size: 36px">Sign Up</div>
        <div style="padding-left: 20px; padding-right: 20px">
          <div class="alert alert-warning alert-dismissible fade show" role="alert" id="signup_alert"
            style="display:none;">
          </div>
          <div class="d-flex justify-content-end">
            <div class="form-check me-3">
              <input class="form-check-input" type="radio" name="user_role" id="role_musician" checked value="musician">
              <label class="form-check-label" for="role_musician">
                Musician
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="user_role" id="role_advertiser" value="advertiser">
              <label class="form-check-label" for="role_advertiser">
                Advertiser
              </label>
            </div>
          </div>
          <div class="mb-3">
            <label for="user_name" class="form-label">User Name</label>
            <input type="text" class="form-control" id="user_name" name="user_name">
          </div>
          <div class="mb-3">
            <label for="user_email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="user_email" name="user_email">
          </div>
          <div class="mb-3">
            <label for="user_pass" class="form-label">Password</label>
            <input type="password" class="form-control" id="user_pass" name="user_pass">
          </div>
          <div class="mb-3">
            <label for="confirm_pass" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_pass" name="confirm_pass">
          </div>
          <div class="ms-4 mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="termspolicy">
            <label class="form-check-label" for="termspolicy">
              Agree to the <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.
            </label>
          </div>
          <div class="mt-4">
            <button type="button" class="btn btn-primary" style="width: 100%" name="submit" value="signin"
              id="signup_btn">Submit</button>
          </div>
          <p class="text-center mt-3">Already have an account? <a href="javascript:void(0)"
              onclick="openSigninModal()">Sign In</a></p>
          <div class="d-flex justify-content-center mt-4">
            <a href="#" class="ms-3 me-3"><i class="fa fa-facebook" aria-hidden="true"
                style="font-size: 20px; color: white"></i></a>
            <a href="#" class="ms-3 me-3"><i class="fa fa-twitter" aria-hidden="true"
                style="font-size: 20px; color: white"></i></a>
            <a href="#" class="ms-3 me-3"><i class="fa fa-google" aria-hidden="true"
                style="font-size: 20px; color: white"></i></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>