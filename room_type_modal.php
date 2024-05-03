<!-- Room Type Modal -->
<div class="modal fade" id="roomTypeModal" tabindex="-1" role="dialog" aria-labelledby="roomTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomTypeModalLabel">Add/Edit Room Type</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form to add/edit room types -->
                <form method="POST" action="add_room_type.php">
                    <div class="form-group">
                        <label for="type_name">Room Type Name:</label>
                        <input type="text" class="form-control" id="type_name" name="type_name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacity:</label>
                        <input type="number" class="form-control" id="capacity" name="capacity" required>
                    </div>
                    <button type="submit" class="btn btn-primary" name="addRoomType">Add Room Type</button>
                </form>
            </div>
        </div>
    </div>
</div>
