<div class="modal fade" id="delete_teacher{{$teacher->id}}" tabindex="-1"
     role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{route('teacher.destroy',$teacher->id)}}" method="post">
            @method('delete')
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;"
                        class="modal-title" id="exampleModalLabel">حذف مدرس</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>رقم المدرس الاكاديمي  <span class="text-danger">{{$teacher->academic_id}}</span></p>
                    <input type="hidden" name="id" value="{{$teacher->id}}">
                    <input type="hidden" name="academic_id" value="{{$teacher->academic_id}}">
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
