<?php
?>
<!-- Create Story Modal -->
<div id="story_create_modal" class="modal-wrapper">
    <div class="modal-content">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Create New Story</h2>

        <!-- form uses a distinct id so it doesn't duplicate the modal wrapper id -->
        <form method="post" action="../crud/story.php">
            <!-- Hidden field to pass the story ID -->
            <input type="hidden" name="action" value="create">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
            <input type="hidden" name="redirect_page">
            <!-- Form fields for character details -->
            <div><p style="text-align: left;">Title <input type="text" name="title" class="input" required/></p></div>
            <div><p style="text-align: left;">Genre: <input type="text" name="genre" class="input" required/></p></div>
            <div><p style="text-align: left;">Synopsis</p>
                <textarea name="synopsis" class="resizable-input" rows="2" required></textarea>
            </div>
            <button type="submit" class="btn primary" name="redirect_page" value="../pages/locations.php">Manage Location</button>
            <button stype="submit" class="btn primary" name="redirect_page" value="../pages/characters.php">Manage Characters</button>
            <button type="submit" class="btn primary" name="redirect_page" value="../pages/timeline.php">Manage Timeline</button>
            <div class="modal-actions-right">
                <!-- Cancel should not submit; use data-close to let modal JS close the dialog -->
                <button type="button" data-close class="btn">Cancel</button>
                <!-- Submit using hidden action -->
                <button type="submit" class="btn primary" name="redirect_page">save</button>
            </div>
        </form>
    </div>
</div>

<!-- Update Story Modal -->
<div id="story_update_modal" class="modal-wrapper">
    <div class="modal-content">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Update Story</h2>
        <form method="post" action="../crud/story.php">
            <!-- Hidden field to pass the story ID -->
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="story_id">
            <!-- Form fields for character details -->
            <div><p style="text-align: left;">Title <input type="text" name="title" class="input" required/></p></div>
            <div><p style="text-align: left;">Genre: <input type="text" name="genre" class="input" required/></p></div>
            <div><p style="text-align: left;">Synopsis</p>
                <textarea name="synopsis" class="resizable-input" rows="2" required></textarea>
            </div>
            <div class="modal-actions-right">
                <!-- Cancel should not submit; use data-close to let modal JS close the dialog -->
                <button type="button" data-close class="btn">Cancel</button>
                <button type="submit" class="btn primary">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Story Modal -->
<div id="story_delete_modal" class="modal-wrapper">
    <div class="modal-content" style="width: 420px;">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Delete <strong data-fill="title"></strong>?</h2>
        <p>Are you sure you want to delete this story?</p>
        <form method="post" action="../crud/story.php">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="story_id" />
            <div class="modal-actions-center">
                <button type="button" data-close class="btn">Cancel</button>
                <button type="submit" class="btn primary">Delete</button>
            </div>
        </form>
    </div>
</div>

<!-- Select Story Modal -->
<div id="story_select_modal" class="modal-wrapper">
    <div class="modal-content" style="width: 420px;">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>You have selected <br><strong data-fill="title"></strong>!</h2>
        <p style="text-align: center;">Where would you like to start?</p>
        <form method="post" action="../crud/story.php">
            <input type="hidden" name="action" value="select">
            <input type="hidden" name="story_id" />
          <div class="modal-actions-center">
            <button class="btn primary" name="redirect_page" value="../pages/new_entry.php">Create New Entry</button>
            <button class="btn primary" name="redirect_page" value="../pages/dashboard.php">Go to Dashboard</button>
          </div>
        </form>
    </div>
</div>

<!-- Sunset Story Modal -->
<div id="story_sunset_modal" class="modal-wrapper">
    <div class="modal-content" style="width: 480px;">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Close Story</h2>
        <p style="text-align: center;">Are you sure you want to close this story?</p>
        <div class="modal-actions-center">
          <button type="button" data-close class="btn">Cancel</button>
          <a type="button" class="btn primary" style="text-decoration:none;" href="../pages/dashboard.php?clear_story=1">Close Story</a>
        </div>
    </div>
</div>