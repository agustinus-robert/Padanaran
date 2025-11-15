<?php
namespace Modules\Admin\Http\Livewire\Upload;

use Closure;
use Livewire\Component;

class BuildingUpload extends Component{    /**     * Create a new component instance.     */    

	public function __construct(
		public string $name = 'file', 
		public bool|int $multiple = false,        
		public bool|int $validate = true,        
		public bool|int $preview = true,        
		public bool|int $required = false,        
		public bool|int $disabled = false,        
		public int $previewMax = 200,        
		public array|string $accept = ['image/png', 'image/jpeg', 'image/webp', 'image/avif'],    
		public string $size = '2MB',        
		public int $number = 10,        
		public string $label = '',        
		public string $sizeHuman = '',        
		public array|string $acceptHuman = [],) {    }        


		public function render() {     
			// Set boolean values    
			if (! $this->multiple) { $this->multiple = 0;    }    
			if (! $this->validate) { $this->validate = 0;    }    
			if (! $this->preview) {  $this->preview = 0;    }    
			if (! $this->required) { $this->required = 0;    }    
			if (! $this->disabled) {        $this->disabled = 0;    }    // Prepare accept files to JSON    
			if (is_string($this->accept)) {        $this->accept = explode(',', $this->accept);    }   
			$this->accept = array_map('trim', $this->accept);    
			$this->accept = array_filter($this->accept);    
			$this->accept = array_unique($this->accept);    
			$this->accept = array_values($this->accept);    
			$this->accept = array_map('strtolower', $this->accept);    
			$fileTypes = $this->accept;    
			$this->accept = json_encode($this->accept);    // Set size human for UI    
			$this->sizeHuman = $this->size;    // Prepare files types for UI    
			foreach ($fileTypes as $type) {        
				$new = explode('/', $type);       
				 if (array_key_exists(1, $new)) {            
				 	$this->acceptHuman[] = ".{$new[1]}";       
				 	 }   
			 }

			$this->acceptHuman = implode(', ', $this->acceptHuman);   
			
			return view('admin::livewire.upload.building-upload');    
		}
}