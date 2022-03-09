<?php
namespace Custom_Dynamic_Tag\Tags;

class Custom_Url_Tag extends \Elementor\Core\DynamicTags\Tag
{
	public $fvar_final = [];
	
    public function get_categories()
    {
        return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
    }

    public function get_group()
    {
        return "custom-dynamic-tags";
    }

    public function get_title()
    {
		
        return __("Custom URL Tag", 'elementor-pro' );
    }

    public function get_name()
    {
        return "custom-url-tag";
    }
	
	public function get_calculation(){
		
		$args = array(
    		'post_type'  => 'product',
			'post__in'   => wc_get_featured_product_ids(),
		);

		$prlist = [];
		foreach( $args['post__in'] as $key => $value ){
			$_product = wc_get_product($value);
			$prlist = $prlist + 
				array( $key => 
					[	$value,
						$_product->get_title(),
						'/?p='. $value . '/',
					  ]);
        }	

		$this->fvar_options[0] = $prlist;
		$this->fvar_options[1] =  array(0 => __( $prlist[0][1] ));
		foreach ($prlist as $key=>$value){
			if($key == 0 ){
				continue;
			}
			$this->fvar_options[1] = $this->fvar_options[1] + array($key  => __( $prlist[$key][1] ));
		}
		$this->fvar_final = $this->fvar_options;
	}
	
		protected function _register_controls() {
		$this->get_calculation();
		$this->add_control(
			'input_one',
			[
				'label' => __( 'Select Product', 'elementor-pro' ),
				'type'  => \Elementor\Controls_Manager::SELECT,
				'default' => '0',
				'options' => $this->fvar_options[1],
			]
		); 
		
	}

    public function render() {
		$this->get_calculation();
		$input_one = $this -> get_settings( 'input_one' );
		$input_one = intval($input_one);
        
		echo $this->fvar_final[0][$input_one][2]; 

	}
}