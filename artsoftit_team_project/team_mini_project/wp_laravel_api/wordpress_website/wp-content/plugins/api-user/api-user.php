<?php
/** 
 * Plugin name: API User
 * Plugin URI: 
 * Description: This is a plugin for fetch, update, insert and delete users data from external Laravel API
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.2
 * License: GPLv2 or later
 * Text Domain: apiuser
*/


// Function to add api user custom admin page on plugin activate
add_action('admin_menu', 'register_admin_api_user_page');
function register_admin_api_user_page () {
    add_menu_page(
        page_title: 'Laravel API User',
        menu_title: 'API User',
        capability: 'manage_options',
        menu_slug: 'api-users',
        callback: 'render_admin_page',
        icon_url: 'dashicons-admin-users',
        position: 6
    );
}


// Function to get Laravel API user
function get_laravel_api_users () {
    $response = wp_remote_get('http://127.0.0.1:8000/users/get');
    
    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['data'])) {
        return $data['data'];
    }

    return false;
}


// Function to render the external-users admin page
function render_admin_page () {
    $users = get_laravel_api_users();

    ?>
        <div class="wrap">
            <h1>API Users</h1>
            <!-- <form action="" method="POST">
                <input type="submit" name="fetch_external_users" class="button button-primary" value="Fetch & Save Users">
            </form> -->
            
            <p>Use the shortcode <strong>[laravel-api-users]</strong> to load these data on the frontend</p>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
    
                <tbody>
                    <?php
                        if ($users) {
                            foreach ($users as $user) {
                                echo '<tr>';
                                echo '<td>' . esc_html($user['id']) . '</td>';
                                echo '<td>' . '<img width="50px" height="50px" src="http://127.0.0.1:8000/storage/'. $user['image'] .'" alt="profile_image" />' . '</td>';
                                echo '<td>' . esc_html($user['name']) . '</td>';
                                echo '<td>' . esc_html($user['email']) . '</td>';
                                echo '<td>' . esc_html($user['phone']) . '</td>';
                                echo '<td>
                                        <a href="?page=external-users&action=edit&id=' . $user['id'] . '" class="button">Edit</a>
                                        <a href="?page=external-users&action=delete&id=' . $user['id'] . '" class="button delete-user">Delete</a>
                                    </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo "<tr>
                                    <td colspan='5'>No users found.</td>
                                </tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
}


?>