<?php
require_once 'auth_check.php';
require_once 'config.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Music Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1e293b;
        }
        ::-webkit-scrollbar-thumb {
            background: #475569;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        .playlist-hover:hover .playlist-play {
            opacity: 1;
            transform: translateY(0);
        }

        body {
            background: #0F172A;
        }
        
        .slate {
            background: #0F172A;
            
        }
        
        .slate-700{
            background: #1C3163;
        }

        #notification {
    transition: opacity 0.5s ease;
}
.notification-success {
    background-color: #38a169; /* Green */
    color: white;
}
.notification-error {
    background-color: #e53e3e; /* Red */
    color: white;
}
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="flex h-screen">
         <div id="notification" class="fixed top-4 right-4 px-4 py-2 z-10 rounded hidden"></div>

        <!-- Sidebar -->
        <div class="w-64 bg-slate-800 flex-shrink-0 fixed h-full">
            <div class="p-6">
                <h1 class="text-2xl font-bold mb-8">Music App</h1>
                <!-- Navigation -->
                <nav class="space-y-4">
                    <button onclick="$('#main').show(); $('#playlists').hide();" class="flex items-center space-x-3 text-gray-300 hover:text-white">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </button>
                    <a href="#" class="flex items-center space-x-3 text-gray-300 hover:text-white">
                        <i class="fas fa-search"></i>
                        <span>Search</span>
                    </a>
                    <button onclick="$('#main').hide(); $('#playlists').show();" class="flex items-center space-x-3 text-gray-300 hover:text-white">
                        <i class="fas fa-book"></i>
                        <span>Your Library</span>
                    </button>
                </nav>

                <div class="mt-8">
                    <h2 class="text-gray-400 uppercase text-sm font-bold mb-4">Playlists</h2>
                    <div class="space-y-3" id="playlist-list">
                        <button onclick="$('#create-playlist-modal').show()" class="flex items-center space-x-3 text-gray-300 hover:text-white" id="create-playlist-btn">
                            <i class="fas fa-plus-square"></i>
                            <span>Create Playlist</span>
                        </button>
                        <a href="#" class="flex items-center space-x-3 text-gray-300 hover:text-white">
                            <i class="fas fa-heart"></i>
                            <span>Liked Songs</span>
                        </a>
                        <!-- Existing playlists will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- User Profile -->
            <div class="absolute bottom-0 w-full p-6 bg-slate-800 pb-32">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-gray-600 flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <span class="text-sm font-medium"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="logout.php" class="ml-auto text-gray-400 hover:text-white">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div id="main" class="flex-1 ml-64 flex flex-col pb-28">
            <!-- Top Bar -->
            <div class="bg-slate-800/60 sticky top-0 z-10 p-4 backdrop-blur-md">
                <div class="flex items-center">
                    <div class="flex-1">
                        <input type="text" id="search" placeholder="Search for music..." 
                               class="w-96 px-4 py-2 rounded-full bg-slate-700 text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-400 hover:text-white">
                            <i class="fas fa-bell"></i>
                        </button>
                        <button class="text-gray-400 hover:text-white">
                            <i class="fas fa-cog"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-8">
                <h2 class="text-2xl font-bold mb-6">Featured Tracks</h2>
                <div id="music-list" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    <!-- Music items will be populated here -->
                </div>
            </div>
        </div>
        <div id="playlists" class="flex-1 ml-64 flex flex-col pb-28">
            <h1 class="p-4 text-xl font-bold">Your playlists</h1>


        </div>
    </div>

    <!-- Create Playlist Modal  -->
    <div id="create-playlist-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
    <div class="slate p-6 rounded-lg w-96">
        <h3 class="text-xl font-bold mb-4">Create New Playlist</h3>
        <form id="create-playlist-form">
            <input type="text" id="playlist-name" class="w-full px-4 py-2 rounded bg-slate-700 text-gray-700 mb-4" 
                   placeholder="Playlist name" required>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="$('#create-playlist-modal').hide()" 
                        class="px-4 py-2 rounded bg-slate-700 hover:bg-slate-600">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-500 hover:bg-green-400">Create</button>
            </div>
        </form>
    </div>
