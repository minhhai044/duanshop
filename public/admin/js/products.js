$(function () {
    $("#add_gallery").click(function (e) {
        e.preventDefault();
        $("#body-gallery").append(`
        <div class="mb-3 row">
            <label for="image_gallery" class="col-4 col-form-label " style="padding-left: 25px;">Gallery Image</label>
            <div class="col-8">
                <input type="file" class="form-control p-1" name="image_galleries[]"
                    id="image_gallery" />
            </div>
        </div>`);
    });

    $("#add_gallery_update").click(function (e) {
        e.preventDefault();
        $("#body-gallery_update").append(`
        <div class="mb-3 row">
            <label for="image_gallery" class="col-4 col-form-label " style="padding-left: 25px;">Gallery Image</label>
            <div class="col-8">
                <input type="file" class="form-control p-1" name="add_galleries[]"
                    id="image_gallery" />
            </div>
        </div>`);
    });
});
