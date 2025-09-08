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
        'API Authentication',
        'API Authentication',
        'manage_options',
        'api-authentication',
        'render_api_authentication_page', 
    );
}


// Function to get Laravel API all users
function get_laravel_api_users (): mixed {
    // Get api token
    $api_token = get_option('laravel_api_token');

    $response = wp_remote_get('http://127.0.0.1:8000/users/get', [
        'headers' => [
              'Authorization'  => 'Bearer ' . $api_token,
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


// Function to get Laravel API single user
function get_laravel_api_single_user ($user_id) {
    // Get singin token
    $api_token = get_option('laravel_api_token');

    $response = wp_remote_get('http://127.0.0.1:8000/user/get/'.$user_id, [
        'headers' => [
            'Authorization' => 'Bearer ' . $api_token,
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


// Function to render the api-authentication admin page
function render_api_authentication_page () {
    if (isset($_POST['generate_token'])) {
        // Accepting and validating input
        $name = sanitize_text_field($_POST['laravel_name']);
        $email = sanitize_email($_POST['laravel_email']);
        $phone = sanitize_text_field($_POST['laravel_phone']);
        $password = sanitize_text_field($_POST['laravel_password']);

        // Combining user auth data
        $user_authentication_data = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password
        ];

        // Sending request and receiving response
        $response = wp_remote_request('http://127.0.0.1:8000/user/generate_token', [
            'method' => 'POST',
            'body' => $user_authentication_data,
        ]);

        if (!is_wp_error($response)) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            if (isset($data['api_token'])) {
                update_option('laravel_api_token', $data['api_token']);
                echo '<div class="notice notice-success"><p>Token Saved Successfully!</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>Login failed</p></div>';
            }

        }
    }

    // Functionality to update api token by API request
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_token'])) {
        // Get api token
        $api_token = get_option('laravel_api_token');

        $user_id = intval($_POST['laravel_user_id']); // Getting user id

        // Getting updated data
        $updated_data = [
            'name' => sanitize_text_field($_POST['laravel_name']),
            'email' => sanitize_email($_POST['laravel_email']),
            'phone' => sanitize_text_field($_POST['laravel_phone'])
        ];

        // Laravel API call
        $response = wp_remote_post('http://127.0.0.1:8000/user/update_token/'.$user_id, [
            'method' => 'POST',
            'body' => $updated_data,
        ]);

        // Error check
        if (!is_wp_error($response)) {
                $body = wp_remote_retrieve_body($response);
                $data = json_decode($body, true);

                if (isset($data['api_token'])) {
                    // global $wpdb;
                    // $option_table = $wpdb->prefix . 'options';
                    // $exist_token = $wpdb->get_results("SELECT * FROM $option_table WHERE option_name = 'laravel_api_token' LIMIT 1", ARRAY_A );
                    // $api_token = $exist_token[0]['option_value'];
                    // echo $data['api_token'] . '</br></br>';
                    // echo $api_token;
                    // if ($data['api_token'] == $api_token) {
                    //     echo "Same";
                    // } else {
                    //     echo "Different";
                    // }


                    update_option('laravel_api_token', $data['api_token']);
                    echo '<div class="notice notice-success"><p>Token Saved Successfully!</p></div>';
                } else {
                    echo '<div class="notice notice-error"><p>Login failed</p></div>';
                }

            }
    }

    // Render new generate token form
    ?>
    <div class="wrap">
        <h1>Laravel API Autentication Information</h1>
        <form action="" method="POST">
        <?php
            global $wpdb;
            $option_table = $wpdb->prefix . 'options';

            $exist_token = $wpdb->get_results("SELECT * FROM $option_table WHERE option_name = 'laravel_api_token' LIMIT 1", ARRAY_A );

            $api_token = $exist_token[0]['option_value']; // Store Api token
                
            // Fetch user data from laravel api matching the API token
            $response = wp_remote_get('http://127.0.0.1:8000/user/get_auth_data/' . $api_token);
            $body = wp_remote_retrieve_body($response);
            $user_auth_data = json_decode($body);
            
            // Check if token existing or not in the wp_options table
            if ($exist_token && !empty($user_auth_data)) {
                // print_r($user_auth_data);
       ?>
            <?php if ($user_auth_data->token_status !== 'Unauthorized') { ?>
                <div class="notice notice-success">
                    <p>Token Active</p>
                </div>
            <?php } else { ?>
                <div class="notice notice-error">
                    <p>Token Expired</p>
                </div>
            <?php } ?>
            
            <input type="hidden" name="laravel_user_id" value="<?php echo esc_html($user_auth_data->data->id); ?>">
            <p>
                <label>Name:</label><br>
                <input style="width: 50%; padding: 2px 10px;" type="text" name="laravel_name" value="<?php echo esc_html($user_auth_data->data->name); ?>" required>
            </p>
            <p>
                <label>Email:</label><br>
                <input style="width: 50%; padding: 2px 10px;" type="email" name="laravel_email" value="<?php echo esc_html($user_auth_data->data->email); ?>" required>
            </p>
            <p>
                <label>Phone:</label><br>
                <input style="width: 50%; padding: 2px 10px;" type="text" name="laravel_phone" value="<?php echo esc_html($user_auth_data->data->phone); ?>" required>
            </p>
            <p>
                <label>Token:</label><br>
                <textarea rows="8" style="width: 50%; padding: 2px 10px;" name="laravel_token">
                    <?php echo esc_html($user_auth_data->data->api_token); ?>
                </textarea>
            </p>
            <p>
                <button type="submit" name="update_token" class="button button-primary">Update Token</button>
            </p>
       <?php
            } else {
        ?>
            <p>
                <label>Name:</label><br>
                <input style="width: 50%; padding: 2px;" type="text" name="laravel_name" required>
            </p>
            <p>
                <label>Email:</label><br>
                <input style="width: 50%; padding: 2px;" type="email" name="laravel_email" required>
            </p>
            <p>
                <label>Phone:</label><br>
                <input style="width: 50%; padding: 2px;" type="text" name="laravel_phone" required>
            </p>
            <p>
                <label>Password:</label><br>
                <input style="width: 50%; padding: 2px;" type="password" name="laravel_password" required>
            </p>
            <p>
                <label>Token:</label><br>
                <textarea rows="8" style="width: 50%; padding: 2px;" name="laravel_token"></textarea>
            </p>
            <p>
                <button type="submit" name="generate_token" class="button button-primary">Generate Token</button>
            </p>
        <?php } ?>    
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
                        <form action="" method="POST" enctype="multipart/form-data">
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
                <form action="" method="POST" enctype="multipart/form-data">
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
    // Get signin token
    $api_token = get_option('laravel_api_token');

    $user_id = intval($_POST['id']); // Getting user id

    // Getting updated data
    $updated_data = [
        'name' => sanitize_text_field($_POST['name']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone'])
    ];

    // Check image upload and prepare for multipart/form-data
    if (!empty($_FILES['image']['name'])) {
        $updated_data['image'] = curl_file_create($_FILES['image']['tmp_name'], $_FILES['image']['type'], $_FILES['image']['name']);
    }

    // Laravel API call
    $response = wp_remote_post('http://127.0.0.1:8000/user/put/'.$user_id, [
        'body'    => $updated_data,
        'headers' => [
            'Authorization' => 'Bearer ' . $api_token,
        ]
    ]);

    // Error check
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if ($data['status'] == 'success') {
            echo '<div class="notice notice-success"><p>User updated: '.esc_html($data['message']).'</p></div>';
        } else {
            // Check for a specific error message and display a custom message
            if (isset($data['exception_error'])) {
                 echo '<div class="notice notice-error"><p>Update failed: '. esc_html($data['exception_error']) .'</p></div>';
            } else {
                 echo '<div class="notice notice-error"><p>Update failed: '. esc_html($data['message']) .'</p></div>';
            }
           
        }
    }
}


// Functionality to delete API user
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    // Get singin token
    $api_token = get_option('laravel_api_token');

    $delet_user_id = intval($_GET['id']);

    // Laravel API call
    $response = wp_remote_request('http://127.0.0.1:8000/user/delete/'.$delet_user_id, [
        'method' => 'DELETE',
        'headers' => [
            'Authorization' => 'Bearer ' . $api_token,
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
    $api_token = get_option('laravel_api_token');

    // Getting insert data
    $insert_data = [
        'name' => sanitize_text_field($_POST['name']),
        'email' => sanitize_email($_POST['email']),
        'phone' => sanitize_text_field($_POST['phone']),
    ];

    // Check image upload and prepare for multipart/form-data
    if (!empty($_FILES['image']['name'])) {
        $insert_data['image'] = curl_file_create($_FILES['image']['tmp_name'], $_FILES['image']['type'], $_FILES['image']['name']);
    }

    // Laravel API call
    $response = wp_remote_post('http://127.0.0.1:8000/user/post/', [
        'body' => $insert_data,
        'headers' => [
            'Authorization' => 'Bearer ' . $api_token,
        ]
    ]);

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