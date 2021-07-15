<div class="wrap">
  <h1 class="wp-heading-inline"><?php _e( 'New Pages', 'sh-bpm-light' ); ?></h1>
  <div class="form-area">
    <form id="createForm" method="post" class="">
      <table class="form-table">
        <tbody>
          <tr class="row<?php echo isset($this->errors['map_name']) ? ' form-invalid' : '' ?>">
            <th>
              <?php _e( 'List of Pages/Posts', 'sh-bpm-light' ); ?><br><?php _e( '(Comma Separated)', 'sh-bpm-light' ); ?> <b>(*)</b>
            </th>
            <td>
              <textarea id="txt_pages_list" name="txt_pages_list" class="regular-text"></textarea>

              <?php if( isset( $this->errors['page_names'] )) { ?>
              <br>
              <span style="color: #B30000"><?php echo $this->errors['page_names']; ?></span>
              <?php } ?>

              <p class="description">
                <?php _e( 'eg. Page1, Page2, page3, PAGE4, PAge5', 'sh-bpm-light' ); ?>
              </p>
            </td>
          </tr>
          <tr class="row<?php echo isset($this->errors['page_contents']) ? ' form-invalid' : '' ?>">
            <th>
              <?php _e( 'Page/Post content', 'sh-bpm-light' ); ?>
            </th>
            <td>

              <?php if( isset( $this->errors['page_content'] )) { ?>
              <br><span style="color: #B30000"><?php echo $this->errors['page_content']; ?></span>

              <?php
                }

                $pages_content = filter_input( INPUT_POST, 'cmb_page_content', FILTER_SANITIZE_STRING );
                $content       = ( isset( $pages_content ) ? htmlspecialchars_decode( $pages_content ) : '' );
                $settings      = [
                  'textarea_name' => 'cmb_page_content',
                  'editor_class'  => 'requiredField',
                  'textarea_rows' => '6',
                  'media_buttons' => true,
                  'tinymce'       => true,
                ];
                wp_editor( $content, 'cmb_page_content', $settings );
              ?>
              <p class="description">

              <?php
                _e( 'eg. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.', 'sh-bpm-light' );
              ?>
              
              </p>
            </td>
          </tr>
          <tr class="type_tr">
            <th>
              <?php _e( 'Type', 'sh-bpm-light' ); ?>
            </th>
            <td>
              <select id="cmb_post_type" name="cmb_post_type" class="regular-text">
                <option value="page">Page</option>
                <option value="post">Post</option>
              </select>
            </td>
          </tr>
          <tr class="page_status_tr">
            <th><?php _e( 'Pages/Posts Status', 'sh-bpm-light' ); ?></th>
            <td>
              <select id="cmb_page_status" name="cmb_page_status" class="regular-text">
                <option value="publish">Publish</option>
                <option value="pending">Pending</option>
                <option value="draft">Draft</option>
              </select>
            </td>
          </tr>
          <tr class="comment_status_tr">
            <th>Pages/Posts Comment Status</th>
            <td>
              <select id="cmb_comment_status" name="cmb_comment_status" class="regular-text">
                <option value="closed">Closed</option>
                <option value="open">Open</option>
              </select>
            </td>
          </tr>
          <tr class="parent_page_id_tr" class="regular-text">
            <th>Parent Page</th>
            <td>

              <?php
                $args = [
                  'show_option_none' => 'No Parent',
                ];
                wp_dropdown_pages( $args );
              ?>

            </td>
          </tr>
        </tbody>
      </table>

      <?php wp_nonce_field('bpm-nonce'); ?>
      <?php submit_button( __('Add Pages', 'sh-bpm-light'), 'primary', 'btn_add_pages' ); ?>

    </form>
  </div>
</div>