<!-- Storyteller Location_modals - ..partials/modals/locations.php
      Modals for creating, updating, deleting locations.
      - includes create, update, delete modals, and unsaved changes modal

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - added unsaved changes modal for new_locations.php selections
  -->

<?php
?>
<!-- Create Location Modal -->
<div id="location_create_modal" class="modal-wrapper">
    <div class="modal-content">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Create New Location</h2>
        <!-- form uses a distinct id so it doesn't duplicate the modal wrapper id -->
        <form method="post" action="../crud/locations.php">
            <!-- Hidden field to pass the story ID -->
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="location_id" />
            <input type="hidden" name="story_id">
            <input type="hidden" name="user_id">
            <input type="hidden" name="redirect_page">
            <!-- Form fields for character details -->
            <div><p>Name <input type="text" name="name" class="input" required/></p></div>
            <div><p>Description:</p>
                <textarea name="description" class="resizable-input" rows="2" required></textarea>
            </div>
            <div class="modal-actions-right">
                <!-- Cancel should not submit; use data-close to let modal JS close the dialog -->
                <button type="button" data-close class="btn">Cancel</button>
                <!-- Submit using hidden action -->
                <button type="submit" class="btn primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Update Location Modal -->
<div id="location_update_modal" class="modal-wrapper">
    <div class="modal-content">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Update Location</h2>
        <form method="post" action="../crud/locations.php">
            <!-- Hidden field to pass the story ID -->
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="location_id" />
            <input type="hidden" name="redirect_page">
            <!-- Form fields for character details -->
            <div><p>Name <input type="text" name="name" class="input" required/></p></div>
            <div><p>Description:</p>
                <textarea name="description" class="resizable-input" rows="2" required></textarea>
            </div>
            <div class="modal-actions-right">
                <!-- Cancel should not submit; use data-close to let modal JS close the dialog -->
                <button type="button" data-close class="btn">Cancel</button>
                <button type="submit" class="btn primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Location Modal -->
<div id="location_delete_modal" class="modal-wrapper">
    <div class="modal-content" style="width: 420px;">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Delete <strong data-fill="delete_location"></strong></h2>
        <p>Are you sure you want to delete this location?</p>
        <form method="post" action="../crud/locations.php">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="location_id" />
            <input type="hidden" name="redirect_page">
            <div class="modal-actions-center">
                <button type="button" data-close class="btn">Cancel</button>
                <button type="submit" class="btn primary">Delete</button>
            </div>
        </form>
    </div>
</div>

<!-- Unsaved Locations Modal -->
<div id="unsaved_new_locations_modal" class="modal-wrapper">
  <div class="modal-content" style="width:480px;">
    <span class="modal-close-x" data-close>&times;</span>
    <h2>Save Location Selections?</h2>
    <p>You have unsaved location selections for this timeline entry. 
       Do you want to save them before leaving?</p>
    <form id="unsaved_locations_form" action="../crud/timeline.php" method="post">
      <input type="hidden" name="entry_id" value="<?= htmlspecialchars($entry_id) ?>">
      <input type="hidden" name="location_id" id="modal_location_id">
      <input type="hidden" name="redirect_page" id="modal_redirect_page">
      <input type="hidden" name="action" id="modal_action">
      <div class="modal-actions-center">
        <button type="button" data-close class="btn">Cancel</button>
        <!-- No DB change, just redirect -->
        <button type="button" class="btn primary" data-action="no_location_change">Continue without Saving</button>
        <!-- Update location, then redirect -->
        <button type="button" class="btn primary" data-action="update_location">Save Selections</button>
      </div>
    </form>
  </div>
</div>
