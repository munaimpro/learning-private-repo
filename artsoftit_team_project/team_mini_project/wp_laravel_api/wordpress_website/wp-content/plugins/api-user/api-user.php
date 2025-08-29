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


// Function to enque scripts
add_action('admin_enqueue_scripts', 'api_user_enqueue_scripts');
function api_user_enqueue_scripts () {
    // Axios CDN enque
    wp_enqueue_script('axios_cdn', 'https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js',[], '1.0.0', true);

    // Custom JS enqueue
    wp_enqueue_script('custom_js', plugin_dir_url(__FILE__).'assets/js/api-user.js',['axios_cdn'], '1.0.0', true);
}


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

    add_submenu_page(
        'api-users',
        'API Signin',
        'API Signin',
        'manage_options',
        'api-signin',
        'render_api_signin_page', 
    );
}


// Function to get Laravel API all users
function get_laravel_api_users (): mixed {
    // Get singin token
    $signin_token = get_option('laravel_api_signin_token');

    $response = wp_remote_get('http://127.0.0.1:8000/users/get', [
        'headers' => [
              'Authorization'  => 'Bearer ' . $signin_token,
              'Accept' => 'application/json'
        ]
    ]);
    
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


// Function to get single Laravel API single user
function get_laravel_api_single_user ($user_id) {
    // Get singin token
    $signin_token = get_option('laravel_api_signin_token');

    $response = wp_remote_get('http://127.0.0.1:8000/user/get/'.$user_id, [
        'headers' => [
            'Authorization' => 'Bearer ' . $signin_token,
            'Accept' => 'application/json'
        ]
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($data['data'])) {
        return $data['data'];
    }

    return false;
}


// Function to render the api-signin admin page
function render_api_signin_page () {
    if (isset($_POST['laravel_phone'], $_POST['laravel_password'])) {
        // Accepting and validating input
        $phone = sanitize_text_field($_POST['laravel_phone']);
        $password = sanitize_text_field($_POST['laravel_password']);

        // Combining user auth data
        $user_signin_data = [
            'phone' => $phone,
            'password' => $password
        ];

        // Sending request and receiving response
        $response = wp_remote_request('http://127.0.0.1:8000/user/signin', [
            'method' => 'POST',
            'body' => $user_signin_data,
        ]);

        if (!is_wp_error($response)) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if (isset($data['signin_token'])) {
                update_option('laravel_api_signin_token', $data['signin_token']);
                echo '<div class="notice notice-success"><p>Token Saved Successfully!</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>Login failed</p></div>';
            }
        }
    }

    // Render signin form
    ?>
    <div class="wrap">
        <h1>Laravel API Signin</h1>
        <form action="" method="POST">
            <p>
                <label>Phone:</label><br>
                <input type="text" name="laravel_phone" required>
            </p>
            <p>
                <label>Password:</label><br>
                <input type="password" name="laravel_password" required>
            </p>
            <p>
                <button type="submit" class="button button-primary">Login & Save Token</button>
            </p>
        </form>
    </div>
    <?php
}


// Function to render the external-users admin page
function render_admin_page (): void {
    $users = get_laravel_api_users();

    ?>
        <div class="wrap">
            <!-- Edit user details on click edit button -->
        <?php
            if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
                $user_id = intval($_GET['id']);
                $single_user = get_laravel_api_single_user($user_id);

                if ($single_user) { ?>
                    <div id="editUserForm" style="margin-bottom:20px;">
                        <h1>Edit User</h1>
                        <form action="" method="POST">
                            <input type="hidden" id="editUserId" name="id" value="<?php echo esc_html($single_user['id']); ?>">
                            <p>
                                <label>Name:</label><br>
                                <input type="text" id="editUserName" name="name" value="<?php echo esc_html($single_user['name'])?>">
                            </p>
                            <p>
                                <label>Email:</label><br>
                                <input type="email" id="editUserEmail" name="email" value="<?php echo esc_html($single_user['email']); ?>">
                            </p>
                            <p>
                                <label>Phone:</label><br>
                                <input type="text" id="editUserPhone" name="phone" value="<?php echo esc_html($single_user['phone']); ?>">
                            </p>
                            <p>
                                <label>Profile Image:</label><br>
                                <img id="editUserImagePreview" src="http://127.0.0.1:8000/storage/<?php echo esc_html($single_user['image']); ?>" alt="Preview" width="80"><br><br>
                                <input type="file" id="editUserImage" name="image">
                            </p>
                            <p>
                                <button type="submit" name="updateUser" class="button button-primary">Update User</button>
                                <button type="button" id="cancelEdit" class="button">Cancel</button>
                            </p>
                        </form>
                    </div>
        <?php   }
            }
        ?>

        <?php
            if (isset($_GET['action']) && $_GET['action'] === 'add') {
        ?>
            <div id="editUserForm" style="margin-bottom:20px;">
                <h1>Add New User</h1>
                <form action="" method="POST">
                    <p>
                        <label>Name:</label><br>
                        <input type="text" id="addUserName" name="name">
                    </p>
                    <p>
                        <label>Email:</label><br>
                        <input type="email" id="addUserEmail" name="email">
                    </p>
                    <p>
                        <label>Phone:</label><br>
                        <input type="text" id="addUserPhone" name="phone">
                    </p>
                    <p>
                        <label>Profile Image:</label><br>
                        <input type="file" id="addUserImage" name="image">
                    </p>
                    <p>
                        <button type="submit" name="insertUser" class="button button-primary">Add User</button>
                        <button type="button" id="cancelEdit" class="button">Cancel</button>
                    </p>
                </form>
            </div>
        <?php } ?>

            <h1>API Users</h1>
            <p>Use the shortcode <strong>[laravel-api-users]</strong> to load these data on the frontend</p>

            <a style="margin-bottom: 20px; margin-top: 30px;" href="?page=api-users&action=add" class="button button-primary">New User</a>

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
                                        <a href="?page=api-users&action=edit&id=' . $user['id'] . '" class="button">Edit</a>
                                        <a href="?page=api-users&action=delete&id=' . $user['id'] . '" class="button delete-user">Delete</a>
                                    </td>';
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


// Functionality to update user details by API request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateUser'])) {
    // Get singin token
    $signin_token = get_option('laravel_api_signin_token');

    $user_id = intval($_POST['id']); // Getting user id

    // Getting updated data
    $updated_data = [
        'name' => sanitize_text_field($_POST['name']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone'])
    ];

    // Check image upload
    if (!empty($_FILES['image']['name'])) {
        
    }

    // Laravel API call
    $response = wp_remote_request('http://127.0.0.1:8000/user/put/'.$user_id, [
        'body' => $updated_data,
        'method' => 'POST',
        'headers' => [
            'Authorization' => 'Bearer ' . $signin_token,
            'Accept' => 'application/json'
        ]
    ] );

    // Error check
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($data['status'] == 'success') {
            echo '<div class="notice notice-success"><p>User updated: '.esc_html($data['message']).'</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Update failed: '. esc_html($data['message']) .'</p></div>';
        }
    }




} 


