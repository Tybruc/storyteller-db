
<?php
/* Storyteller Character_modals - ..partials/modals/characters.php
      Modals for creating, updating, deleting characters.
      - includes create, update, delete modals, and unsaved changes modal

      Created by [Ty Curneen], 2025

      Updated: November 2025
      Changes:
      - added unsaved changes modal for new_characters.php selections
 */

?>
<!-- Create Character Modal -->
<div id="character_create_modal" class="modal-wrapper">
    <div class="modal-content">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Create New Character</h2>
        <!-- form uses a distinct id so it doesn't duplicate the modal wrapper id -->
        <form method="post" action="../crud/characters.php">
            <!-- Hidden field to pass the story ID -->
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="story_id">
            <input type="hidden" name="redirect_page">
            <!-- Form fields for character details -->
            <div><p>Name: <input type="text" name="name" class="input" required/></p></div>
            <div><p>Age: <input type="text" name="age" class="input" required/></p></div>
            <div><p>Description:</p>
                <textarea name="description" class="resizable-input" rows="2" required></textarea>
            </div>
            <div><p>Notes:</p>
                <textarea name="notes" class="resizable-input" rows="2" required></textarea>
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

<!-- Update Character Modal -->
<div id="character_update_modal" class="modal-wrapper">
    <div class="modal-content">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Update Character</h2>
        <form method="post" action="../crud/characters.php">
            <!-- Hidden field to pass the story ID -->
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="character_id">
            <input type="hidden" name="redirect_page">
            <!-- Form fields for character details -->
            <div><p>Name <input type="text" name="name" class="input" required/></p></div>
            <div><p>Age: <input type="text" name="age" class="input" required/></p></div>
            <div><p>Description:</p>
                <textarea name="description" class="resizable-input" rows="2" required></textarea>
            </div>
            <div><p>Notes:</p>
                <textarea name="notes" class="resizable-input" rows="2" required></textarea>
            </div>
            <div class="modal-actions-right">
                <!-- Cancel should not submit; use data-close to let modal JS close the dialog -->
                <button type="button" data-close class="btn">Cancel</button>
                <button type="submit" class="btn primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Character Modal -->
<div id="character_delete_modal" class="modal-wrapper">
    <div class="modal-content" style="width: 420px;">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Delete <strong data-fill="name"></strong></h2>
        <p>Are you sure you want to delete this character?</p>
        <form method="post" action="../crud/characters.php">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="character_id">
            <input type="hidden" name="redirect_page">
            <div class="modal-actions-center">
                <button type="button" data-close class="btn">Cancel</button>
                <button type="submit" name="character_delete" class="btn primary">Delete</button>
            </div>
        </form>
    </div>
</div>

<!-- Unsaved Characters Modal for new_characters.php -->
<div id="unsaved_characters_modal" class="modal-wrapper">
    <div class="modal-content" style="width:480px;">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Save Character Changes?</h2>
        <p>You have unsaved changes for this entry. Do you want to save them before leaving?</p>
        <form id="unsaved_characters_form" action="../crud/timeline.php" method="post">
          <input type="hidden" name="timeline_id" id="modal_timeline_id" value="">
          <input type="hidden" name="character_ids" id="modal_character_ids" value="">
          <input type="hidden" name="redirect_page" id="modal_redirect_page" value="">
          <input type="hidden" name="action" id="modal_action" value="update_characters">
          
        <div class="modal-actions-center">
            <button type="button" data-close class="btn">Cancel</button>
            <!-- No DB change, just redirect -->
            <button type="button" class="btn" data-action="no_character_change">Continue Without Saving</button>
            <!-- Update characters, then redirect -->
            <button type="button" class="btn primary" data-action="update_characters">Save Changes</button>
        </div>
    </div>
  </form>
</div>
