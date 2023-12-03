// Image Preview
$.fn.filepond.registerPlugin(FilePondPluginImagePreview);

// Size Validation
$.fn.filepond.registerPlugin(FilePondPluginFileValidateSize);
$.fn.filepond.setDefaults({
    maxFileSize: "3MB",
});
// Turn input element into a pond with configuration options
const csrfToken = $('meta[name="csrf-token"]').attr("content");

$(".my-pond").filepond({
    allowMultiple: true,
    server: {
        process: "api/upload-file",
        headers: {
            "X-CSRF-TOKEN": csrfToken,
        },
        revert: {
            url: "api/retract-file",
            method: "DELETE",
            headers: {
                "x-CSRF-TOKEN": "{{ csrf_token() }}",
            },
        },
    },
});

// Listen for addfile event
$(".my-pond").on("FilePond:addfile", function (e) {
    // console.log("file added event", e);
});
