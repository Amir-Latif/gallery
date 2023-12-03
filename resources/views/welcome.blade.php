<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Album Management</title>
    <link rel="icon" href="{{ Vite::asset('resources/images/favicon.png') }}" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- styles and scripts-->
    @vite([
    'resources/styles/filepond-plugin-image-preview.css',
    'resources/styles/filepond.css',
    'resources/styles/filepond.css',
    'resources/styles/global.css',

    'resources/scripts/jquery.js',
    'resources/scripts/filepond.js',
    'resources/styles/filepond-plugin-image-preview.js',
    'resources/scripts/filepond-jquery.js',
    'resources/scripts/filepond.js',
    'resources/scripts/global.js',
    ])

</head>

<body class="antialiased">
    <section>
        <h2>Albums</h2>
    </section>
    <section>
        <h2>Add Album</h2>
        <form id="create-form">
            @csrf
            <label for="album-name">Album Name:</label>
            <input name="name" type="text" id="album-name" required />
            <small id="create-validation"></small>
            <button type="submit">Save</button>
            <!-- <button type="button" id="cancel-button">Cancel</button> -->
            <!-- TODO Add insert pics during creation -->
        </form>

    </section>
    <section>
        <h2>Upload Picutres to Album</h2>
        <form id="submit-form">
            @csrf
            <label for="name">Album Name:</label>
            <select name="name" class="album-list-selection">
            </select>
            <!-- <input type="file" name="picture"> -->
            <input type="file" class="my-pond" name="picture" required multiple data-max-file-size="3MB" data-max-files="3" />
            <small id="submit-validation"></small>
            <button type="submit">Save</button>
            <!-- <button type="button" id="cancel-button">Cancel</button> -->
        </form>

    </section>
    <section>
        <h2>Edit Album</h2>
        <form id="edit-form">
            @csrf
            <label for="old_name">Album Name:</label>
            <select name="old_name" class="album-list-selection">
            </select>
            <label for="new_name">New Name:</label>
            <input name="new_name" type="text" id="album-name" required />
            <small id="edit-validation"></small>
            <button type="submit">Save</button>
            <!-- <button type="button" id="cancel-button">Cancel</button> -->
        </form>

    </section>
    <section>
        <h2>Delete Album</h2>
        <form id="delete-form">
            @csrf
            <label for="name">Album Name:</label>
            <select name="name" class="album-list-selection">
            </select>
            <small id="delete-validation"></small>
            <button type="submit" id="delete-button">Delete</button>
        </form>
        <form id="delete-options">
            @csrf
            <input hidden id="old-album-name" name="old_album_name" />
            <label for="option">The album has pictures. Select whether you want to delete it or move the pictures to another album</label>
            <select name="option" type="text">
                <option value="delete">Delete the album with its pictures</option>
                <option value="move">Move the pictures to another album</option>
            </select>
            <label for="new_album">Select the new album name if you opt to move the pictures</label>
            <select name="new_album" class="album-list-selection">
            </select>
            <small id="delete-options-validation"></small>
            <button type="submit" id="delete-button">Delete</button>
        </form>
    </section>

</body>

</html>