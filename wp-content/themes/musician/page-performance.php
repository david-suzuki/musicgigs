<?php
  get_header();

  if ( !is_user_logged_in() ) {
    wp_redirect( get_home_url() );
  }

  global $wpdb;

  $profile_id = null;
  $user_profile = null;
  $performances = null;
  if (isset($_GET['profile_id'])) {
    $profile_id = $_GET['profile_id'];
    $user_profile = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}profile WHERE ID=$profile_id", OBJECT);

    $performances = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}performance WHERE profile_id = $profile_id", OBJECT );
  }
?>
<div class="profile" style="min-height: 100%;">
  <div class="container">
    <h3 class="text-white">Upcoming Performances</h3>
    <div class="row mt-3">
      <div class="col-md-4">
        <div class="picture-container">
          <div class="picture">
            <img
              src="<?php echo $user_profile->avatar_link ? esc_url($user_profile->avatar_link) : esc_url( get_template_directory_uri() . '/assets/images/default-avatar.PNG' ); ?>"
              class="picture-src" id="wizardPicturePreview" title="">
          </div>
          <h6 class="text-white mt-3"><?php echo $user_profile->profile_name ?></h6>
        </div>
      </div>
      <div class="col-md-8">
        <div class="profile-container">
          <div class="mb-3 row">
            <div class="col-5">
              <input placeholder="Date" class="form-control" type="text" onfocus="(this.type='date')" id="start_date">
            </div>
            <div class="col-5">
              <input type="text" class="form-control" id="venue" placeholder="Venue">
            </div>
            <div class="col-2">
              <button class="btn btn-primary form-save-btn" type="button" id="peformance_submit">Create</button>
            </div>
          </div>
          <div class="mb-3">
            <table class="table table-light">
              <thead class="table-dark">
                <tr>
                  <th scope="col">Date</th>
                  <th scope="col">Venue</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="performance_table">
                <?php
                foreach ( $performances as $performance ) {
                ?>
                <tr id="<?php echo 'performance-' . $performance->ID ?>">
                  <td><?php echo $performance->start_date ?></td>
                  <td><?php echo $performance->venue ?></td>
                  <td>
                    <a href="javascript:void(0)" onclick="deletePerformance(<?php echo $performance->ID ?>)"
                      class="ms-3">
                      <i class="fa fa-trash text-danger" aria-hidden="true"></i>
                    </a>
                  </td>
                </tr>
                <?php
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  $("#peformance_submit").on('click', function() {
    const start_date = $("#start_date").val();
    const venue = $("#venue").val();
    const profile_id = "<?php echo $profile_id ?>";

    if (start_date === "" || venue === "") {
      return
    }

    jQuery.ajax({
      type: "post",
      url: "/wp-admin/admin-ajax.php",
      data: {
        start_date,
        venue,
        profile_id,
        action: "performance_submit",
      },
      success: function(response) {
        const res = JSON.parse(response);
        if (res.status === "success") {
          const tr_ele = `
            <tr id="performance-${res.id}">
              <td>${start_date}</td>
              <td>${venue}</td>
              <td>
                <a href="javascript:void(0)" onclick="deletePerformance(${res.id})" class="ms-3">
                  <i class="fa fa-trash text-danger" aria-hidden="true"></i>
                </a>
              </td>
            </tr>
          `;
          $("#performance_table").append(tr_ele)
        }
      },
      error: function(err) {
        console.log(err);
      },
    });
  })
});

function deletePerformance(performance_id) {
  jQuery.ajax({
    type: "post",
    url: "/wp-admin/admin-ajax.php",
    data: {
      performance_id,
      action: "performance_delete",
    },
    success: function(response) {
      const res = JSON.parse(response);
      if (res.status === "success") {
        const tr_ele = $(`#performance-${performance_id}`)
        tr_ele.remove()
      }
    },
    error: function(err) {
      console.log(err);
    },
  });
}
</script>