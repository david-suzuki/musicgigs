<?php
  get_header();

  global $wpdb;
  if (isset($_GET['type']) && isset($_GET['text'])) {
    if ($_GET['text'] === "") {
      $profiles = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}profile", ARRAY_A );
    } else {
      $type = $_GET['type'];
      $text = $_GET['text'];
      $profiles = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}profile WHERE {$type} LIKE '%$text%'", ARRAY_A );
    }
  }
?>
<div class="d-flex justify-content-center logo">
  <a class="navbar-brand" href="<?php echo home_url(); ?>">
    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/roundlogo.webp' ); ?>" alt="">
  </a>
  <a class="navbar-brand" href="<?php echo home_url(); ?>" style="margin-top: 30px">
    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/textlogo2.webp' ); ?>" alt="">
  </a>
</div>
<!-- <div class="d-flex justify-content-center nav-header">
    <div>
      <span class="nav-header-menu">
        Search by Date
      </span>
    </div>
  </div> -->
<!-- <a href="http://localhost/index.php/my-new-page/">my new page</a> -->
<div style="background: #2a2a2a; min-height: 76%; border-left: 5px solid white; border-right: 5px solid white;">
  <div class="row artist-list">
    <?php
      foreach ( $profiles as $profile ) {
    ?>
    <div class="col-md-3 col-sm-6 col-xs-12 artist">
      <img class="artist-avtar" src="<?php echo $profile['avatar_link']; ?>" alt="">
      <video autoplay class="artist-avtar" style="display: none;" muted="muted">
        <source src="<?php echo esc_url( get_template_directory_uri() . '/assets/videos/test.mp4' ); ?>"
          type="video/mp4">
      </video>
      <div class="genre">
        <span style="font-size: 14px">
          <?php echo $profile['genre'] ?>
        </span>
      </div>
      <div class="artist-short-info">
        <div class="d-flex justify-content-between">
          <span style="color: white; font-size: 18px"><?php echo $profile['band_name'] ?></span>
          <span style="color: white; font-size: 14px; display:block;">Over $10,000</span>
          <div style="display:none;" class="video-btns">
            <button class="circle-btn">
              <i class="fa fa-play" aria-hidden="true" style="font-size: 14px;"></i>
            </button>
            <button class="circle-btn">
              <i class="fa fa-pause" aria-hidden="true" style="font-size: 14px;"></i>
            </button>
            <button class="circle-btn">
              <i class="fa fa-stop" aria-hidden="true" style="font-size: 14px;"></i>
            </button>
          </div>
        </div>
        <div class="d-flex justify-content-between">
          <span class="hiring-instruction-link" onclick='contactArtist(<?php echo json_encode($profile) ?>)'>Hiring
            Instructions</span>
          <span style="color: white; font-size: 12px;">Per 2 hour Gig</span>
          <span style="color: white; font-size: 12px; display:none; cursor: pointer">Upcoming Performance</span>
        </div>
      </div>
    </div>
    <?php
        }
      ?>
  </div>
</div>
<div class="modal fade" id="contactArtistModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button class="modal-close-btn"><i class="fa fa-times" style="color: white; font-size:30px"></i></button>
      <div class="modal-body mt-3" style="padding-left: 20px; padding-right: 20px">
        <div class="row">
          <div class="col-md-4">
            <img class="contact-avatar" src="" alt="">
          </div>
          <div class="col-md-8 mt-3">
            <h4>Band Name</h4>
            <span>Review </span><a href="#">Hiring Instructions</a>
            <sapn> before contacting.</span>
          </div>
        </div>

        <div class="mt-5">
          <div class="mb-3">
            <label for="contact_comment" class="form-label">Comment</label>
            <textarea class="form-control" id="contact_comment" rows="4"
              placeholder="Place your comments here..."></textarea>
          </div>
          <div class="mt-4 mb-3">
            <button type="button" class="btn btn-primary" style="width: 100%" value="contact">Submit</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $(".artist").on("mouseover", function() {
    const artistInfoEle = $(this).find(".artist-short-info");
    artistInfoEle.css("opacity", "1.0")

    // hide 'Per 2 hour Gig'
    artistInfoEle.find("span")[3].style.display = "none";
    // show 'Upcoming Performance'
    artistInfoEle.find("span")[4].style.display = "block";
    // hide 'Over $10000'
    artistInfoEle.find("span")[1].style.display = "none";
    // show video-buttons
    artistInfoEle.find(".video-btns")[0].style.display = "block";

    // hide image
    const imgEle = $(this).find("img");
    imgEle.css("display", "none")

    // show vidoe with 1 sec delay
    const videoEle = $(this).find("video");
    videoEle.css("display", "block")
    setTimeout(function() {
      videoEle.get(0).play()
    }, 1000)
  })

  $(".artist").on("mouseout", function() {
    const artistInfoEle = $(this).find(".artist-short-info");
    artistInfoEle.css("opacity", "0.8")
    // show 'Per 2 hour Gig'
    artistInfoEle.find("span")[3].style.display = "block";
    // hide 'Upcoming Performance'
    artistInfoEle.find("span")[4].style.display = "none";
    // show 'Over $10000'
    artistInfoEle.find("span")[1].style.display = "block";
    // hide video-buttons
    artistInfoEle.find(".video-btns")[0].style.display = "none";

    // show image
    const imgEle = $(this).find("img");
    imgEle.css("display", "block")

    // hide vide
    const videoEle = $(this).find("video");
    videoEle.css("display", "none")
    videoEle.get(0).pause()
  })
});

function contactArtist(profile) {
  console.log(profile);
  $('#contactArtistModal').find("img.contact-avatar").attr("src", profile.avatar_link)
  $('#contactArtistModal').find("h4").html(profile.band_name)
  $('#contactArtistModal').modal('show');
}
</script>

<?php
  get_footer();
?>