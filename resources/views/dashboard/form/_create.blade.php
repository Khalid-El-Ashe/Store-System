<div class="card card-success">
    <div class="card-header">
        <h3 class="card-title">Create Category</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            <label for="name">Categoy Name</label>
            <input type="text" @class(['form-control', 'is-invalid'=> $errors->has('name')]) name="name" id="name"
            placeholder="category name">
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Categoy Description</label>
            <textarea class="form-control form-control-lg" type="text" name="description" id="description"
                placeholder="category description...."></textarea>
        </div>
        <div class="form-group">
            <label>Select</label>
            <select class="form-control">
                <option name="parent_id" id="parent_id">Primary Category</option>
                @foreach ($parents as $parent)
                <option value="{{$parent->id}}">{{$parent->name}}</option>
                @endforeach
            </select>
        </div>
        {{-- <div class="form-group">
            <select name="parent_id" id="parent_id" class="form-control">
                <option value="">Primary Category</option>
                @foreach ($parents as $parent)
                <option value="{{$parent->id}}">{{$parent->name}}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="form-group">
            <label for="image">Choose Image</label>
            <div class="custom-file">
                <label class="custom-file-label" for="image">Choose image</label>
                <input type="file" class="custom-file-input" name="image" id="image" multiple accept="image/*">
                {{-- لاحظ فوق اني انا فقط معطي المستخدم يرفع بس صور accept="image*/" --}}
            </div>
            <img id="preview-image" src="#" alt="Selected Image" style="display: none; max-height: 150px;"
                class="mt-2 rounded">
        </div>
        <div class="form-group">
            <legend>Status</legend>
            <div class="form-group clearfix">
                <div class="icheck-primary d-inline">
                    <input type="radio" id="status_active" name="status" value="active"
                        @checked(old('status')==='active' )>
                    <label for="status_active">
                        Active
                    </label>
                </div>

                <div class="icheck-primary d-inline">
                    <input type="radio" id="status_archived" name="status" value="archived"
                        @checked(old('status')==='archived' )>
                    <label for="status_archived">
                        Archived
                    </label>
                </div>
            </div>

            @error('status')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</div>