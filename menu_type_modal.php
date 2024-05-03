<!-- Menu Type Modal -->
<div class="modal fade" id="menuTypeModal" tabindex="-1" role="dialog" aria-labelledby="menuTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuTypeModalLabel">Add/Edit Menu Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to add/edit menu types -->
                <form method="POST" action="add_menu_type.php">
                    <div class="form-group">
                        <label for="room_type">Room Type:</label>
                        <select class="form-control" id="room_type" name="room_type" required>
                            <!-- Populate room type options dynamically -->
                            <?php
                            // Fetch room types from database and populate options
                            $room_types = []; // Fetch room types from database
                            foreach ($room_types as $room_type) {
                                echo "<option value='{$room_type['type_id']}'>{$room_type['type_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Menu Type:</label>
                        <input type="text" class="form-control" id="type" name="type" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="addMenuType">Add Menu Type</button>
                </form>
            </div>
        </div>
    </div>
</div>
