<?php
  get_header();

  if ( !is_user_logged_in() ) {
    wp_redirect( get_home_url() );
  }

  global $wpdb;

  $profile_id = null;
  $user_profile = null;
  if (isset($_GET['profile_id'])) {
    $profile_id = $_GET['profile_id'];
    $user_profile = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}profile WHERE ID=$profile_id", OBJECT);
  }
?>
<div class="profile" style="min-height: 130%;">
  <div class="container">
    <form action="/wp-admin/admin-post.php" enctype="multipart/form-data" method="post" id="profile_form">
      <input type="hidden" name="action" value="submit_profile_form">
      <input type="hidden" name="profile_id" value="<?php echo $profile_id ?>">
      <h3 class="text-white">My Profile</h3>
      <div class="row mt-3">
        <div class="col-md-4">
          <div class="picture-container">
            <div class="picture">
              <img
                src="<?php echo $user_profile->avatar_link ? esc_url($user_profile->avatar_link) : esc_url( get_template_directory_uri() . '/assets/images/default-avatar.PNG' ); ?>"
                class="picture-src" id="wizardPicturePreview" title="">
              <input type="file" id="wizard-picture" class="" name="profile_avatar">
            </div>
            <h6 class="text-white mt-3">Choose Picture</h6>
          </div>
        </div>
        <div class="col-md-8">
          <div class="profile-container">
            <div class="mb-3">
              <label for="profile_name" class="form-label text-white">Name <span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="profile_name" name="profile_name"
                value="<?php echo $user_profile->profile_name ?>" required>
            </div>
            <div class="mb-3 row">
              <div class="col-6">
                <label for="band_name" class="form-label text-white">Band Name <span
                    class="text-danger">*</span></label>
                <input type="text" class="form-control" id="band_name" name="band_name"
                  value="<?php echo $user_profile->band_name ?>">
              </div>
              <div class="col-6">
                <label for="band_size" class="form-label text-white">Band Size <span
                    class="text-danger">*</span></label>
                <select class="form-select" id="band_size" name="band_size">
                  <option hidden>Select...</option>
                  <option value="Band" <?php if ($user_profile->band_size == 'Band') {?> selected <?php } ?>>Band
                  </option>
                  <option value="Trio Act" <?php if ($user_profile->band_size == 'Trio Act') {?> selected <?php } ?>>
                    Trio Act
                  </option>
                  <option value="Duo Act" <?php if ($user_profile->band_size == 'Duo Act') {?> selected <?php } ?>>Duo
                    Act
                  </option>
                  <option value="Solo Act" <?php if ($user_profile->band_size == 'Solo Act') {?> selected <?php } ?>>
                    Solo Act
                  </option>
                </select>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-4">
                <label for="youtube_code" class="form-label text-white">Youtube Code <span
                    class="text-danger">*</span></label>
                <input type="text" class="form-control" id="youtube_code" name="youtube_code"
                  value="<?php echo $user_profile->youtube_code ?>">
              </div>
              <div class="col-4">
                <label for="budget" class="form-label text-white">Budget <span class="text-danger">*</span></label>
                <div class="d-flex">
                  <input type="number" class="form-control" id="budget" name="budget"
                    value="<?php echo $user_profile->budget ?>">
                  <span style="color:#f6931f; font-weight:bold; margin-left:5px; margin-top:5px">$</span>
                </div>
              </div>
            </div>
            <div class="mb-4 row">
              <div class="col-6">
                <label for="genre" class="form-label text-white">Genre <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="genre" name="genre" placeholder="Start typing..."
                  autocomplete="off" value="<?php echo $user_profile->genre ?>">
              </div>
              <div class="col-6">
                <label for="event_type" class="form-label text-white">Event Type <span
                    class="text-danger">*</span></label>
                <select class="form-select" id="event_type" name="event_type">
                  <option hidden>Select...</option>
                  <option value="Bar | Nightclub" <?php if ($user_profile->event_type == 'Bar | Nightclub') {?> selected
                    <?php } ?>>Bar | Nightclub</option>
                  <option value="Corporate Event" <?php if ($user_profile->event_type == 'Corporate Event') {?> selected
                    <?php } ?>>Corporate Event</option>
                  <option value="Private Party" <?php if ($user_profile->event_type == 'Private Party') {?> selected
                    <?php } ?>>Private Party</option>
                  <option value="Wedding" <?php if ($user_profile->event_type == 'Wedding') {?> selected <?php } ?>>
                    Wedding</option>
                </select>
              </div>
            </div>
            <div class="mb-4">
              <label for="gig_cost" class="form-label text-white">Gig Cost:</label>
              <label id="amount" style="color:#f6931f; font-weight:bold"></label>
              <div id="slider-range"></div>
            </div>
            <div>
              <label class="text-white">Uploaded PDF <i class="fa fa-file-pdf-o" aria-hidden="true"
                  style="color:red"></i></label><br />
              <a href="<?php echo $user_profile->pdf_link ?>" target="_blank"><?php echo $user_profile->pdf_link ?></a>
              <div>
                <input type="file" id="actual-btn" accept=".pdf" hidden name="profile_pdf" />
                <label for="actual-btn" class="file-upload-label">Choose File</label>
                <span id="file-chosen">No file chosen</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <button class="btn btn-primary mt-4 form-save-btn" type="button" id="profile_submit">Save</button>
      <button class="btn btn-outline-secondary mt-4 form-save-btn text-white me-3" type="button"
        id="profile_cancel">Cancel</button>
    </form>
  </div>
