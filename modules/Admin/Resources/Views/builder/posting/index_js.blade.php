<script>


  

$(document).ready(function(){


    var myData = {};

    dataTableVar = $('#datatablesSimple').DataTable({
        processing: true,
        searching: true,
         info: false,
         "dom": 'rtip',
        order : [
            ['0', 'asc']
        ],
        ajax: {
            url: "{{ route('postingtb') }}",
            data:function ( d ) {
                myData.menu_id = "{{$id_menu}}";
                return  $.extend(d, myData);
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'title', name: 'title'},
            {data: 'slug', name: 'slug'},
            {data: 'created_at', name: 'created_at'},
            {data: 'status', name: 'status'},
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center'
            },
        ]
    });
});

$(document).on('click', '#set_publish', function(){
    Swal.fire({
        title: "Set Publish?",
        text: "Publish this post?",
        icon: "info",
        showCancelButton: true,            
        showCloseButton: true,
        focusConfirm: false,
        focusCancel: false,
        allowOutsideClick: false,
        reverseButtons: true,
    })
    .then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                type:"GET",
                url:"{{url('posting_publish')}}"+'/'+$(this).data('id'),
                success:function(response){
                    var json = JSON.parse(response)
                    if(json.status == true){
                        Swal.fire({
                            title: 'Publish!',
                            text: 'Post was publish',
                            icon: 'success',
                            showConfirmButton:false,
                            focusConfirm: false,
                            focusCancel: false,
                            allowOutsideClick: false,
                            timer: 1500,
                            showLoaderOnConfirm: true,
                        })

                        dataTableVar.ajax.reload();
                    } else {
                        Swal.fire({
                            title: 'Not Publish!',
                            text: 'Post not Publish',
                            icon: 'danger',
                            showConfirmButton:false,
                            focusConfirm: false,
                            focusCancel: false,
                            allowOutsideClick: false,
                            timer: 1500,
                            showLoaderOnConfirm: true,
                        })                        
                    }
                }
            })
        }
    })

})

$(document).on('click', '#set_draft', function(){
    Swal.fire({
        title: "Set Draft?",
        text: "Draft this post?",
        icon: "info",
        showCancelButton: true,            
        showCloseButton: true,
        focusConfirm: false,
        focusCancel: false,
        allowOutsideClick: false,
        reverseButtons: true,
    })
    .then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                type:"GET",
                url:"{{url('posting_draft')}}"+'/'+$(this).data('id'),
                success:function(response){
                    var json = JSON.parse(response)
                    if(json.status == true){
                        Swal.fire({
                            title: 'Drafted!',
                            text: 'Post was draft',
                            icon: 'success',
                            showConfirmButton:false,
                            focusConfirm: false,
                            focusCancel: false,
                            allowOutsideClick: false,
                            timer: 1500,
                            showLoaderOnConfirm: true,
                        })

                        dataTableVar.ajax.reload();
                    } else {
                        Swal.fire({
                            title: 'Not Drafted!',
                            text: 'Post not draft',
                            icon: 'danger',
                            showConfirmButton:false,
                            focusConfirm: false,
                            focusCancel: false,
                            allowOutsideClick: false,
                            timer: 1500,
                            showLoaderOnConfirm: true,
                        })                        
                    }
                }
            })
        }
    })
   
})

$(document).on('click', '#show_article', function(){
      $('#modal_show_detail').html('')

     $.ajax({
        type:"GET",
        url:"{{url('posting_view')}}"+'/'+$(this).data('id'),
        success:function(response){
            $('#modal_show_detail').html(response)
        }
    });
})

$(document).on('click', '#show_sch', function(){

     var footer_response = '';

  
     $.ajax({
        type:"GET",
        url:"{{url('sch_view')}}"+'/'+$(this).data('id'),
        success:function(response){
            $('#modal_show').html(response)
            //$('#sch_footer').html(footer_response)
        }
    });
})


</script>