</div>

    <!-- Now Playing Bar -->
    <div class="fixed bottom-0 left-0 right-0 bg-slate-800 border-t border-slate-700 p-4">
        <div class="max-w-screen-2xl mx-auto flex items-center justify-between">
            <!-- Track Info -->
            <div class="flex items-center space-x-4 w-1/4">
                <div id="current-track-image" class="w-14 h-14 bg-slate-700 rounded"></div>
                <div>
                    <h4 id="current-track" class="font-medium">No track selected</h4>
                    <p id="current-artist" class="text-sm text-gray-400">Select a track to play</p>
                </div>
                <button class="text-gray-400 hover:text-white">
                    <i class="far fa-heart"></i>
                </button>
            </div>

            <!-- Player Controls -->
            <div class="flex-1 flex flex-col items-center">
               
                <audio id="audio-player" controls class="w-full h-5 rounded"></audio>
            </div>

            <!-- Volume Controls -->
            <div class="w-1/4 flex items-center justify-end space-x-4">
                <button class="text-gray-400 hover:text-white">
                    <i class="fas fa-list"></i>
                </button>
                <button class="text-gray-400 hover:text-white">
                    <i class="fas fa-volume-up"></i>
                </button>
                <input type="range" class="w-24">
            </div>
        </div>
    </div>

    <!-- Edit Playlist Modal -->
<div id="edit-playlist-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center" style="display: none;">
    <div class="slate p-6 rounded-lg w-96">
        <h3 class="text-xl font-bold mb-4">Edit Playlist</h3>
        <form id="edit-playlist-form">
            <input type="text" id="edit-playlist-name" class="w-full px-4 py-2 rounded bg-slate-700 text-gray-700 mb-4" 
                   placeholder="New Playlist Name" required>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="$('#edit-playlist-modal').hide()" 
                        class="px-4 py-2 rounded bg-slate-700 hover:bg-slate-600">Cancel</button>
                <button type="submit" class="px-4 py-2 rounded bg-green-500 hover:bg-green-400">Save</button>
            </div>
        </form>
    </div>
</div>

 <!-- Delete playlist modal  -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="slate p-6 rounded-md shadow-lg w-96">
        <h2 class="text-xl font-bold mb-4">Are you sure you want to delete this playlist?</h2>
        <div class="flex justify-between">
            <button id="cancelDelete" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-400">Cancel</button>
            <button id="confirmDelete" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-400">Delete</button>
        </div>
    </div>
