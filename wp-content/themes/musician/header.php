<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php bloginfo( 'name' ); ?></title>
  <?php wp_head() ?>
</head>

<body <?php body_class(); ?> style="height: 90vh">

  <header class="site-header">
    <div class="d-flex justify-content-between top-header">
      <div class="d-flex align-items-center">
        <div>
          <a class="top-header-menu" href="	<?php echo get_home_url(); ?>">
            Home
          </a>
        </div>
        <div>
          <a class="top-header-menu" data-bs-toggle="modal" data-bs-target="#pricingModal">
            Pricing
          </a>
        </div>
        <div>
          <a class="top-header-menu" data-bs-toggle="modal" data-bs-target="#aboutusModal">
            About Us
          </a>
        </div>
        <div>
          <a class="top-header-menu" data-bs-toggle="modal" data-bs-target="#contactusModal">
            Contact Us
          </a>
        </div>
      </div>

      <div class="d-flex align-items-center">
        <div class="dropdown top-header-menu">
          <button class="search-btn dropdown-toggle" type="button" id="dropdownSearch" data-bs-toggle="dropdown"
            aria-expanded="false">
            <i class="fa fa-search" aria-hidden="true"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-dark">
            <div class="m-3">
              <form action="<?php echo get_permalink( get_page_by_path( 'search' ) ); ?>" method="get" id="search_form">
                <select class="form-select form-select-sm mb-2" name="type" id="search_type">
                  <option value="city">by Cities</option>
                  <option value="budget">by Budget</option>
                  <option value="event_type">by Event Type</option>
                  <option value="band_size">by Band Size</option>
                  <option value="genre">by Genre</option>
                </select>
                <input type="text" class="form-control form-control-sm" placeholder="search text..." name="text"
                  id="search_text">
              </form>
            </div>
          </div>
        </div>
        <?php
        if ( is_user_logged_in() ) {
        ?>
        <div class="dropdown" style="margin-right: 20px;">
          <button class="dropdown-toggle" type="button" id="user_account_button" data-bs-toggle="dropdown"
            aria-expanded="false">
            <?php
             echo wp_get_current_user()->display_name;
            ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="user_account_button">
            <?php
              if (wp_get_current_user()->roles[0] === "editor" || wp_get_current_user()->roles[0] === "administrator") {
            ?>
            <li><a class="top-header-menu" href="<?php echo get_permalink( get_page_by_path( 'profiles' ) ); ?>">My
                Profiles</a></li>
            <li>
              <?php
              } else if (wp_get_current_user()->roles[0] === "author") {
            ?>
            <li><a class="top-header-menu" href="<?php echo get_permalink( get_page_by_path( 'profile' ) ); ?>">My
                Advertisements</a></li>
            <li>
              <?php
              }
            ?>
              <hr class="dropdown-divider">
            </li>
            <li><a href="<?php echo wp_logout_url( home_url() ); ?>" class="top-header-menu">
                <i class="fa fa-sign-out" aria-hidden="true"></i> Sign Out
              </a></li>
          </ul>
        </div>
        <?php
      } else {
        ?>
        <a class="top-header-menu" data-bs-toggle="modal" data-bs-target="#signinModal">
          <i class="fa fa-sign-in" aria-hidden="true"></i> Sign In
        </a>
        <?php
      }
        ?>
      </div>
    </div>

    <?php
    get_template_part( 'modals');
  ?>
  </header>