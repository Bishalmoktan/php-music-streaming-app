<?php
require_once 'config.php';
require_once 'auth_check.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$userId = $_SESSION['user_id'];

switch ($action) {
    case 'create_playlist':
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $sql = "INSERT INTO playlists (user_id, name) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $userId, $name);
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'id' => mysqli_insert_id($conn)]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to create playlist']);
        }
        break;

        $trackUrl = mysqli_real_escape_string($conn, $_POST['track_url']);
        $trackName = mysqli_real_escape_string($conn, $_POST['track_name']);
        $artistName = mysqli_real_escape_string($conn, $_POST['artist_name']);
        $trackImage = mysqli_real_escape_string($conn, $_POST['track_image']);

        // Check if already liked
        $sql = "SELECT id FROM liked_songs WHERE user_id = ? AND track_url = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $userId, $trackUrl);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            // Unlike
            $sql = "DELETE FROM liked_songs WHERE user_id = ? AND track_url = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "is", $userId, $trackUrl);
            $liked = false;
        } else {
            // Like
            $sql = "INSERT INTO liked_songs (user_id, track_url, track_name, artist_name, track_image) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "issss", $userId, $trackUrl, $trackName, $artistName, $trackImage);
            $liked = true;
        }

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'liked' => $liked]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update like status']);
        }
        break;

    case 'get_playlists':
        $sql = "SELECT id, name FROM playlists WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $playlists = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $playlists[] = $row;
        }
        echo json_encode(['success' => true, 'playlists' => $playlists]);
        break;
   
    case 'edit_playlist':
    $playlistId = (int)$_POST['id'];
    $newName = mysqli_real_escape_string($conn, $_POST['name']);

    $sql = "UPDATE playlists SET name = ? WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $newName, $playlistId, $userId);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update playlist']);
    }
    break;

    case 'delete_playlist':
    $playlistId = (int)$_POST['playlist_id'];

    $sql = "DELETE FROM playlists WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $playlistId, $userId);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete playlist']);
    }
    break;
}
?>