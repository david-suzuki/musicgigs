<?php
  get_header();

  if ( !is_user_logged_in() ) {
    wp_redirect( get_home_url() );
  }

  $user = wp_get_current_user();
  $date = date("Y-m-d");
  $dt = strtotime($date);
  $expire_date = date("Y-m-d", strtotime("+1 month", $dt));

  global $wpdb;
  if (isset($_GET['tokenstr'])) {
    $tokenstr = $_GET['tokenstr'];
    $payment = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}payment WHERE user_id=$user->ID", OBJECT);

    $user_email = $user->user_email;
    $now_date = date("Y-m-d");
    $source_str = $user->user_email . date("Y-m-d");
    $encode_str = base64_encode($source_str);
    $token_str = hash('sha256', $encode_str);

    if ($tokenstr === $token_str) {
      if (is_null($payment)) {
        $wpdb->insert(
          'wp_payment',
          array(
            'expire' => $expire_date,
            'balance' => 10.00,
            'user_id' => $user->ID
          ),
          array( '%s', '%d', '%d' )
        );
      } else {
        $wpdb->update(
          'wp_payment',
          array(
            'expire' => $expire_date,
            'balance' => 10.00
          ),
          array( 'user_id' => $user->ID ),
          array( '%s', '%d' ),
          array( '%d' )
        );
      }
    }
  }
?>
<div class="profile" style="min-height: 100%;">
  <div class="row justify-content-center">
    <div class="col-12 col-md-5">
      <div class="payment-container">
        <div class="text-center mt-2 mb-4">
          <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/paypallogo.png' ); ?>" alt=""
            width="300px">
        </div>
        <div>
          <div class="divider"><span></span><span>
              <?php echo $user->display_name ?>
            </span><span></span></div>
        </div>
        <div class="payment-info">
          <p class="fw-light text-center text-white" style="font-size: 20px">
            Your subscription for musician profile is completed.
          </p>
          <p class="fw-bold text-center text-white" style="font-size: 52px">
            10.00<span style="font-size: 32px; color: #f6931f">$</span>
          </p>
          <p class="fw-light text-center text-white mb-4" style="font-size: 16px">
            Promotes Unlimited Bands per Month.
          </p>
          <button type="button" class="btn btn-primary" style="width: 85%" id="to_profile_btn">To Profiles</button>
          <div class="fw-light text-center text-white mt-3" style="font-size: 14px">
            Subscription will be expired at <?php echo $expire_date ?>.
          </div>
          <div class="fw-light text-center text-white" style="font-size: 14px">
            Check <a href="#">privacy policy</a> here.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $('#to_profile_btn').on('click', function() {
    window.location.href = "<?php echo get_permalink( get_page_by_path( 'profiles' ) ); ?>";
  })
})
</script>