<div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('Specialization.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 style="font-family: 'Cairo', sans-serif;" class="modal-title" id="exampleModalLabel">
                        اضافة تخصص</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-12">
                            <label for="name">اسم التخصص
                                <span class="text-danger">*
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="name" type="text" name="name" class="form-control"
                                value="{{ old('name') }}" placeholder="أدخل اسم التخصص" required="الحقل مطلوب">
                        </div>
                        <div class="col-12 mt-10">
                            <label for="college_id">الكلية
                                <span class="text-danger">*
                                    @error('college_id')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <select class="form-control h-65" name="college_id" aria-placeholder="اختر كلية" required>
                                <option value="" disabled selected>اختر كلية من القائمة</option>
                                @forelse (\App\Models\College::get() as $data)
                                    <option value="{{ $data['id'] }}"
                                        @if ($data->id == old('college_id')) selected @endif>
                                        {{ $data['name'] }}</option>
                                @empty
                                    <option value="">لا يوجد كليات</option>
                                @endforelse
                            </select>
                        </div>
                        <div class="col-12 mt-10">
                            <label for="educational_qualification">المؤهل المطلوب
                                <span class="text-danger">*
                                    @error('educational_qualification')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <select class="form-control h-65" name="educational_qualification"
                                aria-placeholder="المؤهل مطلوب" required>
                                <option value="" disabled selected>اختر المؤهل</option>
                                <option value="ثانوية عامة علمي" @if (old('educational_qualification') == 'ثانوية عامة علمي') selected @endif>
                                    ثانوية عامة علمي
                                <option value="ثانوية عامة ادبي" @if (old('educational_qualification') == 'ثانوية عامة ادبي') selected @endif>
                                    ثانوية عامة ادبي
                                </option>
                            </select>
                        </div>
                        <div class="col-12 mt-10">
                            <label for="lowest_acceptance_rate">اقل معدل للقبول
                                <span class="text-danger">*
                                    @error('lowest_acceptance_rate')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="lowest_acceptance_rate" type="number" name="lowest_acceptance_rate" class="form-control" min="50"
                                value="{{ old('lowest_acceptance_rate') }}" placeholder="اقل معدل للقبول" required="الحقل مطلوب">
                        </div>
                        <div class="col-12 mt-10">
                            <label for="Price">السعر
                                <span class="text-danger">*
                                    @error('Price')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="Price" type="number" name="Price" class="form-control" min="1"
                                value="{{ old('Price') }}" placeholder="السعر" required="الحقل مطلوب">
                        </div>
                        <div class="col-12 mt-10">
                            <label for="Number_of_years_of_study">عدد السنين الدراسية
                                <span class="text-danger">*
                                    @error('Number_of_years_of_study')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </label>
                            <input id="Number_of_years_of_study" type="number" name="Number_of_years_of_study"
                                class="form-control" min="1" value="{{ old('Number_of_years_of_study') }}"
                                placeholder="أدخل عدد السنين الدراسية" required="الحقل مطلوب">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>
                    <button type="submit" class="btn btn-primary">اضافة</button>
                </div>
            </form>
        </div>
    </div>
</div>
