<?php
namespace Custom_Dynamic_Tag\Tags;

class Custom_Title_Tag extends \Elementor\Core\DynamicTags\Tag
{
	private $fvar_final= [];
	
    public function get_categories()
    {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    public function get_group()
    {
        return "custom-dynamic-tags";
    }

    public function get_title()
    {
		
        return __("Custom Title Tag", 'elementor-pro' );
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
					  	$_product->get_regular_price(),
					  	$_product->get_sale_price(),
					  ]);
        }	
		$fvar_options[0] = $prlist;
		$fvar_options[1] =  array(0 => __( $prlist[0][1] ));
		
		foreach ($prlist as $key=>$value){

			if($key == 0 ){
				continue;
			}
			$fvar_options[1] = $fvar_options[1] + array($key  => __( $prlist[$key][1] ));
			
		}

		
		$this->fvar_final = $fvar_options;


	}

    public function get_name()
    {
        return "custom-title-tag";
    }
	
		protected function _register_controls() {
		$this->get_calculation();
		
		$this->add_control(
			'input_one',
			[
				'label' => __( 'Select Product', 'elementor-pro' ),
				'type'  => \Elementor\Controls_Manager::SELECT,
				'default' => '0',
				'options' => $this->fvar_final[1],
			]
		); 
			
		$this->add_control(
			'input_two',
			[
				'label' => __( 'Select Value', 'elementor-pro' ),
				'type'  => \Elementor\Controls_Manager::SELECT,
				'default' => 1,
				'options' => [
					0  => __( 'ID', 'elementor-pro' ),
					1  => __( 'Title', 'elementor-pro' ),
					2 => __( 'Regular Price', 'elementor-pro' ),
					3 => __( 'Sale Price', 'elementor-pro' ),
				],
			]
		); 

	}

    public function render() {
		$this->get_calculation();
		$input_one = intval($this -> get_settings( 'input_one' ));
		$input_two = $this -> get_settings( 'input_two' );
		if (!empty($this->fvar_final[0][$input_one][3]) && $input_two == 2 ){
			$output = "<s>AED " . $this->fvar_final[0][$input_one][2] . "</s>"; 
		}
		else{
			switch($input_two){
				case 0: $output= $this->fvar_final[0][$input_one][0]; break;
				case 1: $output= $this->fvar_final[0][$input_one][1]; break;
				case 2: $output= "AED " . $this->fvar_final[0][$input_one][2]; break;
				case 3: $output= "AED " . $this->fvar_final[0][$input_one][3]; break;
				default: $output= $this->fvar_final[0][$input_one][1]; break;
			}
		}
		echo $output;
	}
}