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


// Function to render the external-users admin page
function render_admin_page () {
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
                        // if ($results) {
                            // foreach ($results as $user) {
                                echo '<tr>';
                                echo '<td>' . esc_html('1') . '</td>';
                                echo '<td>' . esc_html('<img src="">') . '</td>';
                                echo '<td>' . esc_html('Samiul Alam') . '</td>';
                                echo '<td>' . esc_html('samiul@gmail.com') . '</td>';
                                echo '<td>' . esc_html('01823456789') . '</td>';
                                echo '<td>
                                        <a href="?page=external-users&action=edit&id=' . 1 . '" class="button">Edit</a>
                                        <a href="?page=external-users&action=delete&id=' . 1 . '" class="button delete-user">Delete</a>
                                    </td>';
                                echo '</tr>';
                            // }
                        // } else {
                        //     echo "<tr>
                        //             <td colspan='5'>No users found.</td>
                        //         </tr>";
                        // }
                    ?>
                </tbody>
            </table>
        </div>
    <?php
}


?>