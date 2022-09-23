<?php
  get_header();
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
      global $wpdb;
      $profiles = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}profile", ARRAY_A );

      $i = 0;
      foreach ( $profiles as $profile ) {
    ?>
    <div class="col-md-3 col-sm-6 col-xs-12 artist">
      <input type="hidden" value="<?php echo $i ?>" class="profile-idx">
      <img class="artist-avtar" src="<?php echo $profile['avatar_link']; ?>" alt="">
      <div class="youtube-video-player" style="display: none;">
        <iframe id="player-<?php echo $profile['ID'] ?>" width="330" height="200"
          src="https://www.youtube.com/embed/<?php echo $profile['youtube_code']?>?enablejsapi=1" frameborder="0"
          allow="autoplay" style="border: solid 4px #37474F"></iframe>
      </div>
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
            <button class="circle-btn video-play-btn">
              <i class="fa fa-play" aria-hidden="true" style="font-size: 14px;"></i>
            </button>
            <button class="circle-btn video-pause-btn">
              <i class="fa fa-pause" aria-hidden="true" style="font-size: 14px;"></i>
            </button>
            <button class="circle-btn video-stop-btn">
              <i class="fa fa-stop" aria-hidden="true" style="font-size: 14px;"></i>
            </button>
          </div>
        </div>
        <div class="d-flex justify-content-between">
          <span class="hiring-instruction-link" onclick='contactArtist(<?php echo json_encode($profile) ?>)'>Hiring
            Instructions</span>
          <span style="color: white; font-size: 12px;">Per 2 hour Gig</span>
          <span style="color: white; font-size: 12px; display:none; cursor: pointer"
            onclick='showUpcomingPerformances(<?php echo json_encode($profile) ?>)'>Upcoming Performance</span>
        </div>
      </div>
    </div>
    <?php
      $i = $i + 1;
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

<div class="modal fade" id="upcomingPerformanceModal" tabindex="-1" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
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
          <table class="table performance-table">
            <thead>
              <tr>
                <th scope="col"></th>
                <th scope="col">Date</th>
                <th scope="col">Venue</th>
              </tr>
            </thead>
            <tbody id="performance_table">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let videoStatus = "stop"
let profileIdx = -1;
const json_profiles = `<?php echo json_encode($profiles) ?>`;
const profiles = JSON.parse(json_profiles);

var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

let player = [];

function onYouTubeIframeAPIReady() {
  for (let i = 0; i < profiles.length; i++) {
    player[i] = new YT.Player(`player-${profiles[i].ID}`, {
      playerVars: {
        'autoplay': 1,
        'controls': 0,
        'playsinline': 1,
      },
      events: {
        'onReady': onPlayerReady,
        'onStateChange': onPlayerStateChange
      }
    });
  }
}

// 4. The API will call this function when the video player is ready.
function onPlayerReady(event) {
  $(".video-play-btn").on('click', function() {
    console.log("play")
    videoStatus = "play";
    player[profileIdx].playVideo();
  })

  $(".video-pause-btn").on('click', function() {
    videoStatus = "pause";
    player[profileIdx].pauseVideo();
  })

  $(".video-stop-btn").on('click', function() {
    videoStatus = "stop";
    player[profileIdx].stopVideo();
  })

  $(".artist").on("mouseover", function() {
    profileIdx = parseInt($(this).find(".profile-idx").val());

    if (videoStatus === "stop") {
      let artistInfoEle = $(this).find(".artist-short-info");
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
      var imgEle = $(this).find("img");
      imgEle.css("display", "none")

      // show vidoe with 1 sec delay
      var videoEle = $(this).find(".youtube-video-player");
      videoEle.css("display", "block")

      // play video
      player[profileIdx].playVideo();
    }
  })

  $(".artist").on("mouseout", function() {
    profileIdx = parseInt($(this).find(".profile-idx").val());

    if (videoStatus === "stop") {
      let artistInfoEle = $(this).find(".artist-short-info");
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
      var imgEle = $(this).find("img");
      imgEle.css("display", "block")

      // hide vide
      var videoEle = $(this).find(".youtube-video-player");
      videoEle.css("display", "none")

      // stop video
      player[profileIdx].stopVideo();
    }
  })
}

// 5. The API calls this function when the player's state changes.
//    The function indicates that when playing a video (state=1),
//    the player should play for six seconds and then stop.

function onPlayerStateChange(event) {
  // if (event.data == YT.PlayerState.PLAYING) {
  // }
}

function contactArtist(profile) {
  $('#contactArtistModal').find("img.contact-avatar").attr("src", profile.avatar_link)
  $('#contactArtistModal').find("h4").html(profile.band_name)
  $('#contactArtistModal').modal('show');
}

function showUpcomingPerformances(profile) {
  console.log(profile);
  $('#upcomingPerformanceModal').find("img.contact-avatar").attr("src", profile.avatar_link)
  $('#upcomingPerformanceModal').find("h4").html(profile.band_name)

  jQuery.ajax({
    type: "post",
    url: "/wp-admin/admin-ajax.php",
    data: {
      profile_id: profile.ID,
      action: "get_performances",
    },
    success: function(response) {
      const res = JSON.parse(response);
      if (res.status === "success") {
        const tbody_ele = $("#performance_table")
        const performances = res.performances;
        let i = 1;
        for (let performance of performances) {
          const tr_ele = `
              <tr>
                <th scope="row">${i}</th>
                <td>${performance.start_date}</td>
                <td>${performance.venue}</td>
              </tr>
            `;
          tbody_ele.append(tr_ele);
          i++
        }
        $('#upcomingPerformanceModal').modal('show');
      }
    },
    error: function(err) {
      console.log(err);
    },
  });
}
</script>

<?php
  get_footer();
?>