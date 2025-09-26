<?php
/** 
 * Plugin Name: CV Villas API
 * Plugin URI: 
 * Description: Fetch property list data from external CV Villas API
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.2
 * License: GPLv2 or later
 * Text Domain: apiuser
*/

// Register Admin Menu
add_action('admin_menu', 'register_admin_api_user_page');
function register_admin_api_user_page() {
    add_menu_page(
        'CV Villas Property', // Page Title
        'CV Villas Property', // Menu Title
        'manage_options',
        'cv-villas-property',
        'render_admin_page',
        'dashicons-admin-users',
        6
    );
}

// Fetch properties from API
function get_cvvillas_api_properties(): mixed {
    $api_token = "0d7bb75b-3821-475e-856b-bd9678979az2";

    $response = wp_remote_get("https: //www.cvvillas.com/umbraco/api/travel/gettravelitems/?apiKey=0d7bb75b-3821-475e-856b-bd9678979az2", [
        'headers' => [
            'Accept' => 'application/json',
        ],
        'timeout' => 120,
    ]);

    if (is_wp_error($response)) {
        echo '<pre>';
        print_r($response);
        echo '</pre>';
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // echo $data['Result'];
    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';

    // API returns an array of villas directly
    if (is_array($data)) {
        return $data;
    }

    return false;
}

// Render Admin Page
function render_admin_page(): void {
    $properties = get_cvvillas_api_properties();
    ?>
    <div class="wrap">
        <h1>CV Villas Property</h1>
        <p>Use the shortcode <strong>[cvvillas-property-list]</strong> to load these data on the frontend</p>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Beds</th>
                    <th>Baths</th>
                    <th>Sleeps</th>
                    <th>From Price</th>
                    <th>Availability</th>
                    <th>Features</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($properties['Result']['TravelItems']['TravelItem']) {
                    $villas = $properties['Result']['TravelItems']['TravelItem'];
                    foreach ($villas as $villa) {
                        $name = $villa['Name'] ?? '';
                        $location = $villa['Location'] ?? '';
                        $country = $villa['Country']['Name'] ?? '';
                        $beds = $villa['BedCount'] ?? 'N/A';
                        $baths = $villa['BathroomCount'] ?? 'N/A';
                        $sleeps = $villa['SleepNumber'] ?? 'N/A';
                        $price = $villa['FromPrice'] ?? '';
                        $availability = $villa['AvailabilityText'] ?? '';
                        $image = $villa['Gallery']['Item'][0] ?? '';
                        $features = isset($villa['KeyFeatures']['Item']) ? implode(', ', $villa['KeyFeatures']['Item']) : '';

                        echo "<tr>
                            <td><img src='{$image}' width='100' /></td>
                            <td>{$name}</td>
                            <td>{$location}, {$country}</td>
                            <td>{$beds}</td>
                            <td>{$baths}</td>
                            <td>{$sleeps}</td>
                            <td>£{$price}</td>
                            <td>{$availability}</td>
                            <td>{$features}</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No property found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}
