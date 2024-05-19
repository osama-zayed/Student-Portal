<div class="modal fade" id="delete_library{{$library['id']}}" tabindex="-1"
    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
       <form action="{{route('library_Book.destroy',$library['name'])}}" method="post">
           @method('delete')
           @csrf
           <div class="modal-content">
               <div class="modal-header">
                   <h5 style="font-family: 'Cairo', sans-serif;"
                       class="modal-title" id="exampleModalLabel">حذف الكتاب</h5>
                   <button type="button" class="close" data-dismiss="modal"
                           aria-label="Close">
                       <span aria-hidden="true">&times;</span>
                   </button>
               </div>
               <div class="modal-body">
                   <p>اسم الكتاب <span class="text-danger">{{$library['name']}}</span></p>
                   <input type="hidden" name="id" value="{{$library['id']}}">
                   <input type="hidden" name="name" value="{{$library['name']}}">
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
