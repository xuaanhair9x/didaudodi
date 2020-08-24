<?php
class NHP_Options_select_img extends NHP_Options{	
	
	/**
	 * Field Constructor.
	 *
	 * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
	 *
	 * @since NHP_Options 1.0
	*/
	function __construct($field = array(), $value ='', $parent){
		
		parent::__construct($parent->sections, $parent->args, $parent->extra_tabs);
		$this->field = $field;
		$this->value = $value;
		//$this->render();
		
	}//function
	


	/**
	 * Field Render Function.
	 *
	 * Takes the vars and outputs the HTML for the field in the settings
	 *
	 * @since NHP_Options 1.0
	*/
	function render(){
		
		$class = ( isset( $this->field['class'] ) ) ? $this->field['class'] : '';
		$i = 0;
		$first = '';
		
		echo '<select id="'.$this->field['id'].'" name="'.$this->args['opt_name'].'['.$this->field['id'].']" class="img-select '.$class.'" rows="6">';
			
			foreach($this->field['options'] as $k => $v){
				
				echo '<option value="'.$k.'" '.selected($this->value, $k, false).' data-img="'.$v['img'].'">'.$v['name'].'</option>';

				if ( $i === 0 ) $first = $k;
				$i++;
				
			}//foreach

		echo '</select>';

		echo '<img src="'.(isset($this->field['options'][$this->value]['img']) ? $this->field['options'][$this->value]['img'] : $this->field['options'][$first]['img']).'" class="img-preview" style="vertical-align: middle; margin: 0 10px;"/>';

		echo (isset($this->field['desc']) && !empty($this->field['desc']))?' <span class="description">'.$this->field['desc'].'</span>':'';
		
	}//function

	/**
	 * Enqueue Function.
	 *
	 * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
	 *
	 * @since NHP_Options 1.0
	*/
	function enqueue(){
        
        wp_enqueue_script(
			'field_select_img',
			NHP_OPTIONS_URL.'fields/select_img/field_select_img.js',
			array('jquery'),
			MTS_THEME_VERSION,
			true
		);
		
	}//function
	
}//class
?>