</div>

<script>
$(document).ready(function() {
  // Prepare the preview for profile picture
  $("#wizard-picture").change(function() {
    readURL(this);
  });

  // pdf upload button functionality
  const actualBtn = document.getElementById("actual-btn");

  const fileChosen = document.getElementById("file-chosen");

  actualBtn.addEventListener("change", function() {
    fileChosen.textContent = this.files[0].name;
  });

  // slider for gig cost
  let gig_min = "<?php echo $user_profile->gig_cost_min ?>";
  let gig_max = "<?php echo $user_profile->gig_cost_max ?>";
  let gig_cost_min = gig_min !== "" ? parseInt(<?php echo $user_profile->gig_cost_min ?>) : 10000
  let gig_cost_max = gig_max !== "" ? parseInt(<?php echo $user_profile->gig_cost_max ?>) : 20000

  $("#slider-range").slider({
    range: true,
    min: 0,
    max: 50000,
    values: [gig_cost_min, gig_cost_max],
    slide: function(event, ui) {
      $("#amount").html("$" + ui.values[0] + " - $" + ui.values[1]);
      gig_cost_min = ui.values[0];
      gig_cost_max = ui.values[1];
    },
  });
  $("#amount").html(
    "$" +
    $("#slider-range").slider("values", 0) +
    " - $" +
    $("#slider-range").slider("values", 1)
  );

  // profile form submit
  $("#profile_submit").on("click", function() {
    $("<input type='hidden'/>")
      .attr("name", "gig_cost_min")
      .attr("value", gig_cost_min)
      .appendTo("#profile_form");
    $("<input type='hidden'/>")
      .attr("name", "gig_cost_max")
      .attr("value", gig_cost_max)
      .appendTo("#profile_form");
    $("#profile_form").submit()
  });

  $("#profile_cancel").on("click", function() {
    window.location.href = "<?php echo get_permalink( get_page_by_path( 'profiles' ) ); ?>";
  })
});

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $("#wizardPicturePreview").attr("src", e.target.result).fadeIn("slow");
    };
    reader.readAsDataURL(input.files[0]);
  }
}

const field = document.getElementById("genre");
const ac = new Autocomplete(field, {
  data: [{
    label: "I'm a label",
    value: 42,
  }, ],
  maximumItems: 5,
  threshold: 1,
  onSelectItem: ({
    label,
    value
  }) => {
    console.log("user selected:", label, value);
  },
});

ac.setData([{
    label: "Alternative",
    value: "Alternative",
  },
  {
    label: "Americana",
    value: "Americana",
  },
  {
    label: "Big Bands",
    value: "Big Bands",
  },
  {
    label: "Bluegrass",
    value: "Bluegrass",
  },
  {
    label: "Blues",
    value: "Blues",
  },
  {
    label: "Children's Music",
    value: "Children's Music",
  },
  {
    label: "Christian",
    value: "Christian",
  },
  {
    label: "Classical",
    value: "Classical",
  },
  {
    label: "Country-Western",
    value: "Country-Western",
  },
  {
    label: "Dance Bands",
    value: "Dance Bands",
  },
  {
    label: "Electronic",
    value: "Electronic",
  },
  {
    label: "Hip Hop",
    value: "Hip Hop",
  },
  {
    label: "Jazz",
    value: "Jazz",
  },
  {
    label: "K-Pop",
    value: "K-Pop",
  },
  {
    label: "Latin",
    value: "Latin",
  },
  {
    label: "Metal",
    value: "Metal",
  },
  {
    label: "Pop",
    value: "Pop",
  },
  {
    label: "R & B",
    value: "R & B",
  },
  {
    label: "Rock",
    value: "Rock",
  },
  {
    label: "Top 40",
    value: "Top 40",
  },
  {
    label: "Variety/Oldies",
    value: "Variety/Oldies",
  },
  {
    label: "World Music",
    value: "World Music",
  },
]);
</script>