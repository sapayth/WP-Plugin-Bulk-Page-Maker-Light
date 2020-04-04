<?php
  wp_enqueue_script( 'bpm-script' );
  wp_enqueue_style( 'bpm-style' );
?>
<div class="wrap">
  <h1 class="wp-heading-inline"><?php _e( 'New Pages', 'bulk-page-maker' ); ?></h1>
  <div class="form-area">
    <form id="createForm" method="post" class="">
      <table class="form-table">
        <tbody>
          <tr class="row<?php echo isset($this->errors['map_name']) ? ' form-invalid' : '' ?>">
            <th>List of Pages/Posts<br>(Comma Separated) <b>(*)</b></th>
            <td>
              <textarea class="code" id="txt_pages_list" cols="60" rows="5" name="txt_pages_list"></textarea>
              <?php if( isset( $this->errors['page_names'] )) { ?>
                  <br><span style="color: #B30000"><?php echo $this->errors['page_names']; ?></span>
              <?php } ?>
              <p class="description">eg. Test1, Test2, test3, test4, test5</p>
            </td>
          </tr>
          <tr class="type_tr">
            <th>Type</th>
            <td>
              <select id="cmb_post_type" name="cmb_post_type">
                <option value="page">Page</option>
                <option value="post">Post</option>
              </select>
            </td>
          </tr>
          <tr class="page_status_tr">
            <th>Pages/Posts Status</th>
            <td>
              <select id="cmb_page_status" name="cmb_page_status">
                <option value="publish">Publish</option>
                <option value="pending">Pending</option>
                <option value="draft">Draft</option>
                <option value="auto-draft">Auto Draft</option>
                <option value="private">Private</option>
                <option value="trash">Trash</option>
              </select>
            </td>
          </tr>
          <tr class="comment_status_tr">
            <th>Pages/Posts Comment Status</th>
            <td>
              <select id="cmb_comment_status" name="cmb_comment_status">
                <option value="closed">Closed</option>
                <option value="open">Open</option>
              </select>
            </td>
          </tr>
          <!-- <tr class="authors_tr">
            <th>Author</th>
            <td>
             
            </td>
          </tr> -->
          <tr class="parent_page_id_tr">
          	<th>Parent Page</th>
          	<td><?php wp_dropdown_pages(); ?></td>
          </tr>
        </tbody>
      </table>
      <?php wp_nonce_field('bpm'); ?>
      <?php submit_button( __('Add Pages', 'bulk-page-maker'), 'primary', 'btn_add_pages' ); ?>
    </form>
  </div>
</div>