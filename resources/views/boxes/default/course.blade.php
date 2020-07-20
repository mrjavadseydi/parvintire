<div class="box box-purple">

    <div class="box-header">
        <h3 class="box-title">اطلاعات دوره</h3>
        <div class="box-tools">
            <i class="box-tools-icon icon-minus"></i>
        </div>
    </div>
    
    <div class="box-body">

        <?php
        $courseStatus = $post->meta('courseStatus');
        ?>
        <div class="input-group">
            <label>وضعیت دوره</label>
            <select name="course_status">
                @foreach(courseStatus() as $key => $value)
                    <option {{ ($key == $courseStatus ? 'selected' : '') }} value="{{ $key }}">{{ $value['title'] }}</option>
                @endforeach
            </select>
        </div>

            <?php
            $courseType = $post->meta('courseType');
            ?>
            <div class="input-group">
                <label>نوع دوره</label>
                <select name="course_type">
                    @foreach(courseTypes() as $key => $value)
                        <option {{ ($key == $courseType ? 'selected' : '') }} value="{{ $key }}">{{ $value['title'] }}</option>
                    @endforeach
                </select>
            </div>

            <?php
            $teacherId = $post->meta('teacher');
            ?>
            <div class="input-group">
                <label>مدرس</label>
                <select class="select2" name="teacher">
                    <option value="">انتخاب مدرس</option>
                    @foreach(teachers() as $teacher)
                        <option {{ ($teacher->id == $teacherId ? 'selected' : '') }}
                                value="{{ $teacher->id }}">{{ $teacher->name() }}
                        </option>
                    @endforeach
                </select>
            </div>

    </div>
    
    <div class="box-footer">
    
    </div>
    
</div>