</div>

    <script>
    $(document).ready(function() {
        const clientId = '2f7dd033';
        
        function searchMusic(query = '') {
            const apiUrl = `https://api.jamendo.com/v3.0/tracks/?client_id=${clientId}&format=json&limit=20&search=${query}`;
            
            $.get(apiUrl, function(data) {
                $('#music-list').empty();
                
                data.results.forEach(track => {
                    const trackCard = `
                        <div class="group playlist-hover bg-slate-800 p-4 rounded-lg hover:bg-slate-700 transition-all duration-300">
                            <div class="relative mb-4">
                                <img src="${track.image}" alt="${track.name}" class="w-full aspect-square object-cover rounded-md">
                                <button class="play-button playlist-play absolute bottom-2 right-2 w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center opacity-0 transform translate-y-2 transition-all duration-300 hover:scale-110 hover:bg-green-400"
                                        data-url="${track.audio}"
                                        data-name="${track.name}"
                                        data-artist="${track.artist_name}"
                                        data-image="${track.image}">
                                    <i class="fas fa-play"></i>
                                </button>
                            </div>
                            <h3 class="font-bold text-white mb-1 truncate">${track.name}</h3>
                            <p class="text-gray-400 text-sm truncate">${track.artist_name}</p>
                        </div>
                    `;
                    $('#music-list').append(trackCard);
                });
            });
        }

    $('#create-playlist-form').on('submit', function (e) {
        e.preventDefault();

        const playlistName = $('#playlist-name').val();

        $.ajax({
            type: "POST",
            url: "playlist_actions.php",
            data: {
                action: "create_playlist",
                name: playlistName
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $('#create-playlist-modal').hide();
                    loadPlaylists();
                    $('#create-playlist-form')[0].reset();

                    // Show success notification
                    showNotification("Playlist created successfully!", "success");
                } else {
                    // Show error notification
                    showNotification("Failed to create playlist: " + response.error, "error");
                }
            },
            error: function () {
                // Show error notification
                showNotification("An error occurred while creating the playlist.", "error");
            }
        });
    });

  function loadPlaylists() {
    $.ajax({
        type: "POST",
        url: "playlist_actions.php",
        data: { action: "get_playlists" },
        dataType: "json",
        success: function (response) {
            if (response.success) {
                const playlists = response.playlists;
                const playlistContainer = $("#playlists");

                playlistContainer.html('<h1 class="p-4 text-xl font-bold">Your playlists</h1>');
                playlists.forEach((playlist) => {
                    const imageUrl = './playlist.jpeg'; 
                    playlistContainer.append(`
                        <div class="p-4 slate-700 rounded-md shadow-md mb-4 flex items-center space-x-4">
                            <img src="${imageUrl}" alt="${playlist.name}" class="w-16 h-16 object-cover rounded-full">
                            <h2 class="text-lg font-semibold flex-1">${playlist.name}</h2>

                            <!-- Edit Button -->
                            <button class="editPlaylist text-yellow-500 hover:text-yellow-400" data-id="${playlist.id}" data-name="${playlist.name}">
                                <i class="fas fa-edit"></i>
                            </button>

                            <!-- Delete Button -->
                            <button data-id="${playlist.id}" class="deletePlaylist text-red-500 hover:text-red-400">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    `);
                });
            } else {
                showNotification("Failed to load playlists", "error");
            }
        },
        error: function () {
            showNotification("An error occurred while fetching playlists.", "error");
        }
    });
}






    // Notification function
    function showNotification(message, type) {
        const notification = $('#notification');
        notification.text(message).removeClass('hidden');

        if (type === "success") {
            notification.addClass('notification-success').removeClass('notification-error');
        } else {
            notification.addClass('notification-error').removeClass('notification-success');
        }

        // Show and then hide after 3 seconds
        notification.css('opacity', '1');
        setTimeout(() => {
            notification.css('opacity', '0');
            setTimeout(() => notification.addClass('hidden'), 500); // Keep hidden after fade out
        }, 3000);
    }



    $('#create-playlist-modal').hide();

$("#playlists").on('click', '.editPlaylist', function() {
    const playlistName = $(this).data('name');
    const playlistId = $(this).data('id');
    editPlaylist(playlistId, playlistName);
});

    function editPlaylist(playlistId, playlistName) {
    $('#edit-playlist-name').val(playlistName); // Assuming you have an input field with id 'edit-playlist-name'
    
    // Show the edit modal
    $('#edit-playlist-modal').show();

    // Add an event listener to handle the form submission
    $('#edit-playlist-form').on('submit', function (e) {
        e.preventDefault();
        const newPlaylistName = $('#edit-playlist-name').val();


        // Make an AJAX call to update the playlist
        $.ajax({
            type: "POST",
            url: "playlist_actions.php",
            data: {
                action: "edit_playlist",
                id: playlistId,
                name: newPlaylistName
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (response.success) {
                    $('#edit-playlist-modal').hide();
                    loadPlaylists();
                    showNotification("Playlist updated successfully!", "success");
                } else {
                    showNotification("Failed to update playlist: " + response.error, "error");
                }
            },
            error: function () {
                showNotification("An error occurred while updating the playlist.", "error");
            }
        });
    });
}


$("#playlists").on('click', '.deletePlaylist', function() {
    const playlistId = $(this).data('id');
    deletePlaylist(playlistId);
});

let playlistIdToDelete = null;

function deletePlaylist(playlistId) {
    playlistIdToDelete = playlistId;  // Store the playlist ID to be deleted
    // Show the confirmation modal
    $("#deleteModal").removeClass("hidden");
}

// Event listener for the Cancel button
$("#cancelDelete").on('click', function() {
    $("#deleteModal").addClass("hidden"); // Hide the modal when cancel is clicked
});

// Event listener for the Confirm button
$("#confirmDelete").on('click', function() {
    if (playlistIdToDelete !== null) {
        $.ajax({
            type: "POST",
            url: "playlist_actions.php",
            data: { 
                action: "delete_playlist", 
                playlist_id: playlistIdToDelete 
            },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    showNotification("Playlist deleted successfully!", "success");
                    loadPlaylists(); // Reload playlists to reflect changes
                } else {
                    showNotification("Failed to delete playlist", "error");
                }
                // Close the modal after action
                $("#deleteModal").addClass("hidden");
            },
            error: function () {
                showNotification("An error occurred while deleting the playlist.", "error");
                // Close the modal after error
                $("#deleteModal").addClass("hidden");
            }
        });
    }
});


        // Initial load
        searchMusic();
        loadPlaylists();

        function hidehome(){
            
        }

        $('#playlists').hide();


        // Search input handler
        let searchTimeout;
        $('#search').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchMusic($(this).val());
            }, 500);
        });

        // Play button handler
        $(document).on('click', '.play-button', function() {
            const audioPlayer = $('#audio-player')[0];
            const trackUrl = $(this).data('url');
            const trackName = $(this).data('name');
            const artistName = $(this).data('artist');
            const trackImage = $(this).data('image');

            $('#current-track').text(trackName);
            $('#current-artist').text(artistName);
            $('#current-track-image').css('background-image', `url(${trackImage})`);
            $('#current-track-image').css('background-size', 'cover');
            audioPlayer.src = trackUrl;
            audioPlayer.play();
        });
    });
    </script>
</body>
</html>