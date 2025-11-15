<style>
body{
    background-color:#dce3f0;
}

.height{
    
    height:100vh;
}

.card{
    width:100%;
    max-height: 370px;
    overflow-y: auto;  /* Menambahkan scroll vertikal jika konten melebihi max-height */
}

.image{
    position:absolute;
    right:12px;
    top:10px;
}

.main-heading{
    
    font-size:40px;
    color:red !important;
}

.ratings i{
    
    color:orange;
    
}

.user-ratings h6{
    margin-top:2px;
}

.colors{
    display:flex;
    margin-top:2px;
}

.colors span{
    width:15px;
    height:15px;
    border-radius:50%;
    cursor:pointer;
    display:flex;
    margin-right:6px;
}

.colors span:nth-child(1) {
    
    background-color:red;
    
}

.colors span:nth-child(2) {
    
    background-color:blue;
    
}

.colors span:nth-child(3) {
    
    background-color:yellow;
    
}

.colors span:nth-child(4) {
    
    background-color:purple;
    
}
</style>

<div>
	<div class="d-flex justify-content-center align-items-center">
	    <div class="card p-3">
	        <div class="d-flex justify-content-between align-items-center ">
	            <div class="mt-2">
	                <h4 class="text-uppercase">{{$categoriesInfo->name}}</h4>
	                <div class="mt-5">
	                    <h5 class="text-uppercase mb-0">{{$brandInfo->name}}</h5>
	                    <h1 class="main-heading mt-0">{{$productInfo->name}}</h1>
	                    <div class="d-flex flex-row user-ratings">
	                        <div class="ratings">
	                        <i class="fa fa-star"></i>
	                        <i class="fa fa-star"></i>
	                        <i class="fa fa-star"></i>
	                        <i class="fa fa-star"></i>
	                        </div>
	                        <h6 class="text-muted ml-1">4/5</h6>
	                    </div>
	                </div>
	            </div>
	            <div class="image">
	            	@php $image = $productInfo->location.'/'.$productInfo->image_name; @endphp
	                <img src="{{asset('uploads/'.$image)}}" width="200">
	            </div>
	        </div>
	        
	        <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
	            <span>Available colors</span>
	            <div class="colors">
	                <span></span>
	                <span></span>
	                <span></span>
	                <span></span>
	            </div>
	            
	        </div>
	        
	        
	        <p>Deskripsi Iphone </p>

	        <div class="mb-4">
		        <table class="table">
		        	<tr>
		        		<th>Nama Gudang</th>
		        		<th>Stock</th>
		        	</tr>
		        	
		        	@foreach($warehouseTotalQty as $warehouseName => $totalQty)
			        	<tr>
	        				<td>{{ $warehouseName }}</td> 
	        				<td>{{ $totalQty }}</td>
	        			</tr>
    				@endforeach
		        </table>
	    	</div>

	    </div>
	</div>
</div>