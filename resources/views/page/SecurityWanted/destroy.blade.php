<div class="modal fade" id="delete_SecurityWanted{{$SecurityWanted->id}}" tabindex="-1"
     role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{route('Security_wanted.destroy',$SecurityWanted->id)}}" method="post">
            @method('delete')
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;"
                        class="modal-title" id="exampleModalLabel">حذف محافظة</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>رقم القيد  <span class="text-danger">{{$SecurityWanted->registration_number}}</span></p>
                    <input type="hidden" name="id" value="{{$SecurityWanted->id}}">
                    <input type="hidden" name="file_name" value="{{$SecurityWanted->SecurityWanted_name}}">
                </div>
                <div class="modal-footer">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">اغلاق</button>
                        <button type="submit"
                                class="btn btn-danger">حذف</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>