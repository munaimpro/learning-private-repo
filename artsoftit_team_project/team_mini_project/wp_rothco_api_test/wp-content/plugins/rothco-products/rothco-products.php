<?php
/** 
 * Plugin name: Rothco Products
 * Plugin URI: 
 * Description: This is a plugin for dropship selling of Rothco.com products
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.2
 * License: GPLv2 or later
 * Text Domain: apiuser
*/

// Function to add api user custom admin page on plugin activate
add_action('admin_menu', 'register_admin_rothco_products_page');
function register_admin_rothco_products_page () {
    add_menu_page(
        page_title: 'Rothco Products',
        menu_title: 'Rothco Products',
        capability: 'manage_options',
        menu_slug: 'rothco-products',
        callback: 'render_admin_rothco_api_product_page',
        icon_url: 'dashicons-archive',
        position: 6
    );

    add_submenu_page(
        'rothco-products',
        'API Authentication',
        'API Authentication',
        'manage_options',
        'api-authentication',
        'render_admin_rothco_api_authentication_page', 
    );
}

// Function to render the Rothco API authentication admin page
function render_admin_rothco_api_authentication_page()
{
    if (isset($_POST['generate_token'])) {
        // Accepting and validating input
        $name = sanitize_text_field($_POST['laravel_name']);
        $email = sanitize_email($_POST['laravel_email']);
        $phone = sanitize_text_field($_POST['laravel_phone']);
        $password = sanitize_text_field($_POST['laravel_password']);

        // Combining user auth data
        $user_authentication_data = [
            'name'     => $name,
            'email'    => $email,
            'phone'    => $phone,
            'password' => $password,
        ];

        // Sending request and receiving response
        $response = wp_remote_request('http://127.0.0.1:8000/user/generate_token', [
            'method' => 'POST',
            'body'   => $user_authentication_data,
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
            'name'  => sanitize_text_field($_POST['laravel_name']),
            'email' => sanitize_email($_POST['laravel_email']),
            'phone' => sanitize_text_field($_POST['laravel_phone']),
        ];

        // Laravel API call
        $response = wp_remote_post('http://127.0.0.1:8000/user/update_token/' . $user_id, [
            'method' => 'POST',
            'body'   => $updated_data,
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
        <h1>Rothco API Token</h1>
        <form action="" method="POST">
        <?php
global $wpdb;
    $option_table = $wpdb->prefix . 'options';

    $exist_token = $wpdb->get_results("SELECT * FROM $option_table WHERE option_name = 'rothco_api_token' LIMIT 1", ARRAY_A);

    // $api_token = $exist_token[0]['option_value']; // Store Api token
    $api_token = 'aXxuFixUO1TJtA0azsTrwJiq2Z3fS3DQR7UV6BDq'; // Store Api token

    // Fetch user data from laravel api matching the API token
    $response = wp_remote_get('http://127.0.0.1:8000/user/get_auth_data/' . $api_token);
    $body = wp_remote_retrieve_body($response);
    $user_auth_data = json_decode($body);

    // Check if token existing or not in the wp_options table
    if ($exist_token && !empty($user_auth_data)) {
        // print_r($user_auth_data);
        ?>
            <?php if ($user_auth_data->token_status !== 'Unauthorized') {?>
                <div class="notice notice-success">
                    <p>Token Active</p>
                </div>
            <?php } else {?>
                <div class="notice notice-error">
                    <p>Token Expired</p>
                </div>
            <?php }?>

            <input type="hidden" name="laravel_user_id" value="<?php echo esc_html($user_auth_data->data->id); ?>">
            <!-- <p>
                <label>Name:</label><br>
                <input style="width: 50%; padding: 2px 10px;" type="text" name="laravel_name" value="<?php echo esc_html($user_auth_data->data->name); ?>" required>
            </p> -->
            <!-- <p>
                <label>Email:</label><br>
                <input style="width: 50%; padding: 2px 10px;" type="email" name="laravel_email" value="<?php echo esc_html($user_auth_data->data->email); ?>" required>
            </p> -->
            <!-- <p>
                <label>Phone:</label><br>
                <input style="width: 50%; padding: 2px 10px;" type="text" name="laravel_phone" value="<?php echo esc_html($user_auth_data->data->phone); ?>" required>
            </p> -->
            <p>
                <label>Token:</label><br>
                <textarea rows="8" style="width: 50%; padding: 2px 10px;" name="laravel_token">
                    <?php echo esc_html($user_auth_data->data->api_token); ?>
                </textarea>
            </p>
            <!-- <p>
                <button type="submit" name="update_token" class="button button-primary">Update Token</button>
            </p> -->
       <?php
} else {
        ?>
            <!-- <p>
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
            </p> -->
            <p>
                <label>Token:</label><br>
                <textarea rows="8" style="width: 50%; padding: 2px;" name="laravel_token">aXxuFixUO1TJtA0azsTrwJiq2Z3fS3DQR7UV6BDq</textarea>
            </p>
            <!-- <p>
                <button type="submit" name="generate_token" class="button button-primary">Generate Token</button>
            </p> -->
        <?php }?>
        </form>
    </div>
    <?php
}

// Function to get Laravel API all users
function get_rothco_api_products(): mixed
{
    // Get api token
    $api_token = get_option('laravel_api_token');

    $response = wp_remote_get('https: //www.rothco.com/api/products/items?fields=short_description,categories,rating&format=json&key=aXxuFixUO1TJtA0azsTrwJiq2Z3fS3DQR7UV6BDq
', [
        'headers' => [
            // 'Authorization' => 'Bearer ' . $api_token,
            'Accept'        => 'application/json',
        ],
    ]);

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // print_r($data);

    if (isset($data['items'])) {
        return $data['items'];
    }

    return false;
}


// Function to render the external-users admin page
function render_admin_rothco_api_product_page(): void
{
    $products = get_rothco_api_products();

    ?>
        <div class="wrap">
            <!-- Edit user details on click edit button -->
        <?php
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
        $user_id = intval($_GET['id']);
        $single_user = get_laravel_api_single_user($user_id);

        if ($single_user) {?>
                    <div id="editUserForm" style="margin-bottom:20px;">
                        <h1>Edit User</h1>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="editUserId" name="id" value="<?php echo esc_html($single_user['id']); ?>">
                            <p>
                                <label>Name:</label><br>
                                <input type="text" id="editUserName" name="name" value="<?php echo esc_html($single_user['name']) ?>">
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
        <?php }
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
        <?php }?>

            <h1>Rothco Products</h1>

            <!-- <a style="margin-bottom: 20px; margin-top: 30px;" href="?page=api-users&action=add" class="button button-primary">New User</a> -->

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Item Name</th>
                        <th>Short Description</th>
                        <th>Categories</th>
                        <th>Rating</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
if ($products) {
        foreach ($products as $product) {
            echo '<tr>';
            echo '<td>' . esc_html($product['item_index']) . '</td>';
            // echo '<td>' . '<img width="50px" height="50px" src="http://127.0.0.1:8000/storage/' . $user['image'] . '" alt="profile_image" />' . '</td>';
            echo '<td>' . esc_html($product['item_name']) . '</td>';
            echo '<td>' . esc_html($product['item_short_desc']) . '</td>';
            echo '<td>' . esc_html($product['categories'][0]) . '</td>';
            echo '<td>' . esc_html($product['rating']) . '</td>';
            echo '<td>
                    <a href="?page=api-users&action=edit&id=' . $product['item_index'] . '" class="button">Edit</a>
                    <a href="?page=api-users&action=delete&id=' . $product['item_index'] . '" class="button delete-user">Delete</a>
                </td>';
        }
    } else {
        echo "<tr>
                                    <td colspan='5'>No products found.</td>
                                </tr>";
    }
    ?>
                </tbody>
            </table>
        </div>
    <?php
}
?>