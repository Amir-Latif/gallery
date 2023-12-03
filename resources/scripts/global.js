import.meta.glob(["../images/**"]);

const deletionOptionsValidation = document.querySelector(
    "#delete-options-validation"
);
const oldAlbumId = document.querySelector("#old_album_id");
//#region Prepare list of albums
function getAlbumNames() {
    const albumListSelections = $(".album-list-selection");

    $.ajax({
        url: "api/get-album-names",
        method: "GET",
        contentType: "application/json",
        success: function (data) {
            albumListSelections.each(function () {
                $(this).html('<option value="" default>Select Album</option>');
            });

            data.forEach(function (name) {
                albumListSelections.each(function () {
                    $(this).append(`<option value='${name}'>${name}</option>`);
                });
            });
        },
        error: function (error) {
            console.error("Error fetching album names:", error);
        },
    });
}

getAlbumNames();
//#endregion Album List

//#region Create Album
const createValidation = $("#create-validation");
const createForm = $("#create-form");

createForm.on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    $.ajax({
        url: "api/create-album",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            name: formData.get("name"),
        }),
        success: function (data) {
            createValidation.text(data.status);
            createForm[0].reset();
            getAlbumNames();
        },
        error: function (error) {
            console.error("Error creating album:", error);
        },
    });
});

//#endregion Create Album

//#region upload files
const submitValidation = $("#submit-validation");
const submitForm = $("#submit-form");
submitForm.on("submit", function (e) {
    e.preventDefault();

    const form = new FormData(this);

    $.ajax({
        url: "api/submit-files",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (data) {
            submitValidation.text(data);
            submitForm[0].reset();
            $(".my-pond").filepond("removeFiles");
        },
        error: function (error) {
            submitValidation.text(error.responseJSON.message);
            console.log(error.responseJSON.message);
        },
    });
});

//#endregion upload files

//#region album editing
const editValidation = $("#edit-validation");
const editingForm = $("#edit-form");

editingForm.on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);
    $.ajax({
        url: "api/edit-album",
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            old_name: formData.get("old_name"),
            new_name: formData.get("new_name"),
        }),
        success: function (data) {
            editValidation.text(data.message);
            editingForm[0].reset();
            getAlbumNames();
        },
        error: function (error) {
            console.error("Error editing album:", error);
        },
    });
});
//#endregion album editing

//#region album deleting
const deleteValidation = $("#delete-validation");
const deleteOptionsValidation = $("#delete-options-validation");
const deleteForm = $("#delete-form");
const deleteOptions = $("#delete-options");
deleteForm.submit(function (e) {
    e.preventDefault();
    const form = new FormData(e.currentTarget);
    $.ajax({
        url: "api/delete-album",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (data) {
            deleteValidation.text(data);
            deleteForm[0].reset();
            getAlbumNames();
        },
        error: function (error) {
            const arr = error.responseJSON.message.split("/");
            const err = arr[0];
            deleteValidation.text(err);

            if (err === "Album has pictures") {
                $("#old-album-name").val(arr[1]);
                deleteOptions.show();
            }
        },
    });
});

// album deleting options
const deleteOptionsForm = $("#delete-options")
deleteOptionsForm.submit(function (e) {
    e.preventDefault();
    const form = new FormData(e.currentTarget);
    $.ajax({
        url: "api/delete-album-options",
        method: "POST",
        data: form,
        processData: false,
        contentType: false,
        success: function (data) {
            deleteOptions.hide();
            deleteOptionsForm[0].reset();
            getAlbumNames();
        },
        error: function (error) {
            deleteOptionsValidation.text(error.responseJSON.message);
        },
    });
});
//#endregion album deleting
