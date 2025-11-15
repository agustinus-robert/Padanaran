<div>
	<div wire:ignore x-data x-init="document.addEventListener('DOMContentLoaded', function() {
	   const pond = FilePond.create($refs.input, {
	        allowMultiple: 'true',
	        server: {
	            process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
	                @this.upload('featuredImage', file, load, error, progress)
	            },
	            revert: (filename, load) => {
	                @this.removeUpload('featuredImage', filename, load)
	            },
	        },
	    });
	    FilePond.registerPlugin(FilePondPluginImagePreview);
	    this.addEventListener('pondReset', e => {
	        pond.removeFiles();
	    });
	});">
	    <input type="file" x-ref="input" {!! isset($attributes['accept']) ? 'accept="' . $attributes['accept'] . '"' : '' !!}>
	</div>
</div>