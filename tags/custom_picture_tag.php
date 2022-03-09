<?php
namespace Custom_Dynamic_Tag\Tags;
class Custom_Picture_Tag extends \Elementor\Core\DynamicTags\Data_Tag
{
    public function get_categories()
    {
        return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
    }

    public function get_group()
    {
        return "custom-dynamic-tags";
    }

    public function get_title()
    {
        return "Custom Picture Tag";
    }

    public function get_name()
    {
        return "custom-picture-tag";
    }
	
		protected function _register_controls() {
		$args = array(
    		'post_type'  => 'product',
			'post__in'   => wc_get_featured_product_ids(),
		);
		$postslist = get_posts( $args );

		if(!empty($postslist)){
		
		$final_options = array($postslist{0}->{'ID'}  => __( $postslist{0}->{'post_title'} ));
		foreach ($postslist as $key => $value){
			if($key == 0 ){
				continue;
			}
			$final_options = 
				$final_options + array($postslist{$key}->{'ID'}  => __( strval( $postslist{$key}->{'post_title'} )));
		}

		
		$this->add_control(
			'input_one',
			[
				'label' => __( 'Select Product', 'elementor-pro' ),
				'type'  => \Elementor\Controls_Manager::SELECT,
				'default' => $postslist{0}->{'ID'},
				'options' => $final_options,
			]
		);
			
		}
		else{
			$this->add_control(
			'input_one',
			[
				'label' => __( 'Select Product', 'elementor-pro' ),
				'type'  => \Elementor\Controls_Manager::SELECT,
				'options' => [ 0  => __( 'Empty', 'elementor-pro' )],
			]
		);
		}

	}

    public function get_value( array $options = array() )
    {
		
		$input_one = $this->get_settings( 'input_one' );
		
        return [
                'id' => $input_one,
				'url' => wp_get_attachment_image_src(get_post_thumbnail_id( $input_one ), 'full')[0],
            ]; 
    }
}