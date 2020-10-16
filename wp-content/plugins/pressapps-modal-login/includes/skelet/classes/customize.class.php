<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Customize Class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if(!class_exists("SkeletFramework_Customize")){
  class SkeletFramework_Customize extends SkeletFramework_Abstract {

    /**
     *
     * sections
     * @access public
     * @var array
     *
     */
    public $options = array();

    /**
     *
     * panel priority
     * @access public
     * @var bool
     *
     */
    public $priority = 1;

    /**
     *
     * instance
     * @access private
     * @var class
     *
     */
    private static $instance = null;

    // run customize construct
    public function __construct( $options ) {

     
      $this->options = apply_filters( 'sk_customize_options', $options );

      if( ! empty( $this->options ) ) {
        $this->addAction( 'customize_register', 'customize_register' );
      }

    }

    // instance
    public static function instance( $options = array() ){
      if ( is_null( self::$instance ) && SK_ACTIVE_CUSTOMIZE ) {
        self::$instance = new self( $options );
      }
      return self::$instance;
    }

    // customize register
    public function customize_register( $wp_customize ) {

      // load extra WP_Customize_Control
      sk_locate_template ( 'functions/customize.php' );
      do_action( 'sk_customize_register' );

      $panel_priority = 1;

      foreach ( $this->options as $value ) {

        $this->priority = $panel_priority;

        if( isset( $value['sections'] ) ) {

          $wp_customize->add_panel( $value['name'], array(
            'title'       => $value['title'],
            'priority'    => ( isset( $value['priority'] ) ) ? $value['priority'] : $panel_priority,
            'description' => ( isset( $value['description'] ) ) ? $value['description'] : '',
          ));

          $this->add_section( $wp_customize, $value, $value['name'] );

        } else {

          $this->add_section( $wp_customize, $value );

        }

        $panel_priority++;

      }

    }

    // add customize section
    public function add_section( $wp_customize, $value, $panel = false ) {
      global $skelet_path;
      $section_priority = ( $panel ) ? 1 : $this->priority;
      $sections         = ( $panel ) ? $value['sections'] : array( 'sections' => $value );

      foreach ( $sections as $section ) {

        // add_section
        $wp_customize->add_section(  $skelet_path["prefix"].'_'.$section['name'], array(
          'title'       => $section['title'],
          'priority'    => ( isset( $section['priority'] ) ) ? $section['priority'] : $section_priority,
          'description' => ( isset( $section['description'] ) ) ? $section['description'] : '',
          'panel'       => ( $panel ) ? $panel : '',
        ) );

        $setting_priority = 1;

        foreach ( $section['settings'] as $setting ) {

          $setting_name = $skelet_path["prefix"].'_customize' . '[' .  $skelet_path["prefix"].'_'.$setting['name'] .']';

          // add_setting
          $wp_customize->add_setting( $setting_name,
            wp_parse_args( $setting, array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sk_sanitize_clean',
              )
            )
          );

          // add_control
          $control_args = wp_parse_args( $setting['control'], array(
            'unique'    => $skelet_path["prefix"].'_customize',
            'section'   => $skelet_path["prefix"].'_'.$section['name'],
            'settings'  => $setting_name,
            'priority'  => $setting_priority,
          ));

          if( $control_args['type'] == 'sk_field' ) {

            $call_class = 'WP_Customize_'. $control_args['type'] .'_Control';
            $wp_customize->add_control( new $call_class( $wp_customize, $setting['name'], $control_args ) );

          } else {

            $wp_controls = array( 'color', 'upload', 'image', 'media' );
            $call_class  = 'WP_Customize_'. ucfirst( $control_args['type'] ) .'_Control';

            if( in_array( $control_args['type'], $wp_controls ) && class_exists( $call_class ) ) {
              $wp_customize->add_control( new $call_class( $wp_customize, $setting['name'], $control_args ) );
            } else {
              $wp_customize->add_control( $setting['name'], $control_args );
            }

          }

          $setting_priority++;
        }

        $section_priority++;

      }

    }

  }
}
