<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Elementor_Banner_Block extends \Elementor\Widget_Base {

    public function get_name() {
        return 'banner-block';
    }

    public function get_title() {
        return __( 'Banner Block', 'woostify-child' );
    }

    public function get_icon() {
        return 'eicon-slider-push';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Banner Content', 'woostify-child' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'background_image',
            [
                'label' => __( 'Background Image', 'woostify-child' ),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => 'https://source.unsplash.com/random/1920x1080',
                ],
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __( 'Title', 'woostify-child' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Welcome to Our Platform', 'woostify-child' ),
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __( 'Description', 'woostify-child' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __( 'Discover amazing features and join our community today. Experience seamless performance and stunning visuals on any device.', 'woostify-child' ),
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __( 'Button Text', 'woostify-child' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'Get Started', 'woostify-child' ),
            ]
        );

        $this->add_control(
            'button_link',
            [
                'label' => __( 'Button Link', 'woostify-child' ),
                'type' => \Elementor\Controls_Manager::URL,
                'default' => [
                    'url' => '#get-started',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="banner" style="background: url('<?php echo esc_url($settings['background_image']['url']); ?>') no-repeat center center/cover;">
            <div class="banner-content">
                <h1><?php echo esc_html($settings['title']); ?></h1>
                <p><?php echo esc_html($settings['description']); ?></p>
                <?php if ( ! empty( $settings['button_text'] ) ) : ?>
                    <a href="<?php echo esc_url($settings['button_link']['url']); ?>" class="cta-button">
                        <?php echo esc_html($settings['button_text']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </section>

        <style>
            .banner { position: relative; width: 100%; height: 100vh; display: flex; align-items: center; justify-content: center; color: white; text-align: center; overflow: hidden; }
            .banner::before { content: ''; position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.5); z-index:1; }
            .banner-content { position: relative; z-index:2; padding:20px; max-width:800px; }
            .banner-content h1 { font-size:3rem; margin-bottom:20px; text-shadow:2px 2px 4px rgba(0,0,0,0.5); }
            .banner-content p { font-size:1.2rem; margin-bottom:30px; line-height:1.6; }
            .banner-content .cta-button { display:inline-block; padding:15px 30px; background-color:#ff6f61; color:white; text-decoration:none; font-size:1.1rem; border-radius:5px; transition: background-color 0.3s ease, transform 0.3s ease; }
            .banner-content .cta-button:hover { background-color:#e55a50; transform: scale(1.05); }
            @media (max-width:768px){ .banner-content h1{ font-size:2rem; } .banner-content p{ font-size:1rem; } .banner-content .cta-button{ padding:12px 25px; font-size:1rem; } }
            @media (max-width:480px){ .banner{ height:80vh; } .banner-content h1{ font-size:1.5rem; } .banner-content p{ font-size:0.9rem; } .banner-content .cta-button{ padding:10px 20px; font-size:0.9rem; } }
        </style>
        <?php
    }
}
