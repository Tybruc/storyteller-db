<?php
?>
<!-- Log Out Modal -->
<div id="logout_modal" class="modal-wrapper">
    <div class="modal-content" style="width: 420px;">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Log Out</h2>
        <p>Are you sure you want to leave this session?</p>
        <div class="modal-actions-center">
          <button type="button" data-close class="btn">Cancel</button>
          <button type="button" class="btn primary" onclick="window.location.href='../pages/login.php?clear_id=1'">Log Out</button>
        </div>
    </div>
</div>

<!-- Exit to Dashboard Modal -->
<div id="exit_to_dashboard_Modal" class="modal-wrapper">
    <div class="modal-content" style="width: 480px;">
        <span class="modal-close-x" data-close>&times;</span>
        <h2>Exit to Dashboard</h2>
        <p>Are you sure you want to exit to the Dashboard? 
           Any unsaved changes to your current timeline entry will be lost.</p>
        <div class="modal-actions-center">
          <button type="button" data-close class="btn">Cancel</button>
          <button type="button" class="btn primary" onclick="window.location.href='../pages/dashboard.php'">Exit to Dashboard</button>
        </div>
    </div>
</div>