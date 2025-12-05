<?php
?>
<!-- Create Timeline Entry Modal -->
<div id="timeline_create_modal" class="modal-wrapper">
    <div class="modal-content">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Create Timeline Entry</h2>
        <p>Are you ready to create <strong data-fill="name"></strong>?</p>
        <!-- form uses a distinct id so it doesn't duplicate the modal wrapper id -->
        <form method="post" action="../crud/timeline.php">
            <!-- Hidden field to pass the story ID -->
            <input type="hidden" name="action" value="create">
            <div>Title: <input type="text" name="title" class="input" required/></div>
            <div><p style="text-align: left;">What type of entry is this?</p>
                <p><em>("Plot Points" are key moments that drive the story forward, while "Events" provide context and depth.)</em></p>
                <select style="text-align: left;" name="entry_type" required>
                  <option value="" style="color:#9ca3af">Entry Type</option>
                  <option value="plot">Plot Point</option>
                  <option value="event">Event</option>
                </select></div>
            <div>Summary: <textarea name="summary" class="resizeable-input" rows="2" required></textarea></div>
            <input type="hidden" name="location_id" value="0">
            <input type="hidden" name="sequence_no" value="0">
            <button type="submit" class="btn primary" name="redirect_page" value="../pages/new_characters.php">Add Characters</button>
            <button type="submit" class="btn primary" name="redirect_page" value="../pages/new_locations.php">Add Locations</button>
            <!-- Form fields for character details -->
            <div class="modal-actions-right">
                <!-- Cancel should not submit; use data-close to let modal JS close the dialog -->
                <button type="button" data-close class="btn">Cancel</button>
                <!-- Submit using hidden action -->
                <button type="submit" class="btn primary" name="redirect_page" value="../pages/timeline.php">Save & exit</button>

            </div>
        </form>
    </div>
</div>

<!-- Update Timeline Entry Modal -->
<div id="timeline_update_modal" class="modal-wrapper">
    <div class="modal-content">
        <span class="modal-close-x" data-close>&times;</span>
        <h2><strong data-fill="name"></strong> Update</h2>
        <p> To update your timeline entry, please modify the details below as needed.</p>
        <form method="post" action="../crud/timeline.php">
          <!-- Hidden field to pass the story ID -->
          <input type="hidden" name="action" value="create">
          <input type="hidden" name="story_id">
          <input type="hidden" name="entry_id">
          <div>Title: <input type="text" name="title" class="input" required/></div>
          <div><p style="text-align: left;">What type of entry is this?</p>
            <p><em>("Plot Points" are key moments that drive the story forward, while "Events" provide context and depth.)</em></p>
            <select style="text-align: left;" name="entry_type" required>
              <option value="" style="color:#9ca3af">Entry Type</option>
              <option value="plot">Plot Point</option>
              <option value="event">Event</option>
            </select></div>
          <div>Summary: <textarea name="summary" class="resizeable-input" rows="2" required></textarea></div>
          <input type="hidden" name="location_id" value="0">
          <input type="hidden" name="sequence_no" value="0">
          <div class="modal-actions-center">
            <button type="submit" class="btn primary" name="redirect_page" value="../pages/new_locations.php">Add Location</button>
            <button type="submit" class="btn primary" name="redirect_page" value="../pages/new_characters.php">Add Characters</button>
          </div>
          <!-- Form fields for character details -->
          <div class="modal-actions-right">
              <!-- Cancel should not submit; use data-close to let modal JS close the dialog -->
              <button type="button" data-close class="btn">Cancel</button>
              <!-- Submit using hidden action -->
              <button type="submit" class="btn primary" name="redirect_page" value="../pages/timeline.php">Save & exit</button>
          </div>
        </form>
    </div>
</div>

<!-- Delete Timeline Modal -->
<div id="timeline_delete_modal" class="modal-wrapper">
  <div class="modal-content">
    <span class="modal-close-x" data-close>&times;</span>
    <h2>Delete Timeline Event</h2>
    <p>Are you sure you want to delete <strong data-fill="name"></strong>?</p>
    <form id="timeline_delete_form">
      <input type="hidden" name="action" value="delete">
      <input type="hidden" name="timeline_id">
      <div class="modal-actions-center">
        <button type="button" data-close class="btn">Cancel</button>
        <button type="submit" class="btn danger">Delete</button>
      </div>
    </form>
  </div>
</div>

<!-- Unsaved Characters Modal -->
<div id="timeline_save_order_modal" class="modal-wrapper">
  <div class="modal-content" style="width:480px;">
    <span class="modal-close-x" data-close>&times;</span>
    <h2>Save Timeline Order</h2>
    <p>You've changed the order of your timeline entries. 
       Do you want to save them before leaving?</p>
    <form id="save_order_form" action="../crud/timeline.php" method="post">
      <input type="hidden" name="action" value="update_sequence">
      <input type="hidden" name="story_id" id="modal_story_id">
      <input type="hidden" name="entry_order" id="modal_entry_order">
      <div class="modal-actions-center">
        <button type="button" class="btn" data-close>Cancel</button>
        <button type="button" class="btn primary" id="confirm_save_order"> Save Order</button>
      </div>
    </form>
  </div>
</div>

