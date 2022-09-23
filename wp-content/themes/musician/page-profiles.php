<?php
  get_header();

  if ( !is_user_logged_in() ) {
    wp_redirect( get_home_url() );
  }

  global $wpdb;

  $user = wp_get_current_user();
  $user_id = $user->ID;
  $user_profiles = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}profile WHERE user_id=$user_id", ARRAY_A);

  $user_email = $user->user_email;
  $now_date = date("Y-m-d");
  $source_str = $user->user_email . date("Y-m-d");
  $encode_str = base64_encode($source_str);
  $tokenstr = hash('sha256', $encode_str);

  $user_payment = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}payment WHERE user_id=$user_id", ARRAY_A);

  $is_subscribed = true;
  if (is_null($user_payment) || is_null($user_payment['expire'])) {
    $is_subscribed = false;
  } else {
    // check if payment date is expired
    $expire_date = strtotime(date('Y-m-d', strtotime($user_payment['expire']) ) );
    $current_date = strtotime(date('Y-m-d'));

    if($expire_date < $current_date) {
      $is_subscribed = false;
    }
  }
?>
<div class="profile" style="min-height: 100%;">
  <div class="ms-5 me-5">
    <?php
    if ($is_subscribed) {
  ?>
    <a href="<?php echo get_permalink( get_page_by_path( 'profile-detail' ) ); ?>" class="btn btn-primary"
      style="float: right;">+ Add New</a>
    <?php
    } else {
  ?>
    <a href="javascript:void(0)" class="btn btn-primary" style="float: right;" data-bs-toggle="modal"
      data-bs-target="#subscriptionModal">+ Add New</a>
    <?php
    }
  ?>
    <h3 class="text-white">My Profiles</h3>
    <table class="table table-dark table-hover table-bordered border-white text-center align-middle mt-3">
      <thead>
        <tr>
          <th scope="col"></th>
          <th scope="col">Avatar</th>
          <th scope="col">Profile Name</th>
          <th scope="col">Band Name</th>
          <th scope="col">Band Size</th>
          <th scope="col">Youtube</th>
          <th scope="col">Gig cost($)</th>
          <th scope="col">Genre</th>
          <th scope="col">Budget($)</th>
          <th scope="col">Event</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $i = 1;
        foreach ( $user_profiles as $profile ) {
        ?>
        <tr>
          <th scope="row"><?php echo $i ?></th>
          <td><img src="<?php echo $profile['avatar_link']; ?>" style="border-radius: 50%;" width="50">
          </td>
          <td><?php echo $profile['profile_name']; ?></td>
          <td><?php echo $profile['band_name']; ?></td>
          <td><?php echo $profile['band_size']; ?></td>
          <td><?php echo $profile['youtube_code']; ?></td>
          <td><?php echo $profile['gig_cost_min'] . " ~ " . $profile['gig_cost_max']; ?></td>
          <td><?php echo $profile['genre']; ?></td>
          <td><?php echo $profile['budget']; ?></td>
          <td><?php echo $profile['event_type']; ?></td>
          <td>
            <?php
            if ($is_subscribed) {
          ?>
            <a
              href="<?php echo get_permalink( get_page_by_path( 'performance' ) ) . '?profile_id=' . $profile['ID']; ?>">
              <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
            </a>
            <a href="<?php echo get_permalink( get_page_by_path( 'profile-detail' ) ) . '?profile_id=' . $profile['ID']; ?>"
              class="ms-3">
              <i class="fa fa-pencil text-info" aria-hidden="true"></i>
            </a>
            <a href="javascript:void(0)" onclick="triggerDeleteProfileModal(<?php echo $profile['ID'] ?>)" class="ms-3">
              <i class="fa fa-trash text-danger" aria-hidden="true"></i>
            </a>
            <?php
            } else {
          ?>
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#subscriptionModal">
              <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
            </a>
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#subscriptionModal" class="ms-3">
              <i class="fa fa-pencil text-info" aria-hidden="true"></i>
            </a>
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#subscriptionModal" class="ms-3">
              <i class="fa fa-trash text-danger" aria-hidden="true"></i>
            </a>
            <?php
            }
          ?>
          </td>
        </tr>
        <?php
        $i = $i + 1;
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="deleteProfileModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button class="modal-close-btn"><i class="fa fa-times" style="color: white; font-size:30px"></i></button>
      <div class="modal-body mt-3">
        <div class="text-center" style="font-size: 36px">Delete</div>
        <form action="/wp-admin/admin-post.php" method="post">
          <div style="padding-left: 20px; padding-right: 20px">
            <input type="hidden" id="delete_profile_id" name="delete_profile_id">
            <input type="hidden" name="action" value="submit_delete_profile_form">
            <p class="fw-light text-center" style="font-size: 20px">
              Are you sure to delete current profile?
            </p>
          </div>
          <div class="d-flex justify-content-center">
            <button class="btn btn-outline-secondary text-white me-3" type="button"
              data-bs-dismiss="modal">Cancel</button>
            <button class="btn btn-primary pe-4 ps-4" type="submit">Ok</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="subscriptionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button class="modal-close-btn"><i class="fa fa-times" style="color: white; font-size:30px"></i></button>
      <div class="modal-body mt-3">
        <div class="text-center" style="font-size: 36px">Subscription</div>
        <div class="text-center mt-2 mb-4">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/paypallogo.png' ); ?>" alt=""
            width="300px">
        </div>
        <div style="padding-left: 20px; padding-right: 20px">
          <p class="fw-light text-center" style="font-size: 20px">
            You must subscribe to have access for profile management.
          </p>
          <p class="fw-light text-center" style="font-size: 16px">
            $10.00 / Mo. Promotes Unlimited Bands.
          </p>
        </div>
        <div class="d-flex justify-content-center mt-4">
          <?php
            $payment_return_url = get_permalink( get_page_by_path( 'payment-done' ) ) . "?tokenstr=$tokenstr";
            echo do_shortcode('[wp_paypal button="subscribe" name="Musician Monthly Subscription" a3="10.00" p3="1" t3="M" src="1" return="' . $payment_return_url . '"]');
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
function triggerDeleteProfileModal(profile_id) {
  $("#deleteProfileModal").modal('show');
  $("#delete_profile_id").val(profile_id);
}

$(document).ready(function() {
  const isSubscribed = "<?php echo $is_subscribed ?>";
  if (isSubscribed === "") {
    $("#subscriptionModal").modal('show')
  }
})
</script>