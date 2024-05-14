<div class="modal fade" id="delete_College{{$College['id']}}" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
       <form action="{{route('College.destroy',$College['name'])}}" method="post">
           @method('delete')
           @csrf
           <div class="modal-content">
               <div class="modal-header">
                   <h5 style="font-family: 'Cairo', sans-serif;"
                       class="modal-title" id="exampleModalLabel">حذف الكلية</h5>
                   <button type="button" class="close" data-dismiss="modal"
                           aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   <p>اسم الكلية <span class="text-danger">{{$College['name']}}</span></p>
                   <input type="hidden" name="id" value="{{$College['id']}}">
                   <input type="hidden" name="file_name" value="{{$College['name']}}">
               </div>
               <div class="modal-footer">
                   <div class="modal-footer">
                       <button type="button" class="btn btn-primary"
                               data-dismiss="modal">اغلاق</button>
                       <button type="submit"
                               class="btn btn-danger">حذف</button>
                   </div>
               </div>
           </div>
       </form>
   </div>
</div>