// Functionality to delete API user
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    // Get singin token
    $signin_token = get_option('laravel_api_signin_token');

    $delet_user_id = intval($_GET['id']);

    // Laravel API call
    $response = wp_remote_request('http://127.0.0.1:8000/user/delete/'.$delet_user_id, [
        'method' => 'DELETE',
        'headers' => [
            'Authorization' => 'Bearer ' . $signin_token,
            'Accept' => 'application/json'
        ]
    ]);
    
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['status']) && $data['status'] === 'success') {
            echo '<div class="notice notice-success"><p>Delete success: ' . esc_html($data['message']) . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Delete failed: ' . esc_html($data['message'] ?? 'Unknown error') . '</p></div>';
        }
    } else {
        echo '<div class="notice notice-error"><p>Request failed: ' . esc_html($response->get_error_message()) . '</p></div>';
    }
}


// Functionality to add user by API request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertUser'])) {
    // Get singin token
    $signin_token = get_option('laravel_api_signin_token');

    // Getting insert data
    $insert_data = [
        'name' => sanitize_text_field($_POST['name']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
    ];

    // Check image upload
    if (!empty($_FILES['image']['name'])) {
        
    }

    // Laravel API call
    $response = wp_remote_request('http://127.0.0.1:8000/user/post/', [
        'body' => $insert_data,
        'method' => 'POST',
        'headers' => [
            'Authorization' => 'Bearer ' . $signin_token,
            'Accept' => 'application/json'
        ]
    ] );

    // Error check
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($data['status'] == 'success') {
            echo '<div class="notice notice-success"><p>User created: '.esc_html($data['message']).'</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Creation failed: '. esc_html($data['message']) .'</p></div>';
        }
    }




} 
















// Register shortcode to display API users on the frontend
add_shortcode('laravel-api-users', 'frontend_users_table_shortcode');
function frontend_users_table_shortcode (): bool|string {
    // Get API users from Laravel API
    $users = get_laravel_api_users();

    // Create table structure rendered with data
    ob_start();
    ?>
    <table>
        <thead style="padding: 10px 20px; border-bottom: 1px solid #ddd;">
            <tr>
                <th style="padding: 10px 20px; border-bottom: 1px solid #ddd;">ID</th>
                <th style="padding: 10px 20px; border-bottom: 1px solid #ddd;">Profile</th>
                <th style="padding: 10px 20px; border-bottom: 1px solid #ddd;">Name</th>
                <th style="padding: 10px 20px; border-bottom: 1px solid #ddd;">Email</th>
                <th style="padding: 10px 20px; border-bottom: 1px solid #ddd;">Phone</th>
                <th style="padding: 10px 20px; border-bottom: 1px solid #ddd;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if ($users){
                    foreach ($users as $user){
                        echo "<tr>
                                <td style='padding:10px 20px; border-bottom: 1px solid #ddd'>" . esc_html($user['id']) . "</td>"
                                .
                                "<td style='padding:10px; border-bottom: 1px solid #ddd'>". "<img width='50px' height='50px' src='http://127.0.0.1:8000/storage/". $user['image'] ."' />" ."</td>"
                                .
                                "<td style='padding:10px; border-bottom: 1px solid #ddd'>". esc_html($user['name']) ."</td>"
                                .
                                "<td style='padding:10px; border-bottom: 1px solid #ddd'>". esc_html($user['email']) ."</td>"
                                .
                                "<td style='padding:10px; border-bottom: 1px solid #ddd'>". esc_html($user['phone']) ."</td>"
                                .
                                "<td style='padding:10px; border-bottom: 1px solid #ddd'>
                                    <a href='?page=external-users&action=edit&id='" . $user['id'] . "class='button'>Edit</a>
                                    <a href='?page=external-users&action=delete&id='" . $user['id'] . "class='button delete-user'>Delete</a>
                                </td>"
                                .
                            "</tr>";
                    }
                } else {
                    echo "<tr>
                            <td colspan='5'>No users found.</td>
                        </tr>";
                } ?>
        </tbody>
    </table>
    <?php
    return ob_get_clean(); // Return buffered content
}

?>